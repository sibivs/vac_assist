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
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="staff" ))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Biometrics Pending</title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<!--script src="Scripts/jquery-1.4.4.js" type="text/javascript"></script -->
<link rel="stylesheet" href="styles/style.css">
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script> 
<script type="text/javascript">

var intr = setInterval(function(){ window.location.reload(); }, 100000);


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
					$('#info_token_exists1').html(html_1+html_2+html_3+html_4);
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
	
	$("#templatemo_left_content").load("left_menu.php");
});

function submit_bio(refno)
{
	$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data: 'cho=45&reference='+refno,
			dataType:"json",
			success: function(result)
			{
				if(result="success")
				{
					alert("Applicant Status Updated");
					window.location.reload();
				}
				else if(result=='timeout')
				{
					alert('Session Expired. Please Login!')
					window.location.href='login.php';
					window.location.reload();
				}
				else
				{
					alert("Something went wrong. Please contact administator");
					window.location.reload();
				}
			},
			error: function(request, status, error)
			{
					alert(request.responseText);  
			}
						
		});
}

</script>

</head>

<body>
<form name="form1" id="form1" method="post">
<div id="templatemo_container">
	<div id="templatemo_header" style="">
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
                        	BIOMETRIC PENDING APPLICATIONS</label></div>
      			</div> <!---End Page Heading -->
                <div style="padding: 15px; min-height: 300px; vertical-align:top;" align="center">
					
                        <?php
						$cnt=0;
						$date = date("Y-m-d");
						$query_count_result = "SELECT count(*) FROM appointment_schedule where application_status='ro_complete' and date_of_appointment='$date' and mission_id='".$_SESSION['mission_id']."'";
        				$result_count_result = mysql_fetch_array(mysql_query( $query_count_result));

                		if (isset($_GET['pageno'])) 
						{
                			$pageno = $_GET['pageno'];
                		}
                		else
                		{
                        	$pageno = 1;
							$lastpage = 1;

                		}
						if($result_count_result[0]>0)
        				{ 
							?>
                            <table class="table" align="center" style="width: 80%;">
                                <thead class="text-info h5">
                                    <tr>
                                        <td width="10%">Sl.No</td>
                                        <td width="15%">Token Number</td>
                                        <td width="25%">Reference Number</td>
                                        <td width="25%">Appointment Type</td>
                                        <td width="15%">Wait Time</td>
                                        <td width="20%">Submit</td>
                                    </tr>
                                </thead>
                            <?php
                			$query = "SELECT count(*) FROM appointment_schedule where application_status='ro_complete' and date_of_appointment='$date' and mission_id='".$_SESSION['mission_id']."' and id <> ''";
                			$result = mysql_query($query);
                			$query_data = mysql_fetch_row($result);
                			$numrows = $query_data[0];
                			$rows_per_page = 10;
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
                			$limit = 'LIMIT ' .($pageno-1) * $rows_per_page .',' .$rows_per_page;
							$qr_t="SELECT a.reference_number as reference_number,a.actual_appointment as appointment, st.token_no as token_no, TIMEDIFF(CURTIME(), st.ro_finish_at) as waiting FROM appointment_schedule a, submission_timing st where a.id=st.reference_id and a.application_status='ro_complete' and a.date_of_appointment='$date' and st.mission_id='".$_SESSION['mission_id']."' and st.shown_up_remark NOT IN ('reject') ORDER BY st.token_no  ASC $limit ";
                        	$query_instance = mysql_query($qr_t) OR die(mysql_error());
                        	$rows=mysql_num_rows($query_instance);
							while($recResult = mysql_fetch_array($query_instance))
							{
								$cnt++;
						?>
                        <tbody style="border: 1px;">
						<tr>
							<td align="left"> <?php echo $cnt; ?></td>
                            <td style="text-align:left !important;"><?php echo $recResult['token_no']; ?></td>
                            <td style="text-align:left !important;"><?php echo $recResult['reference_number']; ?></td>
                            <td style="text-align:left !important;"><?php echo $recResult['appointment']; ?></td>
                            <td align="left"><?php echo $recResult['waiting'];?> Hrs </td>
                            <td align="left"><input type="button" class="btn btn-info"  onClick="submit_bio('<?php echo $recResult['reference_number']; ?>')" value="Submit"></td>
                           
						</tr>
                        <?php
							}
						}
						?>
                        </tbody>
                    </table>
                    <p style="height:10px"></p>
                    	<?php
						if($result_count_result[0]>0)
        				{ 
							echo "<div id='nextprev'  style='font-size:13px;' align='center'>";
							if ($pageno == 1)
							{
								echo " FIRST PREV ";
							}
							else
							{
								echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1' style='font-size:13px; cursor:pointer;'>FIRST</a> ";
								$prevpage = $pageno-1;
								echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage' style='font-size:13px; cursor:pointer;'>PREV</a> ";
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
						}
						else
						{
							echo "No Applicants pending.";
						}
					?>
                    
                    	
              	</div>
              
		  </div>
		</div>
		
		<p>&nbsp;</p>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
	</div>
</div>
</form>

	<div id="popup_box_token_pending" align="center" style="text-align:center;">    <!-- OUR PopupBox DIV-->
    	<div class="text-warning text-center " id="info_token_exists1"></div>

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