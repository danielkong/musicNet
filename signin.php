<?php
  include_once 'setting.php';
	$username = $_POST["username"];
	if($username){
		$password=$_POST["password"];
		mysql_connect(DB_HOST,DB_USER,DB_PW) or die("Can't connect");
		@mysql_select_db(DB_DB) or die("Unable to select database");
		$query="SELECT * FROM User WHERE uname='$username' AND password='$password'";
		$result=mysql_query($query);
		if(mysql_numrows($result)>0){
			session_start(); 
			$_SESSION['uid']=mysql_result($result,$i,"uid");
			$_SESSION['uname']=mysql_result($result,$i,"uname");
			header( 'Location: index.php' ) ;
		}else{
			echo "username or password is incorrect";	
		}
	}
?>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<style type="text/css">
			.result { margin: 1em; border-collapse: collapse; }
			.result td { padding: .3em; border: 1px #ccc solid; background: #FFFFFF; }
			.result th { background: #fc9; padding: .3em; border: 1px #ccc solid; }
			
			.ranktable { margin: 1em; border-collapse: collapse; }
			.ranktable td { padding: .3em; border: 1px #ccc solid; }
			.ranktable th { background: #fc9; padding: .3em; border: 1px #ccc solid; }
			
			body {    background-image:url(http://freewalls.org/wallpapers/2012/10/Tiesto-Music-Live-Everything-Concert-Club-600x960.jpg);  
  					  background-size: cover;      
  					  background-attachment: fixed; }
		</style>

<body bgcolor='#EEEEEE' background='http://freewalls.org/wallpapers/2012/10/Tiesto-Music-Live-Everything-Concert-Club-600x960.jpg'>
<form name='login' action=signin.php method='POST'>
	<table>
		<tr><td>Username: </td><td><input type='text' name='username' size='100'/></td></tr>
		<tr><td>Password: </td><td><input type='password' name='password' size='100'/></td></tr>
		<tr><td colspan='2'><input type="submit" value="Login"/></td></tr>
	</table>
</form>



</body>
</html>
