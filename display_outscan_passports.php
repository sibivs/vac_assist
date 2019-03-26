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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Passport Submission Report</title>

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

<script type="text/javascript">
function dwnld_excel()
{
		document.form1.action="php_func.php?cho=16&mid=<?php echo $_REQUEST['mid']; ?>";
		document.form1.submit();
}
</script>

</head>

<body contextmenu="false">
	<form id="form1" name="form1" action ="" method="post" enctype="multipart/form-data"> 
<?php
	date_default_timezone_set("Asia/Bahrain");
	$mission_id = $_REQUEST['mid'];
	$query_count_result = "SELECT count(*) FROM passport_inscan_record WHERE current_status = 'outscan' and mission_id='".$mission_id."'";
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

<div align="center" style="width:100%; padding:10px 0 20px 0; font:bold 14px Verdana;">Passports Remains In The Embassy
<br>
<p align="left" style="font-size:12px; color: #7E7E7E; font-weight:normal;">Total <?php echo $result_count_result[0]; ?> records retrived &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a onclick="dwnld_excel();">[Export Result As Excel]</a>
</div>
<table class="tbl1" >
<thead>
	<tr>
    	<td align="left">Sl. No</td>
      	<td align="left">Ref. Number</td>
     	<td align="left">Despatched To Embassy</td>
	<td align="left">Despatched Before</td>
	</tr>
</thead>
                            <?php
						if($result_count_result[0]>0)
        				{ 
                			$query = "SELECT count(*) FROM passport_inscan_record WHERE current_status = 'outscan' and mission_id='".$mission_id."' and id <> ''";
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
							if($pageno == 1 ||$pageno =="" )
							{
								$cnt=0;
							}
							else if($pageno >1)
							{
								$cnt= ($pageno-1)*$rows_per_page;
							}
                			$limit = 'LIMIT ' .($pageno-1) * $rows_per_page .',' .$rows_per_page;
							$qr_t="SELECT * FROM passport_inscan_record WHERE current_status = 'outscan' and mission_id='".$mission_id."' order by date_outscan ASC $limit ";
                        	$query_instance = mysql_query($qr_t) OR die(mysql_error());
                        	$rows=mysql_num_rows($query_instance);
							while($recResult = mysql_fetch_array($query_instance))
							{
								$cnt++;
								$date1 = strtotime(date('Y-m-d'));
								$date2 = strtotime(date('Y-m-d', strtotime($recResult['date_outscan'])));
								$dateDiff = $date1 - $date2;
								$fullDays = floor($dateDiff/(60*60*24));
						?>
						<tr <?php if(($cnt % 2)==0){ ?> style="background-color:#e4ecf7;" <?php } else {?> style="background-color:#FFFFFF;"<?php } ?>>
							<td width="7%" align="left"> <?php echo $cnt; ?></td>
                            <td width="12%" align="left"><?php echo $recResult['gwf_number']; ?></td>
                            <td width="15%" align="left"><?php echo $recResult['date_outscan']; ?></td>
                            <td width="15%" align="left"><?php echo ($fullDays)." days"; ?></td>
                                </tr>
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