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
function validate()
{
	var cnt = 0;
	if($("#appointment").val() == "Select")
	{
		$("#appointment").css("border-color", "red");
		cnt++;
	}
	else
	{
		$("#appointment").css("border-color", "#ccc");
	}
	
	if ($.trim($('#excel_data').val()).length < 1) 
	{
    	$("#excel_data").css("border-color", "red");
		cnt++;
	}
	else
	{
		$("#excel_data").css("border-color", "#ccc");
	}
	
	if(cnt==0 && confirm("Please confirm to upload the "+$("#appointment").val()+" for the day"))
	{
		document.form1.action= "appointments.php";
		document.form1.submit();
	}
}
</script>
</head>

<body>
<div id="templatemo_container">
	<div id="templatemo_header">
		<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
        <label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
    </div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("menu/left_menu.php"); ?></div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>   
	<div id="templatemo_content">
		<script type="text/javascript" language="javascript">
				
				</script>
		<div id="" style="width: 100% !important; " align="center;">
			<div style="width: 100% !important;" align="center;">
				<div align="center"> <label class='text-info h4'>
                        	UPLOAD TODAY'S APPOINTMENTS</label></div>
      			</div>
                <div style="height: 35px;">
				

                </div>
                <div style="width:100%;" align="center">
                <form name="form1" id="form1" action="" method="post">
                
                	<table class="table" style="width: 80%;">
                    	<tr>
                        	<td class="text-info h5" style="text-align: right;">
                            	Select Appointment Type:
                            </td>
                            <td class="text-info h5">
                            	<select id="appointment" name="appointment" class="form-control" style="width: 220px;">
                                	<option value="Select">Select Appointment Type</option>
                                <?php
									$get_appointment_types = mysql_query("select type from appointment_types where mission_id='".$_SESSION['mission_id']."' and status='active' order by type");
									while($get_types = mysql_fetch_array($get_appointment_types))
									{
								?>
                                    
                                    <option value="<?php echo $get_types['type']; ?>"><?php echo $get_types['type']; ?></option>
                                    <?php
									}
									?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                        	<td class="text-info h5" style="vertical-align:central; text-align: right;">
                            <b>Appointment list :</b><br>** This task will not remove the exisiting appointments for the day
                            </td>
                            <td class="text-info h5">
                            <textarea class="form-control" name="excel_data" id="excel_data" style="width:350px;height:150px;"></textarea>
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="2">
                            	<?php
								if($_SERVER['REQUEST_METHOD'] == 'POST')
								{
									require_once("Classes/imports/class.copyFromExcel.php");
									$import_obj = new _copyFromExcel(); 
									$import_obj -> datacopied = $_REQUEST['excel_data'];
									$import_obj -> appointment_type = $_REQUEST['appointment'];
									$import_obj_status = $import_obj -> copyAndInsertAppointment();
									if($import_obj_status=="added")
									{
										echo '<label class="text-success">Appointment list is imported</label>';
									}
									else
									{
										echo '<label class="text-warning">There are already submitted reference numbers.<br>'.trim($import_obj_status, ",").'</label>';
									}
								}
								?>
                            </td>
                        </tr>
                         <tr>
                        	<td colspan="2" class="text-danger h5">
								Note:
                                <ul>
                                	<li type="circle">
                                    	Please Copy the appointments from the manifest Excel report and paste here by appointment type. (ie. Standard appointment together, Premium Lounge together, etc.)
                                    </li>
                                    <li type="circle">
                                    	content should contain the appointment details in this order - Time , Type , Reference Number
                                    </li>
                               	</ul>
                            </td>
                        </tr>
                        <tr>
                        	<td class="text-info h5" colspan="2" style="text-align:center;">
                            	<input type="button" class="btn btn-info" onclick="validate()" value="Upload Appointment" style=""/>
                                <input type="button" class="btn btn-danger" onclick="window.location.href='appointments.php'" value="Reset" style="display: inline;"/>
                            </td>
                            <td class="text-info h5">
                            </td>
                        </tr>
                    </table>
					
                </form>
				</div>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center">
        	<label class="h4 text-justify">Powered By BlackHerring Solutions</label>
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