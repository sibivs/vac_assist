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
if(isset($_SESSION['role_ukvac']) &&  isset($_SESSION['vac']))
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
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<link rel="stylesheet" href="styles/style.css">
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>
<script type="text/javascript">
function reset_page()
{
	document.form1.action="passport_deliver.php";
	document.form1.submit();
}

function submit_delivery(del_method)
{
	document.getElementById('choice').value=del_method;
	document.form1.action="php_func.php?cho=6";
    document.form1.submit();
}

</script>



</head>

<body>
<div id="templatemo_container" style="height:auto; min-height: 700px;">
	<div id="templatemo_header">
		<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
        <label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>

    </div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("menu/left_menu.php"); ?></div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>   
	<div id="templatemo_content" style="min-height: 600px; height: auto;">
		<script type="text/javascript" language="javascript">
				
				</script>
		<div id="" style="width: 100% !important; " align="center;">
			<div style="width: 100% !important;" align="center;">
				<div align="center"> <label class='text-info h4'>
DELIVER PASSPORT TO THE APPLICANT</label></div>
      			</div>
                <div style="height: 35px; color:#F00;">


                </div>
                <div id="counterdelivery-verify" align="center" style="width:100%;">
                <form name="form1" id="form1" method="post">
                <input type="hidden" name="choice" id="choice" value="">
			<table width="70%" id="enter_gwf_tbl" style="" class="table">
				<thead>
                	<tr>
						<td class="text-warning h4">Reference Number</td>
                    	<td  class="text-warning h4">Envelop Number</td>
						<td  class="text-warning h4">Inscanned Date</td>
					</tr>
               	</thead>
                <?php
				$cnt1 =0;
				$cnt2 =0;
				$existing_gwfs="";
				$delivery_type = $_REQUEST['pp_delivery_method'];
				$gwf_mixed=array();
				$gwf_selected=array();
				for($i=1; $i<21; $i++)
				{
					if($_REQUEST['gwf'.$i]!="")
					{
						$gwf_mixed[]=$_REQUEST['gwf'.$i];
					}
				}
				$gwf_selected = array_values(array_unique($gwf_mixed));
				//var_dump($gwf_selected);
				for($j=0;$j<count($gwf_selected); $j++)
				{
					if($gwf_selected!="")
					{
						$cnt1++;
						
						if((substr($gwf_selected[$j], 0, 5))=="GLF S" || (substr($gwf_selected[$j], 0, 4))=="VFS ")
						{
							$get_glfsid= mysql_query("select id from tee_envelop_counts WHERE barcode_no='$gwf_selected' and mission_id='".$_SESSION['mission_id']."'");
							$res_get_glfsid=mysql_fetch_array($get_glfsid);
							
							$gwf_selected_query = "SELECT a.gwf_number as gwf_number,a.date_inscan as date_inscan,a.current_status as current_status, b.barcode_no as barcode_no FROM passport_inscan_record a , tee_envelop_counts b WHERE b.id = '".$res_get_glfsid['id']."' and b.id=a.glfs_id  and a.current_status='inscan' and a.mission_id='".$_SESSION['mission_id']."'";
							$barcode = $gwf_selected[$j];
							//echo $gwf_selected_query;
							$gwf_selected_sql = mysql_query($gwf_selected_query);
								
						}
						else
						{
						//echo $gwf_selected."TEST";
							$gwf_selected_query = "SELECT a.gwf_number as gwf_number,a.date_inscan as date_inscan,a.current_status as current_status,a.glfs_id as glfs_id  FROM passport_inscan_record a WHERE a.gwf_number = '".$gwf_selected[$j]."' and  a.current_status='inscan' and a.mission_id='".$_SESSION['mission_id']."'";
							$gwf_selected_sql = mysql_query($gwf_selected_query);
						}
							
							
						if(mysql_num_rows($gwf_selected_sql)>0)
						{
							$cnt2++;
							$gwf_selected_recResult = mysql_fetch_array($gwf_selected_sql);
							$gwf_selected_existGWF = $gwf_selected_recResult["gwf_number"];
							if(substr($gwf_selected[$j], 0, 5)!="GLF S" || substr($gwf_selected[$j], 0, 4)!="VFS ")
							{
								$barcode = "";
								if($gwf_selected_recResult['glfs_id']!="")
								{
									$qr_get_barcode = "select barcode_no from tee_envelop_counts where id='".$gwf_selected_recResult['glfs_id']."' and mission_id='".$_SESSION['mission_id']."'"	;							
									$res_barcode = mysql_fetch_array(mysql_query($qr_get_barcode));
									$barcode=$res_barcode["barcode_no"];
								}
							}
						?>
						<tbody>
							<tr>
								<td class="text-info" style="">
									<?php echo $gwf_selected_recResult["gwf_number"]; ?><input type="hidden" name="<?php echo "gwf".($j+1); ?>" id="<?php echo "gwf".($j+1); ?>" value="<?php echo $gwf_selected_recResult["gwf_number"]; ?>">
								</td>
								<td class="text-info" style="">
									<?php echo $barcode; ?>
								</td>
								<td class="text-info" style="">
									<?php echo $gwf_selected_recResult["date_inscan"]; ?>
								</td>
							</tr>
					<?php
						}
						else
						{
							$existing_gwfs=$existing_gwfs.$gwf_selected[$j];
							//$file = 'Sample_files/notexisting.txt';
							//file_put_contents($file, $existing_gwfs);
						}
					}
				}
				?>
                			<tr>
								<td colspan="7">
                    <?php 
						if($cnt1 > $cnt2)
						{
							echo "<p><div align='center' class='text-danger h4' style='width: 90%;'> $cnt2 Reference number(s) you entered is either not existing or still Under processing or delivered.<p><br>".$existing_gwfs."</div>";
						}
													
					?>
                    			</td>
							</tr>
                			<tr>
								<td align="middle" valign="middle" colspan="5">
                    <?php
					if($cnt1 <= $cnt2)
					{
					?>
                    				<input type="button" name="Search" value= "Deliver Passport" class="btn btn-success" onclick="submit_delivery('<?php echo$delivery_type; ?>')" />
                        <?php
						}
						?>
                    	&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    				<input type="button" name="reset"  value= "Cancel" class="btn btn-danger" onclick="reset_page()"/>
                    			</td>
							</tr>
                        </tbody>
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