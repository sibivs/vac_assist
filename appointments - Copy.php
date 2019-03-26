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
<link rel="stylesheet" href="styles/style.css">
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="styles/styles_lmenu.css" />
<script src="Scripts/script.js"></script>
<script type="text/javascript" language="javascript">

$( document).ready(function() {
    $("#BrowseFile").click(function(){
        $("#file").trigger("click");
    });
});

$(function() {
     $("input:file").change(function (){
       var fileName = $(this).val();
       $(".filename").html(fileName);
     });
  });
</script>
</head>

<body>
<div id="templatemo_container">
	<div id="templatemo_header">
		<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
        <label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
    </div><!-- End Of Header -->
    <div id="templatemo_userinfo"></div>    
	<div id="templatemo_content">
		<div style="width: 16%;" id="templatemo_left_content">
    		
		</div>
		<script type="text/javascript" language="javascript">
				$(document).ready( function() {
					$("#templatemo_left_content").load("left_menu.php");
					$("#templatemo_userinfo").load("header.php");
				});
				</script>

		<div id="templatemo_right_content" style="width: 80%;">
			<div id="templatemo_content_area">
				<div id="PageHeading" class="PageHeading" align="middle">
      				<p align="center" style="width: 100%; vertical-align:middle;">
                    	<font color="#FFFFFF">
                        	<b>UPLOAD TODAY'S APPOINTMENTS</b>
                        </font>
                    </p>
      			</div>
                <div style="height: 35px;">
				

                </div>
                <div style="width:100%;">
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                    <div id="import-ppt-div" style="width: 100%;">
                    <table width="100%" class="table">
						<tr>
							<td width="30%" class="text-info h5" style="border-top: 0px;" >Select File to Upload  (.xls/.xlsx/.csv) : </td>
							<td width="70%" style="text-align: left; border-top: 0px;"><input type="file" name="file" id="file" style="display:none;"><input type="button" class="btn btn-info" value="Browse File" id="BrowseFile">&nbsp; &nbsp;<span class="filename">Nothing selected</span></td>
						</tr>
						<tr>
							<td colspan="2" align="center"><input type="submit" name="submit" value="Upload Appointment List" class="btn btn-warning"></td>
						</tr>
                        <tr>
                        	<td colspan="2" style="border-top: 0px;">
                            <?php
                    $uploadedStatus = 0;
					$msg ="";
					$date_today = date('Y-m-d');
					$total_appointments_added=0;
					$wrong_status = "";
					$gwf_list="";
					if(isset($_POST["submit"])) 
					{
						if(isset($_FILES["file"])) 
						{
							//if there was an error uploading the file
							if ($_FILES["file"]["error"] > 0) 
							{
								echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
							}
							else 
							{
									//echo "update appointment_schedule set application_status = 'cancelled' where (application_status = 'scheduled' OR application_status = 'shown_up') and mission_id='".$_SESSION['mission_id']."' and scheduled_date_app NOT IN ('".$date_today."')";
									
									//Update all scheduled 
									
									
									$qr=mysql_query("update appointment_schedule set application_status = 'cancelled' where (application_status = 'scheduled' OR application_status = 'shown_up') and mission_id='".$_SESSION['mission_id']."' and scheduled_date_app NOT IN ('".$date_today."')");
									$storagename = "Sample_files/appointments.xlsx";
									move_uploaded_file($_FILES["file"]["tmp_name"],  $storagename);
									$uploadedStatus = 1;
									
	 
									set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
									include 'PHPExcel/IOFactory.php';
	 
									// This is the file path to be uploaded.
									$inputFileName = 'Sample_files/appointments.xlsx';
	 
									try 
									{
										$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
									} 
									catch(Exception $e) 
									{
										die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
									}
	 
									$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
									$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
									$time_column="";
									$reference_num_column="";
									$app_type_column="";
									$visa_type="";
									//$appointments_array= array();
									for ($char = 'A'; $char != 'Z'; $char++) 
									{
										if(isset($allDataInSheet['1'][$char]))
										{
											$column_header = strtoupper(trim($allDataInSheet['1'][$char]));
											if(strpos($column_header,'TIME') !== false)
											{
												$time_column= $char;
											}
											else if( (strpos($column_header,'REFERENCE') !== false) || (strpos($column_header,'GWF') !== false))
											{
												$reference_num_column=$char;
											}
											else if( (strpos($column_header,'TYPE') !== false))
											{
												$visa_type_column=$char;
											}
											else if( (strpos($column_header,'APPOINTMENT') !== false))
											{
												$app_type_column=$char;
											}
											else
											{
											}
										}
										
									} 
									
									
									
									for($i=2;$i<=($arrayCount);$i++)
									{
										$gwf = strtoupper(trim($allDataInSheet[$i][$reference_num_column]));
										$appointment_time= strtoupper(trim($allDataInSheet[$i][$time_column]));
										$visa_type= strtoupper(trim($allDataInSheet[$i][$visa_type_column]));
										$app_category=strtoupper(trim($allDataInSheet[$i][$app_type_column]));
										$appointments_array[$i]=array("time" => $appointment_time, "ref_no" => $gwf, "visa_type" => $visa_type,"app_type"=> $app_category);
										
										if($gwf!="")
										{
											$query = "SELECT reference_number,application_status,remarks FROM appointment_schedule WHERE reference_number = '".$gwf."' and mission_id='".$_SESSION['mission_id']."'";
											$sql = mysql_query($query);
											$result_ref_exists = mysql_num_rows($sql);
											if($result_ref_exists==0) 
											{
												//scheduled
												//shown_up
												//outscan
												//cancelled
												//no_noc
												
												date_default_timezone_set("Asia/Bahrain");
												$date_today=date("Y-m-d");
												if(strpos($app_category,'PREMIUM') !== false)
												{
													$lounge_type="Premium Lounge Appointment";
												}
												else
												{
													$lounge_type="Standard Appointment";
												}
												
												$qr = "insert into appointment_schedule values (DEFAULT,'0','$gwf','$date_today','$date_today','$appointment_time','$app_category','$lounge_type','$visa_type','scheduled','','".$_SESSION['mission_id']."')";
												if(mysql_query($qr))
												{
													$total_appointments_added++;
													$msg = '<font color="#009900">'.$total_appointments_added. ' Records has been Imported.</font>';
												}
												else
												{
													$msg=mysql_error();
												}
											}
											else
											{
												$get_old_status = mysql_fetch_array($sql);
												if($get_old_status['application_status']== "scheduled" || $get_old_status['application_status']== "shown_up")
												{
													//IF exists as scheduled or shown up, remove it.
													if(mysql_query("delete from appointment_schedule where reference_number='$gwf' and mission_id='".$_SESSION['mission_id']."'"))
													{
														if(strpos($app_category,'PREMIUM') !== false)
														{
															$lounge_type="Premium Lounge Appointment";
														}
														else
														{
															$lounge_type="Standard Appointment";
														}
														$qr = "insert into appointment_schedule values (DEFAULT,'0','$gwf','$date_today','$date_today','$appointment_time','$app_category','$lounge_type','$visa_type','scheduled','','".$_SESSION['mission_id']."')";	
														if(mysql_query($qr))
														{
															$total_appointments_added++;
															$msg = '<font color="#009900">'.$total_appointments_added. ' Records has been Imported.</font>';
														}
														else
														{
															$msg=mysql_error();
														}
														
													}
												}
												else
												{
												
													$update_already_submitted = "update appointment_schedule set scheduled_date_app='$date_today' , visa_category='$visa_type' where reference_number='$gwf' and mission_id='".$_SESSION['mission_id']."'";
													if(mysql_query($update_already_submitted))
													{
														$msg = "<div align='center' class='text-warning h5'>Appointment for below Reference numbers already been scheduled/concluded.</div><br>";
														//$get_existing = mysql_fetch_array($sql);
												
														$gwf_list=$gwf_list.$get_old_status['reference_number']."\t". $get_old_status['application_status']."\t".$get_old_status['remarks']."<br>";
													}
												}
											}
										}
									}
									
								}
								
							} 
							else 
							{
								echo "<script> alert('No file selected')</script>";
							}
						}
						if($msg!="")
						{
							echo $msg;
						}
						if($gwf_list!="")
						{
							echo "<div align='center' style='overflow-y: scroll; height:300px;'>".$gwf_list."</div>";
						}
 				?>
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="2" id="td_display_help">
								Note:
                                <ul>
                                	<li type="circle">You can import Microsoft Excel files such as .xls or .xlsx files.</li>
                                    <li type="circle">The import file should contain 5 columns with heading "Time","","Type","Reference","Appointment"</li>
                                    <li type="circle">DONOT Put any content is the second column</li>
                                    <li type="circle"><a href="download.php?opt=10">Click Here </a> to get the sample file</li>
                               	</ul>
                            </td>
                        </tr>
                    </table>
                    </div>
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