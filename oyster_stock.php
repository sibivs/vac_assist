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
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator" || $_SESSION['role_ukvac']=="staff")&&  isset($_SESSION['vac']))
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
<!-- for new Calandar control -->

<link rel="stylesheet" type="text/css" media="all" href="styles/jsDatePick_ltr.min.css" />
<script type="text/javascript" src="Scripts/jsDatePick.min.1.3.js"></script>
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>
<script type="text/javascript">
 function getFile(){
   document.getElementById("file").click();
 }
 
 function subm(obj){
    var file = obj.value;
    $("#filename").html(file);
    event.preventDefault();
  }
 
$( document ).ready(function() 
{ 
	new JsDatePick({
		useMode:2,
		target:"inputField1",
		dateFormat:"%Y-%F-%d",
		cellColorScheme:"armygreen"
		});
		
	new JsDatePick({
		useMode:2,
		target:"inputField2",
		dateFormat:"%Y-%F-%d",
		cellColorScheme:"armygreen"
		});
});
 </script>
</head>

<body>
<?php
$get_custom_permission_qr = mysql_query("select oyster_upload from custom_permissions where user_id='".$_SESSION['user_id_ukvac']."'");
	if(mysql_num_rows($get_custom_permission_qr) >0)
	{
		$get_custom_oyster = mysql_fetch_array($get_custom_permission_qr);
	}
?>

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
				<div align="center"> <label class='text-info h4'>Oyster Card Stock</label></div>
      			</div>
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                	<?php
                    if($_SERVER['REQUEST_METHOD'] == 'POST')
                    {
                        require_once("Classes/imports/class.import_Inventory_oyster.php");
                        $import_obj_oyster = new _import_Inventory_oyster();
                        $import_obj_oyster -> datacopied_tee_oyster = $_REQUEST['excel_data'];
                        $import_obj_status_oyster = $import_obj_oyster -> import_Inventory_TEEs_Function();
                    }
                    ?>
                <div style="height: 15px; color:#F00;">
                </div>
                <div style="width:100%;" align="center">
                
                    <div id="import-ppt-div" style="width: 100%;" align="center">
                    <div align="center" style="text-align:left; vertical-align: middle; height: 50px; background-color:#DDFFF2;" class="form-control">
                    <?php 
					$get_oyster_cnt= mysql_query("select count(id) as count from oyster_card_stock WHERE  status='in_stock' and mission_id='".$_SESSION['mission_id']."'");
					$res_oyster_cnt=mysql_fetch_array($get_oyster_cnt);
					?>
                    	<label style="text-align:center; padding-left: 100px; font-size: 15px; color:purple;" class="text-danger h4"> Available Oyster Cards In Stock  : <?php echo $res_oyster_cnt['count']; ?> </label>
                        
                    </div>
                    <br>
                    
                   		<div class="text-success h4" style="text-align: center;" align="center"><u>Import Oyster Card Stock</u></div><br>
                    <table class="table" style="width: 80%;">
						<tr>
							<td class="text-info" style="font-size: 16px;" >Oyster Card Numbers:<p> <span class="text-danger" style="font-size: 12px;">**Copy and Paste from Your Import Excel sheet</span></td>
							<td style="border-bottom:0px solid #eee; padding:5px;">
                            <textarea class="form-control" name="excel_data" id="excel_data" style="width:350px;height:150px;" <?php if($get_custom_oyster['0']=="0"){ ?> disabled <?php } ?> ></textarea>
                            </td>
						</tr>
						<tr>
							<td colspan="2" align="center"><input type="submit" name="submit" value="Update Oyster Card Stock List" class="btn btn-success" <?php if($get_custom_oyster['0']=="0"){ ?> disabled <?php } ?>/></td>
						</tr>
                        <tr>
                        	<td colspan="2" style="">
                            	<div class="scrollable" id="oyster_response" style="display:none;">
									<?php
                                    //Print the response after import
                                    if($_SERVER['REQUEST_METHOD'] == 'POST')
                                    {
                                        if($import_obj_status_oyster !="")
                                        {
                                            echo $import_obj_status_oyster;
                                        }
                                        
                                        ?>
                                            <script type="text/javascript" language="javascript">
                                            $("#oyster_response").show();
                                            </script>
                                        <?php
                                    }
                                    ?>
                            	</div>
                            </td>
                        </tr>
                    </table>
                    </div>
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