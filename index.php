<?php
  include 'header.php';
	include 'searchbar.php';
	$uid=$_SESSION['uid'];
	$uname=$_SESSION['uname'];

?>

<table>
<tr>
	<td valign="top" rowspan="4" width="600">
	
<?php
	mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
	@mysql_select_db(DB_DB) or die("Unable to select database");
	$keyword = $_GET["keyword"];	
	if($keyword!=null){	
		$type=$_GET["type"];
		
		if($type=="song" || $type=="all"){
			$query="SELECT * FROM Song WHERE title = '$keyword'";
			$result=mysql_query($query);
			$num=mysql_numrows($result);
			print("<h3>Song</h3>");
			if($num==0){
				print("<table class='result'>");
				print("	<tr><td>No result</td></tr>");
			}else{
				print("$num song(s) found");
				print("<table class='result' width='680'>");
				print("	<tr><th>Title</th><th>Year</th></tr>");
			}
			$i=0;
			while($i<$num){
				$sid=mysql_result($result,$i,"sid");
				$title=mysql_result($result,$i,"title");
				$year=mysql_result($result,$i,"year");
				$year=($year=="")?"N/A":$year;//&nbsp;
				print("	<tr><td><a href='song.php?sid=$sid'>$title</a></td><td>$year</td></tr>");
				$i++;
			}
			print("</table>");	
		}
		if($type=="album" || $type=="all"){
			$query="SELECT * FROM Album WHERE alname='$keyword'";
			$result=mysql_query($query);
			$num=mysql_numrows($result);
			print("<h3>Album</h3>");
			if($num==0){
				print("<table class='result'>");
				print("	<tr><td>No result</td></tr>");
			}else{
				print("$num album(s) found");
				print("<table class='result'>");
				print("	<tr><th>Name</th></tr>");
			}
			$i=0;
			while($i<$num){
				$alid=mysql_result($result,$i,"alid");
				$alname=mysql_result($result,$i,"alname");
				print("	<tr><td><a href='album.php?alid=$alid'>$alname</a></td></tr>");
				$i++;
			}
			print("</table>");	
		}
		if($type=="artist" || $type=="all"){
			$query="SELECT * FROM Artist WHERE atname='$keyword'";
			$result=mysql_query($query);
			$num=mysql_numrows($result);
			print("<h3>Artist</h3>");
			if($num==0){
				print("<table>");
				print("	<tr><td>No result</td></tr>");
			}else{
				print("$num artist(s) found");
				print("<table class='result'>");
				print("	<tr><th>Name</th></tr>");
			}
			$i=0;
			while($i<$num){
				$atid=mysql_result($result,$i,"atid");
				$atname=mysql_result($result,$i,"atname");
				print("	<tr><td><a href='artist.php?atid=$atid'>$atname</a></td></tr>");
				$i++;
			}
			print("</table>");	
		}
		if($type=="friend"){
			$query="SELECT * FROM User WHERE uname='$keyword'";
			$result=mysql_query($query);
			$num=mysql_numrows($result);
			print("<h3>Friend</h3>");
			if($num==0){
				print("<table>");
				print("	<tr><td>No result</td></tr>");
			}else{
				print("$num user(s) found");
				print("<table class='result'>");
				print("	<tr><th>Name</th></tr>");
			}
			$i=0;
			while($i<$num){
				$userid=mysql_result($result,$i,"uid");
				$username=mysql_result($result,$i,"uname");
				print("	<tr><td><a href='user.php?uid=$userid'>$username</a></td></tr>");
				$i++;
			}
			print("</table>");	
		}
	}
	else{
		if(isset($uid)){
			print("<h2>We have some recommendation for you</h2>");
			
			print("<h3>Most visited song</h3>");
			print("<table><tr>");
			$query="SELECT * FROM Visited, Song, Album, Artist WHERE uid=$uid AND Song.alid=Album.alid AND Artist.atid=Song.atid AND Song.sid=Visited.sid ORDER BY count DESC LIMIT 5";
			$result=mysql_query($query);
			$num=mysql_numrows($result);
						
			if($num==0){
				print("<td>We don't have your visited history</td>");
			}
			$i=0;
			while($i<$num){
				$sid=mysql_result($result,$i,"Song.sid");
				$title=mysql_result($result,$i,"Song.title");
				$album=mysql_result($result,$i,"Album.alname");
				$artist=mysql_result($result,$i,"Artist.atname");
				print("<td width='150' align='center'>");
				$jsrc = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=album%20".str_replace(" ", "%20", $album."%20".$artist);
				$json = file_get_contents($jsrc);
				$jset = json_decode($json, true);
				print("<a href='song.php?sid=$sid'><img width='128' height='128' src='".$jset["responseData"]["results"][0]["tbUrl"]."'/><br>$title</a><br>$artist</td>");
				$i++;
			}			
			print("</tr></table><br>");		
			
			print("<h3>Your friend recent activity</h3>");
			
			//-----Roger part
			$gl = 20;	
			$visit_full_query = "SELECT Visited.* FROM Visited, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Visited.uid=FD.uid ORDER BY datetime DESC";
			$visit_full_result = mysql_query($visit_full_query);

			$friend_full_query = "SELECT Friend.* FROM Friend, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Friend.uid1=FD.uid OR Friend.uid2=FD.uid ORDER BY datetime DESC";
			$friend_full_result = mysql_query($friend_full_query);

			$rate_full_query = "SELECT Rate.* FROM Rate, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Rate.uid=FD.uid ORDER BY datetime DESC";
			$rate_full_result = mysql_query($rate_full_query);

			$combined_time_query = "SELECT datetime From ((SELECT Visited.datetime FROM Visited, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Visited.uid=FD.uid ORDER BY datetime DESC) UNION (SELECT Friend.datetime FROM Friend, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Friend.uid1=FD.uid OR Friend.uid2=FD.uid ORDER BY datetime DESC) UNION (SELECT Rate.datetime FROM Rate, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Rate.uid=FD.uid ORDER BY datetime DESC)) AS dt ORDER BY datetime DESC LIMIT 100";
			$combined_time_result = mysql_query($combined_time_query);

			$numrr = mysql_numrows($combined_time_result);

			$numrtd = min($numrr,$gl);

			

			if(isset($uid)){
				echo "<table style='font-size:10pt;'>";
				for($i=0;$i<$numrtd;$i++){
					$dt = mysql_result($combined_time_result,$i);	
					$a=0;
					$b=0;
					$c=0;


					if($dt==null)continue;
					//Visit table
					for($j=0;$j<$numrtd;$j++){
						$dt_in_result = mysql_result($visit_full_result, $j, "datetime");
						if($dt==$dt_in_result){
							$sid = mysql_result($visit_full_result, $j, "sid");
							$sidquery = "SELECT title FROM Song WHERE sid='$sid'";
							$sidresult = mysql_query($sidquery);
							$title = mysql_result($sidresult,0);
							
							$fid = mysql_result($visit_full_result, $j, "uid");
							$fnamequery = "SELECT uname FROM User WHERE uid='$fid'";
							$fnameresult = mysql_query($fnamequery);
							$fname = mysql_result($fnameresult, 0);

							echo "<tr><td> <a href='user.php?uid=$fid' target='blank'>$fname</a> listened to <a href='song.php?sid=$sid' target='blank'>$title</a> at $dt </td></tr>";
							$a=1;
							break;
						}
					}
						
					
					if($a==1) continue;
					//Friend table
					for($j=0;$j<$numrtd;$j++){
						$dt_in_result = mysql_result($friend_full_result, $j, "datetime");
						if($dt==$dt_in_result){
							$fid = mysql_result($friend_full_result, $j, "uid1")== $uid ? mysql_result($friend_full_result, $j, "uid2") : mysql_result($friend_full_result, $j, "uid1");
							$ffid = mysql_result($friend_full_result, $j, "uid1")!= $uid ? mysql_result($friend_full_result, $j, "uid2") : mysql_result($friend_full_result, $j, "uid1");
							$fnamequery = "SELECT uname FROM User WHERE uid='$fid'";
							$fnameresult = mysql_query($fnamequery);
							$fname = mysql_result($fnameresult, 0);

							$fnamequery2 = "SELECT uname FROM User WHERE uid='$ffid'";
							$fnameresult2 = mysql_query($fnamequery2);
							$fname2 = mysql_result($fnameresult2, 0);

							echo "<tr><td> <a href='user.php?uid=$fid' target='blank'>$fname</a> and <a href='user.php?uid=$ffid' target='blank'>$fname2</a> just become friends at $dt</td></tr>";
							$b=1;
							break;
						}
					}
					if($b==1) continue;

					//Rate table
					for($j=0;$j<$numrtd;$j++){
						$dt_in_result = mysql_result($rate_full_result, $j, "datetime");
						if($dt==$dt_in_result){
							$sid = mysql_result($rate_full_result, $j, "sid");
							$sidquery = "SELECT * FROM Song WHERE sid='$sid'";
							$sidresult = mysql_query($sidquery);
							$title = mysql_result($sidresult,0, "title");

							$fid = mysql_result($rate_full_result, $j, "uid");
							$fnamequery = "SELECT * FROM User WHERE uid='$fid'";
							$fnameresult = mysql_query($fnamequery);
							$fname = mysql_result($fnameresult, 0, "uname");
				
							$rating = mysql_result($rate_full_result, $j, "rating");

							echo "<tr><td> <a href='user.php?uid=$fid' target='blank' target='blank'>$fname</a> rated the song <a href='song.php?uid=$sid' target='blank'>$title</a> with a rating $rating at $dt </td></tr>";
							$c=1;
							break;
						}
					}
					if($c==1) continue;
				}
				echo "</table>";
			}
			//--end Roger part
				
			//print("<iframe width='100%' height='800' scrolling='no' frameborder='0' src='friendupdate.php'></iframe>");
			print("<br>");
				
			
		}else{
			print("Please register and login to see what we recommend for you");	
		}
		
	}
	
?>
	</td>
	<td width="300"> 
		<h3>Top song</h3> 
		<table class='result'  width="280">
<?php
			$query="SELECT AvgRating.sid, avgrating, title, Artist.atname FROM AvgRating,Song, Artist WHERE AvgRating.sid=Song.sid AND Artist.atid= Song.atid AND cnt>10 ORDER BY AvgRating DESC LIMIT 5";
			$result=mysql_query($query);
			$num=mysql_numrows($result);
						
			$i=0;
			while($i<$num){
				$sid=mysql_result($result,$i,"AvgRating.sid");
				$title=mysql_result($result,$i,"title");
				$avgrating=mysql_result($result,$i,"avgrating");
				$artist=mysql_result($result,$i,"Artist.atname");
				$rank=$i+1;
				print("	<tr><td><a href='song.php?sid=$sid'>$rank. $title</a><br>$artist</td></tr>");
				$i++;
			}

		$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		if ($_SERVER["SERVER_PORT"] != "80"){
    		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
		else{
    		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		//if ($_SERVER["REQUEST_URI"] not include index.php) redirect to index.php;
?>
		</table>			
		<a href="topsong.php">more</a>
		<br>
		<br>
	</td>
</tr>


<tr>
	<td width="300"> <h3>Top song by genre</h3>
		<a id="displayText1" href="javascript:toggle(1);" style="text-decoration: none; color:black;">+</a> Pop 
		<div id="toggleText1" style="display: none"><iframe height="300px" scrolling="no" frameborder="0" src="topsonggenre.php?termid=77"></iframe></div>
		<br>
		
		<a id="displayText3" href="javascript:toggle(3);" style="text-decoration: none; color:black;">+</a> Electronic 
		<div id="toggleText3" style="display: none"><iframe height="300px" scrolling="no" frameborder="0" src="topsonggenre.php?termid=41"></iframe></div>
		<br>
		
		<a id="displayText2" href="javascript:toggle(2);" style="text-decoration: none; color:black;">+</a> Rock 
		<div id="toggleText2" style="display: none"><iframe height="300px" scrolling="no" frameborder="0" src="topsonggenre.php?termid=8"></iframe></div>
		<br>
		
		
		
		<a id="displayText4" href="javascript:toggle(4);" style="text-decoration: none; color:black;">+</a> Hip hop 
		<div id="toggleText4" style="display: none"><iframe height="300px" scrolling="no" frameborder="0" src="topsonggenre.php?termid=36"></iframe></div>
		<br>
		
		<a id="displayText5" href="javascript:toggle(5);" style="text-decoration: none; color:black;">+</a> Jazz 
		<div id="toggleText5" style="display: none"><iframe height="300px" scrolling="no" frameborder="0" src="topsonggenre.php?termid=29"></iframe></div>
		<br>
		<br>
		<a href="topsongterm.php">more</a>
		<br>
		<br>
	</td>
</tr>


<tr>
<td width="300"> 
		<h3>Top album</h3> 
		<table class='result'  width="280">
<?php
			$query="SELECT Album.alid,Album.alname FROM Song, Album, (SELECT sid, avgrating FROM AvgRating WHERE cnt>10) AS s WHERE Song.sid=s.sid AND Album.alid=Song.alid GROUP BY Album.alid ORDER BY MAX(avgrating) DESC LIMIT 5";
			$result=mysql_query($query);
			$num=mysql_numrows($result);
						
			$i=0;
			while($i<$num){
				$alid=mysql_result($result,$i,"Album.alid");
				$alname=mysql_result($result,$i,"Album.alname");
				$rank=$i+1;
				print("	<tr><td><a href='album.php?alid=$alid'>$rank. $alname</a></td></tr>");
				$i++;
			}

?>
		</table>			
		<a href="topalbum.php">more</a>
		<br>
		<br>
	</td>
</tr>

<tr>
<td width="300"> 
		<h3>Top artist</h3> 
		<table class='result'  width="250">
<?php
			$query="SELECT Artist.atid, Artist.atname FROM Song, Artist, (SELECT sid, avgrating FROM AvgRating WHERE cnt>10) AS s WHERE Song.sid=s.sid AND Artist.atid=Song.atid GROUP BY Artist.atid ORDER BY MAX(avgrating) DESC LIMIT 5";
			$result=mysql_query($query);
			$num=mysql_numrows($result);
						
			$i=0;
			while($i<$num){
				$atid=mysql_result($result,$i,"Artist.atid");
				$atname=mysql_result($result,$i,"Artist.atname");
				$rank=$i+1;
				print("	<tr><td><a href='artist.php?atid=$atid'>$rank. $atname</a></td></tr>");
				$i++;
			}

?>
		</table>			
		<a href="topartist.php">more</a>
		<br>
		<br>
	</td>
</tr>



<?php
//<tr>
//	<td width="300"> <h3>Top-5 song rating</h3>
//		<div id="toggleText1" style="display: none">You see what your
//		recommend</div>
//		<a id="displayText1" href="javascript:toggle(1);">more</a><br>
//	</td>
//</tr>
?>


</table>


<script language="javascript"> 
function toggle(number) {
	var ele = document.getElementById("toggleText"+number);
	var text = document.getElementById("displayText"+number);
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		text.innerHTML = "+";
  	}
	else {
		ele.style.display = "block";
		text.innerHTML = "--";
	}
} 
</script>

<?php
	include_once 'tailer.php';
?>
