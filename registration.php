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
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator" ||$_SESSION['role_ukvac']=="staff" )&& isset($_SESSION['vac']))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daily Operations Tracker - UKVAC</title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<link rel="stylesheet" href="styles/style.css">
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>
<script type="text/javascript" language="javascript">

<?php 
if($_SESSION['role_ukvac']=="staff")
{
?>

	var intr = setInterval(function()
	{ 
		$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data: 'cho=44&intvl=15',
			dataType:'json',
			success: function(result)
			{
				if(result=='timeout')
				{
					alert('Session Expired. Please Login!')
					window.location.href='login.php';
					window.location.reload();
				}
				else
				{
					var ro = result.ro_pending;
					var bio= result.bio_pending;
					if(ro != "nopending" || bio != "nopending")
					{
						html_1 = html_2 = html_3 = html_4 ="";
						var html_1 = "<embed src='assets/beep_sound/beep.mp3' autostart='false' width='0' height='0' id='sound1' enablejavascript='true'><u><h4>Applicants waiting<h4></u><br><label class='text-info h5' style='text-align: left;'>There are one or more applicants waiting in the VAC for more than 15 minutes for submission OR Biometrics. Please finish all tokens.</label><br>";
					
					
						var ro_pending = ro.slice(0, -1);
						var bio_pending = bio.slice(0, -1);
					
						if(ro!="nopending")
						{
							var  html_2 = "<label class='text-success h5' style='text-align: left;'>Pending submission: "+ro_pending+".</label> <br>";
						}
						else
						{
							var  html_2 = "";
						}
					
						if(bio!="nopending")
						{
							var  html_3 = "<label class='text-success h5' style='text-align: left;'>Pending Biometrics : "+bio_pending+".</label> <br>";
						}
						else
						{
							var  html_3 = "";
						}
						var html_4 = "<label class='text-danger h5' style='text-align: left;'>** You cannot submit cash tally sheet until and unless all tokens are finished.</label>";
					
						
						$('#info_token_exists').html(html_1+html_2+html_3+html_4);
						if($('#popup_box_token_pending').css('display') != 'none')
						{
							unloadPopupBox_pending_token();
						}
						loadPopupBox_pending_token();
					}
				}
					
			},
			error: function(request, status, error)
			{
					alert(error);  
			}
							
		});
	}, 1000000);


<?php
}
?>


$('#token_no').keydown(function (e){
    if(e.keyCode == 13){
		e.preventDefault();
        retriveao();
    }
})


function retriveao()
{
	var token = $("#token_no").val();
	if(token!="")
	{
		$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data: 'cho=40&token_no='+token,
			dataType:"json",
			success: function(json)
			{
				if(json!="Nodata")
				{
					var ref_details1=json.a.split("__");
					var comments = ref_details1[ref_details1.length-1].split("*");
					//"NoComment" will be returned if no comment. Now display comments under token number
					if(comments['0'] !="NoComments" )
					{
						
						$("#ao_comment_station").html(comments['0']);
						$("#ao_comment_staff").html(comments['4']);
						$("#ao_comment_time").html(comments['2']+" "+comments['3']);
						$("#ao_comment_desc").html(comments['1']);
						$("#ao_comments").show();
					}
					else
					{
						$("#ao_comments").hide();
					}
					
					var ref_details = ref_details1.slice(0, -1);
					$.each( ref_details, function( key, value ) 
					{
						//alert( key + ": " + value );
						var arrayval = value.split(",");
						if(arrayval[5]=='lastday')
						{
							var remark="Prev. Day";
						}
						else if(arrayval[5]=='no_noc')
						{
							var remark="NOC Missing";
						}
						else if(arrayval[5]=="ontime")
						{
							var remark="";
						}
						else if(arrayval[5]=="walkin_without_premium")
						{
							var remark="Missing NOC";
						}
						else if(arrayval[5]=="walkin_with_premium")
						{
							var remark="Walk-In";
						}
						else if(arrayval[5]=="walkin")
						{
							var remark="Walk-In";
						}
						else
						{
							var remark="";
						}
						
						
						
						if(arrayval[4]=="nochange")
						{
							remark_data="";
						}
						else
						{
							remark_data = arrayval[4];
						}
						
						if(arrayval[3]=="shown_up")
						{
							appln_status="Shown Up";
							var enable=1;
						}
						else if (arrayval[3]=="outscan")
						{
							appln_status="Submitted";
							remark_data = "Submitted";
							var enable=0;
						}
						else if (arrayval[3]=="ro_complete")
						{
							appln_status="";
							remark_data = "RO Complete";
							var enable=0;
						}
						else if(arrayval[3]=="no_noc")
						{
							appln_status="Not submitting";
							remark = "NOC Missing";
							var enable=0;
						}
						else if(arrayval[3]=="no_cash")
						{
							appln_status="Not submitting";
							remark = "Insufficiant Cash";
							var enable=0;
						}
						else if(arrayval[3]=="missing_document")
						{
							appln_status="Not submitting";
							remark = "Documents Missing";
							var enable=0;
						}
						else if(arrayval[3]=="premium_not_wanted")
						{
							appln_status="Not submitting";
							remark = "Hesitate to pay premium";
							var enable=0;
						}
						else if(arrayval[3]=="applicant_not_present")
						{
							appln_status="Not submitting";
							remark = "Applicant Not Present";
							var enable=0;
						}
						else if(arrayval[3]=="medical_issues")
						{
							appln_status="Not submitting";
							remark = "Medical Issues";
							var enable=0;
						}
						else if(arrayval[3]=="pending")
						{
							appln_status="Approval Pending";
							remark = "Approval Pending";
							var enable=0;
						}
						else
						{
							appln_status="Not submitting";
							remark = arrayval[3];
							var enable=0;
						}
						var txbx_id = "ac"+key;
						var txbx_id1 = "rj"+key;
						//$data_transfer=$data_transfer.$res_ao_status['reference_number'].",".$res_ao_status['actual_appointment'].",".$res_ao_status['visa_category'].",".$res_ao_status['application_status'].",".$res_ao_status['remarks'].",".$res_ao_status['shown_up_remark']."__";
						var tablerow="<tr><td>"+(key+1)+"</td><td>"+arrayval[0]+"</td><td>"+arrayval[2]+"</td><td>"+arrayval[1]+"</td><td>"+appln_status+"</td><td>"+remark+"</td><td style='color:red;'>"+remark_data+"</td><td style='color:blue;'>"+arrayval[6]+"</td><td id='"+txbx_id+"'></td><td id='"+txbx_id1+"'></td></tr>";
						$('#ao_selected_gwfs').append(tablerow);
						$('#ao_selected_gwfs').show();
						$('#ao_selected_gwfs_div').show();
						$('#retrive_ao').hide();
						if(enable==1)
						{
							$('#load_accept').contents().find('#gwf1').val(arrayval[0]);
							//$('#gwf1').val(arrayval[0]);
							$("#ac"+key).html("<input type='button' class='btn btn-success' value='Accept' onclick='loadPopupBox1(&quot;"+arrayval[0]+"&quot;)'/>");
							$("#rj"+key).html("<input type='button' class='btn btn-danger' value='Reject' onclick='call_popup(&quot;"+arrayval[0]+"&quot;,&quot;individual&quot;)'/>");
						}
						$("#total_app").html("Total Applicants : "+(key+1));
						//$('#retrive_ao').attr('onclick', cancelbutton);
					});
					
					$('#to_add_cancel').html();
					$('#to_add_cancel').html('<input type="button" id="retrive_ao" name="retrive_ao" value="Cancel /Conclude Registration" class="btn btn-info" style="display: inline;" onClick="cancelbutton()"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="button" id="reject_full_token" name="reject_full_token" value="Reject All Tokens" class="btn btn-danger" onClick="call_popup(&quot;'+json.b+'&quot;,&quot;group&quot;)">');
					$("#token_no").attr("disabled","true");
					
				}
				else if(json=='timeout')
				{
					alert('Session Expired. Please Login!')
					window.location.href='login.php';
					window.location.reload();
				}
				else
				{
					alert("Please enter a valid Token Number");
				}
			},
			error: function(request, status, error)
			{
				alert(request.responseText);  
			}
					
		});
	}
}

function reject_full_token(group_id)
{
	
}


function cancelbutton()
{
	window.location.reload();
}

function call_popup(id,type)
{
	$("#ac").html("");
	$("#rj").html("");
	loadPopupBox2(id,type);

}


$( document ).ready(function() {
    if (localStorage.getItem("token_no") != "null" || localStorage.getItem("token_no") != null ) 
	{
		$("#token_no").val(localStorage.getItem("token_no"));
		localStorage.removeItem("token_no");
		retriveao();
	}
	else
	{
		$("#token_no").val("");
		localStorage.removeItem("token_no");
	}
});

</script>

</head>

<body>
<div id="templatemo_container" style="height:870px;">
	<div id="templatemo_header" style="">
    	<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
        <label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
    </div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("menu/left_menu.php"); ?></div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>   
	<div id="templatemo_content" style="height:700px !important;">

		<div id="" style="width: 100% !important; " align="center;">
			<div style="width: 100% !important;" align="center;">
				<div align="center"> <label class='text-info h4'>REGISTRATION OFFICER</label></div>
      			</div>
                <div style="width:100%;" align="center">
                <form action="" method="post">
                 	<table class="table" style="width: 40%;">
                    	<tr>
                    		<td style="border: 0px; text-align:center;">Token Number : <input type="text" id="token_no" style="width: 100px; display:inline; height: 34px;" class="form-control" autofocus /></td>
                      	</tr>
                    	<tr>
                    		<td style="border: 0px; text-align:center;" id='to_add_cancel'><input type="button" id="retrive_ao" name="retrive_ao" value="Retrive AO Details" class="btn btn-info" onClick="retriveao()" />
                     		</td>
                    	</tr>
               		</table> 
                    <div id="display_ref_no" style="display:none"> 
                    
                    </div>
                    <div style="width:100%; text-align: left; float: left; background-color:#8cd98c; border-radius: 5px; padding: 10px; text-align:center; display:none;" id="ao_comments">
                    <table class="table" style="">
                    	<thead>
                        	<tr style="background-color:#8cd98c !important; font-weight: bold;">
                            	<td style="width: 20%;">Comment Station</td>
                            	<td style="width: 20%;">Staff Name</td>
                            	<td style="width: 20%;">Time</td>
                            	<td style="width: 40%;">Comment</td>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr style="background-color:#8cd98c !important;">
                            	<td><span id="ao_comment_station"></span></td>
                            	<td><span id="ao_comment_staff"></span></td>
                            	<td><span id="ao_comment_time"></span></td>
                            	<td><span id="ao_comment_desc"></span></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <div style="width:100%; text-align: left; float: left;" id="total_app"></div>
                    <hr/>
                    <div id="ao_selected_gwfs_div" style="height:300px !important; overflow-y:scroll; display:none;">
                        <table class="table" id="ao_selected_gwfs" width="100%" style="border:0px solid #CCC; display:none;">
                        	<thead>
                            	<tr class="table-content text-info" style="font-weight: 600; font-size: 13px;">
                                    <td >No.</td>
                                    <td >Ref.Number</td>
                                    <td >VISA Category</td>
                                    <td >Appointment Type</td>
                                    <td >Status</td>
                                    <td >Prev. Day Status</td>
                                    <td >Remarks</td>
                                    <td >Supervisor Notes</td>
                                    <td >Accept</td>
                                    <td >Reject</td>
                            	</tr>
                        	</thead>
                        	<tbody class="h6">
                        	</tbody>
                     	</table> 
                 </div> 
                    </form>
                    </div>
				</div>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
<script>
        $("form").submit(function (e) {
            e.preventDefault();
        	retriveao();
        });
</script>
<div id="popup_box_registration" align="center" style="text-align:center;">
    <iframe id="load_accept" type="text/html" src="upload_outscan_report.php?ref=" width="100%" height="100%" style="overflow:auto; border-style: none;" frameborder="0" border="0" cellspacing="0">
    </iframe>                
	<a id="popupBoxClose"><img id="close_pop_up_button" src="styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox1()"></a>
</div>

<div id="popup_box_rejection" align="center" style="text-align:center; border-radius:5px;">    <!-- OUR PopupBox DIV-->
    <div class="text-info text-center h4"><u>Mark As Not Submitting</u></div>
    <div style="height: 40px; display:none; border: 0px; vertical-align: top; padding-bottom: 30px;" class="form-control" align="center" id="response1"></div>
    <table class="table h5">
    	<tr>
        	<td class="text-danger" style="border: 0px; border-color: none; text-align: left;" id="ref">Application Reference Number</td>
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
            	</select>
            </td>
        </tr>
        <tr>
        	<td colspan="2" style="text-align:center; border: 0px; border-color: none; "><input type="button" class="btn btn-info" value="Mark As Not Submitting" id="reject_ro_token"></td>
        </tr>
    </table>
    
    <a id="popupBoxClose"><img src="styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox2()"></a>  
<script type="text/javascript" language="javascript">
function submit_reject(type)
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
			data: 'cho=41&ref='+ref_number+"&reason="+reason+"&rjtype="+type,
			dataType:"json",
			success: function(json)
			{
				if(json=="updated")
				{
					
					$("#popup_box_rejection").html("<label class='text-success h4' style='border: 0px;'>This Reference number is marked as not submitted.</label> <a id='popupBoxClose'><img src='styles/images/closebox.png' style='width:30px; height:30px;' onClick='unloadPopupBox2()'></a>")
						event.preventDefault();
						$("html, body").animate({ scrollTop: 0 }, "slow");
						setTimeout(function(){unloadPopupBox2(); }, 4000);
						alert($("#ao_selected_gwfs > tbody > tr").length)
						if($("#ao_selected_gwfs > tbody > tr").length >1)
						{
							$("#ao_selected_gwfs > tbody").html("");
							retriveao();
						}
						else
						{
							$("#token_no").val("");
							localStorage.removeItem("token_no");
							setTimeout(function(){window.location.reload();}, 4100);
						}
					//alert("Records updated");
					//location.reload();				
				}
				else if(json=="updated_group")
				{
					
					$("#popup_box_rejection").html("<label class='text-success h4' style='border: 0px;'>This Group is marked as not submitted.</label> <a id='popupBoxClose'><img src='styles/images/closebox.png' style='width:30px; height:30px;' onClick='unloadPopupBox2()'></a>")
					event.preventDefault();
					$("html, body").animate({ scrollTop: 0 }, "slow");
					setTimeout(function(){unloadPopupBox2(); }, 4000);
					$("#ao_selected_gwfs > tbody").html("");
					//alert("Records updated");
					$("#token_no").val("");
					localStorage.removeItem("token_no");
					setTimeout(function(){window.location.reload();}, 4100);
									
				}
				else if(json=='timeout')
				{
					alert('Session Expired. Please Login!')
					window.location.href='login.php';
					window.location.reload();
				}
				else
				{
					$("#popup_box_rejection").html("<label class='text-danger h4' style='border: 0px;'>Something went wrong. Please contact admin</label> <a id='popupBoxClose'><img src='styles/images/closebox.png' style='width:30px; height:30px;' onClick='unloadPopupBox2()'></a>")
						event.preventDefault();
						$("html, body").animate({ scrollTop: 0 }, "slow");
						setTimeout(function(){unloadPopupBox2(); }, 4000);
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
	<div id="popup_box_token_pending" align="center" style="text-align:center;">    <!-- OUR PopupBox DIV-->
    	<div class="text-warning text-center " id="info_token_exists"></div>

		<a id="popupBoxClose"><img src="styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox_pending_token()"></a>
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