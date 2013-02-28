<?php
  include 'header.php';
	session_start(); 
	$cuid=$_SESSION['uid'];
	$uname=$_SESSION['uname'];

?>

<?php
	$uid = $_GET["uid"];

	if(isset($cuid)){
		mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
		@mysql_select_db(DB_DB) or die("Unable to select database");

		$friendquery="SELECT uid, uname, gender, age, ulocation FROM User ,(SELECT uid2 FROM Friend WHERE uid1='$uid' UNION SELECT uid1 FROM Friend WHERE uid2='$uid') as Fri where User.uid = Fri.uid2 ORDER BY uname";

		$result=mysql_query($friendquery);

		$numrows=mysql_numrows($result);


		if($numrows==0){
			print("No friends.<br>");
			
		}else{
			echo "All friends ";
				
			print("<table class='result'>");

			print("<tr><th>Name<th><th>Gender<th><th>Age<th><th>Location<th><tr>");

			for($i=0;$i<$numrows;$i++){
				$list = "	<tr>";
				$currentuid = mysql_result($result,$i,0);
				for($j=1;$j<=4;$j++){
					$item=mysql_result($result,$i,$j);
					if($j==1){
						$list.="<td><a href='user.php?uid=$currentuid'>$item</a><td>";}
					else $list.="<td>$item<td>";
				}
				$list.="<tr>";
					
				print("$list");
			}

			

		
		}
		print("</table>");
	}	
?>




<?php
	include_once 'tailer.php';
?>
