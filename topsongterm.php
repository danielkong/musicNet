<?php
  include_once 'header.php';

	mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
	@mysql_select_db(DB_DB) or die("Unable to select database");
		
	$termid=$_GET["termid"];
	
?>



<h3>Top song by term</h3> 

<table width="800">
<tr><td>
<?php
	if($termid){
		$query="SELECT termname FROM Term WHERE termid=$termid";
		$result=mysql_query($query);
		$num=mysql_numrows($result);
		
		
		
		print("<table class='result' width='600'>");
		if($num>0){
			print("<tr><th>".mysql_result($result,$i,"termname")."</th></tr>");
		}
		
		$query="SELECT AvgRating.sid, avgrating, title, Artist.atname FROM AvgRating,Song,SongHasTerm, Artist WHERE Artist.atid= Song.atid AND AvgRating.sid=Song.sid AND SongHasTerm.sid=Song.sid AND SongHasTerm.termid='$termid' AND cnt>10 ORDER BY AvgRating DESC LIMIT 10";
		$result=mysql_query($query);
		$num=mysql_numrows($result);
	
		$i=0;
		while($i<$num){
			$sid=mysql_result($result,$i,"AvgRating.sid");
			$title=mysql_result($result,$i,"title");
			$avgrating=mysql_result($result,$i,"avgrating");
			$artist=mysql_result($result,$i,"Artist.atname");
			$rank=$i+1;
			print("	<tr><td><a href='song.php?sid=$sid'>$rank. $title</a> - $artist</td></tr>");
			$i++;
		}
		
		print("</table>");
	}
	
	//CREATE TABLE TopTermFreq AS (SELECT SongHasTerm.termid,count(*) AS cnt, Term.termname FROM SongHasTerm,Term WHERE Term.termid=SongHasTerm.termid GROUP BY termid ORDER BY cnt DESC LIMIT 20);
	$query="SELECT * From TopTermFreq LIMIT 30";
	$result=mysql_query($query);
	$num=mysql_numrows($result);
	$i=0;
	while($i<$num){
		$tid=mysql_result($result,$i,"termid");
		$tname=mysql_result($result,$i,"termname");
		print("[<a href='topsongterm.php?termid=$tid'>$tname</a>] ");
		$i++;
	}							
?>
</td></tr>

<?php 
	
?>
	
	
	



</table>		

<?php
	include_once 'tailer.php';
?>
