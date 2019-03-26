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
	header("Location:../login.php");
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
include_once("../db_connect.php");
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="staff" ))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VAC Assist</title>
<!-- Main stylesheet and javascripts for the page -->
<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="../Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<!--script src="Scripts/jquery-1.4.4.js" type="text/javascript"></script-->
<link rel="stylesheet" href="../styles/style.css">
<link rel="stylesheet" href="../menu/css/style_menu.css">
<script type="text/javascript">
function add_submission()
{
	var error = 0;
	var token = $("#token_no").val();
	var applicant_count= $("#applicant_count").val();
	var appointment = $("#appointment").val();
	if(token=="")
	{
		$("#token_no").css('border-color',"red");
		error++;
	}
	else
	{
		$("#token_no").css('border-color',"#ccc");
	}
	
	if(applicant_count=="" || applicant_count== '0')
	{
		$("#applicant_count").css('border-color',"red");
		error++;
	}
	else
	{
		$("#applicant_count").css('border-color',"#ccc");
	}
	if(appointment=="select")
	{
		$("#appointment").css('border-color',"red");
		error++;
	}
	else
	{
		$("#appointment").css('border-color',"#ccc");
	}
	
	
	if(error == 0)
	{
		$("#token_no").css('border-color',"#ccc");
		$.ajax(
		{
			type: 'post',
			url: 'func/php_func.php',
			data: 'cho=1&token='+token+'&count='+applicant_count+'&appointment='+appointment,
			dataType:"json",
			success: function(result)
			{
				if(result=="success")
				{
					$("#response").html("Token is added. Applicant can proceed to the counter");
				}
				else if(result=="used")
				{
					$("#response").html("<span class='text-danger'>This Token is already added. Please enter a valid token</span>");
					$("#token_no").css('border-color',"red");
				}
				else
				{
					$("#response").html(result);
				}
			},
			error: function(request, status, error)
			{
				alert(request.responseText);  
			}
						
		});
	}
}
</script>

</head>

	<body>
	<form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
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
				<div align="center"> <label class='text-info h4'>ADMISSION ZONE</label></div>
      					</div>
                        <div style="height: 600px;">
                        	<div style="padding-top: 15px; " align="center">
                            	<table class='table' style="width: 50%;">
                                	<tr>
                                    	<td class="text-warning" style="font-weight: bold; font-size: 16px;">
                                		Enter Token Number : 
                                        </td>
                                        <td>
                                			<input type="text" class="form-control" style="width: 110px; display:inline;" id="token_no" placeholder="Token No"/>
                                      	</td>
                                 	</tr>
                                    <tr>
                                    	<td class="text-warning" style="font-weight: bold; font-size: 16px;">
                                			Appointment Type : 
                                        </td>
                                        <td>
                                        	<select id="appointment" class="form-control" style="width:210px;">
                                				<option value="select">Select Appointment Type</option>
                                    			<option value="Standard Appointment">Standard Appointment</option>
                                    			<option value="Premium Lounge Appointment">Premium Lounge</option> 
                                			</select>
                                        </td>
                                	</tr>
                                	<tr>
                                    	<td class="text-warning" style="font-weight: bold; font-size: 16px;">
                                		Total Applicant's Count : 
                                        </td>
                                        <td>
                                			<input type="text" class="form-control" style="width: 110px; display:inline;" id="applicant_count" placeholder="Applicant Count"/>
                                      	</td>
                                 	</tr>
                                    <tr>
                                    	<td colspan="2">
                                        	<input type="button" class="btn btn-success" value="Submit" onClick="add_submission()">
                                      	</td>
                                 	</tr>
                             </table>
                              	
                            </div>
                            <hr />
                        	<label class="text-info h5" style="text-align:center;" id='response'></label>
                        </div>
					</div>
					<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        				<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            			
					</div>
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
        header("Location:../php_func.php?cho=2");
}
}
?>