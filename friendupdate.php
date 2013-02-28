<?php
  include_once 'setting.php';
	session_start(); 
	$uid=$_SESSION['uid'];
	$uname=$_SESSION['uname'];

	//global var. output number of lines of updated, sorted by timestamp
	$gl = 50;	

	mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
	@mysql_select_db(DB_DB) or die("Unable to select database");

	$visit_full_query = "SELECT Visited.* FROM Visited, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Visited.uid=FD.uid ORDER BY datetime DESC";
	$visit_full_result = mysql_query($visit_full_query);

	$friend_full_query = "SELECT Friend.* FROM Friend, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Friend.uid1=FD.uid OR Friend.uid2=FD.uid ORDER BY datetime DESC";
	$friend_full_result = mysql_query($friend_full_query);


	$rate_full_query = "SELECT Rate.* FROM Rate, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Rate.uid=FD.uid ORDER BY datetime DESC";
	$rate_full_result = mysql_query($rate_full_query);


	$combined_time_query = "SELECT datetime From ((SELECT Visited.datetime FROM Visited, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Visited.uid=FD.uid ORDER BY datetime DESC) UNION (SELECT Friend.datetime FROM Friend, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Friend.uid1=FD.uid OR Friend.uid2=FD.uid ORDER BY datetime DESC) UNION (SELECT Rate.datetime FROM Rate, (SELECT User.uid FROM User, ((SELECT uid2 AS uid FROM Friend WHERE uid1='$uid') UNION (SELECT uid1 as uid FROM Friend WHERE uid2='$uid')) AS FDUNION WHERE User.uid = FDUNION.uid) AS FD WHERE Rate.uid=FD.uid ORDER BY datetime DESC)) AS dt ORDER BY datetime DESC LIMIT 100";
	$combined_time_result = mysql_query($combined_time_query);

	if(isset($uid)){

	echo "<table>";

	for($i=0;$i<$gl;$i++){
		$dt = mysql_result($combined_time_result,$i);
		
		$a=0;
		$b=0;
		$c=0;

		//Visit table
		for($j=0;$j<$gl;$j++){
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

		
		//Friend table
		for($j=0;$j<$gl;$j++){
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
		for($j=0;$j<$gl;$j++){
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
		
?>






<?php
	include_once 'tailer.php';
?>
