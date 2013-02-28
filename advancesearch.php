<?php
  include 'header.php';
	
	$keyword = $_GET["keyword"];	
	$type = trim($_GET["type"]);	
	$yearfrom = trim($_GET["yearfrom"]);	
	$yearto = trim($_GET["yearto"]);	
	//$durationfrom = trim($_GET["durationfrom"]);	
	//$durationto = trim($_GET["durationto"]);	
	$page = $_GET["page"];
?>


<form name='search' action='advancesearch.php' method='GET'>
	<table>
		<tr>
			<td>
				Keyword song title <select name='type'>
					<option value='exact' <?php if($_GET["type"]=="exact") echo "selected";?>>exact</option>
					<option value='beginwith' <?php if($_GET["type"]=="beginwith") echo "selected";?>>beginwith</option>
					<option value='contain' <?php if($_GET["type"]=="contain") echo "selected";?>>contain</option>
				</select>
			</td>
			<td><input type='text' name='keyword' size='100' <?php if($keyword!=null) echo "value='".$keyword."'";?>></td>
		</tr>
		<tr>
			<td>Year</td><td>From <input type='text' name='yearfrom' size='7' <?php if($yearfrom!=null) echo "value='".$yearfrom."'";?>>
			To <input type='text' name='yearto' size='7' <?php if($yearto!=null) echo "value='".$yearto."'";?>></td>
		</tr>
		<!--tr>
			<td>Duration</td><td>From <input type='text' name='durationfrom' size='7' <?php if($durationfrom!=null) echo "value='".$durationfrom."'";?>>
			To <input type='text' name='durationto' size='7' <?php if($durationto!=null) echo "value='".$durationto."'";?>></td>
		</tr-->
			<td colspan="2" align="right"><input type="submit" value="Search"/> </td>
		</tr>
	</table>
</form>



<?php
	//if(($keyword!=null&&$keyword!="")||
	//	($yearfrom!=null&&$yearfrom!="")||
	//	($yearto!=null&&$yearto!="")||
	//	($durationfrom!=null&&$durationfrom!="")||
	//	($durationto!=null&&$durationto!="")){
	if($type!=null){
	mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
	@mysql_select_db(DB_DB) or die("Unable to select database");
	$query="SELECT * FROM Song, Artist WHERE Song.atid=Artist.atid ";
	
	
	if($type=="exact"&&$keyword!=null&&$keyword!=""){
		$query.=" AND title = '$keyword'";
	} 
	if($type=="beginwith"&&$keyword!=null&&$keyword!=""){
		$query.=" AND title LIKE '$keyword%'";
	}
	if($type=="contain"&&$keyword!=null&&$keyword!=""){
		$query.=" AND title LIKE '%$keyword%'";
	} 
	
	if(preg_match('/^(?:0|[1-9][0-9]{0,3})$/', $yearfrom)&&$yearfrom!=null&&$yearfrom!=""){
		$query.=" AND year>=$yearfrom";
	}
	
	if(preg_match('/^(?:0|[1-9][0-9]{0,3})$/', $yearto)&&$yearto!=null&&$yearto!=""){
		$query.=" AND year<=$yearto";
	}
	
	
	
 
	
	$query .=" LIMIT 11";
	if($page!=null){
		$offset=$page*10;
		$query .= " OFFSET $offset";
	}else{
		$page=0;
	}
	echo "<font color='#EEEEEE'>".$query."</font>";
	$result=mysql_query($query);
	$num=mysql_numrows($result);
	if($num>0){
		print("<hr>");
		print("<table width='800' class='result'><tr><th width='400'>Title</th><th>Artist</th><th>Year</th><th>Duration</th></tr>");
	}
	$i=0;
	while($i<$num&& $i<10){
		$sid=mysql_result($result,$i,"Song.sid");
		$title=mysql_result($result,$i,"title");
		$title=($title=="")?"N/A":$title;//&nbsp;
		$year=mysql_result($result,$i,"year");
		$year=($year=="")?"N/A":$year;//&nbsp;
		$duration=mysql_result($result,$i,"duration");
		$artist= mysql_result($result,$i,"atname");
		print("	<tr><td><a href='song.php?sid=$sid'>$title</a></td><td>$artist</td><td>$year</td><td>$duration</td></tr>");
		$i++;
	}
	if($page!=0 ||$num>10){
		print("<tr><td colspan='4' align='center'>");
	}
	if($page!=0){
		$prevpage=$page-1;
		print("<a href='advancesearch.php?page=$prevpage&keyword=$keyword&type=$type&yearfrom=$yearfrom&yearto=$yearto'>previous</a>");
	}else{
		print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp");
	}
	if($num>10){
		$nextpage=$page+1;
		print(" <a href='advancesearch.php?page=$nextpage&keyword=$keyword&type=$type&yearfrom=$yearfrom&yearto=$yearto'>next</a>");
	}
	
	if($page!=0 ||$num>10){
		print("</td></tr>");
	}
	print("</table>");
	}
	
	include_once 'tailer.php';
?>
