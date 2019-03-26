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
	include_once("db_connect.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="styles/style.css">
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html;"/>
<title>Daily Sales Report</title>
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
  top: 15%;
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

$(document).ready(function() {
	$("#loading_div").html('<img src="styles/images/preloader.gif" class="ajax-loader" style=""/>');
	$("#loading_div").show();
	setTimeout(function myFunction() {
    $("#loading_div").hide();
	}, 3000);

});


</script>


</head>


<body contextmenu="false">
<div align="center" id="loading_div" style=" width: 100%; height: 100%; vertical-align: central !important;"></div>
	<form id="form1" name="form1" action ="" method="post" enctype="multipart/form-data"> 
<?php
	date_default_timezone_set("Asia/Bahrain");
	$date_selected = $_SESSION['date_for_report'];
	$date_selected1 = $_SESSION['date_for_report1'];
	$mission_id = $_REQUEST["mid"];
?>


<div align="center" class="label-info" style="padding:10px 0 10px 0; color:#FFF; font-weight:600;" >

Travel Products - Staff-Wise Sales from  <label style="color:#900"><?php echo $date_selected;  ?> </label> To <label style="color:#900"><?php echo $date_selected1;  ?></label>
</div>
<br>
<div style="padding-left: 15px; padding-right: 15px;" >
<table cellspacing="0" class="table table-bordered">
	<thead>
    <tr>
    	<th style='font-size:13px; font-weight:600;' align="left">Product Name</th>
        <th style='font-size:13px; font-weight:600; width: 2%;' align="left">Price (BHD)</th>
        <th style='font-size:13px; font-weight:600; width: 2%;' align="left">Price (GBP)</th>
        <?php 
		$userid_str="";
		$get_users = mysql_query("select user_id,display_name from users where role='staff' and status='active' and mission_id = '".$mission_id."' order by display_name");
		while($res_get_users = mysql_fetch_array($get_users))
		{
			echo "<th align='left' style='font-size:13px; font-weight:600;'>".$res_get_users['display_name']."</th>";
			$userid_str[]=$res_get_users['user_id'];
		}
		?>
        <th style="font-size:13px; font-weight:600; width: 2%;" class="text-info">Total Count</th>
        <th style="font-size:13px; font-weight:600; width: 2%;" class="text-info">Amount (BHD)</th>
        <th style="font-size:13px; font-weight:600; width: 2%;" class="text-info">Amount (GBP)</th>
	</tr>
    </thead>
	<tbody style="font-size: 12px;">
    <?php
		$cnt=0;
		$max = sizeof($userid_str);
		$total_amount=0;
		$total_amount_gbp=0;
		$qr_t=mysql_query("SELECT distinct(price_per_item) as p_per_item,gbp_price,product_name from bts_sales WHERE date_sold >= '$date_selected' and  date_sold <= '$date_selected1' and mission_id='".$mission_id."' GROUP BY gbp_price ORDER BY product_name");
		while($get_product_list=mysql_fetch_array($qr_t))
		{
			
			//while($recResult = mysql_fetch_array($query_instance))
			//{
				$cnt++;
				?>
				<tr>
					<td width="20%" align="left" style="text-align:left;"><?php echo $get_product_list['product_name']; ?></td>
                    <td align="left" style="text-align:center;"><?php echo $get_product_list['p_per_item']; ?></td>
                    <td align="left" style="text-align:center;"><?php if($get_product_list['gbp_price']==""){ echo "0"; }else { echo $get_product_list['gbp_price']; } ?></td>
                   <?php
				   $get_users1 = mysql_query("select user_id,display_name from users where role='staff' and status='active' and mission_id='".$mission_id."' order by display_name");
				   	while($res_get_users1 = mysql_fetch_array($get_users1))
					{
						$get_staff_wisee_cnt = "select sum(quantity_sold) as total_count, price_per_item from bts_sales where product_name= '".$get_product_list['product_name']."' and cast(price_per_item as decimal(10,3))= '".floatval($get_product_list['p_per_item'])."' and user_id='".$res_get_users1['user_id']."' and  date_sold >= '".$date_selected."' and  date_sold <= '".$date_selected1."' and mission_id='".$mission_id."'";
						$res_get_staff_wise_cnt = mysql_fetch_array(mysql_query($get_staff_wisee_cnt));
						?>
						<td><?php echo $res_get_staff_wise_cnt['total_count']; ?></td>
                        
                        <?php
					}
					
					$get_product_wise_cnt = "select sum(quantity_sold) as total_count, price_per_item,gbp_price from bts_sales where product_name= '".$get_product_list['product_name']."' and cast(price_per_item as decimal(10,3))= '".floatval($get_product_list['p_per_item'])."' and  date_sold >= '".$date_selected."' and  date_sold <= '".$date_selected1."' and mission_id='".$mission_id."'";
						$res_product_wise_cnt = mysql_fetch_array(mysql_query($get_product_wise_cnt));
				   	echo "<td class='text-info' style='font-size: 13px; font-weight: bold;'>". $res_product_wise_cnt['total_count']."</td>";
					echo "<td class='text-danger' style='font-size: 13px; font-weight: bold;'>".$res_product_wise_cnt['total_count']*$res_product_wise_cnt['price_per_item'] ."</td>";
					$item_wise_gbp_total = $res_product_wise_cnt['total_count']*$res_product_wise_cnt['gbp_price'];
					if($item_wise_gbp_total==0)
					{
						echo "<td class='text-info' style='font-size: 13px; font-weight: bold;'>NA</td>";
					}
					else
					{
						echo "<td class='text-info' style='font-size: 13px; font-weight: bold;'>". $item_wise_gbp_total."</td>";
					}
					
					$total_amount=	$total_amount+($res_product_wise_cnt['total_count']*$res_product_wise_cnt['price_per_item']);
					$total_amount_gbp=	$total_amount_gbp+($res_product_wise_cnt['total_count']*$res_product_wise_cnt['gbp_price']);
					?>
				</tr>
				<?php
			//}
		}
			?>
            <tr class="text-danger" style="font-size: 13px; font-weight: bold;">
            	<td colspan="3" style="text-align: right;" >Grand Total sale (BD)</td>
                <?php
				$bts_total_per_staff=0;
                $get_users1 = mysql_query("select user_id,display_name from users where role='staff' and status='active' and mission_id='".$mission_id."' order by display_name");
				   	while($res_get_users1 = mysql_fetch_array($get_users1))
					{
						$qr_t=mysql_query("SELECT distinct(price_per_item) as p_per_item,product_name from bts_sales WHERE date_sold >= '$date_selected' and  date_sold <= '$date_selected1' and mission_id='".$mission_id."' ORDER BY product_name");
						while($get_product_list1=mysql_fetch_array($qr_t))
						{
							$get_staff_wisee_cnt = "select sum(quantity_sold)*cast(price_per_item as decimal(10,3)) as total_count from bts_sales where product_name= '".$get_product_list1['product_name']."' and cast(price_per_item as decimal(10,3))= '".floatval($get_product_list1['p_per_item'])."' and user_id='".$res_get_users1['user_id']."' and  date_sold >= '".$date_selected."' and  date_sold <= '".$date_selected1."' and mission_id='".$mission_id."'";
							$res_get_staff_wise_cnt = mysql_fetch_array(mysql_query($get_staff_wisee_cnt));
							$bts_total_per_staff=$bts_total_per_staff+$res_get_staff_wise_cnt['total_count'];
							
						}
						echo "<td>". $bts_total_per_staff ."</td>";
						$bts_total_per_staff=0;
						
					}
					?>
                    <td colspan="2" style="text-align:right;"><?php echo $total_amount; ?></td>
                    <td style="border-bottom:0px !important;"></td>
            </tr>
            <tr class="text-info" style="font-size: 13px; font-weight: bold;">
            	<td colspan="3" style="text-align: right;">Grand Total sale (GBP)</td>
                <?php
				$bts_total_per_staff1=0;
                $get_users1 = mysql_query("select user_id,display_name from users where role='staff' and status='active' and mission_id='".$mission_id."' order by display_name");
				   	while($res_get_users1 = mysql_fetch_array($get_users1))
					{
						$qr_t=mysql_query("SELECT distinct(price_per_item) as p_per_item,product_name from bts_sales WHERE date_sold >= '$date_selected' and  date_sold <= '$date_selected1' and mission_id='".$mission_id."' ORDER BY product_name");
						
						//echo "SELECT distinct(price_per_item) as p_per_item,product_name from bts_sales WHERE date_sold >= '$date_selected' and  date_sold <= '$date_selected1' and mission_id='".$mission_id."' ORDER BY product_name<br>------<br>";
						//mysql_query ("set character_set_client='utf8'");
						//mysql_query ("set character_set_results='utf8'");
						//mysql_query ("set collation_connection='utf8_general_ci'");
						while($get_product_list1=mysql_fetch_array($qr_t))
						{
							
							$get_staff_wis_cnt1 = "select ROUND((sum(quantity_sold)*gbp_price),3) as total_count from bts_sales where product_name= '".$get_product_list1['product_name']."' and cast(price_per_item as decimal(12,3))= '".floatval($get_product_list1['p_per_item'])."' and user_id='".$res_get_users1['user_id']."' and  date_sold >= '".$date_selected."' and  date_sold <= '".$date_selected1."' and mission_id='".$mission_id."'";
							$res_get_stff_wise_cnt1 = mysql_fetch_array(mysql_query($get_staff_wis_cnt1));
							$bts_total_per_staff1=$bts_total_per_staff1+$res_get_stff_wise_cnt1['total_count'];
						}
						
						if($bts_total_per_staff1==0)
						{
							echo "<td> NA </td>";
						}
						else
						{
							echo "<td>". $bts_total_per_staff1 ."</td>";
						}
						$bts_total_per_staff1=0;
					}
				   	if($total_amount_gbp==0)
						{
							echo "<td colspan='3' style='text-align:right;  border-top: 0px;'> NA </td>";
						}
						else
						{
							echo "<td colspan='3' style='text-align:right; border-top: 0px;'>". $total_amount_gbp ."</td>";
						}	
					?>
                    
            </tr>
 		</tbody>
	</table>
	</div>
	<p style="height:10px"></p>
                    	
	<p style="height:30px"></p>

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