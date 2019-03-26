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
	//header("");
	 $url='login.php';
   	echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$url.'">';
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
include_once("db_connect.php");
if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac']))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
<title>VAC Assist - <?php echo $_SESSION['vac']; ?></title>
<!-- Main stylesheet and javascripts for the page -->
<link rel="stylesheet" href="styles/style.css">
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>

<script type="text/javascript">
<?php 
if($_SESSION['role_ukvac']=="staff" ||$_SESSION['role_ukvac']=="passback" )
{
?>

//var intr = setInterval(function(){ window.location.reload(); }, 100000);


$(document).ready(function ()
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
				
					var total_htm = html_1+html_2+html_3+html_4;
					$('#info_token_exists').html(html_1+html_2+html_3+html_4);
					if($('#popup_box_token_pending').css('display') != 'none')
					{
						unloadPopupBox_pending_token();
					}
					loadPopupBox_pending_token();
				}
			}
				
		},
		error: function(error)
		{
				alert(JSON.stringify(error));  
		}
						
	});
	
	$("#templatemo_left_content").load("left_menu.php");
	$("#templatemo_userinfo").load("header.php");
	
	$("#pp_delivery_method").change(function ()
	{
		if($("#pp_delivery_method").val() !='select')
		{
			$("#enter_gwf_tbl").show();
			$("#pp_delivery_method").attr('disabled',true);
		}
		else
		{
			$("#enter_gwf_tbl").hide();
			$("#pp_delivery_method").css('disabled',false);
		}
	});
	
});

<?php
}
?> 



function validate()
{
	var cnt = 0;
	var e = document.getElementById("pp_delivery_method");
	var option_delivery = e.options[e.selectedIndex].value;
	
	if(option_delivery =="select")
	{
		alert("Please select delivery method");
		$("#pp_delivery_method").css('border-color','red');
	}
	else
	{
		$("#pp_delivery_method").css('border-color','#ccc');
		for(i=1; i <21; i++)
		{
			var input_id="gwf"+i;
			var gwf = document.getElementById(input_id).value.toUpperCase();
			if(gwf!="")
			{
				cnt++;		
			}
			
		}
		if(cnt >0)
		{
			document.form1.action="confirm_passport_delivery.php?pp_delivery_method="+option_delivery;
			document.form1.submit();
		}
	}
}


function reset_page()
{
	document.form1.action="passport_deliver.php";
	document.form1.submit();
}


</script>

<style type="text/css">
#cover{ 
	position:fixed; 
	top:0; 
	left:0;
	background:rgba(0,0,0,0.6); 
	z-index:5; 
	width:100%; 
	height:100%; 
	display:none; 
} 
#loginScreen { 
	height:70px; 
	width:700px; 
	margin:0 auto; 
	position:absolute; 
	z-index:10; 
	display:none;
	padding: 10px; 
	border:5px solid #cccccc; 
	border-radius:10px; 
	background-color: #FFF;
	left: 25%;
	top:20%;
}  
</style>

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
				<div align="center"> <label class='text-info h4'>DELIVER PASSPORT TO THE APPLICANT</label></div>
      			</div>
                <div style="height: 35px; color:#F00;">


                </div>
                <div id="counterdelivery" align="center">
                <form name="form1" id="form1" action="" method="post" enctype="multipart/form-data">
             	<?php
				if(isset($_SESSION['response_delivery']))
				{
				?>
                    <div class="coverStyle" id="cover" onClick="window.location.reload()"> </div>
                    <div class="loginScreenStyle" id="loginScreen" align="center" style="text-align:center;">
                    	<label class="text-success" style="font-size: 16px !important; font-weight:normal !important;"><?php echo $_SESSION['response_delivery']; ?></label>
                    </div>
                    <script type="text/javascript" language="javascript">
                    function show_add_service()
                    {
                        $("#loginScreen").show();
                        $("#cover").css('display','block');
                        $("#cover").css('opacity','1');
                    }
					show_add_service();
					</script>
                <?php
					unset($_SESSION['response_delivery']);
				}
				?>
             <table style="width: 900px;">
                    	<tr style="vertical-align:middle;">
                        	<td colspan="2" style="border: none !important; text-align:right; padding-right: 5px; font-size: 15px; font-weight: 600px;" class="text-info">
                            	<b>Select Passport Delivery Type : </b>
                            </td>
                            <td style="text-align:left; padding-left: 10px;">
                    <select name="pp_delivery_method" id="pp_delivery_method" class="form-control" style="width:220px;" >
                        <option value="select">Select Delivery Method</option>
                        <option value="counter_delivery">Counter Delivery</option>
                        <option value="courier_Service">Delivered Via Courier</option>
                        <option value="returned_to_embassy">Returned To Embassy</option>
                    </select>
                            </td>
                        </tr>
            </table>
			<table id="enter_gwf_tbl" class="table" style="border: none !important; width: 900px; display:none;">
            <tr>
                    <td colspan="4" style="height: 20px; border: none;">
                     </td>
                </tr>
                <tr>
                    <td colspan="4" style="border: none; text-align:center;">
                    <label align="center" style="display: inline; padding-bottom: 15px;" class="text-danger h4">Enter Reference Number OR TEE Envelop Number</label>
                     </td>
                </tr>
				<tr style="border: none !important;">
	<td><input name="gwf1" id="gwf1" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;" autofocus></td>
					<td><input name="gwf2" id="gwf2" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
                    <td><input name="gwf3" id="gwf3" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
					<td><input name="gwf4" id="gwf4" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
				</tr>
                <tr>
					<td><input name="gwf5" id="gwf5" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
					<td><input name="gwf6" id="gwf6" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
                    <td><input name="gwf7" id="gwf7" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
					<td><input name="gwf8" id="gwf8" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
				</tr>
                <tr>
					<td><input name="gwf9" id="gwf9" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
					<td><input name="gwf10" id="gwf10" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
                    <td><input name="gwf11" id="gwf11" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
					<td><input name="gwf12" id="gwf12" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
				</tr>
                <tr>
					<td><input name="gwf13" id="gwf13" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
					<td><input name="gwf14" id="gwf14" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
                    <td><input name="gwf15" id="gwf15" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
					<td><input name="gwf16" id="gwf16" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
				</tr>
                <tr>
					<td><input name="gwf17" id="gwf17" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
					<td><input name="gwf18" id="gwf18" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
                    <td><input name="gwf19" id="gwf19" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
					<td><input name="gwf20" id="gwf20" type="text" class="form-control"  placeholder="Reference Number" style="width: 200px;"></td>
				</tr>
                <tr style="height:20px;">
					<td colspan="4"></td>
				</tr>
                <tr>
					<td align="right" valign="middle" colspan="2"><input type="button" name="Search" value= "Submit" class="btn btn-info" onclick="validate()" />&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td align="left" valign="middle" colspan="2">&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="reset"  value= "Reset" class="btn btn-danger" onclick="reset_page()"/></td>
				</tr>
                <tr>
                <td colspan="4">
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
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
	</div>
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