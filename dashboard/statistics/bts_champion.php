<?Php
//Check if BTS Disabled
include_once("../../db_connect.php");
$mission = $_REQUEST['m'];
?>

<script src="../../Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<!--  FOR STATISTICS     -->
<script src="library/RGraph.common.core.js"></script>
<script src="library/RGraph.common.dynamic.js"></script>
<script src="library/RGraph.common.key.js"></script>
<script src="library/RGraph.common.tooltips.js"></script>
<script src="library/RGraph.pie.js"></script>
<!--  FOR STATISTICS     -->
<link rel="stylesheet" href="../../styles/style.css">

<div style=" float:left; height: 400px; overflow:scroll; font-size: 13px; font-weight:600; padding: 5px; text-align:center;" class="text-danger">
   <u> <?php echo date('F-Y'); ?></u>
   <br>
<?php
echo "<br>";
$mission = $_REQUEST['m'];
$width = 200;
	$bg_color="#1857a0";
	$txt = "#FFF";
$montly = array();
$currency = mysql_query("SELECT e.currency AS currency, e.exchng_rate_wt_gbp AS exchange FROM exchange_rates e , missions m WHERE m.id=".$mission." AND FIND_IN_SET(e.currency,m.currencies)");
while($exchange_rate = mysql_fetch_array($currency))
{
	$exchange = $exchange_rate['exchange'];
	$bts_total_per_staff=0;
	$bts_total_per_staff1=0;
    $get_users1 = mysql_query("select user_id,display_name from users where role='staff' and status='active' and mission_id='".$mission."' order by display_name");
	while($res_get_users1 = mysql_fetch_array($get_users1))
	{
		$qr_t=mysql_query("SELECT distinct(price_per_item) as p_per_item,product_name from bts_sales WHERE YEAR(date_sold)=YEAR(CURRENT_DATE) AND MONTH(date_sold)=MONTH(CURRENT_DATE) and mission_id='".$mission."' ORDER BY product_name");
		while($get_product_list1=mysql_fetch_array($qr_t))
		{
			$get_staff_wisee_cnt = "select sum(quantity_sold)*cast(price_per_item as decimal(10,3)) as total_count from bts_sales where product_name= '".$get_product_list1['product_name']."' and cast(price_per_item as decimal(10,3))= '".floatval($get_product_list1['p_per_item'])."' and user_id='".$res_get_users1['user_id']."' and  YEAR(date_sold)=YEAR(CURRENT_DATE) AND MONTH(date_sold)=MONTH(CURRENT_DATE) and mission_id='".$mission."'";
			$res_get_staff_wise_cnt = mysql_fetch_array(mysql_query($get_staff_wisee_cnt));
			$bts_total_per_staff=$bts_total_per_staff+$res_get_staff_wise_cnt['total_count'];
			
			$get_staff_wis_cnt1 = "select ROUND((sum(quantity_sold)*gbp_price),3) as total_count from bts_sales where product_name= '".$get_product_list1['product_name']."' and cast(price_per_item as decimal(12,3))= '".floatval($get_product_list1['p_per_item'])."' and user_id='".$res_get_users1['user_id']."' and  YEAR(date_sold)=YEAR(CURRENT_DATE) AND MONTH(date_sold)=MONTH(CURRENT_DATE) and mission_id='".$mission."'";
			$res_get_stff_wise_cnt1 = mysql_fetch_array(mysql_query($get_staff_wis_cnt1));
			$bts_total_per_staff1=$bts_total_per_staff1+$res_get_stff_wise_cnt1['total_count'];	
				
		}
		$monthly[] = array(
        		"staff_name" => $res_get_users1['display_name'], 
        		"total_bhd" => $bts_total_per_staff, 
        		"total_gbp" => $bts_total_per_staff1
    		);	
			$bts_total_per_staff =0;	
			$bts_total_per_staff1=0;
	}
	$monthly_array = array();
	foreach ($monthly as $key => $row)
	{
		$monthly_array[$key] = $row['total_bhd'];
	}
	array_multisort($monthly_array, SORT_DESC, $monthly);
		
	?>
    <table class="" style="width:100%; font-size:12px !important;">
    <?php
		
	foreach ($monthly as $key => $row) 
	{
	?>
    	<tr style="height: 12px;">
        	<td class="text-danger" style="font-size: 12px; width: 40% !important;">
            	<?php echo $row['staff_name']; ?>
        	</td>
            <td class="text-danger" style="font-size: 12px; width: 60% !important;">
            	<?php
				if((int)$row['total_bhd'] ==0)
				{
					$bg_color = "#FFF";
					$txt = 'black';
				?>
                	<label style="margin-right:0px;background-color:#1857a0; width:4px;">&nbsp;&nbsp;</label>
                <?php
				}
                ?>
            	<label style="margin-right:0px; color:<?php echo $txt; ?>; background-color:<?php echo $bg_color; ?>; width:<?php echo $width; ?>px;">
                	<?php echo $row['total_bhd']." BHD (".$row['total_gbp']." GBP)"; ?>
				</label>
        	</td>
    	</tr>
	<?php
		if((int)$row['total_bhd'] ==0)
		{
			$width = "200";
		}
		else
		{
			$width = ($width-20);
		}
	}
	?>
    </table>
    <?php

	$width = ($width-20);
	?>
    </div>
    <div style="height: 400px; font-size: 13px; float:left; font-weight:600; padding: 5px; text-align:center; overflow:scroll;" class="text-danger">
       <u><?php echo date('F-Y', strtotime('last month')); ?></u>
       <br />
    <?php
	
	/////PREVIOUS MONTH
	echo "<br>";
	$prev_month = array();
	$width = 200;
	$bg_color="#1857a0";
	$txt = "#FFF";
	$bts_total_per_staff_prev=0;
	$bts_total_per_staff_prev1=0;
    $get_users1 = mysql_query("select user_id,display_name from users where role='staff' and status='active' and mission_id='".$mission."' order by display_name");
	while($res_get_users1 = mysql_fetch_array($get_users1))
	{
		$qr_t=mysql_query("SELECT distinct(price_per_item) as p_per_item,product_name from bts_sales WHERE YEAR(date_sold) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(date_sold) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) and mission_id='".$mission."'");
		while($get_product_list1=mysql_fetch_array($qr_t))
		{
			$get_staff_wisee_cnt = "select sum(quantity_sold)*cast(price_per_item as decimal(10,3)) as total_count from bts_sales where product_name= '".$get_product_list1['product_name']."' and cast(price_per_item as decimal(10,3))= '".floatval($get_product_list1['p_per_item'])."' and user_id='".$res_get_users1['user_id']."' and  YEAR(date_sold) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(date_sold) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) and mission_id='".$mission."'";
			$res_get_staff_wise_cnt = mysql_fetch_array(mysql_query($get_staff_wisee_cnt));
			$bts_total_per_staff_prev=$bts_total_per_staff_prev+$res_get_staff_wise_cnt['total_count'];
			
			$get_staff_wis_cnt1 = "select ROUND((sum(quantity_sold)*gbp_price),3) as total_count from bts_sales where product_name= '".$get_product_list1['product_name']."' and cast(price_per_item as decimal(12,3))= '".floatval($get_product_list1['p_per_item'])."' and user_id='".$res_get_users1['user_id']."' and  YEAR(date_sold) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(date_sold) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) and mission_id='".$mission."'";
			$res_get_stff_wise_cnt1 = mysql_fetch_array(mysql_query($get_staff_wis_cnt1));
			$bts_total_per_staff_prev1=$bts_total_per_staff_prev1+$res_get_stff_wise_cnt1['total_count'];	
		}
		$prev_month[] = array(
        	"staff_name" => $res_get_users1['display_name'], 
        	"total_bhd" => $bts_total_per_staff_prev, 
        	"total_gbp" => $bts_total_per_staff_prev1
    	);
		$bts_total_per_staff_prev=0;
		$bts_total_per_staff_prev1=0;
		
	}
	$prev_monthly_array = array();
	foreach($prev_month as $key => $row)
	{
		$prev_monthly_array[$key] = $row['total_bhd'];
	}
	array_multisort($prev_monthly_array, SORT_DESC, $prev_month);
	?>
    <table class="" style="width: 100%; font-size:12px !important;">
    <?php
		
	foreach ($prev_month as $key => $row) 
	{
	?>
    	<tr>
        	<td class="text-danger" style="font-size: 12px; width: 40% !important;">
            	<?php echo $row['staff_name']; ?>
        	</td>
            <td class="text-danger" style="font-size: 12px; width: 60% !important;">
            	<?php
				if((int)$row['total_bhd'] ==0)
				{
					$bg_color = "#FFF";
					$txt = 'black';
				?>
                	<label style="margin-right:0px;background-color:#1857a0; width:4px;">&nbsp;&nbsp;</label>
                <?php
				}
                ?>
            	<label style="margin-right:0px;color:<?php echo $txt; ?>; background-color:<?php echo $bg_color; ?>; width:<?php echo $width; ?>px;">
                	<?php echo $row['total_bhd']." BHD (".$row['total_gbp']." GBP)"; ?>
				</label>
        	</td>
    	</tr>
	<?php
		if((int)$row['total_bhd'] ==0)
		{
			$width = "200";
		}
		else
		{
			$width = ($width-20);
		}
	}
	?>
    </table>
    <?php

}

?>
</div>
