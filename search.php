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
if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac']))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VAC Assist - <?php echo $_SESSION['vac']; ?></title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<link href="styles/style.css" rel="stylesheet" type="text/css" />
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>
<script type="text/javascript">

function submit_search()
{
	var data = document.getElementById('datatosearch').value;
	document.form1.action="search.php";
    document.form1.submit();
}

function deliver()
{
	var htmls="<label class='text-warning' style='font-size: 15px; text-align: center; font-weight: 600; float: left; display: inline;'>Select Passport Delivery Type : </label><select name='choice' id='choice' class='form-control' style='width: 210px;'><option value='counter_delivery'>Counter Delivery</option><option value='courier_Service'>Delivered Via Courier</option><option value='returned_to_embassy'>Returned To Embassy</option></select> <br><input type='button' name='Search' value= 'Deliver Passport' class='btn btn-info' onclick='submit_delivery()' /> <input type='button' name='Cancel' value= 'Cancel' class='btn btn-info' onclick='reload_page()' />";
	
	$("#loginScreen").html(htmls);
	$("#loginScreen").show();
	$("#cover").css('display','block');
	$("#cover").css('opacity','1');
}

function submit_delivery()
{
	//var del_method = document.getElementById('pp_delivery_method').value;
	document.form1.action="php_func.php?cho=6";
    document.form1.submit();
}

function reload_page()
{
	window.location.href='search.php';
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
	height:200px; 
	width:600px; 
	margin:0 auto; 
	position:absolute; 
	z-index:10; 
	display:none;
	padding: 10px; 
	border:5px solid #cccccc; 
	border-radius:10px; 
	background-color: #FFF;
	left: 25%;
	top: 20%;
	text-align:center;
}  
</style>


</head>

<body style="height: 500px; padding: 0px;">
<div id="templatemo_container" style="height: 500px;">
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
				<div align="center"> <label class='text-info h4'>SEARCH APPLICATION STATUS</label></div>
      			</div>      
                <form name="form1" id="form1" method="post">
                	<div class="coverStyle" id="cover" onClick="reload_page()"> </div>
             		<div class="loginScreenStyle" id="loginScreen">
                        <!-- Content for Deliver Passport  -->
                   	</div>
                <div id="counterdelivery-verify" align="center" style="width:100%;">
                	<div align="center" style="border-size: 1px; border:solid; border-color: #CCC; border-radius: 5px; padding: 5px; width: 50%;">
                    	<span class="text-warning" style="font-size: 14px; display:inline; float: left;"> Enter The Reference or Envelope Number To Search  : </span> 
                    	<input type="text" class="form-control" placeholder="Ref.No Or Envl.No" id="datatosearch" name="datatosearch" value="<?php	if(isset($_REQUEST['datatosearch'])){
 echo $_REQUEST['datatosearch']; }?>" autofocus style="width: 210px;">  
                        <br>
                    	<input type="button" value="Search" class="btn btn-success" onClick="submit_search()" onkeydown="if(event.keyCode ==13) submit_search();">
                    </div>
                    <hr>
                    <?php
				if(isset($_REQUEST['datatosearch']))
				{
					?>
			
                <?php
				$existing_gwfs="";
				$gwf_selected=$_REQUEST['datatosearch'];
				if($gwf_selected!="")
				{
						
					if((substr($gwf_selected, 0, 5))=="GLF S")
					{
						$get_glfsid= mysql_query("select id from tee_envelop_counts WHERE barcode_no='$gwf_selected' and mission_id='".$_SESSION['mission_id']."'");
						$res_get_glfsid=mysql_fetch_array($get_glfsid);
							
						$gwf_selected_query = "SELECT a.gwf_number as gwf_number,a.date_inscan as date_inscan,a.current_status as current_status, b.barcode_no as barcode_no FROM passport_inscan_record a , tee_envelop_counts b WHERE b.id = '".$res_get_glfsid['id']."' and b.id=a.glfs_id and a.mission_id='".$_SESSION['mission_id']."'";
						$gwf_selected_sql = mysql_query($gwf_selected_query);
					}
					else
					{
						$gwf_selected_query = "SELECT a.gwf_number as gwf_number,a.date_inscan as date_inscan,a.current_status as current_status,a.glfs_id as glfs_id  FROM passport_inscan_record a WHERE a.gwf_number = '".$gwf_selected."' and a.mission_id='".$_SESSION['mission_id']."'";
						$gwf_selected_sql = mysql_query($gwf_selected_query);
					}
						
						
					if(mysql_num_rows($gwf_selected_sql)>0)
					{
						$gwf_selected_recResult = mysql_fetch_array($gwf_selected_sql);
						$gwf_selected_existGWF = $gwf_selected_recResult["gwf_number"];
						$barcode = "";
						if(substr($gwf_selected, 0, 5)!="GLF S")
						{
							if($gwf_selected_recResult['glfs_id']!="")
							{
								$qr_get_barcode = "select barcode_no from tee_envelop_counts where id='".$gwf_selected_recResult['glfs_id']."' and mission_id='".$_SESSION['mission_id']."'"	;							
								$res_barcode = mysql_fetch_array(mysql_query($qr_get_barcode));
								$barcode=$res_barcode["barcode_no"];
							}
						}
					?>
                    <table width="100%" id="enter_gwf_tbl" width="100%" class="table" style="">
                    	<thead class="text-danger" style="font-size: 15px; font-weight:600px;">
							<tr>
								<td width="10%"  style="" align="left">Ref. Number</td>
                    			<td width="10%"  style="" align="left">Envelope Number</td>
								<td width="10%"  style="" align="left">Inscanned Date</td>
                    			<td width="10%"  style="" align="left">Status</td>
                    			<td width="10%"  style="" align="left">Action</td>
							</tr>
                      	</thead>
                        <tbody class="text-info" style="font-size: 13px;">
                			<tr>
								<td  style="" align="left">
									<?php echo $gwf_selected_recResult["gwf_number"]; ?>
                    			</td>
                        		<td style="" align="left">
									<?php echo $barcode; ?>
                    			</td>
								<td  style="" align="left">
									<?php echo $gwf_selected_recResult["date_inscan"]; ?>
                                </td>
                        		<td  style="" align="left"> 
									<?php echo $gwf_selected_recResult["current_status"];?> 
                                </td>
                        		<td width="5%"  style="" align="left">
									<?php if($gwf_selected_recResult["current_status"]=="inscan") 
									{ 
									?> 
                                    	<a onClick="deliver('<?php  echo $gwf_selected_recResult["gwf_number"]; ?>')" style="font-size: 12px; font-weight:bold; color:blue; cursor:pointer;">Deliver Passport</a>  
                                        <input type='hidden' name='gwf1' id='gwf1' value='<?php echo $gwf_selected_recResult["gwf_number"];?>'> 
									<?php 
									} 
									else 
									{ 
									echo "No Action"; 
									} 
									?>
                              	</td>
							</tr>
                     	</tbody>
							<?php
                                }
                                else
                                {
                                    echo "<p><div align='center' style='height:60px; text-align:center; display: table-cell; vertical-align: middle; color:red;'> The Reference/Envelope number you entered not valid<p></div>";	
                                }
                            }
                            ?>
            		</table>
            		<?php
					}
			
					?>
            	</div>
          	</form>
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