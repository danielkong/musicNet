<?php
  include_once 'header.php';

	$atid = $_GET["atid"];
	if($atid){	
		mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
		@mysql_select_db(DB_DB) or die("Unable to select database");
		
		$query="SELECT * FROM Artist WHERE atid='$atid'";
		$result=mysql_query($query);
		$atnum=mysql_numrows($result);
		
		if($atnum>0){
			$atid=mysql_result($result,0,"atid");
			$atname=mysql_result($result,0,"atname");
			$atname=($atname=="")?"&nbsp;":$atname;
			$atlocation=mysql_result($result,0,"atlocation");
			$atlocation=($atlocation=="")?"&nbsp;":$atlocation;

			$sid=mysql_result($result,0,"sid");
			$alid=mysql_result($result,0,"alid");
			

						
			if($sid){
				$squery="SELECT * FROM Song WHERE sid='$sid'";
				$sresult=mysql_query($squery);
				$snum=mysql_numrows($sresult);
				if($snum>0){
					$sname=mysql_result($sresult,0,"sname");
					$slocation=mysql_result($sresult,0,"slocation");
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
				$query2="SELECT DISTINCT Album.alname, Album.alid FROM Album,Song WHERE Song.atid='$atid' AND Album.alid = Song.alid";
				
				//$query2="SELECT alname FROM Album WHERE alid IN (SELECT Distinct alid FROM Song WHERE atid='$atid')";
				$result2=mysql_query($query2);
				$atnum2=mysql_numrows($result2);
				
				if($atnum2>0){
					$atid_album=mysql_result($result2,0,"alname");
					$atid_album=($atid_album=="")?"&nbsp;":$atid_album;

				}
		}
	}
		
		
							
?>

	<table class='result'>
	<?php if($atnum==0){
			echo "<tr><td>No result</td></tr>";
		}else{?>
		<tr><th colspan='2'>Artist information</th></tr>
	<?php	

		$jsrc = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=album%20".str_replace(" ", "%20", artist."%20".$atname."%20".$atlocation);
		$json = file_get_contents($jsrc);
		$jset = json_decode($json, true);
		print("<tr><td colspan='2' align='center'><img width='128' height='128' src='".$jset["responseData"]["results"][0]["tbUrl"]."'/></a></td></tr>");
	?>
		<tr><td>ID </td><td align='center'> <?php echo $atid;?></td></tr>
		<tr><td>Name  </td><td align='center'> <?php echo $atname;?></td></tr>
		<tr><td>Location  </td><td align='center'> <?php echo $atlocation;?></td></tr>
		
	<?php } ?>
	
	


	<?php if($atnum2==0){
			echo "<tr><td>Null</td></tr>";
		}else{
			$i=0;
			while($i<$atnum2){
				$alname2=mysql_result($result2,$i,"alname");
				$alid2=mysql_result($result2,$i,"alid");
				
				$i2=$i+1;
				
				print(" <tr><td>Album $i2 </td><td align='center'>  <a href='album.php?alid=$alid2'>$alname2</a></td></tr>");
				$i++;
			}
		} ?>

	</table>

<?php
	include_once 'tailer.php';
?>
