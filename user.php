<?php
  include 'header.php';
	session_start(); 
	$cuid=$_SESSION['uid'];
	$uname=$_SESSION['uname'];
	$uid = $_GET["uid"];
	$action = $_GET["run"];


	mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
	@mysql_select_db(DB_DB) or die("Unable to select database");


	function addfriend(){ 


		$cuid=$_SESSION['uid'];
		$uname=$_SESSION['uname'];
		$uid = $_GET["uid"];
		$action = $_GET["run"];
		$currenttime=date("Ymdhis");

    		 $addfriendquery = "INSERT INTO Friend(uid1,uid2,datetime) VALUES('$cuid','$uid', '$currenttime')";
		 mysql_query($addfriendquery);



	} 

	function unfriend(){ 
		$cuid=$_SESSION['uid'];
		$uname=$_SESSION['uname'];
		$uid = $_GET["uid"];
		$action = $_GET["run"];

   		 $deletefriendquery = "DELETE FROM Friend where '$cuid' = uid1 AND '$uid' = uid2 OR '$cuid' = uid2 AND '$uid' = uid1";
		 mysql_query($deletefriendquery);



	} 

	switch($action){ 

	case 'addfriend' : 
    		addfriend(); 
    		break; 
	
	case 'unfriend' : 
		unfriend(); 
    		break; 
 

	} 

?>



<?php



	if($uid!=null){
		
		$query="SELECT * FROM User WHERE uid='$uid'";
		$result=mysql_query($query);

		
		$numrows=mysql_numrows($result);
		$numcols=mysql_num_fields($result);

		if($numrows>0){
			$uname=mysql_result($result,0,"uname");
			$uname=($uname=="")?"&nbsp;":$uname;
			$age=mysql_result($result,0,"age");
			$age=($age=="")?"&nbsp;":$age;
			$gender=mysql_result($result,0,"gender");
			$gender=($gender=="")?"&nbsp;":$gender;
			$ulocation=mysql_result($result,0,"ulocation");
			$ulocation=($ulocation=="")?"&nbsp;":$ulocation;
			

			$friendquery="SELECT uid2 FROM Friend WHERE uid1='$uid' UNION SELECT uid1 FROM Friend WHERE uid2='$uid'";
			$friendqueryresult=mysql_query($friendquery);


			$ratequery="SELECT * FROM Rate WHERE uid='$uid'";
			$ratequeryresult=mysql_query($ratequery);


		}


		$friendsuggestionquery = "SELECT * From (SELECT uid FROM Rate rate2, (SELECT sid FROM Rate rate1 WHERE rate1.uid = '$cuid' AND (rate1.rating = 4 OR rate1.rating = 5)) AS likedsongs WHERE rate2.sid = likedsongs.sid AND (rate2.rating = 4 OR rate2.rating = 5)) AS G1 WHERE G1.uid NOT IN (SELECT uid2 AS uid FROM Friend WHERE uid1='$cuid' UNION SELECT uid1 AS uid FROM Friend WHERE uid2='$cuid') AND G1.uid !='$cuid'";
		$friendsuggestionresult = mysql_query($friendsuggestionquery);
	}

		
?>

<table> <tr><td>
<table class='result' width> <tr><td>
       	<table class='result'>
		
		<tr><th><?php echo "Name:";?></th><td><?php echo $uname;?></td></tr>
		<tr><th><?php echo "Age:";?></th><td><?php echo $age;?></td></tr>
		<tr><th><?php echo "Gender:";?></th><td><?php echo $gender;?></td></tr>
		<tr><th><?php echo "Location:";?></th><td><?php echo $ulocation;?></td></tr>
		

		<?php
			$cuid=$_SESSION['uid'];
			$uname=$_SESSION['uname'];
			$uid = $_GET["uid"];
			$action = $_GET["run"];

			$isfriendquery="SELECT * FROM Friend WHERE uid1='$cuid' AND uid2='$uid' OR uid1='$uid' AND uid2='$cuid'";
			$isfriendqueryresult=mysql_query($isfriendquery);
			$isfriend=mysql_numrows($isfriendqueryresult);


			

			 ?>
		

	</td>
	<td colspan=2> 
<?php	
		
		if($cuid!=$uid&&$isfriend==0){ 
				echo "<a href='user.php?uid=$uid&run=addfriend'>Add Friend</a> ";

			 }
			else if($cuid!=$uid){ 
				echo " &#10003; Friends. <a href='user.php?uid=$uid&run=unfriend'>Unfriend</a> ";
		 
			 }
		

?>
	</td>
</tr>
</table>
     <tr><td>
		<table class='result'>
		<?php 
		echo "<tr><th>Friends: <a href='friendlist.php?uid=$uid'> All Friends</a> </td> <th>Location</th></tr>"; ?>
		<?php 
		$numrow = mysql_numrows($friendqueryresult);

		
			
		//echo $numrow;
		$minrow = min($numrow,5);
		//echo $minrow;
		$recordA = array();
		echo "$recordA[10000]";
		for($i=0;$i<$minrow ;$i++){
			$chosenindex = rand(0,mysql_numrows($friendqueryresult)-1);
			$chosenuserid = mysql_result($friendqueryresult,$chosenindex ,"uid2");
			if($recordA[$chosenuserid]==null){
			
				$chosenusernamequery="SELECT * FROM User WHERE uid=$chosenuserid";
				$chosenusernamequeryresult=mysql_query($chosenusernamequery);
				$chosenusername = mysql_result($chosenusernamequeryresult,0,"uname");
				$chosenuserlocation = mysql_result($chosenusernamequeryresult,0,"ulocation");
				echo "<tr><td width = 300><a href='user.php?uid=$chosenuserid'>$chosenusername</a> </td> <td width = 300> $chosenuserlocation</td></tr>";
				$recordA[$chosenuserid]=1;
			}
			else{
				$i--;
			}
		}

			

		if($numrow==0){
			echo "<tr><td>None<td><tr>";}

		?>
		
		
		
		</table>

	
</td><tr>
<tr><td>

<table class='result'>
		<tr><th>Song Title:</th><th>Artist</th><th>Rating</th></tr>
		<?php 
		$numrow = mysql_numrows($ratequeryresult);
		//echo $numrow;
		$minrow = min($numrow,5);
		//echo $minrow;
		$recordA = array();
		for($i=0;$i<$minrow ;$i++){
			$chosenindex = rand(0,mysql_numrows($ratequeryresult)-1);
			$chosensongid = mysql_result($ratequeryresult,$chosenindex ,"sid");
			$chosensongrating = mysql_result($ratequeryresult,$chosenindex ,"rating");
			if($recordA[$chosensongid]==null){
			
				$chosensongquery="SELECT * FROM Song WHERE sid='$chosensongid'";
				$chosensongqueryresult=mysql_query($chosensongquery);
				$chosensongtitle = mysql_result($chosensongqueryresult,0,"title");
				$chosensongartistid = mysql_result($chosensongqueryresult,0,"atid");
				$chosensongartistnamequery = "SELECT * FROM Artist WHERE atid='$chosensongartistid'";
				$chosensongartistnamequeryresult =mysql_query($chosensongartistnamequery);
				$chosensongartistname = mysql_result($chosensongartistnamequeryresult ,0,"atname");
				echo "<tr><td width = 300><a href='song.php?sid=$chosensongid'>$chosensongtitle</a> </td> <td width = 250> <a href='artist.php?atid=$chosensongartistid'>$chosensongartistname</a> </td>  <td width = 50>$chosensongrating</td></tr>";
				$recordA[$chosensongid]=1;
			}
			else{
				$i--;
			}
		}

		if($numrow==0){
			echo "<tr><td>None</td></tr>";}
		

		?>



		</table>
</td></tr>

</table>
     
</td>

<td>



<?php

		if($uid==$cuid){
		$tnum = mysql_numrows($friendsuggestionresult);
	
		//echo "$tnum";		
		echo "<table class = 'result' width = 200><th> Friend Suggestion </th>";

		if($tnum==0){
			echo "<tr><td>None</td></tr>";
			//echo mysql_numrows($friendsuggestionresult);
		}
		else {	
		//echo $numrow;
			$minirow = min($tnum,20);
			//echo "<tr><td>$minirow</td></tr>";
			$recordC = array();
		
			
			for($i=0;$i<$minirow;$i++){
				$chosenindex = rand(0,$tnum-1);
				$chosenuserid = mysql_result($friendsuggestionresult,$chosenindex);
				
								

				

				if($recordC[$chosenuserid]==null){
					$fnamequery = "select * from User where uid='$chosenuserid'";
					$fnameresult = mysql_query($fnamequery);
					$fname = mysql_result($fnameresult, 0, "uname");
					//echo "<tr><td>$minirow</td></tr>";

					echo "<tr><td> <a href='user.php?uid=$chosenuserid'>$fname</a> </td></tr>";
					$recordC[$chosenuserid]=1;
					
				}

				else { $i--;}
				
			}


		}
		}

?>


</table>

</table>

<br>


<?php
	echo "<br><br><br><br><br>";
	include_once 'tailer.php';
?>
