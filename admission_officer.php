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
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="staff" )&&  isset($_SESSION['vac']))
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
<script src="Scripts/admission_officer.js"></script>
</head>

<body>
<div id="templatemo_container" style="height:870px;">
	<div id="templatemo_header">
		<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
        <label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
    </div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("menu/left_menu.php"); ?></div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>   
	<div id="templatemo_content" style="height:630px;">
		<script type="text/javascript" language="javascript">
				
				</script>
				<div id="" style="width: 100% !important; " align="center;">
					<div style="width: 100% !important;" align="center;">
						<div align="center"> <label class='text-info h4'>
							ADMISSION OFFICER</label>
               			</div>
      				</div>

                <div style="width:100%;">
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                <?php
				$date_today = date('Y-m-d');
				$check_imported = mysql_fetch_array(mysql_query("select count(*) as total from appointment_schedule where scheduled_date_app = '$date_today' and mission_id='".$_SESSION['mission_id']."' and application_status NOT IN ('pending','outscan','ro_complete','shown_up')"));
				if($check_imported['total'] >=1)
				{
				?>
                                <div style="text-align: right; height: 50px; vertical-align:middle; display: none;" id="dsply_another"><input type="button" class="btn btn-info" value="+ Add Another Applicant" onClick="addanother()"></div>
                <div class="form-control" style="display: none;" id="dsply_another_refno" align="center"></div>
                <div id="ao_selected_gwfs_div" style=" max-height:400px !important; overflow-y:scroll; display:none;">
                 <table class="table" id="ao_selected_gwfs" width="100%" style="border:0px solid #CCC; display:none;">
                 	<thead>
                    	<tr class="table-content text-info" style="font-weight: 600; font-size: 13px;">
                        	<td>No.</td>
                            <td>Ref. Number</td>
                            <td>App. At</td>
                            <td>App. Type</td>
                            <td>Remarks</td>
                            <td>Change To</td>
                            <td>Reason </td>
                            <td>Reject</td>
                            <td>Remove from list</td>
                        </tr>
                    </thead>
                    <tbody class="h6" id="tbodyid">
                    </tbody>
                 </table>  
            	</div>  
               	<div id="for_saveandContinue" style="display:none; text-align:center;">
                 	<table class="table">
                    	<tr>
                    		<td style="vertical-align: middle;"><label class="text-danger h5">Applicant's Token Number : </label><input type="text" id="token_no" style="width: 100px; display:inline; height: 34px;" class="form-control" ></td>
                            <td style="vertical-align: center;"><label class="text-danger h5">Comments : </label><textarea id='comment_txt' style='width: 200px; display:inline; height: 50px;' class='form-control'></textarea></td>
                      	</tr>
                    	<tr>
                    		<td id="cancel" colspan="2" >
                            <input type='button' id='saveandContinue' name='saveandContinue' value='Submit AO Details' class='btn btn-info' onClick='submit_appointment_details()' />
                     		</td>
                    	</tr>
               		</table> 
          		</div>
                <div align="center">
                    <table width="50%" class="table" style="border:0px solid #CCC; padding:20px 0 25px 0;" id="app_ref_no">
						<tr>
							<td width="50%" style="font-size: 13px; border-bottom:1px solid #eee; padding:5px 10px 5px 0px; border-right:0px solid #eee; vertical-align:middle; text-align:right;"><label class="text-warning h5">Application Reference Number :</label></td>
							<td width="50%" style="border-bottom:1px solid #eee; padding:5px; vertical-align:middle;"><input type="text" id="ao_ref_no" class="form-control"  placeholder="Application Reference Number" style="width: 300px; text-transform:uppercase;" autofocus></td>
						</tr>
						<tr>
							<td colspan="2" style="font:bold 12px tahoma, arial, sans-serif; text-align:middle; padding:15px 10px 5px 0px; border-right:0px solid #eee;" align="center" id="td_sbmt_btn"><input type="button" id="submit" name="submit" value="Add This Application" class="btn btn-danger" onClick="get_appointment_details()"></td>
						</tr>
                        <tr>
                        	<td colspan="2">
                            
                            </td>
                        </tr>
                    </table>
                 </div>
                    <?php
					
				}
				else
				{
				?>
                	<div class="text-danger h4" style=" width: 100%; text-align: center;" >Appointment list for the day is not uploaded. Please contact Supervisor.</div>
                 <?php
				}
				 ?> 
                 
                    </form>
                    </div>
				</div>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>

<div id="popup_box_rejection" align="center" style="text-align:center;">    <!-- OUR PopupBox DIV-->
    <div class="text-info text-center h4"><u>Mark As Not Submitting</u></div>
    <div style="height: 40px; display:none; border: 0px; vertical-align: top; padding-bottom: 30px;" class="form-control" align="center" id="response1"></div>
    <table class="table h5 pre-scrollable" id="reject_tbl">
    	<tr>
        	<td class="text-danger" style="border: 0px; border-color: none; text-align: left;">Application Reference Number</td>
            <td style="border: 0px; border-color: none; text-align: left;"><input type="text" id="ref_no" class="form-control" value="" style="width: 220px;" readonly></td>
        </tr>
        <tr>
        	<td class="text-danger" style="text-align: left;">Reason For Not Submitting</td>
            <td style="text-align: right;">
            	<select id="reason" class="form-control" style="width: 220px;">
                	<option value="Select">Select a reason</option>
                    <option value="no_noc">Missing NOC</option>
                    <option value="no_cash">No cash</option>
                    <option value="missing_document">Missing documents</option>
                    <option value="premium_not_wanted">Hesitated to pay for premium</option>
                    <option value="applicant_not_present">Applicant is not present</option>
                    <option value="medical_issues">Medical issues</option>
                    <option value="Henna">Henna On Hand</option>
            	</select>
            </td>
        </tr>
        <tr>
        	<td colspan="2" style="text-align:center; border: 0px; border-color: none; "><input type="button" class="btn btn-info" value="Mark As Not Submitting" onClick="submit_reject()"></td>
        </tr>
    </table>
    
    <a id="popupBoxClose"><img src="styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox2()"></a>  
<script type="text/javascript" language="javascript">
function submit_reject()
{
	var ref_number = $("#ref_no").val();
	var reason = $("#reason").val();
	if(reason=="Select")
	{
		$("#response1").html("<label class='text-danger h4' style='border: 0px;'>Please select the reason for not submitting!</label>")
		$("#response1").show();
		event.preventDefault();
		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	}
	else
	{
		$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data: 'cho=41&ref='+ref_number+"&reason="+reason+"&rjtype=individual",
			dataType:"json",
			success: function(json)
			{
				if(json=="updated")
				{
					$("#reject_tbl").remove();
					$("#response1").html("<label class='text-success h4' style='border: 0px;'>This Reference number is marked as not submitted.</label>")
						$("#response1").show();
						event.preventDefault();
						$("html, body").animate({ scrollTop: 0 }, "slow");
						unload_success();
					//alert("Records updated");
					//location.reload();				
				}
				else
				{
					//unloadPopupBox2();
					$("#response1").html("<label class='text-danger h4' style='border: 0px;'>Something went wrong. Please contact admin</label>")
						$("#response1").show();
						event.preventDefault();
						$("html, body").animate({ scrollTop: 0 }, "slow");
						unload_success();
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
</div>
<!-- POP-UP-AO MESSAGE -->
	<div id="PopupBox_ao_msg" align="center" style="text-align:center; border-radius: 15px;">
		<div class="text-warning text-center h4" id="popup_data">
   		</div>
    	<a id="popupBoxClose">
        	<img src="../styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox_ao_msg()">
        </a>
	</div>
	<!-- END POP-UP-AO MESSAGE -->
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