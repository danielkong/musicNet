<?php
  include_once 'header.php';

	$alid = $_GET["alid"];
	if($alid){	
		mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
		@mysql_select_db(DB_DB) or die("Unable to select database");
		
		$query="SELECT * FROM Album WHERE alid='$alid'";
		$result=mysql_query($query);
		$alnum=mysql_numrows($result);
		
		if($alnum>0){
			$alname=mysql_result($result,0,"alname");
			$alname=($alname=="")?"&nbsp;":$alname;

						
			if($sid){
				$squery="SELECT * FROM Song WHERE sid='$sid'";
				$sresult=mysql_query($squery);
				$snum=mysql_numrows($sresult);
				if($snum>0){
					$sname=mysql_result($sresult,0,"sname");
					$slocation=mysql_result($sresult,0,"slocation");
				}
			}
			
			if($atid){
				$atquery="SELECT * FROM Artist WHERE atid='$atid'";
				$atresult=mysql_query($atquery);
				$atnum=mysql_numrows($atresult);
				if($alnum>0){
					$alname=mysql_result($alresult,0,"alname");
				}
			}
			if($uid!=null){
				$rquery="SELECT * FROM Rate WHERE uid='$uid' AND sid=$sid";
				$rresult=mysql_query($rquery);
				$rnum=mysql_numrows($rresult);
				if($rnum>0){
					$rating=mysql_result($rresult,0,"rating");
				}
			}
				$query2="SELECT * FROM Artist,Song WHERE Song.alid='$alid' AND Artist.atid = Song.atid";
				
				//$query2="SELECT alname FROM Album WHERE alid IN (SELECT Distinct alid FROM Song WHERE atid='$atid')";
				$result2=mysql_query($query2);
				$alnum2=mysql_numrows($result2);
				
				if($alnum2>0){
					$year=mysql_result($result2,0,"year");
					$year=($year=="")?"&nbsp;":$year;
					

				}
				
//				avg rating for each album
				$query3="SELECT AVG(rating) FROM Song, Rate WHERE Rate.sid=Song.sid AND Song.alid='$alid'";			
				$result3=mysql_query($query3);
				$ratingnum=mysql_numrows($result3);
				$avgalbum=mysql_result($result3,0,"AVG(rating)");
				$avgalbum=($avgalbum=="")?"&nbsp;":$avgalbum;

		}
		
	}
		
		
							
?>

	<table class='result'>
	<?php if($alnum==0){
			echo "<tr><td>No result</td></tr>";
		}else{?>
		<tr><th colspan='2'>Album information</th></tr>
	<?php	
		$jsrc = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=album%20".str_replace(" ", "%20", $alname."%20".$year);
		$json = file_get_contents($jsrc);
		$jset = json_decode($json, true);
		print("<tr><td colspan='2' align='center'><img width='128' height='128' src='".$jset["responseData"]["results"][0]["tbUrl"]."'/></td></tr>");
	?>
		<tr><td> ID</td><td align='center'> <?php echo $alid;?></td></tr>
		<tr><td> Name</td><td align='center'> <?php echo $alname;?></td></tr>
		<tr><td> Year</td><td align='center'> <?php echo $year;?></td></tr>
		<tr><td> Rating</td><td align='center'> <?php echo $avgalbum;?></td></tr>
	
	<?php } ?>
	
	


	<?php if($alnum2==0){
			echo "<tr><td>Null</td></tr>";
		}else{
			$i=0;
			while($i<$alnum2){
				$title=mysql_result($result2,$i,"title");
				$sid=mysql_result($result2,$i,"sid");
				
				$i2=$i+1;
				
				print("	<tr><td> Song $i2 </td><td align='center'> <a href='song.php?sid=$sid'>$title</a></td></tr>");
				$i++;
			}
		} ?>

	

	</table>

<?php
	include_once 'tailer.php';
?>
