<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
$expire_time = 60*60; //expire time
if((isset($_SESSION['last_activity_ukvac'] )) && ($_SESSION['last_activity_ukvac'] < (time()-$expire_time))) 
{
	echo "<script>alert('Session Expired, Please login')</script>";
	session_destroy();
	header("Location:login.php");
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
	if(isset($_SESSION['role_ukvac']))
	{
		?>
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>VAC Assist - Chat</title>
				<!-- Main stylesheet and javascripts for the page -->
				<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />
				<link rel="stylesheet" href="../styles/style.css">
                <link rel="stylesheet" href="../menu/css/style_menu.css">
				<script src="../Scripts/jquery-1.6.3.js" type="text/javascript"></script>
				<script type="text/javascript" language="javascript">
				$(document).ready( function() {
					$("#menu").load("../menu/left_menu.php");
					$("#templatemo_userinfo").load("../header.php");
				});
				</script>
			</head>
		<body style="min-height: 500px; height: 100%;">
		<form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
		<div id="templatemo_container" style="min-height: 500px; height: 100%;">
			<div id="templatemo_header">
				<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
				<label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
				<label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
			</div><!-- End Of Header -->
            
			<div id="templatemo_userinfo"></div>    
            <div style="padding-top: 30px;">
				<div style="margin-left: 0px !important;">
                	<iframe src="http://192.168.73.128:3000/" style="width: 1200px; min-height: 500px; height: 100%; border: none;" scrolling="no" frameBorder="0"></iframe>
                </div>
				<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
					<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
					
				</div>
			</div>
		</div>
		
		</form>
		</body>
		</html>
	<?php
	}
	else
	{
        header("Location:login.php");
	}
}
?>