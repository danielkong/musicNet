<?php
  include_once 'header.php';

	session_start(); 
	$uname=$_SESSION['uname'];
	$uid = $_GET["uid"];
	$rating = $_GET["rating"];
	$sid = $_GET["sid"];

		mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
		@mysql_select_db(DB_DB) or die("Unable to select database");



	if($sid){	
		
		$currenttime=date("Y-m-d h:i:s");
		$query="SELECT * FROM Song WHERE sid='$sid'";
		$result=mysql_query($query);
		$snum=mysql_numrows($result);
	
		if($snum>0){
			$sid=mysql_result($result,0,"sid");
			$title=mysql_result($result,0,"title");
			$year=mysql_result($result,0,"year");
			$year=($year=="")?"&nbsp;":$year;
			$duration=mysql_result($result,0,"duration");
			$duration=($duration=="")?"&nbsp;":$duration;
			$loudness=mysql_result($result,0,"loudness");
			$loudness=($loudness=="")?"&nbsp;":$loudness;
			$atid=mysql_result($result,0,"atid");
			$alid=mysql_result($result,0,"alid");
			
			if($atid){
				$atquery="SELECT * FROM Artist WHERE atid='$atid'";
				$atresult=mysql_query($atquery);
				$atnum=mysql_numrows($atresult);
				if($atnum>0){
					$atname=mysql_result($atresult,0,"atname");
					$atlocation=mysql_result($atresult,0,"atlocation");
				}
			}
			
			if($alid){
				$alquery="SELECT * FROM Album WHERE alid='$alid'";
				$alresult=mysql_query($alquery);
				$alnum=mysql_numrows($alresult);
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
						
		}
	}
		
		
							
?>
<h2>Song Information</h2>
<?php			
//************	visited table ***********
	session_start(); 
	$uname=$_SESSION['uname'];
	$uid = $_SESSION["uid"];

	if($uid&&$sid){
			$vquery="SELECT * FROM Visited WHERE uid='$uid' AND sid='$sid'";
			$vresult=mysql_query($vquery);
			$vnum=mysql_numrows($vresult);	
//			echo "<h2>$vnum</h2>";
			if($vnum==0){
				$currenttime=date("Y-m-d h:i:s");
				$k=1;
				$addvisit="INSERT INTO Visited(uid, sid, count, datetime) VALUES ('$uid','$sid','$k','$currenttime')";
				mysql_query($addvisit);
				echo "<h5>You visited this song times: $k</h5>";				
			}else{
				$currenttime=date("Y-m-d h:i:s");
				$lastcountquery="SELECT Visited.count FROM Visited WHERE uid='$uid' AND sid='$sid'";
				$countresult=mysql_query($lastcountquery);
				$lastcount=mysql_result($countresult,0,"count");
				$newcount=$lastcount+1;
			 	$editvisit="update Visited set count=$newcount, datetime='$currenttime' where uid='$uid' and sid='$sid'";
				mysql_query($editvisit);			
				echo "<h5>You visited this song times: $newcount</h5>";	
			}	
	}	?>
<table>
<tr>
	<td valign="top"  width="600">
		<table class='result' border="1" width="500">
	<?php if($snum==0){
			echo "<tr><td>No result</td></tr>";
		}else{?>

	<?php	

		$jsrc = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=album%20".str_replace(" ", "%20", $alname."%20".$atname);
		$json = file_get_contents($jsrc);
		$jset = json_decode($json, true);
		print("<tr><td colspan='2' align='center'><img width='128' height='128' src='".$jset["responseData"]["results"][0]["tbUrl"]."'/></a></td></tr>");
	?>
		<tr><td>Title</td><td align='center'> <?php echo $title;?></td></tr>
		<tr><td>Song ID</td><td align='center'> <?php echo $sid;?></td></tr>
		<tr><td>Year</td><td align='center'> <?php echo $year;?></td></tr>
		<tr><td>Duration</td><td align='center'> <?php echo $duration;?></td></tr>
		<tr><td>Loundness</td><td align='center'> <?php echo $loudness;?></td></tr>
	<?php } ?>
	
	<?php 

		if($atnum==0){
			echo "<tr><td align='center'>No artist</td></tr>";
		}else{?>
		
		<tr><td>Atrist name</td><td align='center'><a href='artist.php?atid=<?php echo $atid;?>'> <?php echo $atname;?></a></td></tr>
		<tr><td>Location</td><td align='center'> <?php echo $atlocation;?></td></tr>		

	<?php } ?>
	
	<?php if($alnum==0){
			echo "<tr><td align='center'>No album</td></tr>";
		}else{?>
		<tr><td>Album name</td><td align='center'><a href='album.php?alid=<?php echo $alid;?>'> <?php echo $alname;?></a> </td></tr>
	<?php } 
	
	session_start(); 

	$uname=$_SESSION['uname'];
	$uid = $_SESSION["uid"];


		
	if($uid!=null){
		
		if($rating!=null){
			$ratequery="SELECT * FROM Rate WHERE uid='$uid' AND sid='$sid'";
			$rateresult=mysql_query($ratequery);
			$ratenum=mysql_numrows($rateresult);
			
			if($ratenum==0){
				$currenttime2=date("Y-m-d h:i:s");
				$addrating="INSERT INTO Rate(uid, sid, rating, datetime) VALUES('$uid','$sid','$rating','$currenttime2')";

				mysql_query($addrating);
			}else{
			
				$currenttime3=date("Y-m-d h:i:s");
			 	$editrating="UPDATE Rate SET rating='$rating', datetime='$currenttime3' WHERE uid='$uid' AND sid='$sid'";
				mysql_query($editrating);
			
			}	
			echo "<tr><td>Rating</td><td align='center'> $rating</td></tr>";	
		}else{
			$rquery="SELECT * FROM Rate WHERE uid='$uid' AND sid='$sid'";
			$rresult=mysql_query($rquery);
			$rnum=mysql_numrows($rresult);
			if($rnum>0){
				$rating=mysql_result($rresult,0,"rating");
			}			

			if($rnum==0){
				echo "<tr><td>Rating</td><td align='center'>Not rate yet</td></tr>";
			}else
			{
				$rquery="SELECT * FROM Rate WHERE uid='$uid' AND sid='$sid'";
				$rresult=mysql_query($rquery);
				echo "<tr><td>Rating</td><td align='center'> $rating</td></tr>";
			}	
		}
	}	?>
	</table>
	
		<form name='rating' action=song.php method='GET'>
	<table>
		<tr>
			<td><select name='rating'>
					<option value='1' <?php if($_GET["rating"]=="1") echo "selected";?>>1</option>
					<option value='2' <?php if($_GET["rating"]=="2") echo "selected";?>>2</option>
					<option value='3' <?php if($_GET["rating"]=="3") echo "selected";?>>3</option>
					<option value='4' <?php if($_GET["rating"]=="4") echo "selected";?>>4</option>
					<option value='5' <?php if($_GET["rating"]=="5") echo "selected";?>>5</option>
				</select>
			</td>
		<td><input type='hidden' name='sid' <?php echo "value='".$sid."'";?>/><input type='hidden' name='uid' <?php echo "value='".$uid."'";?>/></td>

		<td colspan='2'><input type="submit" value="Rate Song"/></td></tr>
	</table>
</form>	
		<?php		$sid = $_GET["sid"];
			$Termquery="SELECT * FROM SongHasTerm WHERE sid='$sid'";
			$Termresult=mysql_query($Termquery);
			$Termnum=mysql_numrows($Termresult);
			echo "<h4>This song has total $Termnum term(s).</h4>";
?>
		<table class='result' width="500" border="1">
		<?php	
			$sid = $_GET["sid"];
			$Termquery="SELECT * FROM SongHasTerm WHERE sid='$sid'";
			$Termresult=mysql_query($Termquery);
			$Termnum=mysql_numrows($Termresult);
					$i=0;
						echo "<tr><th> Term Number </th> <th> Term </th> <th width='200'> Weight </th></tr>";
//			 <th width=\"70%\">Recommend Same Type Songs</th>
					while($i<$Termnum){

						$weight=mysql_result($Termresult,$i,"weight");
						$termid=mysql_result($Termresult,$i,"termid");
						$Termnamequery="SELECT * FROM Term WHERE termid='$termid'";
						$Termresult2=mysql_query($Termnamequery);
						$Termname=mysql_result($Termresult2,0,"termname");

						$i2=$i+1;
						$weightwidth=200*$weight;
						$weight=number_format($weight*100,0);
						echo "<tr><td>$i2</td> <td align='center'> $Termname</td> <td align='left'><table bgcolor='#FFFF00' width='$weightwidth'><tr bgcolor='#FFFF00'><td bgcolor='#FFFF00'><font size='1'>$weight</font></td></tr></table></td> </tr>";
//			<td><a href='recommendsong.php?termid=$termid&weight=$weight&sid=$sid'>Same Type and Weight Songs</a></td>						
						$i++;
					}
	?>
		</table>		
	</td>
	<td valign="top" width="300" > 
		<h3>User who also visited this song</h3> 
		<table class='result' width='80%'>
		<?php
		$visitquery="SELECT User.uname, User.uid, Visited.datetime FROM Visited, User WHERE Visited.sid='$sid' AND Visited.uid=User.uid ORDER BY datetime DESC LIMIT 6";
		$visitresult=mysql_query($visitquery);
		$visitnum=mysql_numrows($visitresult);
		$i=0;
		while($i<$visitnum){
			$vuid=mysql_result($visitresult,$i,"User.uid");
			$vuname=mysql_result($visitresult,$i,"User.uname");
			$vdatetime=mysql_result($visitresult,$i,"Visited.datetime");
			print("<tr><td><a href='user.php?uid=$vuid'> $vuname</a></td><td>$vdatetime</td></tr>");
			$i++;
		}
		?>
		</table>	
		<a href="visitsong.php?sid=<?php echo $sid?>">more</a>
		<br>
		<br>
				  
		<h3>Other visitors who like current song also listen these songs</h3> 
		<table class='result' width='80%'>
			<?php
				$sid = $_GET["sid"];
				$uquery="SELECT DISTINCT Song.sid, Song.title FROM Song,(SELECT Rate.sid FROM Rate,(SELECT uid FROM Rate WHERE sid='$sid') AS t WHERE Rate.uid=t.uid ORDER BY Rand() LIMIT 8) AS t2 WHERE t2.sid=Song.sid";
				$uresult=mysql_query($uquery);
				$unum=mysql_numrows($uresult);
	//			echo $unum;
				if($unum==0){
					print ("None rate this song yet.");
				}else{
					$i=0;
					while($i<$unum){
						$uersid=mysql_result($uresult,$i,"Song.sid");		
						$uertitle=mysql_result($uresult,$i,"Song.title");		
						print ("<tr><td><a href='song.php?sid=$uersid'> $uertitle</td></tr>");	
						$i++;
					}
				}
			?>
		</table>

		<br>
		<br>
		<table class='result' width='80%' border='1'>
<?php
				$random=rand(0,$Termnum-1);
//				echo "$random";
				$randomtermid=mysql_result($Termresult,$random,"termid");

				$termnamequery="SELECT * FROM Term WHERE termid='$randomtermid'";
				$termnameresult=mysql_query($termnamequery);
				$sametermname=mysql_result($termnameresult,0,"termname");
				echo "<h3>Recommend Songs that you might like (with the same genre: $sametermname)</h3>";
				
				$sameTermquery="SELECT Song.sid, Song.title FROM Song, (SELECT sid FROM SongHasTerm WHERE termid='$randomtermid' LIMIT 10) AS t WHERE Song.sid=t.sid";
				$sameTermresult=mysql_query($sameTermquery);
				$j=0;
				while($j<10){
					$sid4=mysql_result($sameTermresult,$j,"sid");				
					$title4=mysql_result($sameTermresult,$j,"title");
					print ("<tr><td><a href='song.php?sid=$sid4'> $title4</td></tr>");
					$j++;
				}
?>
		</table>
	</td>
</tr>

</table>

<?php
	include 'tailer.php';
?>











