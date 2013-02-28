<?php
  session_start(); 
	$uname=$_SESSION['uname'];
	$uid = $_SESSION["uid"];
	$sid = $_GET["sid"];
?>

<form name='rate' action=song.php method='GET'>
	<table>
		<tr><td>rating: </td><td><input type='text' name='rating' size='10'/>
		<input type='hidden' name='sid' value='<?php echo $sid;?>'/><input type='hidden' name='uid' value='<?php echo $uid;?>'/></td></tr>
		<tr><td colspan='2'><input type="submit" value="Rate Song"/></td></tr>
	</table>
</form>
