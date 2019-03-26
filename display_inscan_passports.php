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
include_once("db_connect.php");
if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac']))
{
	include_once("db_connect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="styles/style.css">
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daily Reconciliation Report</title>
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

function dwnld_excel(mid)
{
		document.form1.action="php_func.php?cho=14&mid="+mid;
		document.form1.submit();
}
</script>


<script src="Scripts/sorttable.js" type="text/javascript"></script>
</head>

<body>
	<form action="" method="post" name="form1" enctype="multipart/form-data">
		<?php
        date_default_timezone_set("Asia/Bahrain");
        $mission_id = $_REQUEST['mid'];
        $query_count_result = "SELECT count(*) FROM passport_inscan_record WHERE current_status = 'inscan' and mission_id='".$mission_id."'";
        $result_count_result = mysql_fetch_array(mysql_query( $query_count_result));
        if($result_count_result[0]<=0)
        {
            $result_count_result[0]=0;
        }
        if (isset($_GET['pageno'])) 
        {
            $pageno = $_GET['pageno'];
        }
        else
        {
            $pageno = 1;
            $lastpage = 1;
        }
        
        //COUNT OF RECTIFICATION PASSPORTS IN VAC
        $query_count_rect = "SELECT count(*) FROM rectification_cancellation WHERE status = 'inscan' and mission_id='".$mission_id."'";
        $result_count_rect = mysql_fetch_array(mysql_query( $query_count_rect));
        if($result_count_rect[0]<=0)
        {
            $result_count_rect[0]=0;
        }
                                
        ?>
        <div align="center" style="width:100%; padding:10px 0 20px 0; font:bold 14px Verdana;"><span class="text-danger h4">Passports Available in VAC</span>
        <br/>
        <p align="center" style="font-size:13px; color: #7E7E7E; font-weight:normal;"><br>Total <?php echo $result_count_result[0]; ?> Passports and  <?php echo $result_count_rect[0]; ?> Rectification / Cancellation records retrived &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a onClick="dwnld_excel(<?php echo $mission_id; ?>);">[Export Result As Excel]</a></p>
        </div>
        
        <span class="text-info" style="font-size:16px; font-weight:100; padding-left: 50px;"><u>Rectification Or Cancellation</u></span>
        <table class="table table-striped" >
            <thead>
                <tr class="text-danger" style="font-size:14px; font-weight:100;">
                    <th align="left">Sl. No</th>
                    <th align="left">Ref. Number</th>
                    <th align="left">Dispatched To Embassy</th>
                    <th align="left">Inscanned Date</th>
                    <th align="left">In VAC Since</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if($result_count_rect[0]>0)
            { 
                $qr_t_rec="SELECT * FROM rectification_cancellation WHERE status = 'inscan' and mission_id='".$mission_id."' order by inscanned_on ASC";
                $query_instance_rec = mysql_query($qr_t_rec) OR die(mysql_error());
                $rows_rec=mysql_num_rows($query_instance_rec);
                while($recResult_rec = mysql_fetch_array($query_instance_rec))
                {
                    $cnt_rec++;
                    $date1_rec = strtotime(date('Y-m-d'));
                    $date2_rec = strtotime(date('Y-m-d', strtotime($recResult_rec['inscanned_on'])));
                    $dateDiff_rec = $date1_rec - $date2_rec;
                    $fullDays_rec = floor($dateDiff_rec/(60*60*24));
            ?>
        
                <tr>
                    <td width="7%" align="left"> <?php echo $cnt_rec; ?></td>
                    <td width="12%" align="left"><?php echo $recResult_rec['gwf_number']; ?></td>
                    <td width="15%" align="left"><?php echo $recResult_rec['outscan_date']; ?></td>
                    <td width="10%" align="left"><?php echo $recResult_rec['inscanned_on']; ?></td>
                    <td width="15%" align="left"><?php echo ($fullDays_rec+1)." days"; ?></td>
                </tr>
          
            <?php
                }
            }
            ?>
            </tbody>
        </table>
        
        <br>
        
        <span class="text-info" style="font-size:16px; font-weight:100; padding-left: 50px;"><u>Normal Submission</u></span>
        
        
        <table class="table table-striped" >
            <thead>
                <tr class="text-danger" style="font-size:14px; font-weight:100;">
                    <th align="left">Sl. No</th>
                    <th align="left">Ref. Number</th>
                    <th align="left">Dispatched To Embassy</th>
                    <th align="left">Inscanned Date</th>
                    <th align="left">In VAC Since</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if($result_count_result[0]>0)
            { 
                $query = "SELECT count(*) FROM passport_inscan_record WHERE current_status = 'inscan' and mission_id='".$mission_id."' and id <> ''";
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
                $qr_t="SELECT * FROM passport_inscan_record WHERE current_status = 'inscan' and mission_id='".$mission_id."' order by date_inscan ASC $limit ";
                $query_instance = mysql_query($qr_t) OR die(mysql_error());
                $rows=mysql_num_rows($query_instance);
                while($recResult = mysql_fetch_array($query_instance))
                {
                    $cnt++;
                    $date1 = strtotime(date('Y-m-d'));
                    $date2 = strtotime(date('Y-m-d', strtotime($recResult['date_inscan'])));
                    $dateDiff = $date1 - $date2;
                    $fullDays = floor($dateDiff/(60*60*24));
            ?>
            <tr <?php if(($cnt % 2)==0){ ?> style="background-color:#e4ecf7;" <?php } else {?> style="background-color:#FFFFFF;"<?php } ?>>
                <td width="7%" align="left"> <?php echo $cnt; ?></td>
                <td width="12%" align="left"><?php echo $recResult['gwf_number']; ?></td>
                <td width="15%" align="left"><?php echo $recResult['date_outscan']; ?></td>
                <td width="10%" align="left"><?php echo $recResult['date_inscan']; ?></td>
                <td width="15%" align="left"><?php echo ($fullDays+1)." days"; ?></td>
                    </tr>
          
            <?php
                }
            }
            ?>
            </tbody>
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
            echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage&mid=".$mission_id."' style='font-size:13px; cursor:pointer;'>PREVIOUS</a> ";
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