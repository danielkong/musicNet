
<style type="text/css">
  		.result { margin: 1em; border-collapse: collapse; }
			.result td { padding: .3em; border: 1px #ccc solid; background: #FFFFFF; }
			.result th { background: #fc9; padding: .3em; border: 1px #ccc solid; }
			
			.ranktable { margin: 1em; border-collapse: collapse; }
			.ranktable td { padding: .3em; border: 1px #ccc solid; }
			.ranktable th { background: #fc9; padding: .3em; border: 1px #ccc solid; }
			body { font-family: Helvetica; }
		</style>
<table class='result'  width="280">
<?php
	include_once 'setting.php';
	mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
	@mysql_select_db(DB_DB) or die("Unable to select database");
		
	$termid=$_GET["termid"];
	if($termid){
		$query="SELECT AvgRating.sid, avgrating, title, Artist.atname FROM AvgRating,Song,SongHasTerm, Artist WHERE Artist.atid= Song.atid AND AvgRating.sid=Song.sid AND SongHasTerm.sid=Song.sid AND SongHasTerm.termid='$termid' AND cnt>10 ORDER BY AvgRating DESC LIMIT 5";
		$result=mysql_query($query);
		$num=mysql_numrows($result);
	
		$i=0;
		while($i<$num){
			$sid=mysql_result($result,$i,"AvgRating.sid");
			$title=mysql_result($result,$i,"title");
			$avgrating=mysql_result($result,$i,"avgrating");
			$artist=mysql_result($result,$i,"Artist.atname");
			$rank=$i+1;
			print("	<tr><td style='background: #FFFFFF;'><a href='song.php?sid=$sid' target='_blank'>$rank. $title</a><br>$artist</td></tr>");
			$i++;
		}
	}
?>
</table>
