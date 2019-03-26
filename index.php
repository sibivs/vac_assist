<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
$expire_time = 60*60; //expire time
if((isset($_SESSION['last_activity_ukvac'] )) && ($_SESSION['last_activity_ukvac'] < (time()-$expire_time))) 
{
	echo "<script>alert('Session Expired, Please login')</script>";
	session_destroy();
	header("Location:login.php");
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
	include_once("db_connect.php");
	if(isset($_SESSION['role_ukvac']))
	{
		?>
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>VAC Assist - Home</title>
				<!-- Main stylesheet and javascripts for the page -->
				<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
				<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
				<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
                <!--script src="Scripts/jquery-1.4.4.js" type="text/javascript"></script-->
				<link rel="stylesheet" href="styles/style.css">
                <link rel="stylesheet" href="./menu/css/style_menu.css">
				<script type="text/javascript" language="javascript">
				
				function mission_change()
				{
					var mission_selected = $("#select_mission").val();
					if(mission_selected!='select')
					{
						$.ajax(
						{
							type: 'post',
							url: 'php_func.php',
							data: 'cho=54&mission='+mission_selected,
							dataType:"json",
							success: function(result)
							{
								if(result=="not management")
								{
									alert("Invalid Login");
								}
								else if(result =="session added")
								{
									window.location.reload();
								}
							},
							error: function(ts)
							{
								alert(ts.responseText); 
							}
						});
					}
				}
				
				</script>
                <style type="text/css">
				.box-shadow-example {
					height:200px; 
					width: 100%; 
					border-radius:10px;
					text-align:center;
					background-color:#FFFFFF;
					-webkit-box-shadow: 10px 10px 35px -7px rgba(43,94,130,1);
					-moz-box-shadow: 10px 10px 35px -7px rgba(43,94,130,1);
					box-shadow: 10px 10px 35px -7px rgba(43,94,130,1);
				}
				.sub_header_box {
					background-color:#2b5e82;
					color: #FFFFFF;
					text-align:center;
					height: 40px;
					width: 100%;
					font-size: 17px;
					font-weight: 600;
					padding-top: 8px;
					border-top-left-radius: 10px;
					border-top-right-radius: 10px;	
				}
				
				.dashboard_pannel {
					height: 300px;
					width: 450px;
				}
				
				</style>
                
			</head>
		<body style="height: 100%;">
		<form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
		<div id="templatemo_container">
			<div id="templatemo_header">
				<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
				<label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
				<label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
			</div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;">
            <?php require_once("menu/left_menu.php"); ?>
            </div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>    
            <div align="center" style="padding-top: 15px;">
				<div style="width: 1200px; height: 100%; margin-left: 0px !important;">
                <iframe style="width: 100%; height: 400px; border: none;" src="dashboard/slider.php">
                </iframe>
                <?php
					if($_SESSION['role_ukvac']=='management' || $_SESSION['role_ukvac']=='accounts')
					{
                		$get_missions = mysql_query("SELECT m.id as id, m.mission_name as mission,c.country as country ,city.city_name as cityn FROM missions m, country c , country_cities city WHERE m.country_id=c.id AND m.city = city.id and m.status='active' ORDER BY mission_name, country");
				?>
                 	<div style="text-align: center; padding-bottom: 8px; width: 100%; margin-left: 35%;">
                        <label class="text-danger h4" style="display:inline; float: left; ">Select Mission : </label>
						<select id="select_mission" class="form-control" onChange="mission_change()" style="width:300px;">
                      		<option value="select">Select Mission</option>
                      		<?php
							while($missions = mysql_fetch_array($get_missions))
							{
							?>
                    		<option value="<?php echo $missions['id']; ?>" <?php if(isset($_SESSION['mission_id']) && $_SESSION['mission_id']== $missions['id']){  ?>  selected="selected" <?php }?>><?php echo $missions['mission']." VAC - ".$missions['cityn'].",".$missions['country']; ?></option>
                         	<?php
							}
							?>
                  		</select>
                    </div>
                <?php
				}
                if(isset($_SESSION['mission_id']) && $_SESSION['mission_id']!=0)
				{
				?>
                <table style="min-width:1175px; width: 100%; height: 100%; margin-top: 10px;" class="table">
                  <tr>
                    <td class="" style="width:100%; height: 700px; vertical-align:top !important;" colspan="2">
                    	<div class='box-shadow-example' style="height: 660px;">
                        	<div class="sub_header_box">
                            Current Submission Status
                            </div>
                            <div style="min-width:455px; width:30%; height:600px; display: inline; float:left; padding-top: 15px; border:2px; border-color: #ccc; border-radius: 5px; border-style:solid; padding-left: 5px; overflow:hidden;">
								<iframe style="position:relative; border:0; height:100%; width:100%;" scrolling="no" id="appointment_statistics">	
                                	<!-- Data will be loaded from the statistice via the jquery code below. -->
                                </iframe>
                                <?php
								if($_SESSION['role_ukvac']=="staff"||$_SESSION['role_ukvac']=="administrator" || $_SESSION['role_ukvac']=="management" || $_SESSION['role_ukvac']=="passback" || $_SESSION['role_ukvac']=="accounts")
								{
									?>
                                    <!--  FOR STATISTICS     -->
								<script src="dashboard/statistics/library/RGraph.common.core.js"></script>
                                <script src="dashboard/statistics/library/RGraph.common.dynamic.js"></script>
                                <script src="dashboard/statistics/library/RGraph.common.key.js"></script>
                                <script src="dashboard/statistics/library/RGraph.common.tooltips.js"></script>
                                <script src="dashboard/statistics/library/RGraph.pie.js"></script>
                                <!--  FOR STATISTICS     -->
                                    <script>
                                $(document).ready(function(){
                                  $('#appointment_statistics').attr('src','dashboard/statistics/st_appointment.php?m=<?php echo $_SESSION['mission_id']; ?>');
								  window.setInterval(function(){
									  $('#appointment_statistics').attr('src','dashboard/statistics/st_appointment.php?m=<?php echo $_SESSION['mission_id']; ?>');
									}, 240000);
                                });
								</script>
                                    <?php 
								}
								?>
                                
							</div>
                           <!--  ////////////////  -->
                           <div style="min-width:343px; width: 28%; height:600px; display: inline; padding-top: 15px; border:2px; border-color: #ccc; border-radius: 5px; border-style:solid; padding-left: 5px; float:left; overflow:hidden;">
								<iframe style="position:relative; border:0; height:100%; width:100%;" scrolling="no" id="submission_statistics">	
                                	<!-- Data will be loaded from the statistice via the jquery code below. -->
                                </iframe>
                                <?php
								if($_SESSION['role_ukvac']=="staff"||$_SESSION['role_ukvac']=="administrator" || $_SESSION['role_ukvac']=="management" || $_SESSION['role_ukvac']=="passback" || $_SESSION['role_ukvac']=="accounts")
								{
								?>
                                <!--  FOR STATISTICS     -->
								<script src="dashboard/statistics/library/RGraph.common.core.js"></script>
                                <script src="dashboard/statistics/library/RGraph.common.dynamic.js"></script>
                                <script src="dashboard/statistics/library/RGraph.common.key.js"></script>
                                <script src="dashboard/statistics/library/RGraph.common.tooltips.js"></script>
                                <script src="dashboard/statistics/library/RGraph.pie.js"></script>
                                <!--  FOR STATISTICS     -->
                                    <script>
                                $(document).ready(function(){
                                  $('#submission_statistics').attr('src','dashboard/statistics/st_submission.php?m=<?php echo $_SESSION['mission_id']; ?>');
								  window.setInterval(function(){
									  $('#submission_statistics').attr('src','dashboard/statistics/st_submission.php?m=<?php echo $_SESSION['mission_id']; ?>');
									}, 240100);
                                });
								</script>
                                    <?php 
								}
								?>
                                
							</div>
                            <!--- ///////////////////////   -->
                           <div style="min-width:374px; width: 30%; height:600px; display: inline; padding-top: 15px; border:2px; border-color: #ccc; border-radius: 5px; border-style:solid; padding-left: 5px; float:left; overflow:hidden;">
								<iframe id="submission_details" style="position:relative; border:0; height:100%; width:100%;" scrolling="no"></iframe>
                                <?php
								if($_SESSION['role_ukvac']=="staff"||$_SESSION['role_ukvac']=="administrator" || $_SESSION['role_ukvac']=="management" || $_SESSION['role_ukvac']=="passback" || $_SESSION['role_ukvac']=="accounts")
								{
									?>
                                <!--  FOR STATISTICS     -->
								<script src="dashboard/statistics/library/RGraph.common.core.js"></script>
                                <script src="dashboard/statistics/library/RGraph.common.dynamic.js"></script>
                                <script src="dashboard/statistics/library/RGraph.common.key.js"></script>
                                <script src="dashboard/statistics/library/RGraph.common.tooltips.js"></script>
                                <script src="dashboard/statistics/library/RGraph.pie.js"></script>
                                <!--  FOR STATISTICS     -->
                                    <script>
                                $(document).ready(function(){
                                  $('#submission_details').attr('src','dashboard/statistics/applicantion_status/current_status.php?m=<?php echo $_SESSION['mission_id']; ?>');
								  window.setInterval(function(){
									  $('#submission_details').attr('src','dashboard/statistics/applicantion_status/current_status.php?m=<?php echo $_SESSION['mission_id']; ?>');
									}, 240200);
                                });
								</script>
                                    <?php 
								}
								?>
                                
							</div>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="" style="width:30%; height: 430px;">
                    	<div class='box-shadow-example' style="height: 400px;">
                        	<div class="sub_header_box">
                            	Birthdays
                            </div>
                            
                        </div>
                    </td>
                    <td style="width:70%; height: 430px;">
                    <div class='box-shadow-example' style="height: 400px;">
                        	<div class="sub_header_box">
                            	BTS Champions
                            </div>
                            <?php
                            $get_disabled_pages = mysql_query("select pa.file_name as filename from mission_page_disabled pd, pages_associated pa where pd.mission_id='".$_SESSION['mission_id']."' and pd.page_id=pa.id and pd.status='pg_disabled' and pa.file_name='bts.php'");
							if(mysql_num_rows($get_disabled_pages)==0)
							{
								?>
                            <iframe id="bts_champion" style="position:relative; border:0; height:300px; width:100%;" scrolling="no"></iframe>
                                <?php
								if($_SESSION['role_ukvac']=="staff"||$_SESSION['role_ukvac']=="administrator" || $_SESSION['role_ukvac']=="management" || $_SESSION['role_ukvac']=="passback" || $_SESSION['role_ukvac']=="accounts")
								{
									?>
                                    <script>
                                $(document).ready(function(){
                                  	$('#bts_champion').attr('src','dashboard/statistics/bts_champion.php?m=<?php echo $_SESSION['mission_id']; ?>');
								  	window.setInterval(function(){
										$('#bts_champion').attr('src','dashboard/statistics/bts_champion.php?m=<?php echo $_SESSION['mission_id']; ?>');
									}, 240200);
                                });
								</script>
                                    <?php 
								}
							}
                            else
							{
							?>
								<div class="text-danger" style="font-size: 16px; text-align:center;">
                                    No BTS products for selected mission!
                                  </div>
							<?php	
							}
							?>
                        </div>
                  	</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
               	<?php
					}
					else
					{
				?>
                <div style="height:100px; width: 100%; font-size: 16px; text-align:center; padding-top: 50px;" class="text-warning">
                	Please select a mission to view the statistics!
                </div>
                <?php
					}
				?>  
        		</div>
                </div>
				<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
					<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
					
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