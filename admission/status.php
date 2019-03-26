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
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator" ||$_SESSION['role_ukvac']=="staff" )&&  isset($_SESSION['vac']))
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
<script src="../Scripts/script.js"></script>
<script type="text/javascript">
var intr = setInterval(function(){ window.location.reload(); }, 20000);


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
				<div align="center"> <label class='text-info h4'>APPLICATION STATUS</label></div>
      					</div>
                        <div style="height: 600px;">
                        	<div style="padding-top: 15px; text-align: center;">
                        	<label class="text-info h5" style="text-align:center;" id='response'></label>
                            <div style="vertical-align:top;" align="center">
					
                        <?php
						$cnt=0;
						$date = date("Y-m-d");
						$query_count_result = "SELECT count(*) FROM appointment_schedule where  date_of_appointment='$date' and mission_id='".$_SESSION['mission_id']."'"; //application_status IN ('shown_up','ro_complete') and
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
                                <thead class="text-danger h5">
                                    <tr>
                                        <td width="10%">Sl.No</td>
                                        <td width="15%">Token Number</td>
                                        <td width="15%">Ref. Number</td>
                                        <td width="15%">Wait Time</td>
                                        <td width="20%">Status</td>
                                    </tr>
                                </thead>
                            <?php
                			$query = "SELECT count(*) FROM appointment_schedule where   date_of_appointment='$date' and mission_id='".$_SESSION['mission_id']."' and id <> ''";
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
							$qr_t="SELECT st.token_no as token_no, TIMEDIFF(CURTIME(), st.ao_finish_at) as waiting,a.reference_number as reference_number, a.application_status as application_status FROM appointment_schedule a, submission_timing st where a.id=st.reference_id  and a.date_of_appointment='$date' and st.mission_id='".$_SESSION['mission_id']."' ORDER BY st.token_no  ASC $limit ";
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
                            <td align="left"><?php echo $recResult['waiting'];?> Hrs </td>
                            <td align="left"><?php if($recResult['application_status']=="shown_up") { echo "Waiting For Submission"; } else if($recResult['application_status']=="ro_complete") { echo "Waiting For Biometrics"; } else if($recResult['application_status']=="outscan") { echo "Submitted"; } ?></td>
                           
						</tr>
                        <?php
							}
						}
						?>
                        </tbody>
                    </table>
                    <hr />
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