<?php
  	include_once 'header.php';

		session_start(); 
		$uname=$_SESSION['uname'];
		$uid = $_SESSION["uid"];
		$sid = $_GET["sid"];

		mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
		@mysql_select_db(DB_DB) or die("Unable to select database");
		
		$query="SELECT Song.title FROM Song WHERE sid='$sid'";
		$result=mysql_query($query);
		$sname=mysql_result($result,0,'title');

		$visitquery="SELECT User.uname, User.uid, Visited.datetime, Visited.count FROM Visited, User WHERE Visited.sid='$sid' AND Visited.uid=User.uid ORDER BY datetime";
		$visitresult=mysql_query($visitquery);
		$visitnum=mysql_numrows($visitresult);

		print("<h2> $sname Log Page </h2>");
		print("<table calss='result' border='1' width='600'> ");
		print("<tr><td width='200'>User Name</td><td width='350'>Last Visited Time</td><td width='50'>Count</td></tr>");

		$i=0;
		$sum=0;
		while($i<$visitnum){
			$uid=mysql_result($visitresult,$i,"User.uid");
			$uname=mysql_result($visitresult,$i,"User.uname");
			$datetime=mysql_result($visitresult,$i,"Visited.datetime");
			$count=mysql_result($visitresult,$i,"Visited.count");

			print("<tr><td><a href='user.php?uid=$uid'> $uname</a></td><td>$datetime</td><td>$count</td></tr>");
			$sum=$sum+$count;
			$i++;
		}
		print("</table>");
		
		print("<br><h3>The total number of this song has been visited is $sum.</h3>");

?>
		
<?php
		include 'tailer.php';
?>

