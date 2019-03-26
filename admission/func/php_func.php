<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
include_once("../../db_connect.php");
$ch=$_REQUEST['cho'];
switch($ch)
{
	case 00:
		check_alive();
		break;
	case 1:
		if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="staff"))){ new_admission(); }else{ header("Location:index.php"); }
		break;
}
	
function check_alive()
{
	
	if(!isset($_SESSION['last_activity_ukvac'])) 
	{
		print json_encode("timedout");
	}
	else
	{
		$created_at = $_SESSION['last_activity_ukvac'];
		if(!((int)$created_at>(time()-3600))) 
		{
			print json_encode("timedout");
		}
		else
		{
			print json_encode($created_at);
		}
	}
}


function new_admission()
{
	$token =strtoupper($_REQUEST['token']);
	$applicant_count = $_REQUEST['count'];
	$appointment = $_REQUEST['appointment'];
	$mission = $_SESSION['mission_id'];
	date_default_timezone_set("Asia/Bahrain");
	$date_today=date('Y-m-d');
	$time_now= date("G:i");
	$get_token=mysql_fetch_array(mysql_query("select count(*) as total from submission_timing where mission_id = '$mission' and token_no = '$token' and date_created='$date_today'"));
	if($get_token['total'] != '0')
	{
		print json_encode("used");
	}
	else
	{
		$get_max_group = mysql_fetch_array(mysql_query("select max(applicant_group_id) as max_id from appointment_schedule where mission_id='".$_SESSION['mission_id']."'")) ;
		//insert token into appointment
		for($i=0; $i<$applicant_count; $i++)
		{
			$max_new_id=$get_max_group['max_id']+1;
			$inser_in_appointment = "insert into appointment_schedule values (DEFAULT,'$max_new_id','','$date_today','$date_today','$time_now','".strtoupper($appointment)."','".$appointment."','','shown_up','','$mission')";
			mysql_query($inser_in_appointment);
		}
		
		$get_ref_no=mysql_query("select id from appointment_schedule where applicant_group_id='$max_new_id' and mission_id='".$_SESSION['mission_id']."'");
		
		while($get_details=mysql_fetch_array($get_ref_no))
		{
			$insert_new_token = "insert into submission_timing values(DEFAULT,'".$get_details['id']."','$token','$max_new_id','$date_today','$time_now','on_time','','','$mission')";
			mysql_query($insert_new_token);
		}
		print json_encode("success");
	}
}
			
			