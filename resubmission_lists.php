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
				<div align="center"> <label class='text-info h4'>PASSPORT RESUBMISSION LIST</label></div>
      			</div>
                <div style="height: 35px; color:#F00;">
				

                </div>
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
				$query = "SELECT count(*) FROM passport_resubmission WHERE status = 'wait_resubmit' and mission_id='".$_SESSION['mission_id']."' and id <> ''";
				$result = mysql_query($query);
				$query_data = mysql_fetch_row($result);
                $numrows = $query_data[0];
				if($numrows >0)
				{
				?>
                    <table class="table">
                        <thead class="text-info h5" style="font-size: 13px; font-weight: 600;">
                            <tr>
                                <td style="width: 8%;">Sl. No</td>
                                <td style="width: 14%;">Old Ref. Number</td>
                                <td style="width: 14%;">Submitted On</td>
                                <td style="width: 16%;">Current Status</td>
                                <td style="width: 15%;">Action</td>
                            </tr>
                        </thead>
                    	<?php 
                			$rows_per_page =10;
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
							$qr_t="SELECT a.gwf_number as gwf_number, a.date_outscan as date_outscan, b.status as status FROM passport_inscan_record a , passport_resubmission b WHERE a.id=b.actual_ref_id and a.current_status='outscan' and b.status ='wait_resubmit' and a.mission_id='".$_SESSION['mission_id']."' order by b.id ASC $limit";
							$query_instance = mysql_query($qr_t) OR die(mysql_error());
                        	$rows=mysql_num_rows($query_instance);
							while($recResult = mysql_fetch_array($query_instance))
							{
								$cnt++;
								if($recResult['status']=="wait_resubmit")
								{
									$status="Waiting for resubmission";
								}
								else
								{
									$status="";
								}
								
								
								?>
								<tbody style="border-top-width: 1px;">
									<tr>
										<td align="left"> <?php echo $cnt; ?></td>
										<td align="left"><?php echo $recResult['gwf_number']; ?></td>
                                        <td align="left"><?php echo $recResult['date_outscan']; ?></td>
										<td align="left"><?php echo $status; ?></td>
                                       	<td style="width: 18%;">
                                        	<input id="popup" type="button" class=" btn btn-info" value="Accept Resubmission" onClick="loadPopupBox('<?php echo $recResult['gwf_number']; ?>')">
                                      	</td>
									</tr>
								</tbody>
								<?php
							}
							?>
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
                    </tbody>
                </table>
                <?php
                 }
				else
				{
				?>
					<div class="text-info" style="text-align:center; font-weight: 600;">No applications pending for passport resubmission</div>
				<?php
                } 
				?>  
                    
				</div>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
	</div>
</div>
<div id="popup_box" align="center" style="text-align:center;">    <!-- OUR PopupBox DIV-->
    <div class="text-info text-center h4"><u>Accept Passport Resubmission</u></div>
    <table class="table h5">
    	<tr>
        	<td class="text-danger" style="border: 0px; border-color: none; text-align: left;">Old Reference Number</td>
            <td style="border: 0px; border-color: none; text-align: left;"><input type="text" id="old_ref" class="form-control" value="" style="width: 220px;" readonly></td>
        </tr>
        <tr>
        	<td class="text-danger" style="text-align: left;">New Reference Number</td>
            <td style="text-align: right;"><input type="text" id="new_ref" class="form-control" value="" style="width: 220px;"></td>
        </tr>
        <tr>
        	<td colspan="2" style="text-align:center; border: 0px; border-color: none; "><input type="button" class="btn btn-info" value="Accept Resubmission" onClick="submit_resubmission()"></td>
        </tr>
    </table>
    
    <div id="msg" style="color:red; font-weight: bold; font-size: 12px; display:none;">Please enter the new reference number.</div>
    
    <a id="popupBoxClose"><img src="styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox()"></a>  
<script type="text/javascript" language="javascript">
function submit_resubmission()
{
	var old_ref = $("#old_ref").val();
	var new_ref = $("#new_ref").val();
	if(new_ref=="")
	{
		$('#msg').show();
	}
	else
	{
		$('#msg').hide();
		var new_ref=$('#new_ref').val();
		var old_ref=$('#old_ref').val();
		
		$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data: 'cho=38&nref='+new_ref+"&oref="+old_ref,
			dataType:"json",
			success: function(json)
			{
				if(json=="resubmitted")
				{
					unloadPopupBox();
					alert("Records updated");
					location.reload();
					
				}
				else if(json=='timeout')
				{
					alert('Session Expired. Please Login!')
					window.location.href='login.php';
					window.location.reload();
				}
				else
				{
					alert("Something went wrong. Please contact admin");
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