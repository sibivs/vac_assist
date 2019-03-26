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
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
include_once("db_connect.php");
if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac']))
{
	$disabled = explode(",",$_SESSION['disabled_string']);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
<title>VAC Assist - <?php echo $_SESSION['vac']; ?></title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="styles/style.css">
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script src="Scripts/script.js"></script>


<script type="text/javascript">

vas_tobe_collected = 0;
phtocopy_only = 0;
function validate()
{
	var cnt=0;
	var err=0;
	var gwf = $("#gwf1").val().toUpperCase();
	var glfsno = $("#glfs1").val().toUpperCase();
	frm_html = $('#frm_show').html();
	if(frm_html.length)
	{
		var frm_no = frm_html.replace("Replace FRM Number - ","");
		frm_no = frm_no.replace(" with Actual Ref. Number","");
	}
	else
	{
		frm_no="no_frm";
	}
	if($("#token").length)
	{
		var tokenno =  document.getElementById('token').value; 
		var ref_id= document.getElementById('id').value;
	}
	else
	{
		var tokenno = "0000";
		var ref_id= ""
	}
	
	
	if(gwf=="")
	{
		var err_msg_id="t_gwf1";
		$("#gwf1").css("border-color","red");
		err++;
		event.preventDefault();
		$("html, body").animate({ scrollTop: 0 }, "slow");
  		return false;
	}
	else
	{
		var err_msg_id="t_gwf1";
		$("#gwf1").css("border-color","#CCC");
		cnt++;
	}
			
	if(glfsno=="")
	{
		//var err_msg_id="t_"+input_id;
		$("#glfs1").css("border-color","red");
		err++;
		event.preventDefault();
		$("html, body").animate({ scrollTop: 0 }, "slow");
  		return false;
	}
	else
	{
		//var err_msg_id="t_"+input_id;
		$("#glfs1").css("border-color","#ccc");
		cnt++;
	}
	if(cnt >0 && err==0)
	{
		var vas_string = "";
		$("input:checkbox").each(function(){
    		var $this = $(this);
    		if($this.is(":checked"))
			{
        		var element_id = $this.attr("id");
				if($('#copies_'+element_id).length)
				{
					var copy = $('#copies_'+element_id).val();
					vas_string = vas_string+element_id+"_"+copy+",";
				}
				else
				{
					vas_string = vas_string+element_id+"_1,";
				}
    		}
			
		});
		var vas = vas_string.substring(0,vas_string.length-1) 
		//document.form1.action="php_func.php?cho=19&token="+tokenno;
		//document.form1.submit();
		$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data: 'cho=19&glfs='+glfsno+'&gwf='+gwf+'&vas='+vas+'&token='+tokenno+'&ref_id='+ref_id+'&frm_number='+frm_no,
			dataType:"json",
			success: function(result)
			{
				$('html, body').animate({scrollTop : 0},800);
				if(result=="added")
				{
					$("#msg").html('<font color="#009900">Outscan Record has been Added.</font>');
				}
				else if(result=="premium_missing")
				{
					$("#msg").html('<font color="red">This is a premium Lounge Applicant. Please select Premium Lounge VAS before submitting.</font>');
				}
				else if(result=="count_missing")
				{
					$("#msg").html('<font color="red">Please fill the product count</font>');
				}
				
				else if(result=="exists")
				{
					$("#msg").html('<font color="red">Reference Number is existing.</font>');
				}
				else if(result=='timeout')
				{
					alert('Session Expired. Please Login!')
					window.location.href='login.php';
					window.location.reload();
				}
				else
				{
				}
				if(result=="added" || result=="exists")
				{
					setTimeout(function(){
						window.parent.$('#close_pop_up_button').trigger("click");
					}, 2000);
				}
			},
			error: function(ts)
			{
				alert(ts.responseText); 
			}
		});
	}
}


function reset_page()
{
	document.form1.action="upload_outscan_report.php";
	document.form1.submit();
}

function disable(val,count)
{
	var selectedval= val.value;
	if(selectedval=="premium"|| selectedval=="pl-online")
	{
		document.getElementById('sms_gwf'+count).disabled=true;
		document.getElementById('courier_gwf'+count).disabled=true;
		document.getElementById('pcopy_gwf'+count).disabled=true;
		
		document.getElementById('sms_gwf'+count).checked=false;
		document.getElementById('courier_gwf'+count).checked=false;
		document.getElementById('pcopy_gwf'+count).value=0;
	}
	else
	{
		document.getElementById('sms_gwf'+count).disabled=false;
		document.getElementById('courier_gwf'+count).disabled=false;
		document.getElementById('pcopy_gwf'+count).disabled=false;
	}
	
}



function chkGLFS(id)
{
	var barcode =  $("#"+id).val();
	if(barcode=="")
	{
		$("#glfs_availability_result").html("<label style='color:red'>Cannot be Empty</label>");
		$("#id").addClass('textfield_error');
		$("#Search").attr("disabled", true);
	}
	else
	{
		$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data: 'cho=31&glfsno='+barcode,
			dataType:"json",
			success: function(result)
			{
				if(result==1)
				{
					$("#glfs_availability_result").html("<label style='color:red'>Pouch not scanned</label>");
					$("#"+id).css("border-color","red");
					$("#Search").attr("disabled", true);
				}
				else if(result==2)
				{
					$("#glfs_availability_result").html("<label style='color:red'>Pouch already used </label>");
					$("#"+id).css("border-color","red");
					$("#Search").attr("disabled", true);	
				}
				else if(result==3)
				{
					$("#glfs_availability_result").html("<label style='color:purple'>Pouch is available</label>");
					$("#"+id).css("border-color","green");	
					$("#Search").attr("disabled", false);
				}
				else if(result=='timeout')
				{
					alert('Session Expired. Please Login!')
					window.location.href='login.php';
					window.location.reload();
				}
				else
				{
				}
				event.preventDefault();
				$("html, body").animate({ scrollTop: 0 }, "slow");
				return false;
			},
			error: function(request, status, error)
			{
				alert(request.responseText);  
			}
					
		});
	}

}


function calculate_photocopy(id,amount,currency)
{
	copies = $("#copies_"+id).val();
	if(copies=="")
	{
		copies=0;
	}
	if(isNaN(copies))
	{
		$("#copies_"+id).css('border-color','red');
		$("#pcopy_price").html("<span class='text-danger'>Allowed numbers Only</span>");
	}
	else
	{
		total = (parseInt(copies)*parseFloat(amount)).toFixed(3);
		result = total+" "+currency;
		$("#pcopy_price").html(result);
			
		vas_tobe_collected = (parseFloat(vas_tobe_collected) - parseFloat(phtocopy_only)).toFixed(3);
		vas_tobe_collected = (parseFloat(vas_tobe_collected)+parseFloat(total)).toFixed(3);
		$("#vas_total_collect").html(vas_tobe_collected+" "+currency);
		phtocopy_only = total;
	}	
}


function vas_add(amount, currency, id,copy)
{
	if(copy =="nocopy")
	{
		if ($("#"+id).prop('checked')==true)
		{ 
			vas_tobe_collected = (parseFloat(vas_tobe_collected) + parseFloat(amount)).toFixed(3);
			$("#vas_total_collect").html(vas_tobe_collected+" "+currency);
		}
		else
		{
			vas_tobe_collected = (parseFloat(vas_tobe_collected)-parseFloat(amount)).toFixed(3);
			$("#vas_total_collect").html(vas_tobe_collected+" "+currency);
		}
	}
}


</script>

</head>

<body>
		<div id="templatemo_right_content" style="width: 95%;">
			<div id="templatemo_content_area" align="center">
				<div id="PageHeading" class="PageHeading" align="middle" style="width: 100% !important;">
      				<p align="center" style="width: 100%; vertical-align:middle;">
                    	<font color="#FFFFFF">
                        	<b>APPLICATION SUBMISSION</b>
                        </font>
                    </p>
      			</div>
                <div style="height: 35px; color:#F00; text-align: right; vertical-align:bottom; font-size: 12px;">

                </div>
                <div id="counterdelivery" align="center" style="width:100%; font-size:13px;">
                <form name="form1" id="form1" action="" method="post">
                <div id="msg" align="center" style="height: 30px; vertical-align:middle; padding-top:10px;">
                
                </div>
                
			<table id="enter_gwf_tbl" width="100%" class="table" style="border: 0px !important;">
				<tr>
					<td style="vertical-align: middle; text-align:center; padding-left: 15px; border: 0px !important;" colspan="2">
                    	<label class="text-info h5" style="display: inline;">Application Reference Number: </label> <input name="gwf1" id="gwf1" type="text" class="form-control"  placeholder="Reference Number" style="width:220px; display:inline;">
                    </td>
                    <td style="vertical-align: middle; text-align:center; border: 0px !important;" colspan="2">
                    	<?php
                        if(!in_array('manage_tees.php',$disabled))
						{
							$token_val	="";
							$ref_id	="";
						?>
                        <label class="text-info h5" style="display: inline;">Envelope Number:</label> <input name="glfs1" id="glfs1" type="text" class="form-control" placeholder="Enter Envelope Number" value="" onChange="chkGLFS('<?php echo "glfs1"; ?>')" style="display:inline; width:220px;" autofocus>
                        <div id='glfs_availability_result'></div>
			<input name="token" id="token" type="hidden" value="" >
                       	<?php
						}
						else
						{
						?>
                        <input name="glfs1" id="glfs1" type="hidden" value="0000">
                        <?php 
							if(isset($_REQUEST['token']))
							{
								$token_val = $_REQUEST['token'];
							}
							else
							{
								$token_val	="";
							}
							if(isset($_REQUEST['id']))
							{
								$ref_id = $_REQUEST['id'];
							}
							else
							{
								$ref_id	="";
							}
						}
							//echo $token_val;
						?>
                        <input name="token" id="token" type="hidden" value="<?php echo $token_val; ?>" >
                        <input name="id" id="id" type="hidden" value="<?php echo $ref_id; ?>" >
                    </td>
                    <td style="vertical-align: middle; text-align:center; border: 0px !important;">
                    	<span class="text-info h5">Total VAS amount to be collected :</span>
                        <span class="text-warning h5" style="font-weight:bold;" id="vas_total_collect"></span>
                    </td>
          		</tr>
                <tr id="frm_warning" name="frm_warning" style="display:none;">
                	<td colspan="5"><label id="frm_show" style="font-size: 15px; color:red;"></label></td>
                </tr>
                <tr>
                    <td style="vertical-align: middle; text-align:center; height: 50px;" colspan="5" class="h5 text-warning"><strong>Select Value Added Services Choosen</strong></td>
                </tr>
                    <?php
					//GET VAS List from VAS Database
					$get_bts_entries = mysql_query("select * from price_list where current_status='active' and mission_id='".$_SESSION['mission_id']."'");
					while($res_VAS_list=mysql_fetch_array($get_bts_entries))
					{
					?>
                    <tr >
                        <td style="vertical-align: middle; text-align:left; padding-left: 30%; border: 0px !important;" colspan="5">
                           <input name="<?php echo $res_VAS_list['id']; ?>" id="<?php echo $res_VAS_list['id']; ?>" type="checkbox" class="form-control" style="width: 15px; height: 15px; display: inline;" value="<?php echo $res_VAS_list['id']; ?>" onChange="vas_add('<?php echo $res_VAS_list['amount'] ?>','<?php echo $res_VAS_list['currency'] ?>','<?php echo $res_VAS_list['id']; ?>','<?php if($res_VAS_list['need_text_box']==1) { echo 'copy'; } else { echo 'nocopy'; } ?>')"> <?php echo "&nbsp; &nbsp;".$res_VAS_list['service_name']." - (".$res_VAS_list['amount']." ".$res_VAS_list['currency'].")"; ?>
                           
                           <?php
						   	if($res_VAS_list['need_text_box']==1)
							{
							?>
                            	<span class="" style="padding-left:15px;">Enter the count  : <input type="text" id="copies_<?php echo $res_VAS_list['id']; ?>" name="copies_<?php echo $res_VAS_list['id']; ?>" class="form-control" style="width: 100px; display:inline;" value="0" onChange="calculate_photocopy('<?php echo $res_VAS_list['id']; ?>','<?php echo $res_VAS_list['amount']; ?>','<?php echo $res_VAS_list['currency']; ?>')" onKeyDown, onKeyUp="calculate_photocopy('<?php echo $res_VAS_list['id']; ?>','<?php echo $res_VAS_list['amount']; ?>','<?php echo $res_VAS_list['currency']; ?>')"></span><span class="text-warning" style="padding-left: 50px;">Total Amount:</span><span class="text-warning" id="pcopy_price" style="padding-left: 10px;">0</span>
                            <?php	
							}
						   ?>
                        </td>
                    </tr>
                    <?php
					}
					?>
                
                <tr>
					<td colspan="5" style="border: 0px !important;"></td>
				</tr>
                <tr>
					<td align="middle" valign="middle" colspan="5"><input type="button" id="Search" name="Search" value= "Submit Application" class="btn btn-success" onclick="validate()" />&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					
				</tr>
                <tr>
                <td colspan="5" style="border: 0px !important;">
                <p>
                <?php
				if(isset($_SESSION['response_delivery']))
				{
					echo $_SESSION['response_delivery'];
					unset($_SESSION['response_delivery']);
				}
				?>

                </td>
                </tr>
            </table>
                </form>
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