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
	$disabled = explode(",",$_SESSION['disabled_string']);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cash Tally Sheet</title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script src="Scripts/jquery-1.4.4.js" type="text/javascript"></script>
<link rel="stylesheet" href="styles/style.css">
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script> 
<script type="text/javascript">
var total_amt=0;

function calculate_total(amt,curr)
{
	var cnt=0;
	document.getElementById(curr+'grant_total').innerHTML="<b>0</b>";
	var  txtbx= curr+'1000s';
	if( $('#'+txtbx).length )         // use this if you are using id to check
	{
		if((document.getElementById(curr+'1000s').value!="") && (!isNaN(document.getElementById(curr+'1000s').value)))
		{
			var count1000 = document.getElementById(curr+'1000s').value;
			document.getElementById(curr+'1000s').style.borderColor="#CCC";
		}
		else
		{
			count1000=0;
			document.getElementById(curr+'1000s').style.borderColor="red";
			cnt++;
		}
		var total1000 = (parseInt(count1000)*1000);
		document.getElementById(curr+'lbl_1000').innerHTML=total1000;
	}
	else
	{
		count1000=0;
		total1000=0;
	}
	var  txtbx= curr+'500s';
	if( $('#'+txtbx).length )         // use this if you are using id to check
	{
		if((document.getElementById(curr+'500s').value!="") && (!isNaN(document.getElementById(curr+'500s').value)))
		{
			var count500 = document.getElementById(curr+'500s').value;
			document.getElementById(curr+'500s').style.borderColor="#CCC";
		}
		else
		{
			count500=0;
			document.getElementById(curr+'500s').style.borderColor="red";
			cnt++;
		}
		var total500 = (parseInt(count500)*500);
		document.getElementById(curr+'lbl_500').innerHTML=total500;
	}
	else
	{
		count500=0;
		total500=0;
	}
	var  txtbx= curr+'100s';
	if( $('#'+txtbx).length )         // use this if you are using id to check
	{
	
		if((document.getElementById(curr+'100s').value!="") && (!isNaN(document.getElementById(curr+'100s').value)))
		{
			var count100 = document.getElementById(curr+'100s').value;
			document.getElementById(curr+'100s').style.borderColor="#CCC";
		}
		else
		{
			count100=0;
			document.getElementById(curr+'100s').style.borderColor="red";
			cnt++;
		}
		var total100 = (parseInt(count100)*100);
		document.getElementById(curr+'lbl_100').innerHTML=total100;
	}
	else
	{
		count100=0;
		total100=0;
	}
	var  txtbx= curr+'50s';
	if( $('#'+txtbx).length )         // use this if you are using id to check
	{
		if((document.getElementById(curr+'50s').value!="") && (!isNaN(document.getElementById(curr+'50s').value)))
		{
			var count50 = document.getElementById(curr+'50s').value;
			document.getElementById(curr+'50s').style.borderColor="#CCC";
		}
		else
		{
			count50=0;
			document.getElementById(curr+'50s').style.borderColor="red";
			cnt++;
		}
		var total50 = (parseInt(count50)*50);
		document.getElementById(curr+'lbl_50').innerHTML=total50;
	}
	else
	{
		count50=0;
		total50=0;
	}
	var  txtbx= curr+'20s';
	if( $('#'+txtbx).length )         // use this if you are using id to check
	{
		if((document.getElementById(curr+'20s').value!="") && (!isNaN(document.getElementById(curr+'20s').value)))
		{
			var count20 = document.getElementById(curr+'20s').value;
			document.getElementById(curr+'20s').style.borderColor="#CCC";
		}
		else
		{
			count20=0;
			document.getElementById(curr+'20s').style.borderColor="red";
			cnt++;
		}
		var total20 = (parseInt(count20)*20);
		document.getElementById(curr+'lbl_20').innerHTML=total20;
	}
	else
	{
		count20=0;
		total20=0;
	}
	var  txtbx= curr+'10s';
	if( $('#'+txtbx).length )         // use this if you are using id to check
	{
		if((document.getElementById(curr+'10s').value!="") && (!isNaN(document.getElementById(curr+'10s').value)))
		{
			var count10 = document.getElementById(curr+'10s').value;
			document.getElementById(curr+'10s').style.borderColor="#CCC";
		}
		else
		{
			count10=0;
			document.getElementById(curr+'10s').style.borderColor="red";
			cnt++;
		}
		var total10 = (parseInt(count10)*10);
		document.getElementById(curr+'lbl_10').innerHTML=total10;
	}
	else
	{
		count10=0;
		total10=0;
	}
	var  txtbx= curr+'5s';
	if( $('#'+txtbx).length )         // use this if you are using id to check
	{	
	
		if((document.getElementById(curr+'5s').value!="") && (!isNaN(document.getElementById(curr+'5s').value)))
		{
			var count5 = document.getElementById(curr+'5s').value;
			document.getElementById(curr+'5s').style.borderColor="#CCC";
		}
		else
		{
			count5=0;
			document.getElementById(curr+'5s').style.borderColor="red";
			cnt++;
		}
		var total5 = (parseInt(count5)*5);
		document.getElementById(curr+'lbl_5').innerHTML=total5;
	}
	else
	{
		count5=0;
		total5=0;
	}
	var  txtbx= curr+'1s';
	if( $('#'+txtbx).length )         // use this if you are using id to check
	{	
		if((document.getElementById(curr+'1s').value!="") && (!isNaN(document.getElementById(curr+'1s').value)))
		{
			var count1 = document.getElementById(curr+'1s').value;
			document.getElementById(curr+'1s').style.borderColor="#CCC";
		}
		else
		{
			count1=0;
			document.getElementById(curr+'1s').style.borderColor="red";
			cnt++;
		}
		var total1 = (parseInt(count1)*1);
		document.getElementById(curr+'lbl_1').innerHTML=total1;
	}
	else
	{
		count1=0;
		total1=0;
	}
	var  txtbx= curr+'p500s';
	if( $('#'+txtbx).length )         // use this if you are using id to check
	{
		if((document.getElementById(curr+'p500s').value!="") && (!isNaN(document.getElementById(curr+'p500s').value)))
		{
			var countp500 = document.getElementById(curr+'p500s').value;
			document.getElementById(curr+'p500s').style.borderColor="#CCC";
		}
		else
		{
			countp500=0;
			document.getElementById(curr+'p500s').style.borderColor="red";
			cnt++;
		}
		var total500pf = (parseFloat(countp500)*0.500);
		var total500p = Math.round(total500pf*100)/100;
		document.getElementById(curr+'lbl_p500').innerHTML=total500p;
	}
	else
	{
		countp500=0;
		total500p=0;
	}
	var  txtbx= curr+'p100s';
	if( $('#'+txtbx).length )         // use this if you are using id to check
	{
		if((document.getElementById(curr+'p100s').value!="") && (!isNaN(document.getElementById(curr+'p100s').value)))
		{
			var countp100 = document.getElementById(curr+'p100s').value;
			document.getElementById(curr+'p100s').style.borderColor="#CCC";
		}
		else
		{
			countp100=0;
			document.getElementById(curr+'p100s').style.borderColor="red";
			cnt++;
		}
		var total100pf = (parseFloat(countp100)*0.100);
		var total100p = Math.round(total100pf*100)/100;
		document.getElementById(curr+'lbl_p100').innerHTML=total100p;
	}
	else
	{
		countp100=0;
		total100p=0;
	}
	var  txtbx= curr+'p50s';
	if( $('#'+txtbx).length )         // use this if you are using id to check
	{
	
		if((document.getElementById(curr+'p50s').value!="") && (!isNaN(document.getElementById(curr+'p50s').value)))
		{
			var countp50 = document.getElementById(curr+'p50s').value;
			document.getElementById(curr+'p50s').style.borderColor="#CCC";
		}
		else
		{
			countp50=0;
			document.getElementById(curr+'p50s').style.borderColor="red";
			cnt++;
		}
		var total50pf = (parseFloat(countp50)*0.050);
		var total50p = Math.round(total50pf*100)/100;
		document.getElementById(curr+'lbl_p50').innerHTML=total50p;
	}
	else
	{
		countp100=0;
		total50p=0;
	}

	var totalamount=(total1000+total500+total100+total50+total20+total10+total5+total1+total500p+total100p+total50p);
	document.getElementById(curr+'grant_total').innerHTML=totalamount;
	checkequal(curr);	
	
	if(cnt ==0)
	{

		return true;
	}
	else
	{
		return false;
	}
}

function checkequal(curr)
{
	var amountinhand= parseFloat(document.getElementById(curr+'grant_total').innerHTML).toFixed(3);
	var amountaspersale = parseFloat(document.getElementById(curr+'itemwisegtttl').innerHTML).toFixed(3);
	var diff0 = (amountinhand - amountaspersale)
	var diff = Math.round(diff0 * 100) / 100
	if(diff > 0)
	{
		//alert("Excess");
		var msgs = "<p style='color:red;' align='center'>There is an Excess of  " +diff+" "+curr +".</p>"
		document.getElementById(curr+'msg_alert').innerHTML=msgs;
		
	}
	else if(diff<0)
	{
		//alert("Short");
		var msgs = "<p style='color:red;' align='center'>There is a Shortage of  " +diff+" "+curr +".</p>"
		document.getElementById(curr+'msg_alert').innerHTML=msgs;
	}
	else if(diff==0)
	{
		document.getElementById(curr+'grant_total').style.color="green";
		document.getElementById(curr+'itemwisegtttl').style.color="green";
		var msgs = "<p style='color:green;' align='center'>The Amount In Hand is Matching With The Amount In Cash Sheet.</p>"
		document.getElementById(curr+'msg_alert').innerHTML=msgs;
		$("#"+curr+'msg_alert').css('background-color','#A7EDAA');
		//document.getElementById('show_submit').style.display="block";
	}
}

function forwardmail(curr)
{
	if(curr.indexOf(',') > -1)
	{
		var arr = curr.split(',');
	}
	else
	{
		var arr = [];
		arr[0] = curr;
	}
	for(i=0; i< arr.length; i++)
	{
		if(!calculate_total('all',arr[i]))
		{
			var res = 0;
		}
	}
	
	var html_val = $("#foremail").html();
	//$("#htmlformail").val(html_val);
	
	if(res !=0)
	{
		var total_applns = document.getElementById('total_appln_taken').value;
		if(total_applns=="")
		{
			alert("Please Enter Total No. Of Applications Taken Today.")
			document.getElementById('total_appln_taken').style.borderColor='red';
		}
		else
		{
			$('#total_appln_taken').css('border-color','#DDD');
			document.form1.action="php_func.php?cho=18&cr="+curr;
			document.form1.submit();
		}
	}
	else
	{
		alert("Please Enter the Note Couunts.");
	}
	
}

</script>

</head>

<body>
<form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>
" method="post" enctype="multipart/form-data">
<textarea id="htmlformail" name="htmlformail" style="visibility:hidden;"></textarea>
<div id="templatemo_container">
<div id="templatemo_header">
		<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
        <label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
    </div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("menu/left_menu.php"); ?></div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>   
	<div id="templatemo_content" style="min-height: 1100px; max-height: 1500px;">
		<script type="text/javascript" language="javascript">
				
				</script>
		<div id="" style="width: 100% !important; " align="center;">
			<div style="width: 100% !important;" align="center;">
				<div align="center"> 
                <?php
                $myid = $_SESSION['user_id_ukvac'];
				$qr_chksubmitted = "select * from daily_consolidated_submissions where employee_id ='".$myid."' and date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."'";
				$rowexist = mysql_num_rows(mysql_query($qr_chksubmitted));
				if($rowexist >0)
				{
					if(!isset($_SESSION['response_ukvac']))
					{
						echo "<label class='text-info h4'><u>RESUBMIT CASH TALLY SHEET</u></label>";
					}
				}
				else
				{
					echo "<label class='text-info h4'><u>PREPARE CASH TALLY SHEET</u></label>";
				}
				?>
                
                
                </div>
      			</div> <!---End Page Heading -->
                
                <div id="response" style="padding: 15px; width: 100%;" align="center">
                <?php 
				////Iterate through the currencies...
				$get_currencies = mysql_fetch_array(mysql_query("select currencies from missions where id='".$_SESSION['mission_id']."' and status='active'"));
                $curr = preg_replace('/\s+/', '', $get_currencies['currencies']);
                if (preg_match('/,/',$curr))
         		{
                	$currency = explode(",",$curr);
               	}
              	else
           		{
           			$currency[0]=$curr;
            	}
					if(isset($_SESSION['response_ukvac']) && $_SESSION['response_ukvac']== "emailsent")
					{
						unset($_SESSION['response_ukvac']);
						echo "<p align='center' style='color:green; font-weight: bold;'>Cash Tally Sheet Submitted</p>";
						$get_uniue_submitted_user_id = mysql_query("select distinct(user_id) from daily_vas_entries_total where mission_id='".$_SESSION['mission_id']."' and date='".date('Y-m-d')."' order by user_id");
						$get_uniue_submitted_user_id = mysql_query("select distinct(user_id) from daily_vas_entries_total where mission_id='".$_SESSION['mission_id']."' and date='".date('Y-m-d')."' order by user_id");
						$missing = 0;
						while($uniue_submitted_user_id = mysql_fetch_array($get_uniue_submitted_user_id))
						{
							$check_cash_sheet_sbmtd = mysql_num_rows(mysql_query("select id from daily_consolidated_submissions where employee_id ='".$uniue_submitted_user_id['user_id']."' and mission_id='".$_SESSION['mission_id']."' and date='".date('Y-m-d')."'"));
							if($check_cash_sheet_sbmtd !=1)
							{
								$missing++;
							}
						}
						if($missing <1 && !isset($_SESSION['response_ukvac_final']))
						{
						?>
						<iframe src="display_consolidated_report.php?cu=<?php echo $curr; ?>" id="frame" style="display:none;"></iframe>
						<?php
						}
						
					}
					else if(isset($_SESSION['response_ukvac']) && $_SESSION['response_ukvac']== "emailsentfailed")
					{
						echo "<p align='center' style='color:red; font-weight: bold;'>Something Went Wrong. Please Contact Admin.</p>";
						unset($_SESSION['response_ukvac']);
	
					}
					else if(isset($_SESSION['response_ukvac']) && $_SESSION['response_ukvac']== "noreceivers")
					{
						echo "<p align='center' style='color:red; font-weight: bold;'>Email Receivers Are Not Configured. Please Contact Admin.</p>";
						unset($_SESSION['response_ukvac']);
	
					}
					else
					{
						//echo "";
						//unset($_SESSION['response_ukvac']);
					}
					unset($_SESSION['response_ukvac']);
					if(isset($_SESSION['response_ukvac_final']))
					{
						unset($_SESSION['response_ukvac_final']);
					}
				?>
                </div>
                <?php
					//$get_vas_ids_with_currency = "select id from price_list where mission_id = '".$_SESSION['mission_id']."' and current_status = 'active' and currency = '$value'";
				
					$qs="select count(*) as totalcount from passport_inscan_record where date_outscan='".date('Y-m-d')."' and application_taken_by='".$_SESSION['uid_ukvac']."' and current_status='outscan' and mission_id='".$_SESSION['mission_id']."'";
					$qry_total_taken = mysql_query($qs);
					$totalmyapplnts = mysql_fetch_array($qry_total_taken);
					if( count( array_filter( $totalmyapplnts)) == 0)
					{
						$totalmyapplnts[0]='0';
					}
					?>
                	<div style="padding-top: 5px; width: 100%;" align="center" id="foremail">
                		<table class="table" style="width: 100%; height: 40px; font-size: 14px; border: 1px #000000 !important;" id="t1_0">
		  					<tr >
		    					<td valign="middle" style="border-top: 0px; font-weight: bold;">
                                    	<label class='text-info'>&nbsp;&nbsp;&nbsp;Name : </label>
                                        <label id='staffname' name='staffname' class="text-warning" >
											<?php echo $_SESSION['display_name_ukvac']; ?>
                                       	</label>
                         		</td>
		    					<td valign="middle" style="border-top: 0px; font-weight: bold;">
                                    	<label class='text-info'>&nbsp;&nbsp;&nbsp;Date :</label>
                                        <label class="text-warning" >
											<?php echo date('d-M-Y'); ?>
                                      	</label>
                         		</td>
                                
		    					<td valign="middle" style="vertical-align: central; border-top: 0px; font-weight: bold;">
                                    	<label class='text-info' id='total_appln_taken' name='total_appln_taken'>&nbsp;&nbsp;&nbsp;Applications Taken :</label>
                                        <label class="text-warning"> <?php echo $totalmyapplnts[0]; ?></label>
                            	</td>
                                <td valign="middle" style="border-top: 0px; font-weight: bold;">
                                    	<label class='text-info'>&nbsp;&nbsp;&nbsp;Location :</label>
                                        <label class="text-warning" >
											<?php echo $_SESSION['vac']." VAC - ".$_SESSION['vac_city'].",".$_SESSION['vac_country']; ?>
                                      	</label>
                         		</td>
	      					</tr>
                    	</table>
                        <?php
                        foreach($currency as $key=> $value)
      					{
							$qr_chksubted ="select * from daily_consolidated_submissions where employee_id ='".$myid."' and date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='$value'";
							$res = mysql_fetch_array(mysql_query($qr_chksubted));
						?>
                        <hr style="color: black !important;"/>
                        <div class="text-danger" style="font-size: 16px; font-weight: bold; padding-top: 5px;"><u>Cash Tally Sheet - <?php echo $value; ?> </u></div><br>
                        <?php
							$get_currenct_notes = mysql_fetch_array(mysql_query("select notes from note_values where currency='".$value."'"));
							$notes = preg_replace('/\s+/', '', $get_currenct_notes['notes']);
							if (preg_match('/,/',$notes))
							{
								$notes_each = explode(",",$notes);
							}
							else
							{
								$notes_each[0]=$notes;
							}
						?>
                        
                        
                        <table class="table table-bordered tbl1" style="width: 100%; border: 1px #000000 !important; font-size: 12px;" id="t1">
                        	<tr>
                            	<td>
                                	<span class="text-info h5" style="font-weight:bold;">Note Value</span>
                               	</td>
                                <?php
                            	if(in_array("1000",$notes_each))
								{
								?>
                                <td valign="middle" >
                                    <strong>1000s</strong>
                               	</td>
                                <?php
								}
                            	if(in_array("500",$notes_each))
								{
								?>
                                <td valign="middle" >
                                    <strong>500s</strong>
                               	</td>
                                <?php
								}
								if(in_array("100",$notes_each))
								{
								?>
                                <td valign="middle" >
                                    <strong>100s</strong>
                               	</td>
                                <?php
								}
								if(in_array("50",$notes_each))
								{
								?>
                                <td valign="middle" >
                                    <strong>50s</strong>
                               	</td>
                                <?php
								}
								if(in_array("20",$notes_each))
								{
								?>
                                <td valign="middle" >
                                    <strong>20s</strong>
                               	</td>
                                <?php
								}
								if(in_array("10",$notes_each))
								{
								?>
                                <td valign="middle" >
                                    <strong>10s</strong>
                               	</td>
                                <?php
								}
								if(in_array("5",$notes_each))
								{
								?>
                                <td valign="middle" >
                                    <strong>5s</strong>
                               	</td>
                                <?php
								}
								if(in_array("1",$notes_each))
								{
								?>
                                <td valign="middle" >
                                    <strong>1s</strong>
                               	</td>
                                <?php
								}
								if(in_array("0.500",$notes_each))
								{
								?>
                                <td valign="middle" >
                                    <strong>0.500s</strong>
                               	</td>
                                <?php
								}
								if(in_array("0.100",$notes_each))
								{
								?>
                                <td valign="middle" >
                                    <strong>0.100s</strong>
                               	</td>
                                <?php
								}
								if(in_array("0.50",$notes_each))
								{
								?>
                                <td valign="middle" >
                                    <strong>0.050s</strong>
                               	</td>
                                <?php
								}
								?>
                           	</tr>
                          	<tr>
                            	<td>
                                	<span class="text-info h5" style="font-weight: bold;">Note Counts</span>
                                </td>
                                <?php
                            	if(in_array("1000",$notes_each))
								{
								?>
                                <td valign="middle">
                                    <input type="text" id='<?php echo $value; ?>1000s' name='<?php echo $value; ?>1000s' class="form-control" value="<?php if($rowexist>0){echo $res['1000s'];} ?>" placeholder="1000's" style="width: 100px;" onkeyup="calculate_total('1000','<?php echo $value; ?>')"/>
                             	</td>
                                <?php
								}
                            	if(in_array("500",$notes_each))
								{
								?>
								<td valign="middle">
                                    <input type="text" id='<?php echo $value; ?>500s' name='<?php echo $value; ?>500s' class="form-control" value="<?php if($rowexist>0){echo $res['500s'];} ?>" placeholder="500's" style="width: 100px;" onkeyup="calculate_total('500','<?php echo $value; ?>')"/>
                             	</td>
                                <?php
								}
                            	if(in_array("100",$notes_each))
								{
								?>
                                <td valign="middle">
                                    <input type="text" id='<?php echo $value; ?>100s' name='<?php echo $value; ?>100s' class="form-control" value="<?php if($rowexist>0){echo $res['100s'];} ?>" placeholder="100's" style="width: 100px;" onkeyup="calculate_total('100','<?php echo $value; ?>')"/>
                             	</td>
                                <?php
								}
								if(in_array("50",$notes_each))
								{
								?>
                                <td valign="middle">
                                    <input type="text" id='<?php echo $value; ?>50s' name='<?php echo $value; ?>50s' class="form-control" value="<?php if($rowexist>0){echo $res['50s'];} ?>" placeholder="50's" style="width: 100px;" onkeyup="calculate_total('50','<?php echo $value; ?>')"/>
                             	</td>
                                <?php
								}
								if(in_array("20",$notes_each))
								{
								?>
                                <td valign="middle">
                            	<input type="text" id='<?php echo $value; ?>20s' name='<?php echo $value; ?>20s' class="form-control" value="<?php if($rowexist>0){echo $res['20s'];} ?>" placeholder="20's" style="width: 100px;" onkeyup="calculate_total('20','<?php echo $value; ?>')"/>
                          		</td>
                                <?php
								}
								if(in_array("10",$notes_each))
								{
								?>
                            	<td valign="middle">
                                <input type="text" id='<?php echo $value; ?>10s' name='<?php echo $value; ?>10s' class="form-control" value="<?php if($rowexist>0){echo $res['10s'];} ?>" placeholder="10's" style="width: 100px;" onkeyup="calculate_total('10','<?php echo $value; ?>')"/>
                         		</td>
                                <?php
								}
								if(in_array("5",$notes_each))
								{
								?>
                                <td valign="middle">
                            <input type="text" id='<?php echo $value; ?>5s' name='<?php echo $value; ?>5s' class="form-control" value="<?php if($rowexist>0){echo $res['5s'];} ?>" placeholder="5's" style="width: 100px;" onkeyup="calculate_total('5','<?php echo $value; ?>')"/> 
                    			</td>
                                <?php
								}
								if(in_array("1",$notes_each))
								{
								?>
                                <td valign="middle">
                            <input type="text" id='<?php echo $value; ?>1s' name='<?php echo $value; ?>1s' class="form-control" value="<?php if($rowexist>0){echo $res['1s'];} ?>" placeholder="1's" style="width: 100px;" onkeyup="calculate_total('1','<?php echo $value; ?>')"/>
                      			</td>
                                <?php
								}
								if(in_array("0.500",$notes_each))
								{
								?>
                                <td valign="middle">
                        	<input type="text" id='<?php echo $value; ?>p500s' name='<?php echo $value; ?>p500s' class="form-control" value="<?php if($rowexist>0){echo $res['p500s'];} ?>" placeholder="0.500's" style="width: 100px;" onkeyup="calculate_total('p500','<?php echo $value; ?>')"/>
                       			</td>
                                <?php
								}
								if(in_array("0.100",$notes_each))
								{
								?>
                                <td valign="middle">
                            <input type="text" id='<?php echo $value; ?>p100s' name='<?php echo $value; ?>p100s' class="form-control" value="<?php if($rowexist>0){echo $res['p100s'];} ?>" placeholder="0.100's" style="width: 100px;" onkeyup="calculate_total('p100','<?php echo $value; ?>')"/>
                        		</td>
                                <?php
								}
								if(in_array("0.50",$notes_each))
								{
								?>
                                <td valign="middle">
                            <input type="text" id='<?php echo $value; ?>p50s' name='<?php echo $value; ?>p50s' class="form-control" value="<?php if($rowexist>0){echo $res['p50s'];} ?>" placeholder="0.050's" style="width: 100px;" onkeyup="calculate_total('p50','<?php echo $value; ?>')"/>
                        		</td>
                                <?php
								}
								?>
                           	</tr>
                          	<tr>
                            	<td>
                                	<span class="text-info h5" style="font-weight: bold;">Total</span>
                                </td>
                                <?php
                            	if(in_array("1000",$notes_each))
								{
								?>
                                <td valign="middle">
                                    <label id='<?php echo $value; ?>lbl_1000' name='<?php echo $value; ?>lbl_1000' style="font-weight:bold;" ><?php if($rowexist>0){echo $res['1000s']*1000;} ?></label>
                             	</td>
                                <?php
								}
								if(in_array("500",$notes_each))
								{
								?>
                                <td valign="middle">
                                    <label id='<?php echo $value; ?>lbl_500' name='<?php echo $value; ?>lbl_500' style="font-weight:bold;" ><?php if($rowexist>0){echo $res['500s']*500;} ?></label>
                             	</td>
                                <?php
								}
								if(in_array("100",$notes_each))
								{
								?>
                                <td valign="middle">
                                    <label id='<?php echo $value; ?>lbl_100' name='<?php echo $value; ?>lbl_100' style="font-weight:bold;" ><?php if($rowexist>0){echo $res['100s']*100;} ?></label>
                             	</td>
                                <?php
                                }
								if(in_array("50",$notes_each))
								{
								?>
                                <td valign="middle">
                                    <label id='<?php echo $value; ?>lbl_50' name='<?php echo $value; ?>lbl_50' style="font-weight:bold;" ><?php if($rowexist>0){echo $res['50s']*50;} ?></label>
                             	</td>
                                <?php
                                }
								if(in_array("20",$notes_each))
								{
								?>
                                <td valign="middle">
                            	<label id='<?php echo $value; ?>lbl_20' name='<?php echo $value; ?>lbl_20' style="font-weight:bold;" ><?php if($rowexist>0){echo $res['20s']*20;} ?></label>
                           		</td>
                                <?php
                                }
								if(in_array("10",$notes_each))
								{
								?>
								<td valign="middle">
                                <label id='<?php echo $value; ?>lbl_10' name='<?php echo $value; ?>lbl_10' style="font-weight:bold;" ><?php if($rowexist>0){echo $res['10s']*10;} ?></label>
                       		</td>
                            	<?php
								}
								if(in_array("5",$notes_each))
								{
								?>
                                <td valign="middle">
                            <label id='<?php echo $value; ?>lbl_5' name='lbl_5' style="font-weight:bold;" ><?php if($rowexist>0){echo $res['5s']*5;} ?></label>
                				</td>
                                <?php
								}
								if(in_array("1",$notes_each))
								{
								?>
                                <td valign="middle">
                            <label id='<?php echo $value; ?>lbl_1' name='<?php echo $value; ?>lbl_1' style="font-weight:bold;" ><?php if($rowexist>0){echo $res['1s']*1;} ?></label>
                      			</td>
                                <?php
								}
								if(in_array("0.500",$notes_each))
								{
								?>
                                <td valign="middle">
                        	<label id='<?php echo $value; ?>lbl_p500' name='<?php echo $value; ?>lbl_p500' style="font-weight:bold;" ><?php if($rowexist>0){echo $res['p500s']*0.500;} ?></label>
                        		</td>
                                <?php
								}
								if(in_array("0.100",$notes_each))
								{
								?>
                                <td valign="middle">
                        	<label id='<?php echo $value; ?>lbl_p100' name='<?php echo $value; ?>lbl_p100' style="font-weight:bold;" ><?php if($rowexist>0){echo $res['p100s']*0.100;} ?></label>
                        		</td>
                                <?php
								}
								if(in_array("0.50",$notes_each))
								{
								?>
                                <td valign="middle">
                        	<label id='<?php echo $value; ?>lbl_p50' name='<?php echo $value; ?>lbl_p50' style="font-weight:bold;" ><?php if($rowexist>0){echo $res['p50s']*0.050;} ?></label>
                        		</td>
                                <?php
								}
								?>
                          	</tr>
		  					<tr >
		    					<td colspan="2" valign="top">
                            		<p align="right" style="color:red">
                            			<strong>
                                			Total Amount (<?php echo $value; ?>) :&nbsp;&nbsp;&nbsp;
                                        </strong>
                            		</p>
                        		</td>
		    					<td valign="middle" style="color:#060">
                            		<label id='<?php echo $value; ?>grant_total' name='<?php echo $value; ?>grant_total' style="color:red; font-weight: bold;"><?php if($rowexist>0){echo $res['total_amount'];} else {echo 0;} ?></label>
                        		</td>
	      					</tr>
	  					</table>
                		<p>&nbsp;</p>
                        <div id="<?php echo $value; ?>msg_alert" name="<?php echo $value; ?>msg_alert" style="font-size: 13px; font-weight: bold; height: 30px; padding-top: 8px; width:100%; background-color:#FDC1CA;"></div>
                		<hr>
                        
                        
                        
                        
                        
                        <!---------- VAS Table    -->
                <div style="float:left; width: 50%; display:inline; padding-right: 5px;">
                <div style="max-height: 350px; height:auto; overflow:auto; padding-bottom: 5px;">
                <label class="text-success" style="font-size:14px; font-weight: 600"><u>VAS Products Sold</u></label>
                <table class="table table-bordered" style="width:100%; height: 80px; border: 1px #000000 !important; font-size: 12px;" id="t2">
                	<tr>
                        <td valign="top">
                            	<span class="text-info h5" style="font-weight:bold;">Category</span>
                        </td>
                        <td valign="top">
                           		<span class="text-info h5" style="font-weight:bold;">Count</span>
                        </td>
                        <td valign="top">
                            	<span class="text-info h5" style="font-weight:bold;">Total Amount(<?php echo $value;  ?>)</span>
                      	</td>
                  	</tr>
                  	<?php
				  	$grant_total_amount_VAS=0;
				  	$get_total_each = mysql_query("select * from daily_vas_entries_total where user_id='$myid' and date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."'");
					while($get_vas_total_for_day=mysql_fetch_array($get_total_each))
				  	{
						$vas_name_and_price1 = mysql_query("select * from price_list where id='".$get_vas_total_for_day['vas_id']."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'");					  	
						$vas_name_and_price=mysql_fetch_array($vas_name_and_price1);
					 	$vas_id=$vas_name_and_price['id'];
					  	$vas_name = $vas_name_and_price['service_name'];
					  	$vas_price = $vas_name_and_price['amount'];
					  	$total_vas_count = $get_vas_total_for_day['total'];
					  	$total_vas_amount = $vas_price*$total_vas_count;
					  	$grant_total_amount_VAS = $grant_total_amount_VAS+$total_vas_amount;
						if(mysql_num_rows($vas_name_and_price1)>0)
						{
              		?>
              		
              		<tr>
                    	<td valign="top" style="padding-right: 10px;">
                            	<?php echo $vas_name; ?> &nbsp;(<label><?php echo $vas_price; ?></label>&nbsp;<?php echo $value; ?>)
                     	</td>
                    	<td valign="top" style="padding-left: 10px;">
                    		<input type="text" id='<?php echo $vas_id; ?>' name='<?php echo $vas_id; ?>' class="form-control" value="<?php echo $total_vas_count; ?>" style="width: 100px; cursor:not-allowed; display: inline;" readonly />	
                    	</td>
                    	<td valign="top">
                            <label style="font-weight:bold; padding-left: 10px;" ><?php echo $total_vas_amount; ?></label>&nbsp;
                      	</td>
                  	</tr>
              		<?php
						}
				  	}
					if(!in_array("bts.php",$disabled))
					{
						$get_exchange_rate = mysql_fetch_array(mysql_query("select exchng_rate_wt_gbp from exchange_rates where currency ='".$value."'"));
						$exchange_rate = $get_exchange_rate['exchng_rate_wt_gbp'];
						
						$datetoday = date('Y-m-d');
						$user_id = $_SESSION['user_id_ukvac'];
						$grand_total_bts=(float)0;
						$getbts_sales_today = "select quantity_sold*gbp_price as total_amount,date_sold,user_id from bts_sales where user_id=$user_id and date_sold='$datetoday' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$q_ftch = mysql_query($getbts_sales_today);
						while($res_getbts_sales_today= mysql_fetch_array($q_ftch))
						{
							$ttl_roundamount = round($res_getbts_sales_today['total_amount']*$exchange_rate);
							$grand_total_bts=$grand_total_bts+ $ttl_roundamount;
						}
						?>
					<tr>
                    	<td valign="top" style="padding-right: 10px;">
                            	<strong>Britain Travel Shop Products</strong>
                     	</td>
                    	<td valign="top" style="padding-left: 10px;">
                        </td>
                    	<td valign="top" style="padding-left: 10px;">
                        	<label id='totalbtssales' name='totalbtssales' style="font-weight:bold;" ><?php echo $grand_total_bts; ?></label>
                        </td>
                  	</tr>
             		<?php
					}
					else
					{
						$grand_total_bts=0;
					}
			 		?>
          			<tr>
                    	<td colspan="2" valign="top" style="padding-right: 10px;">
                        	<p align="right" style="color:red;">
                            	<strong>Total Amount (<?php echo $value; ?>) :&nbsp;&nbsp;&nbsp;</strong>
                          	</p>
                  		</td>
                    	<td valign="top">
                            <p>&nbsp;
                            	<label id='<?php echo $value; ?>itemwisegtttl' name='<?php echo $value; ?>itemwisegtttl' style="font-weight:bold; padding-left: 10px; color:red;" ><?php echo $grant_total_amount_VAS+$grand_total_bts;  ?></label>&nbsp;
                            </p>
                        </td>
                  	</tr>
            	</table>
                <script> checkequal('<?php echo $value; ?>'); </script>
				<p>&nbsp;</p>
            <?php    
			}
			//Close Currency Loop
			?>
            </div>
             </div>
                <div style="width: 50%; margin-left: 50%;">
                <?php
                   if(!in_array("bts.php",$disabled))
                {
                ?>
            			<?php
						$allbts_sales_today = "select quantity_sold, gbp_price, quantity_sold*gbp_price as total_amount,product_name from bts_sales where user_id=$user_id and date_sold='$datetoday' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$slno=0;
            if(mysql_num_rows(mysql_query($allbts_sales_today)) > 0)
						{
						?>
                        <label class="text-success" style="font-size:14px; font-weight: 600"><u>Travel Products List</u> </label>
            		<div style="height: 350px; overflow:auto; padding-bottom: 5px;">
     				<table class="table table-bordered" style="width:100%; border: 1px #000000 !important; font-size: 12px;" id="t3">
        				<tr>
                            <td valign="top">
                                <span class="text-info h5" style="font-weight:bold;">Sl. No</span>
                          	</td>
                            <td  valign="top">
                            	<span class="text-info h5" style="font-weight:bold;">Product Name</span>
                            </td>
                            <td  valign="top">
                            	<span class="text-info h5" style="font-weight:bold;">Quantity Sold</span>
                            </td>
                            <td  valign="top">
                               	<span class="text-info h5" style="font-weight:bold;">Unit Price (<?php echo $value;  ?>)</span>
                            </td>
                            <td  valign="top">
                                <span class="text-info h5" style="font-weight:bold;">Total Amount(<?php echo $value;  ?>)</span>
                            </td>
                      	</tr>
                        <?php
						$res_allsales = mysql_query($allbts_sales_today);
						while($res1_allsales = mysql_fetch_array($res_allsales))
						{
							$slno++;
						?>
            			<tr>
            				<td valign="top" style="text-align:left; padding-left: 5px;">
								<?php echo $slno; ?>
                            </td>
              				<td valign="top" style="text-align:left; padding-left: 5px;">
								<?php echo $res1_allsales['product_name'];?>
                           	</td>
               				<td valign="top" style="text-align:left; padding-left: 5px;">
								<?php echo $res1_allsales['quantity_sold'];?>
                            </td>
               				<td valign="top" style="text-align:left; padding-left: 5px;">
								<?php echo ($res1_allsales['gbp_price']*$exchange_rate);?>
                          	</td>
              				<td valign="top" style="text-align:left; padding-left: 5px;">
								<?php echo round($res1_allsales['total_amount']*$exchange_rate);?>
                            </td>
          				</tr>
						<?php
                        }
                        ?>
            		</table>
                    </div>
   				<?php
						}		
				}
				?>
                </div>
                <br>
                <div style="width:100%; padding-top: 5px; text-align:left; display:block; clear:both;">
                	<label style="text-align:center; font-weight: bold;"><u>Comments</u></label>
               		<textarea class="form-control" id="<?php echo $value; ?>comments" name="<?php echo $value; ?>comments" style="width: 50%;" rows="3" class="inputtext"><?php if($rowexist>0){echo $res['comments'];} ?></textarea>
               	</div>
            
           	<p>&nbsp;</p>	  
            <div id="show_submit" name="show_submit" align="center" style="width:100%;" >
            	<input type="button" class="btn btn-info" id="sendmailsheet" name="sendmailsheet" onClick="forwardmail('<?php echo $curr; ?>')"  value="Submit Cash Tally Sheet"/>
              	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             	<input type="button" class="btn btn-danger" id="sendmailsheet1" name="sendmailsheet1" value="Cancel" />
                <p style="padding: 8px"></p>
              	<input type="hidden" value="" name="h1" id="h1"/>
             	<input type="hidden" value="" name="h2" id="h2"/>
             	<input type="hidden" value="" name="h3" id="h3"/>
             	<input type="hidden" value="" name="h4" id="h4"/>
              	<input type="hidden" value="" name="h5" id="h5"/>
            </div>
     	
	</div>
</div>
		
<p>&nbsp;</p>
<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span>
    <br>
	
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