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
if(isset($_SESSION['role_ukvac']) &&  isset($_SESSION['vac']))
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
hr.style-four {
    height: 12px;
    border: 0;
    box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5);
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
  top: 10%;
  margin-left: -32px; /* -1 * image width / 2 */
  margin-top: -32px; /* -1 * image height / 2 */
}
</style>

<script type="text/javascript">
function dwnld_excel(mid,d1,d2)
{
		document.form1.action="php_func.php?cho=51&mid="+mid+"&d1="+d1+"&d2="+d2;
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
}, 2000)

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
	$query_count_result = "SELECT count(*) FROM bts_sales WHERE date_sold >= '$date_selected' and date_sold <= '$date_selected1' and mission_id='".$mission_id."'";
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
?>


<div align="center" style="" class="text-danger h4">

Travel Product Sales from  <label style="color:#3187F0"><?php echo $date_selected;  ?> </label> To <label style="color:#3187F0"><?php echo $date_selected1;  ?></label>
<br>
<p align="left" style="font-size:12px; color: #7E7E7E; font-weight:normal;">Total <?php echo $result_count_result[0]; ?> records retrived &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a onclick="dwnld_excel('<?php echo $mission_id; ?>','<?php echo $date_selected;?>','<?php echo $date_selected1;?>')">Download As Excel</a>
</div>

<?php
$get_total = mysql_query("select currency, sum(ROUND(quantity_sold*price_per_item,3)) as total, sum(ROUND((quantity_sold*gbp_price),3))  as gbp_price from bts_sales where mission_id= '".$mission_id."' and date_sold >= '$date_selected' and date_sold <= '$date_selected1' group by currency");
?>
<br>
<table class="table table-bordered" style="width: 50%; float:middle; border-radius: 8px;">
	<thead class="">
        <tr class="text-warning" style="font-weight: 13px;">
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

<table cellspacing="0" class="table" style="background:#CCC; border:0px solid #CCC; ">
	<thead class="text-warning" style="font-size: 13px;">
    <tr>
    	<th align="left">Sl. No</th>
      	<th align="left">Sold By</th>
        <th align="left">Applicant Name</th>
    	<th align="left">Phone</th>
		<th align="left">Product Name</th>
     	<th align="left">Quantity</th>
     	<th align="left">Unit Price (BD)</th>
   		<th align="left">Total (BD)</th>
        <th align="left">Unit Price (GBP)</th>
   		<th align="left">Total (GBP)</th>
	</tr>
    </thead>
                <?php
				if($result_count_result[0]>0)
				{ 
					$query = "SELECT count(*) FROM bts_sales WHERE date_sold >= '$date_selected' and date_sold <= '$date_selected1' and mission_id='".$mission_id."' and id <> ''";
					$result = mysql_query($query);
					$query_data = mysql_fetch_row($result);
					$numrows = $query_data[0];
					$rows_per_page = 30;
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
					$qr_t="SELECT a.product_name as item, a.quantity_sold as total, a.date_sold as date, a.price_per_item as unit_price,a.gbp_price as gbp_price, a.applicant_name as applicant, a.applicant_phone as phone, b.display_name as staff from bts_sales a, users b WHERE a.date_sold >= '$date_selected' and a.date_sold <= '$date_selected1' and a.user_id=b.user_id and a.mission_id='".$mission_id."' order by b.display_name ASC $limit ";
					$query_instance = mysql_query($qr_t) OR die(mysql_error());
					$rows=mysql_num_rows($query_instance);
					if($pageno == 1 ||$pageno =="" )
					{
						$cnt=0;
					}
					else if($pageno >1)
					{
						$cnt= ($pageno-1)*$rows_per_page;
					}
							while($recResult = mysql_fetch_array($query_instance))
							{
								$cnt++;
						?>
                        <tbody class="text-info" style="font-size: 13px;">
						<tr <?php if(($cnt % 2)==0){ ?> style="background-color:#e4ecf7; padding: 5px !important;" <?php } else {?> style="background-color:#FFFFFF;  padding: 5px !important;"<?php } ?>>
							<td width="4%" align="left"> 
							<?php 
								echo $cnt; 
							?></td>
                            <td width="10%" style="text-align:left;"><?php echo $recResult['staff']; ?></td>
			    			<td width="10%" align="left"><?php echo $recResult['applicant']; ?></td>
                            <td width="10%" align="left"><?php echo $recResult['phone']; ?></td>
                            <td width="30%" align="left"><?php echo $recResult['item']; ?></td>
			    			<td width="3%" align="left"><?php echo $recResult['total']; ?></td>
							<td width="10%" align="left"><?php echo $recResult['unit_price']; ?></td>
                    		<td width="6%" align="left"><?php echo round($recResult['unit_price']*$recResult['total'],2); ?></td>
                            <td width="10%" align="left"><?php if($recResult['gbp_price']!=""){ echo $recResult['gbp_price'];} else {echo "NA";} ?></td>
                            <td width="15%" align="left"><?php if($recResult['gbp_price']!=""){ echo round($recResult['gbp_price']*$recResult['total'],2);} else { echo "NA";} ?></td>
                       		</tr>
                            </tbody>
                        <?php
							}
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
                        	echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1&mid=".$mission_id."' style='font-size:13px; cursor:pointer;'>FIRST</a> ";
                        	$prevpage = $pageno-1;
                        	echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage&mid=".$mission_id."' style='font-size:13px; cursor:pointer;'>PREV</a> ";
                		} 
                		echo " ( Page $pageno of $lastpage ) ";
                		if ($pageno == $lastpage)
                		{
                			echo " NEXT LAST ";
                		}
                		else
                		{
                        	$nextpage = $pageno+1;
                        	echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage&mid=".$mission_id."' style='font-size:13px; cursor:pointer;'>NEXT</a> ";
                        	echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage&mid=".$mission_id."' style='font-size:13px; cursor:pointer;'>LAST</a> ";
                		}
                		echo "</div>";
						

					?>
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