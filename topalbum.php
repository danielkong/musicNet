<?php
  include_once 'header.php';

	mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
	@mysql_select_db(DB_DB) or die("Unable to select database");
		
	$page=$_GET["page"];
	if($page){
		if($page>9||$page<0){
			$offset=0;
		}else{
			$offset=$page*10;
		}
	}else{
		$offset=0;
	}
	$startoffset=$offset+1;
	$endoffset=$offset+10;
	
	
?>

<h3>Top album by rating</h3> 

<?php echo "<h4>Rank $startoffset-$endoffset</h4>";?>
<table width="800" class='result'>

<?php
	
	$query="SELECT Album.alid,Album.alname, Artist.atname FROM Song, Album, Artist, (SELECT sid, avgrating FROM AvgRating WHERE cnt>10) AS s WHERE Song.sid=s.sid AND Album.alid=Song.alid AND Artist.atid=Song.atid GROUP BY Album.alid ORDER BY MAX(avgrating) DESC LIMIT 10 OFFSET $offset";
	$result=mysql_query($query);
	$num=mysql_numrows($result);
	
	$i=0;
	while($i<$num){
		$alid=mysql_result($result,$i,"Album.alid");
		$alname=mysql_result($result,$i,"Album.alname");
		$atname=mysql_result($result,$i,"Artist.atname");
		$rank=($page*10)+$i+1;
		print("	<tr><td><a href='album.php?alid=$alid'>$rank. $alname</a> - $atname</td></tr>");
		$i++;
	}
		
							
?>

</table>		
<a href="topalbum.php">[1-10]</a>
<a href="topalbum.php?page=1">[11-20]</a>
<a href="topalbum.php?page=2">[21-30]</a>
<a href="topalbum.php?page=3">[31-40]</a>
<a href="topalbum.php?page=4">[41-50]</a>
<a href="topalbum.php?page=5">[51-60]</a>
<a href="topalbum.php?page=6">[61-70]</a>
<a href="topalbum.php?page=7">[71-80]</a>
<a href="topalbum.php?page=8">[81-90]</a>
<a href="topalbum.php?page=9">[91-100]</a>

<?php
	include_once 'tailer.php';
?>
