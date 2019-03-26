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
<link rel="stylesheet" href="styles/style.css">
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->

<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>

</head>

<body style="font-size: 13px;">
<div id="templatemo_container" style="height:1400px;">
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
				<div align="center"> <label class='text-info h4'>APPOINTMENT STATUS  <?php echo date("d-m-Y"); ?></label></div>
      			</div>
                <br>
                <div style="text-align: right; float: right; display:inline;"><input type="button" class="btn btn-danger" value="Get Current Appointment Status" onClick="loadPopupBox3()"></div>
                <div style="width:100%;">
                <form action="" method="post" enctype="multipart/form-data">
                
               	<?php
				if (isset($_GET['pageno'])) 
				{
					$pageno = $_GET['pageno'];
				}
				else
				{
					$pageno = 1;
					$lastpage = 1;
				}
				$query = "SELECT count(*) FROM appointment_schedule WHERE date_of_appointment ='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and application_status NOT IN('rejected') and id <> ''";
				$result = mysql_query($query);
				$query_data = mysql_fetch_row($result);
                $numrows = $query_data[0];
				if($numrows >0)
				{
				?>
                    <table class="table">
                        <thead class="text-danger" style="">
                            <tr>
                                <td >Sl.No</td>
								<td >Token</td>
                                <td >Ref. Number</td>
                                <td >Time</td>
                                <td >Submitted At</td>
                                <td >Appointment Type</td>
                                <td >Visa Type</td>
                                <td >Wait Time</td>
                                <td >Status</td>
                            </tr>
                        </thead>
                  		<tbody>

                    	<?php 
                			$rows_per_page = 30;
                			$lastpage = ceil($numrows/$rows_per_page);
                			$pageno = (int)$pageno;
					        if ($pageno < 1)
                			{
                        		$pageno = 1;
                			}
                			elseif ($pageno > $lastpage)
                			{
                        		$pageno = $lastpage;
                			} 
							if($pageno == 1 ||$pageno =="" )
							{
								$cnt=0;
							}
							else if($pageno >1)
							{
								$cnt= ($pageno-1)*$rows_per_page;
							}
                			$limit = 'LIMIT ' .($pageno-1) * $rows_per_page .',' .$rows_per_page;
							$qr_t="SELECT * FROM appointment_schedule WHERE date_of_appointment ='".date('Y-m-d')."' and application_status NOT IN('rejected') and mission_id='".$_SESSION['mission_id']."' order by scheduled_date_app ASC , CAST(time_appointment AS TIME) ASC $limit ";
							$query_instance = mysql_query($qr_t) OR die(mysql_error());
                        	$rows=mysql_num_rows($query_instance);
							while($recResult = mysql_fetch_array($query_instance))
							{
								if($recResult['application_status']=="ro_complete" || $recResult['application_status']=="shown_up")
								{
									$get_time_wait = mysql_fetch_array(mysql_query("select TIMEDIFF(CURTIME(), st.ao_finish_at) as wait from submission_timing st, appointment_schedule a where a.id=st.reference_id and a.id= '".$recResult['id']."' and a.mission_id='".$_SESSION['mission_id']."'"));
								}
								else if($recResult['application_status']=="outscan")
								{
									$get_time_wait = mysql_fetch_array(mysql_query("select TIMEDIFF(st.bio_finish_at, st.ao_finish_at) as wait from submission_timing st, appointment_schedule a where a.id=st.reference_id and a.id= '".$recResult['id']."' and a.mission_id='".$_SESSION['mission_id']."'"));
								}
								else if($recResult['application_status']=="scheduled")
								{
									$get_time_wait['wait']="";
								}
								
								$cnt++;
								if($recResult['application_status']=="scheduled")
								{
									$status="<span style='color:orange; font-size:13px;'>Scheduled</span>";
								}
								else if($recResult['application_status']=="outscan")
								{
									$status="<span style='color:green; font-size:13px;'>Submitted</span>";
								}
								else if($recResult['application_status']=="no_noc")
								{
									$status="<span style='color:brown; font-size:13px;'>NOC Missing</span>";
								}
								else if($recResult['application_status']=="shown_up")
								{
									$status="<span style='color:purple; font-size:13px;'>AO Created</span>";
								}
								else if($recResult['application_status']=="no_cash")
								{
								   $status="<span style='color:red; font-size:13px;'>Insufficiant Cash</span>";
								}
								else if($recResult['application_status']=="missing_document")
								{   
								   $status="<span style='color:red; font-size:13px;'>Documents Missing</span>";
								}
								else if($recResult['application_status']=="premium_not_wanted")
								{
							$status="<span style='color:red; font-size:13px;'>Hesitate to pay premium</span>";
								}
								else if($recResult['application_status']=="applicant_not_present")
								{
							$status="<span style='color:red; font-size:13px;'>Applicant not present</span>";
								}
								else if($recResult['application_status']=="medical_issues")
								{
									$status="<span style='color:red; font-size:13px;'>Medical Issues</span>";
								}
								else if($recResult['application_status']=="ro_complete")
								{
									$status="<span style='color:red; font-size:13px;'>BIO Pending</span>";
								}
								else if($recResult['application_status']=="Henna")
								{
									$status="<span style='color:red; font-size:13px;'>Henna On Hand</span>";
								}
								else if($recResult['application_status']=="pending")
								{
									$status="<span style='color:red; font-size:13px;'>Approval Pending</span>";
								}
								else
								{
									$status="Unknown";
								}
								$get_ao_time = mysql_fetch_array(mysql_query("select token_no, DATE_FORMAT(ao_finish_at ,'%h:%i') as ao_finish_at from submission_timing where reference_id='".$recResult['id']."' and shown_up_remark not in ('reject') and mission_id='".$_SESSION['mission_id']."'"));
								
								?>
									<tr>
										<td align="left"> <?php echo $cnt; ?></td>
										<td align="left"> <?php echo strtoupper($get_ao_time['token_no']); ?></td>
										<td align="left"><?php echo $recResult['reference_number']; ?></td>
	<td align="left"><?php if($recResult['scheduled_date_app']=="0000-00-00") { echo "Walk-In";} else { echo $recResult['time_appointment']; } ?></td>
    <td align="left"><?php if($recResult['application_status']!="scheduled") { echo $recResult['date_of_appointment']." <span style='color: red'> - ".$get_ao_time['ao_finish_at']."</span>"; } else { echo "";} ?></td>
										<td align="left"><?php echo $recResult['appointment_type']; ?></td>
										<td align="left"><?php echo $recResult['visa_category']; ?></td>
                                        <td align="left"><?php echo $get_time_wait['wait']; ?></td>
										<td align="left"><?php echo $status; ?></td>
									</tr>
								
								<?php
							}
							?>
                            </tbody>
							</table>
							<p style="height:10px"></p>
							<?php
							echo "<div id='nextprev'  style='font-size:13px;' align='center'>";
							if ($pageno == 1)
							{
								echo " FIRST PREV ";
							}
							else
							{
								echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1' style='font-size:13px; cursor:pointer;'>FIRST</a> ";
								$prevpage = $pageno-1;
								echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage' style='font-size:13px; cursor:pointer;'>PREVIOUS</a> ";
							} 
							echo " ( Page $pageno of $lastpage ) ";
							if ($pageno == $lastpage)
							{
								echo " NEXT LAST ";
							}
							else
							{
								$nextpage = $pageno+1;
								echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage' style='font-size:13px; cursor:pointer;'>NEXT</a> ";
								echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage' style='font-size:13px; cursor:pointer;'>LAST</a> ";
							}
							echo "</div>";
					?>
                    <p style="height:30px"></p>
                <?php
                 }
				else
				{
					
				?>
					<div class="text-warning" style="font-size: 15px !important;">No appointments are scheduled for the day. <?php if($_SESSION['role_ukvac'] == 'administrator') { ?><a href="appointments.php">Click Here </a> to upload the appointments.<?php } ?></div>
				<?php
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

<div id="popup_box_ao_status" align="center" style="text-align:center;">    <!-- OUR PopupBox DIV-->
    <div class="text-warning text-center h4"><u>AO Current Status</u></div>
    <div style="height: 40px; display:none; border: 0px; vertical-align: top; padding-bottom: 30px;" class="form-control" align="center" id="response1"></div>
    <?php 
	$date_today = date("Y-m-d");
	$get_total_app = mysql_query("select count(*) as total_appointment from appointment_schedule where scheduled_date_app='$date_today' and mission_id='".$_SESSION['mission_id']."'");
	$result_total_appointment= mysql_fetch_array($get_total_app);
	if($result_total_appointment['total_appointment'] >0)
	{
		$total_app = $result_total_appointment['total_appointment'];
	}
	else
	{
		$total_app=0;
	}
	
	
	$get_total_shown_up = mysql_query("select count(*) as total_shown_up from appointment_schedule where application_status NOT IN ('scheduled','applicant_not_present')  and scheduled_date_app='$date_today' and mission_id='".$_SESSION['mission_id']."'");
	$result_total_shown_up= mysql_fetch_array($get_total_shown_up);
	if($result_total_shown_up['total_shown_up'] >0)
	{
		$total_shown_up = $result_total_shown_up['total_shown_up'];
	}
	else
	{
		$total_shown_up=0;
	}
	
	
	
	$get_total_submissions = mysql_query("select count(*) as total_submitted from appointment_schedule where application_status IN ('outscan')  and date_of_appointment ='$date_today' and scheduled_date_app = '$date_today' and mission_id='".$_SESSION['mission_id']."'");
	$result_total_submissions= mysql_fetch_array($get_total_submissions);
	if($result_total_submissions['total_submitted'] >0)
	{
		$total_submission = $result_total_submissions['total_submitted'];
	}
	else
	{
		$total_submission=0;
	}


	$get_total_submissions_prev = mysql_query("select count(*) as total_submitted from appointment_schedule where application_status IN ('outscan')  and scheduled_date_app = '$date_today' and date_of_appointment NOT IN ('$date_today') and mission_id='".$_SESSION['mission_id']."'");
	$result_total_submissions_prev= mysql_fetch_array($get_total_submissions_prev);
	if($result_total_submissions_prev['total_submitted'] >0)
	{
		$total_submission_prev = $result_total_submissions_prev['total_submitted'];
	}
	else
	{
		$total_submission_prev=0;
	}
	
	
	$get_total_returned = mysql_query("select count(*) as total_returned from appointment_schedule where application_status NOT IN ('scheduled','outscan','shown_up','ro_complete')  and scheduled_date_app='$date_today' and mission_id='".$_SESSION['mission_id']."'");
	$result_total_returned= mysql_fetch_array($get_total_returned);
	if($result_total_returned['total_returned'] >0)
	{
		$total_returned = $result_total_returned['total_returned'];
	}
	else
	{
		$total_returned=0;
	}

	$walkins = mysql_query("select count(*) as walkin FROM appointment_schedule WHERE date_of_appointment='$date_today' AND `scheduled_date_app` NOT IN ('$date_today') and application_status NOT IN('rejected','pending') and mission_id='".$_SESSION['mission_id']."'");
	$result_walkins= mysql_fetch_array($walkins);
	if($result_walkins['walkin'] >0)
	{
		$total_walkin = $result_walkins['walkin'];
	}
	else
	{
		$total_walkin =0;
	}

	$get_total_submissions_waiting = mysql_query("select count(*) as total_submitted_waiting from appointment_schedule where application_status IN ('shown_up','ro_complete')  and scheduled_date_app='$date_today' and mission_id='".$_SESSION['mission_id']."'");
	$result_total_submissions_waiting= mysql_fetch_array($get_total_submissions_waiting);
	if($result_total_submissions_waiting['total_submitted_waiting'] >0)
	{
		$total_submission_waiting = $result_total_submissions_waiting['total_submitted_waiting'];
	}
	else
	{
		$total_submission_waiting=0;
	}
	
	
	$get_total_noshow = mysql_query("select count(*) as total_noshow from appointment_schedule where application_status IN ('scheduled')  and scheduled_date_app='$date_today' and mission_id='".$_SESSION['mission_id']."'");
	$result_total_noshow= mysql_fetch_array($get_total_noshow);
	if($result_total_noshow['total_noshow'] >0)
	{
		$total_noshow = $result_total_noshow['total_noshow'];
	}
	else
	{
		$total_noshow=0;
	}
	
	?>
    <table class="table">
    	<tr>
        	<td class="text-info" style="text-align: left; font-weight: bold; font-size: 14px;">Date : <?php echo date("d-m-Y"); ?></td>
            <td class="text-info" style="text-align: left; font-weight: bold; font-size: 14px;">Total Appoinmtnents : <?php echo $total_app;  ?></td>
        </tr>
        <tr>
        	<td colspan="2" class="text-info" style="text-align: left;font-size: 14px;">Total shown up applicants  :  <span style="font-weight:normal;"><?php echo ($total_submission+$total_returned+$total_submission_waiting); //echo $total_shown_up; ?></span></td>
        </tr>
        <tr>
        	<td colspan="2" class="text-danger" style="border: 0px; border-color: none; text-align: left; font-size: 14px; padding-left: 50px;">- Total submitted applicants :  <span style="font-weight:normal;"><?php echo $total_submission; ?></span></td>
        </tr>
	<tr>
        	<td colspan="2" class="text-danger" style="border: 0px; border-color: none; text-align: left; font-size: 14px; padding-left: 50px;">- Total not submitted applicants :  <span style="font-weight:normal;"><?php echo $total_returned; ?></span></td>
        </tr>
        <tr>
        	<td colspan="2" class="text-danger" style="border: 0px; border-color: none; text-align: left; font-size: 14px; padding-left: 50px;">- Total applicants waiting for submission :  <span style="font-weight:normal;"><?php echo $total_submission_waiting; ?></span></td>
        </tr>
	<tr>
        	<td colspan="2" class="text-info" style="font-size: 14px;border: 0px; border-color: none; text-align: left;">Total no shows  :  <span style="font-weight:normal;"><?php echo ($total_noshow+$total_submission_prev); ?></span></td>
        </tr>
	<tr>
        	<td colspan="2" class="text-danger" style="border: 0px; border-color: none; text-align: left; font-size: 14px; padding-left: 50px;">- Previous Day Submissions :  <span style="font-weight:normal;"><?php echo $total_submission_prev; ?></span></td>
	</tr>
	<tr>
        	<td colspan="2" class="text-danger" style="border: 0px; border-color: none; text-align: left; font-size: 14px; padding-left: 50px;">- Today's No Shows :  <span style="font-weight:normal;"><?php echo $total_noshow; ?></span></td>
        </tr>
	<tr>
        	<td colspan="2" class="text-info" style="font-size: 14px;text-align: left;">Total Walk-In applicantions Submitted  :  <span style="font-weight:normal;"><?php echo $total_walkin; ?></span></td>
        </tr>    
</table>
    
    <a id="popupBoxClose"><img src="styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox3()"></a>    
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