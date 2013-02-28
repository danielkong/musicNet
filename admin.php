<?php
  include 'header.php';
	session_start(); 
	$uid=$_SESSION['uid'];
	$uname=$_SESSION['uname'];
	$query = $_POST["query"];

?>


<?php 
	if($uname=='admin'){ 
?>
	<form name='search' action=admin.php method='POST'>
		<table>
			<tr>
				<td><textarea name="query" cols="100" rows="5"><?php if($query!=null) echo "$query"; ?></textarea><br></td>
				<td><input type="submit" value="Query"/></td>
			</tr>
		</table>
	</form>
<?php
	}
?>

<?php
	

	if($query !=null&&$uname=='admin'){
		
		mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
		@mysql_select_db(DB_DB) or die("Unable to select database");

		$starttime = microtime(true);
		$result=mysql_query($query);
		$endtime = microtime(true);
		$timeused = $endtime - $starttime;

		$numrows=mysql_numrows($result);
		$numcols=mysql_num_fields($result);


		if($numrows==0){
			print("total time: $timeused seconds<br>");
			print("<table class='result'>");
			print("	<tr><td>No result</td></tr>");
		}else{
			print("$numrows record(s) returned <br>");
			print("total time: $timeused seconds");
			print("<table class='result'>");

			$col = "	<tr>";
			for($k=0;$k<$numcols;$k++){
				$colname = mysql_fetch_field($result,$k)->name;
				$col.="<th>$colname<th>";
			}

			$col.="<tr>";
			print("$col");

			for($i=0;$i<$numrows;$i++){
				$list = "	<tr>";
				for($j=0;$j<$numcols;$j++){
					$item=mysql_result($result,$i,$j);
					$list.="<td>$item<td>";
				}
				$list.="<tr>";
					
				print("$list");
			}

			

		
		}
		print("</table>");
	}	
?>




<?php
	include_once 'tailer.php';
?>
