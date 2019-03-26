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
<link href="styles/style.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>TEE Envelop Usage Report</title>
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
		//document.form1.action="php_func.php?cho=13";
		//document.form1.submit();
}//
</script>


</head>


<body contextmenu="false">
	<form id="form1" name="form1" action ="" method="post" enctype="multipart/form-data"> 
<?php

	date_default_timezone_set("Asia/Bahrain");
	$date_selected1 = $_SESSION['date_for_report1'];
	$date_selected2 = $_SESSION['date_for_report2'];
	$session_id = $_REQUEST['mid'];
	$query_count_free = "SELECT count(*) as total_available FROM tee_envelop_counts WHERE tee_type = 'small' and status= 'available' and mission_id='".$session_id."'";
	$result_count_free = mysql_fetch_array(mysql_query( $query_count_free));
	
	$query_count_used = "SELECT count(*) as total_used FROM tee_envelop_counts WHERE tee_type = 'small' and status= 'used' and date_used >= '$date_selected1' and date_used <= '$date_selected2' and mission_id='".$session_id."'";
	$result_count_used = mysql_fetch_array(mysql_query( $query_count_used));
?>


<div align="center" style="width:100%;" class="text-warning h4">
<u>
TEE Envelopes Used From <span style="color:blue;"><?php echo $date_selected1;?></span> To <span style="color:blue;"><?php echo $date_selected2; ?></span></u>
<br>
</div>
<div align="center" style="width:100%; padding:10px 0 20px 0;">
<table class="table table-bordered" style="width:60%;">
	<thead class="text-danger" style="font-size: 14px;">
        <tr>
            <th align="center">Total Available Small TEEs</th>
            <th align="center">Total Used Small TEEs In Selected Period</th>
        </tr>
	</thead>
   	<tbody class="text-success text-center" style="font-size: 14px;">
    	<tr >
           	<td ><?php echo $result_count_free['total_available']; ?></td>
     		<td ><?php echo $result_count_used['total_used']; ?></td>
      	</tr>
	</tbody>
</table>
<p style="height:30px"></p>
<?php
$get_staffwise_count="SELECT DISTINCT users.display_name as display_name, (SELECT COUNT(*) FROM tee_envelop_counts WHERE staff_id = users.user_id and STATUS='used' AND tee_type='small' and date_used >= '$date_selected1' and date_used <= '$date_selected2') AS used_pouch_count FROM users where role='staff' and status='active' and mission_id='".$session_id."' order by users.display_name ";



//echo $get_staffwise_count;

$result_staffwise_count = mysql_query($get_staffwise_count);
?>
<table width="30%" cellspacing="0" class="table table-bordered" style="width: 30%;">
	<thead class="text-danger text-center" style="font-size: 14px;">
        <tr>
            <th style="width: 70%;">Staff Name</th>
            <th style="width: 30%;">TEEs Used</th>
        </tr>
	</thead>
   	<tbody class="text-info" style="font-size: 13px;">
    <?php
	while($res_count_tees=mysql_fetch_array($result_staffwise_count))
	{
	?>
       	<tr >
           	<td ><?php echo $res_count_tees['display_name']; ?></td>
     		<td ><?php echo $res_count_tees['used_pouch_count']; ?></td>
      	</tr>
    <?php
	}
	?>
	</tbody>
</table>

</div>
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