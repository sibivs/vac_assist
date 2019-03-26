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
include_once("../db_connect.php");
if(isset($_SESSION['role_ukvac']) && $_SESSION['role_ukvac']=="power_admin" &&  isset($_SESSION['vac']))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>VAC Assist - <?php echo $_SESSION['vac']; ?></title>
    <!-- Main stylesheet and javascripts for the page -->
    <link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />
    <!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
    <script src="../Scripts/jquery-1.6.3.js" type="text/javascript"></script>
    <script type="text/javascript" src="../Scripts/jquery.style_en.js"></script>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../menu/css/style_menu.css">
    
    <script src="func/script.js"></script>
    <script type="text/javascript" src="func/custom_script.js"></script>

</head>

<body>
    <div id="templatemo_container">
        <div id="templatemo_header">
            <img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
            <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">VAC ASSIST - Management Console</label>
        </div><!-- End Of Header -->
        <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("../menu/left_menu.php"); ?></div>
        <div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>   
        <div id="templatemo_content">
            <div id="" style="width: 100% !important; " align="center;">
                <div style="width: 100% !important;" align="center;">
                    <div align="center"> <label class='text-info h4'>CUSTOM PERMISSIONS</label></div>
                </div>
                <div align="center" style="margin-top: 5px;">
                    <form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                    	<input id="user_id_hidden" type="hidden" value="">
                    	<table class="table table-striped" style="width: 50%;" align="center">
                        	<tr>
                            	<td>
                                	<span class="text-warning h4">Select User : </span>
                                </td>
                                <td>
                                	<select class="form-control" style="width:250px; display:inline;" id="user_list" onChange="get_mission_list()">
                            			<option value="select">Select User</option>
										<?php
                                        $get_staff_list = mysql_query("SELECT distinct display_name FROM users WHERE status = 'active' and role='administrator'  order by display_name  ASC");
                                        while($staff_list = mysql_fetch_array($get_staff_list))
                                        {
                                            echo "<option value='".$staff_list['display_name']."'>".$staff_list['display_name']."</option>";
                                        }
                                        ?>
                        			</select>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                	<span class="text-warning h4" id="display_mission" style="display:none;">Select Mission : </span>
                                </td>
                                <td>
                                	<span id="mission_list_span" style="display:inline;"></span>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="2" style="display:none; text-align:center;" id="submit_button_tr" >
                                	<input type="button" class="btn btn-success" style="display:inline" value="View Permissions" onClick="retrive_user_permissions()"/>
                            		<input type="button" class="btn btn-danger" style="display:inline" value="Reset Window" onClick="window.location.reload();" />
                                </td>
                            </tr>
                        </table>
                        <hr style="height:2px;" class="fa-strikethrough">
                        <div id="permission_list_div" style="display:none">
                            <!-- To display the content -->
                            <table class="table table-hover" style="width:50%;">
                                <thead>
                                    <tr class="text-danger h4">
                                        <td>Custom Permission</td>
                                        <td>View</td>
                                        <td>Manage</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Upload Appointment Manifest</td>
                                        <td></td>
                                        <td><input type="checkbox" id="appointment_manage"></td>
                                    </tr>
                                    <tr>
                                        <td>Inscan Passports</td>
                                        <td></td>
                                        <td><input type="checkbox" id="inscan_manage"></td>
                                    </tr>
                                    <tr>
                                        <td>Add New USers</td>
                                        <td></td>
                                        <td><input type="checkbox" id="user_manage"></td>
                                    </tr>
                                    <tr>
                                        <td>User List</td>
                                        <td><input type="checkbox" id="user_list_view"></td>
                                        <td><input type="checkbox" id="user_list_manage"></td>
                                    </tr>
                                    <tr>
                                        <td>Approvals (Walk-in, Edit, Delete)</td>
                                        <td><input type="checkbox" id="approval_view"></td>
                                        <td><input type="checkbox" id="approval_manage"></td>
                                    </tr>
                                    <tr>
                                        <td>Email Recipients (Daily cash sheets, approval emails)</td>
                                        <td><input type="checkbox" id="email_view"></td>
                                        <td><input type="checkbox" id="email_manage"></td>
                                    </tr>
                                    <tr>
                                        <td>VAS Services</td>
                                        <td><input type="checkbox" id="vas_view"></td>
                                        <td><input type="checkbox" id="vas_manage"></td>
                                    </tr>
                                    <tr>
                                        <td>Inventory - Oyster stock</td>
                                        <td><input type="checkbox" id="oyster_view"></td>
                                        <td><input type="checkbox" id="oyster_manage"></td>
                                    </tr>
                                    <tr>
                                        <td>Inventory - TEE stock</td>
                                        <td><input type="checkbox" id="tee_view"></td>
                                        <td><input type="checkbox" id="tee_manage"></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                	<tr>
                                        <td colspan="3" style="text-align:center;">
                                            <input type="button" class="btn btn-success" style="display:inline" value="Update Permissions" onClick="update_user_permissions()"/>
                                            <input type="button" class="btn btn-danger" style="display:inline" value="Reset Window" onClick="window.location.reload();" />
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
            <span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span>
            <br>
                <!--img src="styles/images/powered-by3.png" style=" width: 156px; height: auto; padding-top: 10px;" /-->
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