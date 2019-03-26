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
<link href="styles/style.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Daily Submission Report</title>
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
</style>

	<script type="text/javascript" language="javascript">
		function dwnld_excel(mid,d1,d2)
		{
				document.form1.action="php_func.php?cho=50&mid="+mid+"&d1="+d1+"&d2="+d2;
				document.form1.submit();
		}
    </script>
</head>


<body contextmenu="false">
	<form id="form1" name="form1" action ="" method="post" enctype="multipart/form-data"> 
<?php

	date_default_timezone_set("Asia/Bahrain");
	$date_selected = $_SESSION['date_for_report'];
	$date_selected1 = $_SESSION['date_for_report1'];
	$mission_id=$_REQUEST['mid'];
	$query_count_result = "SELECT count(*) FROM passport_inscan_record WHERE date_outscan >= '$date_selected' and date_outscan <= '$date_selected1' and mission_id='".$mission_id."'";
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


<div align="center" style="width:100%;" class="text-success h4">

<?php 
if($date_selected === $date_selected1)
{

echo "Applications Accepted On ".$date_selected;
}
else
{
	echo "Applications Accepted between ".$date_selected." - ".$date_selected1;
}
?>

<br>
<p align="left" style="font-size:12px; color: #7E7E7E; font-weight:normal;">Total <?php echo $result_count_result[0]; ?> records retrieved &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a onclick="dwnld_excel('<?php echo $mission_id; ?>','<?php echo $date_selected;?>','<?php echo $date_selected1;?>')">Download Excel</a>
</div>
<table width="100%" class="table table-bordered" style="">
	<thead>
    <tr class="text-warning" style="font-size: 13px;">
    	<th style="width:5%;">Sl. No</th>
      	<th style="width:15%;">Ref. Number</th>
		<th style="width:15%;">Submitted By</th>
     	<th style="width:15%;">Despatched To Embassy</th>
     	<th style="width:10%;">Inscanned Date</th>  	
    	<th style="width:15%;">Delivered Date</th>
     	<th style="width:10%;">Delivered by</th>
      	<th style="width:15%;">Delivery Type</th>
	</tr>
    </thead>
                <?php
				if($result_count_result[0]>0)
				{ 
					$query = "SELECT count(*) FROM passport_inscan_record WHERE date_outscan >= '$date_selected' and date_outscan <= '$date_selected1' and mission_id='".$mission_id."' and id <> ''";
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
					$qr_t="SELECT * FROM passport_inscan_record WHERE date_outscan >= '$date_selected' and date_outscan <= '$date_selected1' and mission_id='".$mission_id."'  order by application_taken_by ASC $limit ";
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
                        <tbody class="text-info">
						<tr <?php if(($cnt % 2)==0){ ?> style="background-color:#e4ecf7;" <?php } else {?> style="background-color:#FFFFFF;"<?php } ?> >
							<td width="4%" align="left"> 
							<?php 
								echo $cnt; 
							?></td>
                            <td width="10%" align="left"><?php echo $recResult['gwf_number']; ?></td>
			    <td width="12%" align="left"><?php echo $recResult['application_taken_by']; ?></td>
                            <td width="10%" align="left"><?php echo $recResult['date_outscan']; ?></td>
                            <td width="10%" align="left"><?php if($recResult['date_inscan']!="0000-00-00")echo $recResult['date_inscan']; ?></td>
			    
                            <td width="15%" align="left"><?php if($recResult['delivered_on']=="0000-00-00"){ echo ""; }else { echo $recResult['delivered_on']." ".$recResult['delivered_at'];} ?></td>
                    		<td width="8%" align="left" style="text-align: left !important;"><?php echo $recResult['delivered_by']; ?></td>
					<td width="15%" align="left"> 
								<?php
								if($recResult['current_status']=="courier_Service")
								{
									echo "Courier Service";
								}
								else if($recResult['current_status']=="counter_delivery")
								{
									echo "Counter Delivery";
								}
								else if($recResult['current_status']=="")
								{
									echo "";
								}
								
								?>
                             	</td>
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