<?php
  include_once 'header.php';


	$keyword = $_GET["keyword"];
	if($keyword){	
		$type=$_GET["type"];
		mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
		
		
		if($type=="song"){
			$query="SELECT * FROM Song WHERE sname='$keyword'";
			$result=mysql_query($query);
			$num=mysql_numrows($result);
			
			if($num==0){
				print("<table>");
				print("	<tr><td>No result</td></tr>");
			}else{
				print("$num song(s) found");
				print("<table>");
				print("	<tr><td>Title</td><td>Year</td></tr>");
			}
			for ($i=0; $i<$num; i++) {
				$sid=mysql_result($result,$i,"sid");
				$title=mysql_result($result,$i,"title");
				$year=mysql_result($result,$i,"year");
				$year=($year=="")?"&nbsp;":$year;
				print("	<tr><a href='song.php?sid=$sid'><td>$title</td><td>$year</td></a></tr>");
			}
		}else if($type=="album"){
			$query="SELECT * FROM Album WHERE alname='$keyword'";
			$result=mysql_query($query);
			$num=mysql_numrows($result);
			
			if($num==0){
				print("<table>");
				print("	<tr><td>No result</td></tr>");
			}else{
				print("$num album(s) found");
				print("<table>");
				print("	<tr><td>Name</td></tr>");
			}
			for ($i=0; $i<$num; i++) {
				$alid=mysql_result($result,$i,"alid");
				$alname=mysql_result($result,$i,"alname");
				print("	<tr><a href='album.php?alid=$alid'><td>$alname</td></a></tr>");
			}
		}else if($type=="artist"){
			$query="SELECT * FROM Song WHERE atname='$keyword'";
						$result=mysql_query($query);
			$num=mysql_numrows($result);
			
			if($num==0){
				print("<table>");
				print("	<tr><td>No result</td></tr>");
			}else{
				print("$num artist(s) found");
				print("<table>");
				print("	<tr><td>Name</td></tr>");
			}
			for ($i=0; $i<$num; i++) {
				$atid=mysql_result($result,$i,"atid");
				$atname=mysql_result($result,$i,"atname");
				print("	<tr><a href='artist.php?atid=$atid'><td>$atname</td></a></tr>");
		}else if($type=="friend"){
			$query="SELECT * FROM User WHERE uname='$keyword'";
			$num=mysql_numrows($result);
			
			if($num==0){
				print("<table>");
				print("	<tr><td>No result</td></tr>");
			}else{
				print("$num user(s) found");
				print("<table>");
				print("	<tr><td>Name</td></tr>");
			}
			for ($i=0; $i<$num; i++) {
				$uid=mysql_result($result,$i,"uid");
				$uname=mysql_result($result,$i,"uname");
				print("	<tr><a href='user.php?uid=$uid'><td>$uname</td></a></tr>");
		}
		print("</table>");	
		
	else{
		print("Some content");
	}
?>

</body>
</html>
