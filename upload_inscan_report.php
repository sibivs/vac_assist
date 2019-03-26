<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
$expire_time = 60*60; //expire time
if((isset($_SESSION['last_activity_ukvac'] )) && ($_SESSION['last_activity_ukvac'] < (time()-$expire_time))) 
{
	echo "<script type='text/javascript'>alert('Session Expired, Please login')</script>";
	session_destroy();
	header("Location:login.php");
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
include_once("db_connect.php");
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator")&&  isset($_SESSION['vac']))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VAC Assist - <?php echo $_SESSION['vac']; ?></title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<link rel="stylesheet" href="styles/style.css">
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>
<script type="text/javascript" language="javascript">

</script>

</head>

<body>
<div id="templatemo_container" style="height:700px !important;">
	<div id="templatemo_header">
		<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
        <label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
    </div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("menu/left_menu.php"); ?></div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>   
	<div id="templatemo_content" style="height:700px !important;">
		<script type="text/javascript" language="javascript">
				
				</script>
		<div id="" style="width: 100% !important; " align="center;">
			<div style="width: 100% !important;" align="center;">
				<div align="center"> <label class='text-info h4'>
					UPLOAD PASSPORT RECEIVED FROM EMBASSY</label></div>
      			</div>
                <div style="height: 35px; color:#F00;">
				

                </div>
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
					<?php
                    if($_SERVER['REQUEST_METHOD'] == 'POST')
                    {
                        require_once("Classes/imports/class.Inscan_FromTextarea.php");
                        $import_obj_inscan = new _copyFromExcel_inscan();
                        $import_obj_inscan -> datacopied_inscan = $_REQUEST['excel_data'];
                        $import_obj_status_inscan = $import_obj_inscan -> copyAndInsertInscan();
                    }
                    ?>
                    <div id="import-ppt-div" style="width: 100%;">
                    <table width="100%" class="table">
						<tr>
							<td width="30%" class="text-info" style="border-top: 0px; font-size: 16px;" >Inscan Reference Numbers:<p> <span class="text-danger" style="font-size: 12px;">**Copy and Paste from Your Inscan Excel sheet</span></td>
							<td width="70%" style="text-align: left; border-top: 0px;"><textarea class="form-control" name="excel_data" id="excel_data" style="width:350px;height:150px;"></textarea></td>
						</tr>
						<tr>
							<td colspan="2" align="center" style=""><input type="submit" name="submit" value="Import Inscan Records" class="btn btn-danger"></td>
						</tr>
                        <tr>
                        	<td colspan="2" style="border-top: 0px;">
							
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="2" id="td_display_help" style="height: 300px;">
                            	<div class="scrollable" id="inscan_response" style="display:none;">
									<?php
                                    //Print the response after import
                                    if($_SERVER['REQUEST_METHOD'] == 'POST')
                                    {
                                        if($import_obj_status_inscan !="")
                                        {
                                            echo $import_obj_status_inscan;
                                        }
										
										?>
											<script type="text/javascript" language="javascript">
                                            $("#inscan_response").show();
                                            </script>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                    </div>
                    </form>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
	</div>
</div>
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