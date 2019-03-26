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
		?>
		<script type='text/javascript'>
        
            window.self.close()
        
        </script>
	<?php
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac']))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Daily Reconciliation Report</title>
<link rel="stylesheet" href="styles/style.css">
<script type="text/javascript" src="Scripts/jquery-1.6.3.js"></script>

</head>

<body contextmenu="false">
	<form id="form1" name="form1"> 
    <?php
		date_default_timezone_set("Asia/Bahrain");
		$date_today=$_SESSION['date_selected'];
		$total_ppts_BFwd=$_SESSION['total_ppts_BFwd'];
		$total_inscanned_today=$_SESSION['total_inscanned_today'];
		$inscanned_today=$_SESSION['inscanned_today'];
		$inscanned_rectification = $_SESSION['inscanned_today_recti'];
		$total_delivered_counter=$_SESSION['total_delivered_counter'];
		$total_delivered_courier=$_SESSION['total_delivered_courier'];
		$res_get_delivered_today=$_SESSION['total_rectification_del_today'];
		$total_delivered_today=$_SESSION['total_delivered_today'];
		$total_ppts_inVAC=$_SESSION['total_ppts_inVAC'];
		$total_co = $_SESSION['total_CarriedO'];
		
		
		unset($_SESSION['date_selected']);
		unset($_SESSION['total_ppts_BFwd']);
		unset($_SESSION['total_inscanned_today']);
		unset($_SESSION['total_delivered_counter']);
		unset($_SESSION['total_delivered_courier']);
		unset($_SESSION['total_rectification_del_today']);
		unset($_SESSION['total_delivered_today']);
		unset($_SESSION['total_ppts_inVAC']);
		unset($_SESSION['total_CarriedO']);
		
	?>
	<div id="printd" align="center" style="background-color:white;">
	<div align="center" style="width:100%; padding:10px 0 20px 0; font:bold 14px Verdana;">
    	<span class="text-danger h4">Passport Reconciliation Report For <?php echo $date_today; ?></span>
    </div>
	<table class="table table-bordered" style="width:80%;">
		<tr>
        	<td style="width: 75%;" align="left" class="text-warning h4">
            	Brought Forward From Previous Day 
           	</td>
         	<td style="width: 25%;" align="left" class="text-warning h4"> 
				<?php echo $total_ppts_BFwd;	?>
          	</td>
     	</tr>
        <tr>
        	<td align="left" class="text-warning h4">
            	Total Passports Recieved On <?php echo $date_today; ?> 
            </td>
            <td align="left" class="text-warning h4"> 
				<?php echo $total_inscanned_today;	?>
           	</td>
   		</tr>
		<tr>
        	<td style="padding-left: 10%;" align="left" class="text-info h5">
            	Passports Recieved on <?php echo $date_today; ?> 
           	</td>
           	<td align="left" class="text-info h5"> 
				<?php echo $inscanned_today;	?>
          	</td>
     	</tr>
		<tr>
        	<td style="padding-left: 10%;" align="left" class="text-info h5">
            	Passports Recieved On <?php echo $date_today; ?> - Rectification 
            </td>
         	<td align="left" class="text-info h5"> 
				<?php echo $inscanned_rectification;	?>
            </td>
      	</tr>
        <tr>
     		<td align="left" class="text-warning h4">
            	Total Delivered On <?php echo $date_today; ?> 
           	</td>
        	<td align="left" class="text-warning h4"> 
				<?php echo $total_delivered_today;	?>
          	</td>
     	</tr>
       	<tr>
         	<td style="padding-left: 10%;" align="left" class="text-info h5">
            	Total Delivered On <?php echo $date_today; ?> - Counter 
            </td>
         	<td align="left" class="text-info h5"> 
				<?php echo $total_delivered_counter;	?>
            </td>
      	</tr>
      	<tr>
        	<td style="padding-left: 10%;" align="left" class="text-info h5">
            	Total Delivered On <?php echo $date_today; ?> - Courier 
           	</td>
        	<td align="left" class="text-info h5"> 
				<?php echo $total_delivered_courier;	?>
           	</td>
      	</tr>
       	<tr>
        	<td style="padding-left: 10%;" align="left" class="text-info h5">
            	Total Delivered On <?php echo $date_today; ?> - Rectification / Cancellation 
           	</td>
         	<td align="left" class="text-info h5"> 
				<?php echo $res_get_delivered_today;	?>
           	</td>
     	</tr>
        <tr>
         	<td class="text-warning h4">
            	Total Carried Over On the Day selected 
           	</td>
          	<td class="text-warning h4"> 
				<?php echo $total_co;	?>
            </td>
      	</tr>
     	<tr>
        	<td class="text-success h4">
            	Total Remaining In VAC As of <?php echo date('d-m-Y'); ?>
            </td>
           	<td class="text-success h4"> 
				<?php echo $total_ppts_inVAC;	?>
            </td>
       	</tr>
	</table>
	</div>
    <div align="center" style="width:100%">
    <p>
    	<input type="button" class="btn btn-block btn-info" onclick="printPage('printd');" value="Print Report" style="width: 140px; display:inline">
       	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
     	<input type="button" class="btn btn-block btn-danger" value="Close" onclick="window.close();" style="width: 140px; display:inline">
	</div>
	</p>
	<script type="text/javascript">
		function printPage(pg_id)
		{
			var headstr = "<html><head><title>Daily Reconciliation Reportr</title></head><body>";
			var footstr = "</body>";
			var newstr = document.all.item(pg_id).innerHTML;
			var oldstr = document.body.innerHTML;
			document.body.innerHTML = '<div style="background-color:#FFFFFF;" align="center">'+headstr+newstr+footstr+'</div>';
			window.print();
			document.body.innerHTML = oldstr;
		}
	</script>
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