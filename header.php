<?php 
  include_once 'setting.php';
	//if(strpos($_SERVER["REQUEST_URI"], 'index.php') == false){
	//if(substr($_SERVER["REQUEST_URI"], -7, 6)=="hidden");
	//	echo $_SERVER["REQUEST_URI"];
		//if(substr($_SERVER["REQUEST_URI"] , -1)=="/"){
		//	header( 'Location: '.$_SERVER["REQUEST_URI"].'index.php' ) ;
		//}else{
		//	header( 'Location: '.$_SERVER["REQUEST_URI"].'/index.php' ) ;
		//}
	//}
	
	session_start(); 
	$uid=$_SESSION['uid'];
	$uname=$_SESSION['uname'];
	
?>

<html>
	<head>
		<title>MusicNet Group 4</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<style type="text/css">
			.result { margin: 1em; border-collapse: collapse; }
			.result td { padding: .3em; border: 1px #ccc solid; background: #FFFFFF; }
			.result th { background: #fc9; padding: .3em; border: 1px #ccc solid; }
			
			.ranktable { margin: 1em; border-collapse: collapse; }
			.ranktable td { padding: .3em; border: 1px #ccc solid; }
			.ranktable th { background: #fc9; padding: .3em; border: 1px #ccc solid; }
			
			body { font-family: Helvetica; }
		</style>
	</head>

	<body bgcolor='#EEEEEE'>

	
		<table width='900'>
			<tr>
				<td width="500">
					<font color='#009900' size='4'>
					<?php 
						if(isset($uid)) echo "Welcome, $uname"; 
						else echo "Anonymous";
					?>
					</font> 
				</td>
				<td align='right'>
					<?php 	
						//if(isset($uid)) echo "<a href='friendupdate.php'>Friend Update page</a>  ";
						if($uname=="admin"){
							echo "<a href='admin.php'>Admin Page</a> |";
						}
						
						if(isset($uid)) {
							echo " <a href='index.php'>Home</a> | <a href='user.php?uid=$uid'>My Profile</a> | <a href='signout.php'>Sign out</a>";
						}
						else {
							echo " <a href='index.php'>Home</a> | <a href='signin.php'>Sign in</a>";
						}
					?> 
				</td>
			</tr>
			<tr><!--#0099FF-->
			<td valign='top' colspan='2' bgcolor='#000000'>
				<a href='index.php'><img src='http://colouringbook.org/SVG/2011/COLOURINGBOOK.ORG/CBOOK/music_headphones_icon_black_white_line_art_coloring_book_colouring-69px.png'/></a>
				<font face='Impact' color='#FFFFFF' size='7'>Music Net</font> 
				<font face='Impact' color='#FFFFFF' size='6'> - A Social Network for Music Enthusiasts</td>
			</tr>
		</table> 
