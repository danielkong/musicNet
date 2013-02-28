<?php
  include_once 'header.php';

	$termid=$_GET["termid"];
	$weight=$_GET["weight"];
	$sid=$_GET["sid"];

		mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
		@mysql_select_db(DB_DB) or die("Unable to select database");
	

		$Termnamequery="SELECT * FROM Term WHERE termid='$termid'";
		$Termnameresult=mysql_query($Termnamequery);
		$termname=mysql_result($Termnameresult,0,"termname");
	print("<tr><td>Type: $termname</tr></td><br>");
	print("<tr><td>Weight: $weight</tr></td><br>");

		$query="SELECT * FROM SongHasTerm WHERE termid='$termid' AND weight='$weight'";
		$result=mysql_query($query);
		$sidnum=mysql_numrows($result);

		if($sidnum==0){
			echo "<tr><td>Null</td></tr>";
		}else{
			$i=0;
			while($i<$sidnum){
				$rsid=mysql_result($result,$i,"sid");
				$i2=$i+1;
				$query2="SELECT * FROM Song WHERE sid='$rsid'";
				$result2=mysql_query($query2);
				$rsname=mysql_result($result2,0,"title");
		//		echo $rsname;	
		//		echo "<br>";
?>				
				
<?php				print("	<tr><td>Same type and weight Song $i2: <a href='song.php?sid=$rsid'>$rsname</a></td></tr><br>");?>
<?php				$i++;
			}	
	}
?>

<?php
	include_once 'tailer.php';
?>
