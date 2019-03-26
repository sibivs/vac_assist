<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
$expire_time = 60*60; //expire time
if((isset($_SESSION['last_activity_ukvac'] )) && ($_SESSION['last_activity_ukvac'] < (time()-$expire_time))) 
{
	session_destroy();
	?>
		<script type='text/javascript'>
        
            window.close()
        
        </script>
	<?php
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac']))
{
	include_once("db_connect.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="styles/style.css">
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html;" /><title>Daily Sales Report</title>
<style type="text/css">
a {
	color: #00F; 
	text-decoration:none;
	cursor:pointer; 
}
a:hover { 
	color: #600; 
	text-decoration:underline; 
}

#loading_div {
  height: 100%;
  width: 100%;
  position: absolute;
  background-color: rgba(0,0,0,0.1); /* for demonstration */
}
.ajax-loader {
  position: absolute;
  left: 50%;
  top: 50%;
  margin-left: -32px; /* -1 * image width / 2 */
  margin-top: -32px; /* -1 * image height / 2 */
}
</style>

<script type="text/javascript">
function dwnld_excel()
{
		document.form1.action="php_func.php?cho=13";
		document.form1.submit();
}


$(function()
{
   	$("#loading_div").html('<img src="styles/images/preloader.gif" class="ajax-loader" style=""/>');
	$("#loading_div").show();
	//$("#loading_div").hide();
});

setTimeout(function myFunction() {
    $("#loading_div").hide();
}, 3000)

</script>


</head>


<body contextmenu="false">
<div align="center" id="loading_div" style=" width: 100%; height: 100%; vertical-align: central !important;"></div>
	<form id="form1" name="form1" action ="" method="post" enctype="multipart/form-data"> 
<?php

	date_default_timezone_set("Asia/Bahrain");
	$date_selected = $_SESSION['date_for_report'];
	$date_selected1 = $_SESSION['date_for_report1'];
	$mission_id = $_REQUEST['mid'];
?>


<div align="center" style="width:100%; padding:10px 0 20px 0; font:bold 14px Verdana;">

Travel Product Sales from  <label style="color:#3187F0"><?php echo $date_selected;  ?> </label> To <label style="color:#3187F0"><?php echo $date_selected1;  ?></label>
<br>
<?php
$get_total = mysql_query("select currency, sum(ROUND(quantity_sold*price_per_item,3)) as total, sum(ROUND((quantity_sold*gbp_price),3))  as gbp_price from bts_sales where mission_id= '".$mission_id."' and date_sold >= '$date_selected' and date_sold <= '$date_selected1' group by currency");
?>
<br>
<table class="table table-bordered" style="width: 50%; float:middle; ">
	<thead class="">
        <tr class="text-warning h5">
            <th align="left" >Total Sale for selected period</th>
            <th align="left" >Total Sale (GBP)</th>
        </tr>
    </thead>
    <tbody>
    <?php
    	while($get_total_res = mysql_fetch_array($get_total))
		{
	?>
    	<tr class="text-info">
			<td><?php echo $get_total_res['total']." (".$get_total_res['currency'].")"; ?></td>
			<td><?php echo $get_total_res['gbp_price']; ?></td>
        </tr>
    <?php
		}
	?>    
    </tbody>
</table>
<p></p>
<hr class="style-four"/>
</div>
<table class="table table-bordered" style="">
	<thead>
    <tr class="h5 text-danger">
    	<th align="left">No</th>
		<th align="left">Product Name</th>
     	<th align="left">Quantity</th>
     	<th align="left">Unit Price (BD)</th>
   		<th align="left">Total (BD)</th>
        <th align="left">Unit Price (GBP)</th>
   		<th align="left">Total (GBP)</th>
   		    	
    	
	</tr>
    </thead>
    <?php
	$qr_t="SELECT product_name as item,ROUND(sum(quantity_sold),0) as total, price_per_item as unit_price,gbp_price as gbp_price, ROUND((sum(quantity_sold)*price_per_item),3) as total_bd, ROUND((sum(quantity_sold)*gbp_price),3) as total_gbp  from bts_sales WHERE date_sold >= '$date_selected' and  date_sold <= '$date_selected1' and mission_id='".$mission_id."' group by  product_name , price_per_item ASC ";
	$query_instance = mysql_query($qr_t) OR die(mysql_error());
	$rows=mysql_num_rows($query_instance);
	$cnt=0;
	$total_bhd=0;
	$total_gbp=0;
	while($recResult = mysql_fetch_array($query_instance))
	{
		$cnt++;
	?>
    <tbody>
		<tr class="text-info">
			<td width="4%" align="left"> <?php echo $cnt;?></td>
            <td width="46%" align="left" style="text-align:left;"><?php echo $recResult['item']; ?></td>
			<td width="10%" align="left"><?php echo $recResult['total']; ?></td>
			<td width="10%" align="left"><?php echo $recResult['unit_price']; ?></td>
           	<td width="10%" align="left"><?php echo $recResult['total_bd']; ?></td>
          	<td width="10%" align="left"><?php echo $recResult['gbp_price']; ?></td>
         	<td width="10%" align="left"><?php echo $recResult['total_gbp']; ?></td>
  		</tr>
  	<?php
	}
	?>
	</tbody>
</table>

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