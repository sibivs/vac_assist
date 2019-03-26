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
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator" ||$_SESSION['role_ukvac']=="staff" )&&  isset($_SESSION['vac']))
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
<link rel="stylesheet" href="menu/css/style_menu.css">
<link rel="stylesheet" href="styles/style.css">
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script src="Scripts/script.js"></script>
<script type="text/javascript">
function retrive()
{
	var gwf = $('#edit_gwf').val();
	if(gwf!="")
	{
		var date = new Date();
   		date.setTime(date.getTime()+(2*1000));
   		var expires = "; expires="+date.toGMTString();
		document.cookie = "gwf="+gwf+expires+"; path=/";
		document.cookie = "status=v;"+expires+"; path=/";
		
		document.form1.action="edit_outscanned.php";
		document.form1.submit();
	}
	else
	{
		alert("Please Enter Valid Reference Number");
	}
	
}


function edit_submit(gwf, glfs,vas)
{
	var splitted = vas.split(","); //--> gives you splitted[0] = 1_0
	var new_str = "";
	for(i=0; i<=(splitted.length-1); i++)
	{
		var splitted1 = splitted[i].split("_"); //--> gives you splitted[0] = 1 and splitted[1] = 0
		if($("#"+splitted1[0]).attr('checked'))
		{
			if(splitted1[1]==0)
			{
				new_str=new_str+splitted1[0]+"_1,";
			}
			else
			{
				var copy = $("#copies_"+splitted1[0]).val();
				new_str=new_str+splitted1[0]+"_"+copy+",";
			}
		}
	}
		
	if(confirm("Confirm to Update the Reference Number - "+gwf))
	{
		//alert(rmv_gwf); 
		$(function () 
		{
			$.ajax(
			{
				type: 'post',
			  	url: 'php_func.php',
				data: 'cho=28&dt='+gwf+'&glfsno='+glfs+"&vas_selected="+new_str,
			  	dataType:"json",
				success: function(json)
				{
					if(json=="failed_update")
					{
						$("#popup_data").html("<span  class='h4' style='color:red !important;'>Failed To Update. Try Again</span>")
						loadPopupBox_manage_submission();
						setTimeout(function(){unloadPopupBox_manage_submission(); }, 3000);
					}
					else if(json=='timeout')
					{
						$("#popup_data").html("<span  class='h4' style='color:red !important;'>Session Expired. Please Login!</span>")
						loadPopupBox_manage_submission();
						setTimeout(function(){unloadPopupBox_manage_submission(); }, 3000);
					}
					else
					{
						$("#popup_data").html("<span  class='h4' style='color:green !important;'>Submission Record is updated</span>")
						loadPopupBox_manage_submission();
						setTimeout(function(){unloadPopupBox_manage_submission(); }, 3000);
					}
				},
				error: function(request, status, error)
				{
					alert(request.responseText);  
				}
				
			});
		});
		
	}
}

function getbts(vas)
{
	//alert(vas);
	var splitted = vas.split(",");
	for(i=0; i<=(splitted.length-1); i++)
	{
		var splitted1 = splitted[i].split("_");
		document.getElementById(splitted1[0]).checked=true;
	}
		
}




function chk_count_copies(vas,needtxtbx,id)
{
	var splitted = vas.split(",");
	for(i=0; i<=(splitted.length-1); i++)
	{
		var splitted1 = splitted[i].split("_");
		if(splitted1[0]==id)
		{
			document.getElementById("copies_"+splitted1[0]).value=splitted1[1];
		}
	}
}

function enable_edit_request(ref_no)
{
	var comment = $("#edit_comment").val();
	if(comment =="")
	{
		$('#edit_comment').removeClass('form-control');
		$('#edit_comment').addClass('textfield_error');
	}
	else
	{
		$('#edit_comment').removeClass('textfield_error');
		$('#edit_comment').addClass('form-control');
		$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data: 'cho=52&reference='+ref_no+'&request=e&comment='+comment,
			dataType:"json",
			success: function(json)
			{
				if(json=="submitted")
				{
					$("#popup_data").html("<span  class='h4' style='color:green !important;'>Your request is Submitted for approval</span>")
				}
				else if(json=="pending_approval")
				{
					$("#popup_data").html("<span style='color:red !important;' class='h4'>Cannot resubmit request. Approval pending.</span>")
				}
				else
				{
					$("#popup_data").html("<span style='color:red !important;' class='h4'>Something went wrong!.</span>")
				}
				$("#gwf_show").html("");
				loadPopupBox_manage_submission();
				setTimeout(function(){unloadPopupBox_manage_submission(); }, 3000);
			},
			error: function(request, status, error)
			{
				alert(request.responseText);  
			}
		});
	}
}


function remove_gwf_request(ref_no)
{
	var comment = $("#edit_comment").val();
	if(comment =="")
	{
		$('#edit_comment').removeClass('form-control');
		$('#edit_comment').addClass('textfield_error');
	}
	else
	{
		$('#edit_comment').removeClass('textfield_error');
		$('#edit_comment').addClass('form-control');
		$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data: 'cho=52&reference='+ref_no+'&request=d&comment='+comment,
			dataType:"json",
			success: function(json)
			{
				if(json=="submitted")
				{
					$("#popup_data").html("<span  class='h4' style='color:green !important;'>Your request is Submitted for approval</span>")
				}
				else if(json=="pending_approval")
				{
					$("#popup_data").html("<span style='color:red !important;' class='h4'>Cannot resubmit request. Approval pending.</span>")
				}
				else
				{
					$("#popup_data").html("<span style='color:red !important;' class='h4'>Something went wrong!.</span>")
				}
				$("#gwf_show").html("");
				
				loadPopupBox_manage_submission();
				setTimeout(function(){unloadPopupBox_manage_submission(); }, 3000);
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
<div id="templatemo_container">
	<div id="templatemo_header">
		<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
        <label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
    </div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("menu/left_menu.php"); ?></div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>   
	<div id="templatemo_content" style="height: 950px !important;">
		<div id="" style="width: 100% !important; " align="center;">
			<div style="width: 100% !important;" align="center;">
				<div align="center"> <label class='text-info h4'>MANAGE PASSPORTS SUBMITTED TODAY</label></div>
      			</div>
                <div id="div_reports" align="center" style="margin-top: 5px;">
                <form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
				
                <div style="width:100%;" id="searchbox">
                <p align="center" style="padding:10px 0 15px 0;" ></p>    
                    <table id="table_for_search" width="50%" style="width:50%;" class="table">
						<tr>
							<td style="" class="text-warning" style="font-size: 14px;">Application Reference Number</td>
							<td style="vertical-align:middle; font-weight:bold;">:</td>
                            <td style="vertical-align:middle; text-align:left;">
                            	<input type="text" class="form-control" style="width:210px;" name="edit_gwf" id="edit_gwf"/>
                           	</td>
						</tr>
                        <tr>
							<td colspan="3" height="10px" style="border:none;"></td>
						</tr>
                        <tr>
							<td colspan="3" style="vertical-align:middle; font-weight:bold; text-align:center; padding:10px;"><input type="button" onClick="retrive()" class="btn btn-success" value="Retrive Details"></td>
						</tr>
                    </table>
                    <hr class="dl-horizontal" style="height: 4px;"/>
                    <?php
					//After submitting edit request or delete request
					//END After submitting edit request or delete request
					?>
                    
                    </div>
                    
            	<?php
				if(!empty($_COOKIE["gwf"]) && !empty($_COOKIE["status"]))  
				{ 
					date_default_timezone_set("Asia/Bahrain");
					$date_selected = date("Y-m-d");
					$user = $_SESSION['uid_ukvac'];
					$search = $_COOKIE['gwf'];
					$query_count_result = "SELECT * FROM passport_inscan_record WHERE gwf_number='$search'  and date_outscan= '$date_selected' and application_taken_by='$user' and mission_id='".$_SESSION['mission_id']."'";
					//echo $query_count_result;
					$q= mysql_query( $query_count_result);
					if(mysql_num_rows($q)>0)
					{
						$recResult = mysql_fetch_array($q);
						$get_ref_no_edit = mysql_fetch_array(mysql_query('select id as ref_id from appointment_schedule where reference_number ="'.$search.'" and mission_id="'.$_SESSION['mission_id'].'"'));
						$get_glfs=mysql_fetch_array(mysql_query('select barcode_no from tee_envelop_counts where id="'.$recResult['glfs_id'].'" and mission_id="'.$_SESSION['mission_id'].'"'));
						?>
						<div id="gwf_show" align="center" style="width:100%; padding:10px 0 20px 0; font:bold 14px Verdana;">
						<?php
                    	echo "Your Search Result for <b>" .$search."</b>";
						?>
                        </div>
                        
                        <?php
							$get_approval_status= mysql_query("select current_status from approvals where ref_id='".$get_ref_no_edit['ref_id']."' and mission_id = '".$_SESSION['mission_id']."' and status='1' and current_status= 'approved'");
							if(mysql_num_rows($get_approval_status) >0)
							{
								$get_approval_status_res = mysql_fetch_array($get_approval_status);
								$status = $get_approval_status_res['current_status'];
							}
							else
							{
								$status = "Zero";
							}
							?>
                        
                        <table cellspacing="0" class="table tablestyle">
                            <thead>
                                <tr class="text-warning">
                                    <th align="left">Reference Number</th>
                                    <th align="left">Envelope Number</th>
                                    <th align="left">Submitted On</th>
                                    <th align="left">Comment</th>
                                    <th align="left">Manage This Entry</th>
                                    <th align="left">Remove This Entry</th>
                        
                                </tr>
                            </thead>
                                        
                            <tbody >
                                <tr>
                                    <td align="left" style="vertical-align:middle;"><?php echo $recResult['gwf_number']; ?></td>
                                    <td align="left" style="vertical-align:middle;"><?php echo $get_glfs['barcode_no']; ?></td>
                                    <td align="left" style="vertical-align:middle;"><?php echo $recResult['date_outscan']; ?></td>
                                    <td align="left" style="vertical-align:middle;"><textarea id="edit_comment" class="form-control" style="width:250px; height: 40px;" placeholder="Remarks for your request"></textarea></td>
                                    <td align="left" style="vertical-align:middle;"><?php if($status!="approved"){ ?><input type="button" onClick="enable_edit_request('<?php echo $search; ?>')" class="btn btn-info" value="Put Edit Request"><?php } else{ echo "Approved"; }?></td>
				    				<td align="left" style="vertical-align:middle;"><?php if($status!="approved"){ ?><input type="button" onClick="remove_gwf_request('<?php echo $search; ?>')" class="btn btn-danger" value="Put Delete Request"><?php } ?></td>
                                </tr>
                                </tbody>
                            </table>
                        <p style="height:30px"></p>
                            <div style="width:62%; height: 400px !important; overflow-y:scroll !important; text-align: left;" >
                            <label class="text-danger h4"><u>Selected VAS Services</u></label>
                            	<table cellspacing="0" style="border:1px solid #FFF; width: 90%;" class="table">
                                <?php
								$get_bts_entries = mysql_query("select * from price_list where current_status = 'active' and mission_id='".$_SESSION['mission_id']."'");
								$vas_exists = "";
					while($res_VAS_list=mysql_fetch_array($get_bts_entries))
					{
					?>
                    <tr>
                        <td style="vertical-align: middle; padding-left: 5%; text-align:left !important; border:1px solid #FFF;" colspan="5">
                        
                           <input name="<?php echo $res_VAS_list['id']; ?>" id="<?php echo $res_VAS_list['id']; ?>" type="checkbox" class="form-control" style="width: 17px; height: 17px; display: inline;" value="<?php echo $res_VAS_list['id']; ?>" <?php if($status!="approved"){ ?> disabled <?php } ?>> <?php echo " - ".$res_VAS_list['service_name']."(".$res_VAS_list['amount']." ".$res_VAS_list['currency'].")"; 
						   $vas_exists = $vas_exists .$res_VAS_list['id']."_".$res_VAS_list['need_text_box'].",";
						   	if($res_VAS_list['need_text_box']==1)
							{
							?>
                            	<span class="" style="padding-left:15px;">Enter the count  : <input type="text" id="copies_<?php echo $res_VAS_list['id']; ?>" name="copies_<?php echo $res_VAS_list['id']; ?>" class="inputtext" style="width: 60px;" value="0" <?php if($status!="approved"){ ?> readonly <?php } ?>></span>
                               <script> chk_count_copies('<?php echo $recResult['vas_entries']; ?>','<?php echo $res_VAS_list['need_text_box']; ?>','<?php echo $res_VAS_list['id']; ?>');</script> 
                            <?php	
							}
						   ?>
                        </td>
                        
                    </tr>
                    <?php
					}
					if($status=="approved")
					{
					?>
                    <tr>
                    	<td style="text-align:center; border: 0px;">
                        	<input type="button" id="button_update"  class="btn btn-danger" value="Update VAS" onClick="edit_submit('<?php echo $search; ?>','<?php echo $get_glfs['barcode_no']; ?>','<?php echo $vas_exists; ?>')">
                        </td>
                    </tr>
                    <?php
						}
					?>
                            </table>
                            <script> getbts('<?php echo $recResult['vas_entries']; ?>');</script>
                            </div>
                            <?php	
						}
						else
						{
						?>
                        <div align="center" style="width:100%; padding:10px 0 20px 0; font:bold 14px Verdana;">

                    	Your Search for "<b><?php echo $search; ?></b>" Returned 0 rows!
                        <br>
                        </div>
                        <?php
						
						}
}
						?>
                        
                    
                    </form>

				</div>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
	</div>
</div>


<!-- POP-UP-Response MESSAGE -->
	<div id="PopupBox_manage_submission" align="center" style="text-align:center; border-radius: 15px;">
		<div class="text-warning text-center h4" id="popup_data">
   		</div>
    	<a id="popupBoxClose">
        	<img src="../styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox_manage_submission()">
        </a>
	</div>
	<!-- END POP-UP-ResponseO MESSAGE -->

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