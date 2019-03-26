<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
$ch=$_REQUEST['cho'];
date_default_timezone_set("Asia/Bahrain");
$expire_time = 60*60; //expire time
include_once("db_connect.php");
if($ch ==00)
{
	check_alive();
}
else if($ch==1)
{
	login();
}
else if($ch==2)
{
	logout();
}
else
{
	if((isset($_SESSION['last_activity_ukvac'] ))&&($_SESSION['last_activity_ukvac']<(time()-$expire_time))) 
	{
		logout();
	}
	else 
	{
		$_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
		switch($ch)
		{
			case 3:
				if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator"|| $_SESSION['role_ukvac']=="power_admin") &&  isset($_SESSION['vac'])){	add_new_user();}else{logout();}
				break;	
			case 4:
				if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator" || $_SESSION['role_ukvac']=="power_admin") &&  isset($_SESSION['vac'])){	reset_password_user();}else{logout();}
				break;
			case 5:
				if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator" || $_SESSION['role_ukvac']=="power_admin") &&  isset($_SESSION['vac'])){delete_staff();}else{logout();}
				break;
			case 6:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff")||($_SESSION['role_ukvac']=="passback"))&&  isset($_SESSION['vac']) ){ deliver_ppt(); }else{ logout(); }
				break;
			case 7:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac']) ){ inscan_rectification(); }else{ logout(); }
				break;
			case 8:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff")||($_SESSION['role_ukvac']=="passback"))&&  isset($_SESSION['vac']) ){ outscan_rectification(); }else{ logout(); }
				break;
			case 9:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff")||($_SESSION['role_ukvac']=="passback"))&&  isset($_SESSION['vac'])){ rectification_deliver_pp(); }else{ logout(); }
				break;
			case 10:
				if(isset($_SESSION['role_ukvac']) &&  isset($_SESSION['vac'])){ get_daily_reconciliation(); }else{ logout(); }
				break;
			case 11:
				if(isset($_SESSION['role_ukvac']) &&  isset($_SESSION['vac'])){ insert_reconcil_intotxt(); }else{ logout(); }
				break;
			case 12:
				if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac'])){ delivered_report(); }else{ logout(); }
				break;
			case 13:
				if(isset($_SESSION['role_ukvac']) && isset($_SESSION['vac'])){ download_excel(); }else{ logout(); }
				break;
			case 14:
				if(isset($_SESSION['role_ukvac']) &&  isset($_SESSION['vac'])){ download_excel_inVAC(); }else{ logout(); }
				break;
			case 15:
				if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac'])){ daily_sales_pwise(); }else{ logout(); }
				break;
			case 16:
				if(isset($_SESSION['role_ukvac']) &&  isset($_SESSION['vac'])){ download_excel_outscan(); }else{ logout(); }
				break;
				
			case 17:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff")||($_SESSION['role_ukvac']=="passback"))&&  isset($_SESSION['vac'])){ add_bts_sales(); }else{ logout(); }
				break;
				
			case 18:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff")||($_SESSION['role_ukvac']=="passback"))&&  isset($_SESSION['vac'])){ mail_send(); }else{ logout(); }
				break;
				
			case 19:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){ outscan_new(); }else{ logout(); }
				break;
				
				
			case 20:
				if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator")&&  isset($_SESSION['vac'])){ delete_email(); }else{ logout(); }
				break;
				
			case 21:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator"))&&  isset($_SESSION['vac'])){ add_email(); }else{ logout(); }
				break;
				
			case 22:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator"))&&  isset($_SESSION['vac'])){ add_service(); }else{ logout(); }
				break;
				
			case 23:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator"))&&  isset($_SESSION['vac'])){ delete_service(); }else{ logout(); }
				break;
	
			case 24:
				if(isset($_SESSION['role_ukvac']) && isset($_SESSION['vac']) ){ daily_submit_report(); }else{ logout(); }
				break;
				
			case 25:
				if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac'])){ daily_sales(); }else{ logout(); }
				break;
				case 26:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){ update_service(); }else{ logout(); }
				break;
	
				case 27:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){ removegwf(); }else{ logout(); }
				break;
				
				
				case 28:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){ updategwf(); }else{ logout(); }
				break;
				
				case 29:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff")||($_SESSION['role_ukvac']=="passback"))&&  isset($_SESSION['vac'])){ putcontent(); }else{ logout(); }
				break;
				
				case 30:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff")||($_SESSION['role_ukvac']=="passback"))&&  isset($_SESSION['vac'])){ email_putcontent(); }else{ logout(); }
				break;
				
				case 31:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){ getglfsavaliability(); }else{ logout(); }
				break;
				
				
				case 33:
				if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac'])){ report_teecount(); }else{ logout(); }
				break;
				
				case 34:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff")||($_SESSION['role_ukvac']=="passback"))&&  isset($_SESSION['vac'])){ auto_reconciliation(); }else{ logout(); }
				break;
				
				case 35:
				if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac'])){ daily_sales_swise(); }else{ logout(); }
				break;
				
				case 36:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff") ||($_SESSION['role_ukvac']=="passback"))&&  isset($_SESSION['vac'])){ get_pp_status(); }else{ logout(); }
				break;
				
				case 37:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){ get_appointment__status(); }else{ logout(); }
				break;
				
				case 38:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){ update_passport_resubmission(); }else{ logout(); }
				break;
				case 39:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){ submit_AO(); }else{ logout(); }
				break;
				case 40:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){ retrive_AO(); }else{ logout(); }
				break;
				
				case 41:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){ mark_as_not_submitted(); }else{ logout(); }
				break;
				
				case 42:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff") ||($_SESSION['role_ukvac']=="passback"))&&  isset($_SESSION['vac'])){get_oyster_status(); }else{ logout(); }
				break;
				
				
				/*case 43: Walkin process merged with AO
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){walk_in_create(); }else{ logout(); }
				break;*/
				
				case 44:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff") ||($_SESSION['role_ukvac']=="passback"))&&  isset($_SESSION['vac'])){check_pending_token(); }else{ logout(); }
				break;
				
				case 45:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator")||($_SESSION['role_ukvac']=="staff"))&&  isset($_SESSION['vac'])){update_bio_submit(); }else{ logout(); }
				break;
				
				case 46:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator"))&&  isset($_SESSION['vac'])){update_approval(); }else{ logout(); }
				break;
				
				case 47:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator"))&&  isset($_SESSION['vac'])){reject_approval(); }else{ logout(); }
				break;
				
				/*case 48:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator"))&&  isset($_SESSION['vac'])){submit_walk_in(); }else{ logout(); }
				break;*/
								
				case 49:
				if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="administrator"))&&  isset($_SESSION['vac'])){approve_walk_in(); }else{ logout(); }
				break;
				
				case 50:
				if(isset($_SESSION['role_ukvac']) &&  isset($_SESSION['vac'])){download_excel_submitted_today(); }else{ logout(); }
				break;
				
				case 51:
				if(isset($_SESSION['role_ukvac']) &&  isset($_SESSION['vac'])){download_excel_bts_pwise(); }else{ logout(); }
				break;
				
				case 52:
				if(isset($_SESSION['role_ukvac']) && isset($_SESSION['vac'])){submit_edit_del_rq(); }else{ logout(); }
				break;
				
				/*case 52:Walkin process merged with AO
				if(isset($_SESSION['role_ukvac']) &&  isset($_SESSION['vac'])){create_ao_walkin(); }else{ logout(); }
				break;
				
				case 53:
				if(isset($_SESSION['role_ukvac']) &&  isset($_SESSION['vac'])){delete_ao_walkin(); }else{ logout(); }
				break;*/
				
				case 54:
				if(isset($_SESSION['role_ukvac']) &&  isset($_SESSION['vac'])){management_set_mission(); }else{ logout(); }
				break;
			}
		
		}
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


function login()
{
	require_once("Classes/login/class.login.php");
	$login_obj = new _login_function();
	$login_obj -> uname= $_REQUEST['name'];
	$login_obj -> pwd = $_REQUEST['data'];
	$login_obj -> mission_select = $_REQUEST['mission_selected'];
	$login_status = $login_obj ->login();
	print json_encode($login_status); 
}

function logout()
{
	//session_destroy();
	require_once("Classes/login/class.login.php");
	$login_obj = new _login_function();
	$logout_status =$login_obj ->__logout();
	if (strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest')
   	{
		print json_encode($logout_status);
	}
	else
	{
		echo "<h4>Session Timed Out, Please Login!</h4>";
		header("Location:index.php");	
	}
}



function add_new_user()
{
	
//	file_put_contents('Sample_files/log.txt', $_REQUEST['mission_name'].PHP_EOL , FILE_APPEND | LOCK_EX);
	//$_REQUEST['mission_name'] send data in => 5  or 5,12 or 5,6,7 format
	require_once("Classes/users/class.users.php");
	$users_obj = new _user_functions();
	$users_obj-> staff_login = $_REQUEST['add_user_uid'];
	$users_obj-> display_name = $_REQUEST['add_user_name'];
	$users_obj-> new_pswd = md5($_REQUEST['add_user_pwd1']);
	$users_obj-> user_role = $_REQUEST['add_user_role'];
	$users_obj-> mission_name = $_REQUEST['mission_name'];
	$users_obj-> dateof_birth = $_REQUEST['dob'];
	$result = $users_obj->add_new_user();
	print json_encode($result);
}


function reset_password_user()
{
	$empid= $_REQUEST['user'];
	$new_password = $_REQUEST['passwd'];
	$password_md5 = MD5($new_password);
	//print json_encode($new_password);
	$query_changestatus = "update users set password = '$password_md5' where user_id = $empid and status = 'active'";
	if(mysql_query($query_changestatus))
	{
		mysql_close();
		print json_encode("changed");
	}
	else
	{
		print json_encode("failed");
	}
}


function delete_staff()
{
	/*$page = $_REQUEST['pn'];
	$empid= $_REQUEST['member_id'];
	$query_changestatus = "update users set status = 'deleted' where user_id = $empid and status = 'active'";
	if(mysql_query($query_changestatus))
	{
		mysql_close();
		$_SESSION['response_user']="User is Deleted";
	    header("Location:users.php?pageno=".$page);
	}
	else
	{
		mysql_close();
		$_SESSION['response_user']="Error occured. Please try again";
	    header("Location:users.php?pageno=".$page);

	}*/
	//$page = $_REQUEST['pn'];
	$empid= $_REQUEST['member_id'];
	$query_changestatus = "update users set status = 'deleted' where user_id = $empid and status = 'active'";
	if(mysql_query($query_changestatus))
	{
		mysql_close();
		print json_encode("success");
		//$_SESSION['response_user']="User is Deleted";
	    //header("Location:users.php?pageno=".$page);
	}
	else
	{
		mysql_close();
		print json_encode("error");
		//$_SESSION['response_user']="Error occured. Please try again";
	    //header("Location:users.php?pageno=".$page);

	}
	
	
}


function deliver_ppt()
{
	$staff_delivered=$_SESSION['uid_ukvac'];
	$choice_del_method = $_REQUEST['choice'];
	date_default_timezone_set("Asia/Bahrain");
	$date_today=date("Y-m-d");
	$time_now=date("h:i:sa");
	$not_valid_cnt=0;
	$success_insertion=0;
	$error_insertion=0;
	for($i=1; $i<21; $i++)
	{
		if(isset($_POST['gwf'.$i]))
		{
			$gwf_deliver=$_REQUEST['gwf'.$i];
			$qr = "update passport_inscan_record set current_status = '".$choice_del_method."', delivered_on ='".$date_today."' , delivered_by ='".$staff_delivered."', delivered_at ='".$time_now."' where gwf_number='".$gwf_deliver."' and mission_id='".$_SESSION['mission_id']."'";
			if(mysql_query($qr))
			{
				$success_insertion++;
			}
			else
			{
				$error_insertion++;
				$msg_error=mysql_error();
			}
		}
		else
		{
			$not_valid_cnt++;

		}
	}
	if($not_valid_cnt>0 && $success_insertion ==0)
	{
			$msg_notvalid = "<div align='center' style='color:red'>No Valid Reference numbers selected.<p></div>";
			$_SESSION['response_delivery']=$msg_notvalid;
	    	header("Location:passport_deliver.php");
	}
	else if($success_insertion>0)
	{
		$msg_success = "<div align='center'>Passport Status Updated<p></div>";
		$_SESSION['response_delivery']=$msg_success;
	 	header("Location:passport_deliver.php");
	}
	else if($error_insertion>0)
	{
		$_SESSION['response_delivery']=$msg_error;
	 	header("Location:passport_deliver.php");
	}
}


function inscan_rectification()
{
	
	//$staff_delivered=$_SESSION['uid_ukvac'];
	$gwf_inscan=$_REQUEST['gwf'];
	date_default_timezone_set("Asia/Bahrain");
	$date_today=date("Y-m-d");
	//$time_now=date("h:i:sa");
	$qr = "update rectification_cancellation set status = 'inscan', inscanned_on ='".$date_today."' where gwf_number='".$gwf_inscan."' and mission_id='".$_SESSION['mission_id']."'";
	if(mysql_query($qr))
	{
		print json_encode("success");
	}
	else
	{
		print json_encode("failed");
	}
}


function outscan_rectification()
{
	$gwf_outscan_rectification = $_REQUEST['gwf'];
	$typeof_submission = $_REQUEST['type'];
	date_default_timezone_set("Asia/Bahrain");
	$date_today=date("Y-m-d");
	//$time_now=date("h:i:sa");
	
	$qr_chk_duplicate="select * from rectification_cancellation where gwf_number = '$gwf_outscan_rectification' and (status ='inscan' or status='outscan') and mission_id='".$_SESSION['mission_id']."'";
	$res_chk_duplicate=mysql_num_rows(mysql_query($qr_chk_duplicate));
	if($res_chk_duplicate==0)
	{
		$qr = "insert into rectification_cancellation values ('NULL','$gwf_outscan_rectification','$date_today','','','','','$typeof_submission','outscan','".$_SESSION['mission_id']."')";
		if(mysql_query($qr))
		{
			print json_encode("success");
		}
		else
		{
			print json_encode("success");
		}
	}
	else
	{
		print json_encode("ref_existing");	
	}
	
}


function rectification_deliver_pp()
{
	$staff_delivered=$_SESSION['uid_ukvac'];
	$choice_del_method = "counter_delivery";
	date_default_timezone_set("Asia/Bahrain");
	$date_today=date("Y-m-d");
	$time_now=date("h:i:sa");
	$gwf_deliver=$_REQUEST['gwf'];
	$qr = "update rectification_cancellation set status = '".$choice_del_method."', delivered_on ='".$date_today."' , delivered_by ='".$staff_delivered."', delivered_at ='".$time_now."' where gwf_number='".$gwf_deliver."' and mission_id='".$_SESSION['mission_id']."'";
	if(mysql_query($qr))
	{
		print json_encode("success");
	}
	else
	{
		print json_encode("failed");
	}
}


function get_daily_reconciliation()
{
	$mission = $_REQUEST['mid'];
	$report_type=$_REQUEST['pp_reports'];
	date_default_timezone_set("Asia/Bahrain");
	$date_today=$_REQUEST['inputField'];
	// Get Inscanned Today
	$qr_get_inscanned_today="select count(*) from passport_inscan_record where date_inscan='$date_today' and mission_id='".$mission."'";
	$res_get_inscanned_today=mysql_fetch_array(mysql_query($qr_get_inscanned_today));
														
	$qr_get_inscanned_today2="select count(*) from rectification_cancellation where inscanned_on='$date_today' and mission_id='".$mission."'";
	$res_get_inscanned_today2=mysql_fetch_array(mysql_query($qr_get_inscanned_today2));			
	//Total Passports Inscanned on the day
	$total_inscanned_today= (int)$res_get_inscanned_today[0]+(int)$res_get_inscanned_today2[0];
						
	//---------------------------------/
						
	//Get Delivered Today - Counter
	$qr_get_delivered_counter="select count(*) from passport_inscan_record where delivered_on='$date_today' and current_status='counter_delivery' and mission_id='".$mission."'";
	$res_get_delivered_counter=mysql_fetch_array(mysql_query($qr_get_delivered_counter));
								
	//$qr_get_delivered_counter2="select count(*) from rectification_cancellation where delivered_on='$date_today' and status='counter_delivery'";
	//echo $qr_get_delivered_counter2."<br>";
	//$res_get_delivered_counter2=mysql_fetch_array(mysql_query($qr_get_delivered_counter2));
	//Total Passports DELIVERED on the day
	//$total_delivered_counter= (int)$res_get_delivered_counter[0]+(int)$res_get_delivered_counter2[0];
	$total_delivered_counter= (int)$res_get_delivered_counter[0];

	//---------------------------------/
						
	//Get Delivered Today-Courier
	$qr_get_delivered_courier="select count(*) from passport_inscan_record where delivered_on='$date_today' and current_status='courier_Service' and mission_id='".$mission."'";
	$res_get_delivered_courier=mysql_fetch_array(mysql_query($qr_get_delivered_courier));
								
	$qr_get_delivered_courier2="select count(*) from rectification_cancellation where delivered_on='$date_today' and status='courier_Service' and mission_id='".$mission."'";
	$res_get_delivered_courier2=mysql_fetch_array(mysql_query($qr_get_delivered_courier2));
	
	
	//Total Passports DELIVERED on the day
	$total_delivered_courier= (int)$res_get_delivered_courier[0]+(int)$res_get_delivered_courier2[0];
						
						
	//---------------------------------/
						
	//Get Total Delivered Today
	$qr_get_delivered_today="select count(*) from passport_inscan_record where delivered_on='$date_today' and mission_id='".$mission."'";
	$res_get_delivered_today=mysql_fetch_array(mysql_query($qr_get_delivered_today));
								
	$qr_get_delivered_today2="select count(*) from rectification_cancellation where delivered_on='$date_today' and mission_id='".$mission."'";
	$res_get_delivered_today2=mysql_fetch_array(mysql_query($qr_get_delivered_today2));
	//Total Passports DELIVERED on the day
	$total_delivered_today= (int)$res_get_delivered_today[0]+(int)$res_get_delivered_today2[0];
						
						
						
	//---------------------------------/
						
	//Get Remaining PPTs in VAC
	$qr_get_ppts_inVAC="select count(*) from passport_inscan_record where current_status='inscan' and mission_id='".$mission."'";
	$res_get_ppts_inVAC=mysql_fetch_array(mysql_query($qr_get_ppts_inVAC));
							
								
	$qr_get_ppts_inVAC2="select count(*) from rectification_cancellation where status='inscan' and mission_id='".$mission."'";
	$res_get_ppts_inVAC2=mysql_fetch_array(mysql_query($qr_get_ppts_inVAC2));
						
	//Total Passports Remains in VAC
	$total_ppts_inVAC= (int)$res_get_ppts_inVAC[0]+(int)$res_get_ppts_inVAC2[0];
												
	//---------------------------------/
						
	//Get Brought Forward Passport Count
	$qr_get_ppts_BFwd="select count(*) from passport_inscan_record where current_status='inscan' and date_inscan < '$date_today' and mission_id ='".$mission."'";
	$res_get_ppts_BFwd=mysql_fetch_array(mysql_query($qr_get_ppts_BFwd));
							
								
	$qr_get_ppts_BFwd2="select count(*) from rectification_cancellation where status='inscan' and inscanned_on < '$date_today' and mission_id='".$mission."'";
	$res_get_ppts_BFwd2=mysql_fetch_array(mysql_query($qr_get_ppts_BFwd2));


	// Get count of passports in vac yesterday, but delivered today!
	$qr_inscanned_delivered_today="select count(*) from passport_inscan_record where delivered_on >='$date_today' and date_inscan < '$date_today' and mission_id='".$mission."'";
	$res_inscanned_delivered_today=mysql_fetch_array(mysql_query($qr_inscanned_delivered_today));
								
	$qr_inscanned_delivered_today2="select count(*) from rectification_cancellation where delivered_on >='$date_today' and inscanned_on < '$date_today' and mission_id='".$mission."'";
	$res_inscanned_delivered_today2=mysql_fetch_array(mysql_query($qr_inscanned_delivered_today2));
	
	//Total Passports DELIVERED on the day which forwaded from yesterday
	$total_inscanned_delivered_today= (int)$res_inscanned_delivered_today[0]+(int)$res_inscanned_delivered_today2[0];

	//Total Passports Brought Forward
	$total_ppts_BFwd= (int)$res_get_ppts_BFwd[0]+(int)$res_get_ppts_BFwd2[0]+$total_inscanned_delivered_today;
	
	
	//Total Passports carried Over to Next Day
	$qr_get_ppts_co="select count(*) from passport_inscan_record where current_status='inscan' and date_inscan <= '$date_today' and mission_id ='".$mission."'";
	$res_get_ppts_co=mysql_fetch_array(mysql_query($qr_get_ppts_co));
							
								
	$qr_get_ppts_co2="select count(*) from rectification_cancellation where status='inscan' and inscanned_on <= '$date_today' and mission_id='".$mission."'";
	$res_get_ppts_co2=mysql_fetch_array(mysql_query($qr_get_ppts_co2));


	//Total Passports carried Over to Next Day
	$qr_inscanned_co_today="select count(*) from passport_inscan_record where delivered_on >'$date_today' and date_inscan <= '$date_today' and mission_id='".$mission."'";
	$res_inscanned_co_today=mysql_fetch_array(mysql_query($qr_inscanned_co_today));
								
	$qr_inscanned_co_today2="select count(*) from rectification_cancellation where delivered_on >'$date_today' and inscanned_on <= '$date_today' and mission_id='".$mission."'";
	$res_inscanned_co_today2=mysql_fetch_array(mysql_query($qr_inscanned_co_today2));
	
	
	//Total Passports Brought Forward
	$total_co = (int)$res_get_ppts_co[0]+(int)$res_get_ppts_co2[0]+(int)$res_inscanned_co_today[0]+(int)$res_inscanned_co_today2[0];
	
	 
	
	
	$_SESSION['date_selected'] = $date_today;
	$_SESSION['total_ppts_BFwd'] = $total_ppts_BFwd;
	$_SESSION['inscanned_today']=$res_get_inscanned_today[0];
	$_SESSION['inscanned_today_recti']=$res_get_inscanned_today2[0];
	$_SESSION['total_inscanned_today'] = $total_inscanned_today;
	$_SESSION['total_delivered_counter']=$total_delivered_counter;
	$_SESSION['total_delivered_courier']=$total_delivered_courier;
	$_SESSION['total_rectification_del_today']=$res_get_delivered_today2[0];
	$_SESSION['total_delivered_today']=$total_delivered_today;
	$_SESSION['total_ppts_inVAC']=$total_ppts_inVAC;
	$_SESSION['total_CarriedO'] = $total_co;
	$link = "open";
	print(json_encode($link));
	
	//$url='passport_delivery_reports.php';
   	/*$link = "<script>window.open('passport_delivery_reports.php', '_self')</script>";
	echo $link;*/
}

function delivered_report()
{
	unset($_SESSION['date_for_report']);
	unset($_SESSION['date_for_report1']);
	$date_selected = $_REQUEST['inputField'];
	$date_selected1 = $_REQUEST['inputField1'];
	$_SESSION['date_for_report']= $date_selected;
	$_SESSION['date_for_report1']= $date_selected1;
	$link = "open";
	print(json_encode($link));
}


function download_excel()
{
		$dt_select = $_SESSION['date_for_report'];
		$dt_select1 = $_SESSION['date_for_report1'];
		$mission_id = $_REQUEST['mid'];
		$qr="SELECT gwf_number, date_outscan,date_inscan,delivered_on ,delivered_at ,delivered_by ,current_status FROM passport_inscan_record WHERE (current_status = 'counter_delivery' or current_status = 'courier_Service') and delivered_on >= '$dt_select'  and delivered_on <= '$dt_select1' and mission_id='".$mission_id."' order by delivered_on";
						
		/*******EDIT LINES 3-8*******/  
		$DB_TBLName = "passport_inscan_record"; //MySQL Table Name   
		$filename = "Passport_Delivery_detils_".date('Ymd');         //File Name
		/*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
		$result = mysql_query($qr) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
		$file_ending = "xls";
		//header info for browser
		header("Content-Type: application/xls");    
		header("Content-Disposition: attachment; filename=$filename.xls");  
		header("Pragma: no-cache"); 
		header("Expires: 0");
		$sep = "\t"; //tabbed character
		//start of printing column names as names of MySQL fields
		for ($i = 0; $i < mysql_num_fields($result); $i++) 
		{
			echo mysql_field_name($result,$i) . "\t";
		}
		print("\n");    
		//end of printing column names  
		//start while loop to get data
		while($row = mysql_fetch_row($result))
		{
			$schema_insert = "";
			for($j=0; $j<mysql_num_fields($result);$j++)
			{
				if(!isset($row[$j]))
				{
					$schema_insert .= "NULL".$sep;
				}
				elseif ($row[$j] != "")
				{
					if($j==6)//if column=priority
					{
						if($row[$j]=="counter_delivery")
						{
							$schema_insert .= "Counter Delivery".$sep;
						}
						else if($row[$j]=="courier_Service")
						{
							$schema_insert .= "Courier Delivery".$sep;
						}
						else
						{
							$schema_insert .= "".$sep;
						}
					}
					else
					{
						$schema_insert .= "$row[$j]".$sep;
					}
				}
				else
				{
					$schema_insert .= "".$sep;
				}
			}
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\t";
			print(trim($schema_insert));
			print "\n";
			//return;
		}   
}

function download_excel_inVAC()
{
		$mission_id = $_REQUEST['mid'];
	//$dt_select = $_SESSION['date_for_report'];
		$qr="SELECT gwf_number , date_outscan ,date_inscan, DATEDIFF(CURDATE(),date_inscan)+1 AS days FROM passport_inscan_record WHERE current_status = 'inscan' and mission_id='".$mission_id."' order by date_inscan  ASC";
				
		$qr_rect="SELECT gwf_number , outscan_date ,inscanned_on, DATEDIFF(CURDATE(),inscanned_on)+1 AS days  FROM rectification_cancellation WHERE status = 'inscan' and mission_id='".$mission_id."' order by inscanned_on  ASC";		
		/*******EDIT LINES 3-8*******/  
		//$DB_TBLName = "passport_inscan_record"; //MySQL Table Name   
		$filename = "Passport_Remains_In_VAC_".date('Ymd').".xls";         //File Name
		/*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
		$result = mysql_query($qr) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno()); 
		while($row = mysql_fetch_assoc($result))
		{
			$json1[] = $row;
		}
		
		$result_rect = mysql_query($qr_rect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());  
		while($row_rect = mysql_fetch_assoc($result_rect))
		{
			$json2[] = $row_rect;
		}
			
		$csv_fields=array();

		$csv_fields[] = 'Reference Number';
		$csv_fields[] = 'Outscanned On';
		$csv_fields[] = 'Inscanned On';	
		$csv_fields[] = 'Total Days In VAC';
			
		header("Content-Disposition: attachment; filename=\"".$filename."\"");
		header("Content-Type: application/vnd.ms-excel;");
		header("Pragma: no-cache");
		header("Expires: 0");
		$out = fopen("php://output", 'a');
		
		fputcsv($out, $csv_fields,"\t");
		
		foreach ($json1 as $data)
		{
			fputcsv($out, $data,"\t");
		}
		foreach ($json2 as $data)
		{
			fputcsv($out, $data,"\t");
		}
		fclose($out);
		}



function download_excel_outscan()
{
		$mission_id = $_REQUEST['mid'];
		$qr="SELECT gwf_number, date_outscan FROM passport_inscan_record WHERE current_status = 'outscan' and mission_id='".$mission_id."' order by date_inscan  ASC";
						
		/*******EDIT LINES 3-8*******/  
		$DB_TBLName = "passport_inscan_record"; //MySQL Table Name   
		$filename = "Passport_remains_in_embassy_".date('Ymd');         //File Name
		/*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
		$result = mysql_query($qr) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
		$file_ending = "xls";
		//header info for browser
		header("Content-Type: application/xls");    
		header("Content-Disposition: attachment; filename=$filename.xls");  
		header("Pragma: no-cache"); 
		header("Expires: 0");
		$sep = "\t"; //tabbed character
		//start of printing column names as names of MySQL fields
		for ($i = 0; $i < mysql_num_fields($result); $i++) 
		{
			echo mysql_field_name($result,$i) . "\t";
		}
		print("\n");    
		//end of printing column names  
		//start while loop to get data
		while($row = mysql_fetch_row($result))
		{
			$schema_insert = "";
			for($j=0; $j<mysql_num_fields($result);$j++)
			{
				if(!isset($row[$j]))
				{
					$schema_insert .= "NULL".$sep;
				}
				elseif ($row[$j] != "")
				{
					$schema_insert .= "$row[$j]".$sep;
				}
				else
				{
					$schema_insert .= "".$sep;
				}
			}
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\t";
			print(trim($schema_insert));
			print "\n";
			//return;
		}   
}

function add_bts_sales()
{
	$applicant_name =$_REQUEST['nme'];
	$applcnt_phone = $_REQUEST['no'];
	$staff_id = $_SESSION['user_id_ukvac'];
	$date_today = date('Y-m-d');
	$currecy = $_REQUEST['curr'];
	$qr_insert_sale="";
	$udxarr = json_decode(str_replace('\\', '', $_POST['dt']));
	$get_id=mysql_fetch_array(mysql_query("select max(id) as max_id from bts_sales"));
	$new_id = $get_id['max_id']+1;
	for($i=2; $i< count($udxarr); $i++)
	{
		if($udxarr[$i]!="")
		{
			$eachcolumnineachrow = explode("|",$udxarr[$i]);
			$eachcolumnineachrow1 = round($eachcolumnineachrow[1],2);
			$qr_insert_sale = $qr_insert_sale. " ('".$new_id."',$staff_id,'$eachcolumnineachrow[0]',$eachcolumnineachrow[3],'$date_today',$eachcolumnineachrow1,$eachcolumnineachrow[2],'$applicant_name','$applcnt_phone','".$_SESSION['mission_id']."','".$currecy."'),";
		}
		if (array_key_exists(4,$eachcolumnineachrow))
		{
			//Update Oyster Inventory
			$update_oy_card=mysql_query("update oyster_card_stock set sale_id= '$new_id' , status='sold' where card_no='".$eachcolumnineachrow[4]."' and mission_id='".$_SESSION['mission_id']."'");
		}
		$new_id++;
		
	}
	$fullqry= "insert into bts_sales values ".$qr_insert_sale;
	$fullqry1= rtrim($fullqry, ",");
	//$file = "new.txt";
	//file_put_contents($file, $fullqry1);
	//echo $fullqry1;
	if(mysql_query($fullqry1))
	{
		print json_encode("inserted");
	}
	else
	{
		print json_encode(mysql_error());
	}

}


function mail_send()
{
	$currencies = $_REQUEST['cr'];
	$msg="";
	$html = $_POST['htmlformail'];
	//file_put_contents('Sample_files/consolidated_report.txt',$html."<<<<<<Test");
	if (preg_match('/,/',$currencies))
    {
    	$currency = explode(",",$currencies);
	}
	else
	{
     	$currency[0]=$currencies;
	}
	
	$qs="select count(*) as totalcount from passport_inscan_record where date_outscan='".date('Y-m-d')."' and application_taken_by='".$_SESSION['uid_ukvac']."' and current_status='outscan' and mission_id='".$_SESSION['mission_id']."'";
	$qry_total_taken = mysql_query($qs);
	$totalmyapplnts = mysql_fetch_array($qry_total_taken);
	if( count( array_filter( $totalmyapplnts)) == 0)
	{
		$totalmyapplnts[0]='0';
	}
	
                    
    $staffinfo = '<table style="width: 800px;"><tr><td><table style="width: 756px; height: 80px; border: 1px #000000 !important; border-collapse:collapse; border-spacing: 0; background-color: transparent;">
		<tr >
			<td valign="middle" style="border-top: 0px; font-weight: bold;">
				<p>
				<span style="font-size: 14px; color:#31708f;">&nbsp;&nbsp;&nbsp;Name : </span>
				<span style="font-size: 14px; color:#8a6d3b;" >'.
					$_SESSION['display_name_ukvac'].'
				</span>
				</p>
			</td>
			<td valign="middle" style="border-top: 0px; font-weight: bold;">
				<p>
				<span style="font-size: 14px; color:#31708f;">&nbsp;&nbsp;&nbsp;Date :</span>
				<span style="font-size: 14px; color:#8a6d3b;" >'.date('d-F-Y').'</span>
				</p>
			</td>
			<td valign="middle" style="vertical-align: central; border-top: 0px; font-weight: bold;">
				<p>
				<span style="font-size: 14px; color:#31708f;">&nbsp;&nbsp;&nbsp;Applications Taken :</span>
				<span style="font-size: 14px; color:#8a6d3b;">'.$totalmyapplnts[0].'</span>
				</p>
			</td>
		</tr>
		<tr>
        	<td valign="middle" style="border-top: 0px; font-weight: bold;">
           		<span style="font-size: 14px; color:#31708f;">&nbsp;&nbsp;&nbsp;Location :</span>
               	<span style="font-size: 14px; color:#8a6d3b;">'.$_SESSION['vac'].' VAC - '.$_SESSION['vac_city'].','.$_SESSION['vac_country'].'</span>
           	</td>
   		</tr>
	</table>';
	
	
	foreach($currency as $key=> $value)
    {
		$disabled = explode(",",$_SESSION['disabled_string']);
		$get_currenct_notes = mysql_fetch_array(mysql_query("select notes from note_values where currency='".$value."'"));
		$notes = preg_replace('/\s+/', '', $get_currenct_notes['notes']);
		if (preg_match('/,/',$notes))
		{
			$notes_each = explode(",",$notes);
		}
		else
		{
			$notes_each[0]=$notes;
		}
		
		if(in_array("1000",$notes_each))
		{
			$count_1000=$_REQUEST[$value.'1000s'];
			$str1000 = "<tr>
		<td width='109' valign='top'><p align='right'><strong>1000X&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='150' valign='top'><p>&nbsp;".$count_1000."</p></td>
		<td width='138' valign='top'><p>&nbsp;".($count_1000*1000) ."</p></td>
	  </tr>";
		}
		else
		{
			$count_1000=0;
			$str1000 ="";
		}
		if(in_array("500",$notes_each))
		{
			$count_500=$_REQUEST[$value.'500s'];
			$str500 = "<tr>
		<td width='109' valign='top'><p align='right'><strong>500X&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='150' valign='top'><p>&nbsp;".$count_500."</p></td>
		<td width='138' valign='top'><p>&nbsp;".($count_500*500) ."</p></td>
	  </tr>";
		}
		else
		{
			$count_500=0;
			$str500="";
		}
		if(in_array("100",$notes_each))
		{
			$count_100=$_REQUEST[$value.'100s'];
			$str100 = "<tr>
		<td width='109' valign='top'><p align='right'><strong>100X&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='150' valign='top'><p>&nbsp;".$count_100."</p></td>
		<td width='138' valign='top'><p>&nbsp;".($count_100*100) ."</p></td>
	  </tr>";
		}
		else
		{
			$count_100=0;
			$str100="";
		}
		if(in_array("50",$notes_each))
		{
			$count_50=$_REQUEST[$value.'50s'];
			$str50 = "<tr>
		<td width='109' valign='top'><p align='right'><strong>50X&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='150' valign='top'><p>&nbsp;".$count_50."</p></td>
		<td width='138' valign='top'><p>&nbsp;".($count_50*50) ."</p></td>
	  </tr>";
		}
		else
		{
			$count_50=0;
			$str50="";
		}
		if(in_array("20",$notes_each))
		{
			$count_20=$_REQUEST[$value.'20s'];
			$str20 = "<tr>
		<td width='109' valign='top'><p align='right'><strong>20X&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='150' valign='top'><p>&nbsp;".$count_20."</p></td>
		<td width='138' valign='top'><p>&nbsp;".($count_20*20) ."</p></td>
	  </tr>";
		}
		else
		{
			$count_20=0;
			$str20="";
		}
		if(in_array("10",$notes_each))
		{
			$count_10=$_REQUEST[$value.'10s'];
			$str10 = "<tr>
		<td width='109' valign='top'><p align='right'><strong>10X&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='150' valign='top'><p>&nbsp;".$count_10."</p></td>
		<td width='138' valign='top'><p>&nbsp;".($count_10*10)."</p></td>
	  </tr>";
		}
		else
		{
			$count_10=0;
			$str10="";
		}
		if(in_array("5",$notes_each))
		{
			$count_5=$_REQUEST[$value.'5s'];
			$str5 = "<tr>
		<td width='109' valign='top'><p align='right'><strong>5X&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='150' valign='top'><p>&nbsp;".$count_5."</p></td>
		<td width='138' valign='top'><p>&nbsp;".($count_5*5)."</p></td>
	  </tr>";
		}
		else
		{
			$count_5=0;
			$str5="";
		}
		if(in_array("1",$notes_each))
		{
			$count_1=$_REQUEST[$value.'1s'];
			$str1cn = "<tr>
		<td width='109' valign='top'><p align='right'><strong>1X&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='150' valign='top'><p>&nbsp;".$count_1."</p></td>
		<td width='138' valign='top'><p>&nbsp;".($count_1*1)."</p></td>
	  </tr>";
		}
		else
		{
			$count_1=0;
			$str1cn="";
		}
		if(in_array("0.500",$notes_each))
		{
			$count_p500=$_REQUEST[$value.'p500s'];
			$strp500 = "<tr>
		<td width='109' valign='top'><p align='right'><strong>0.500X&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='150' valign='top'><p>&nbsp;".$count_p500."</p></td>
		<td width='138' valign='top'><p>&nbsp;".($count_p500*0.500)."</p></td>
	  </tr>";
		}
		else
		{
			$count_p500=0;
			$strp500="";
		}
		if(in_array("0.100",$notes_each))
		{
			$count_p100=$_REQUEST[$value.'p100s'];
			$strp100 = "<tr>
		<td width='109' valign='top'><p align='right'><strong>0.100X&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='150' valign='top'><p>&nbsp;".$count_p100."</p></td>
		<td width='138' valign='top'><p>&nbsp;".($count_p100*0.100)."</p></td>
	  </tr>";
		}
		else
		{
			$count_p100=0;
			$strp100="";
		}
		if(in_array("0.50",$notes_each))
		{
			$count_p50=$_REQUEST[$value.'p50s'];
			$strp50 = "<tr>
		<td width='109' valign='top'><p align='right'><strong>0.50X&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='150' valign='top'><p>&nbsp;".$count_p50."</p></td>
		<td width='138' valign='top'><p>&nbsp;".($count_p50*0.050)."</p></td>
	  </tr>";
		}
		else
		{
			$count_p50=0;
			$strp50 = "";
		}
		
		//$count_20=$_REQUEST[$value.'20s'];
		//$count_10=$_REQUEST[$value.'10s'];
		//$count_5=$_REQUEST[$value.'5s'];
		//$count_1=$_REQUEST[$value.'1s'];
		//$count_500=$_REQUEST[$value.'500s'];
		//$count_100=$_REQUEST[$value.'100s'];
		//echo "Value--".$count_20;
		//echo "Value--".$count_10;
		//echo "Value--".$count_5;
		//echo "Value--".$count_1;
	
	$getbtsnames = "select * from price_list where current_status='active' and mission_id='".$_SESSION['mission_id']."' and currency = '".$value."'";
	$res=mysql_query($getbtsnames);

	$total_cash_in_hand = ($count_1000*1000)+($count_500*500)+($count_100*100)+($count_50*50)+($count_20*20)+($count_10*10)+($count_5*5)+($count_1*1)+($count_p500*0.500)+($count_p100*0.100)+($count_p50*0.050);
	
	$str_hdr= 
	"<table width='756' border='1' cellpadding='0' cellspacing='0'>
	<tr>
                            	<td align='center'><span style='font-weight: 600; font-size: 16px; color: #31708f;'>Note Value</span></td>
                            	<td align='center'><span style='font-weight: 600; font-size: 16px; color: #31708f;'>Note Counts</span></td>
                            	<td align='center'><span style='font-weight: 600; font-size: 16px; color: #31708f;'>Total</span></td>
                          	</tr>";
	  
	  $footer = "<tr>
		<td width='397' colspan='3' valign='top'><p>&nbsp;</p></td>
	  </tr>
	  <tr>
		<td width='259' valign='top'><p align='right'><strong>Total Amount (in ".$value.") :&nbsp;&nbsp;&nbsp;</strong></p></td>
		<td width='138' colspan='2' valign='top'><p>&nbsp;".$total_cash_in_hand."</p></td>
	  </tr>
	  
	</table>";
	
	$str1 = $str_hdr.$str1000.$str500.$str100.$str50.$str20.$str10.$str5.$str1cn.$strp500.$strp100.$strp50.$footer;
	
	$str2_1= "<p style='height: 20px;'></p><br><br><table width='756' border='1' cellpadding='0' cellspacing='0'><tr><td width='187' valign='top'><p align='center'><span style='font-weight: 600; font-size: 16px; color: #31708f;'>Category</span></p></td><td width='114' valign='top'><p align='center'><span style='font-weight: 600; font-size: 16px; color: #31708f;'>Count</span></p></td><td width='150' valign='top'><p align='center'><span style='font-weight: 600; font-size: 16px; color: #31708f;'>Total Amount(".$value.")</span></p></td></tr>";
	
	
	$str2_2="";
	$myid = $_SESSION['user_id_ukvac'];
	$grant_total_amount_VAS=0;
	$get_total_each = mysql_query("select dve.id as id, dve.user_id as user_id, dve.date as date,dve.vas_id as vas_id ,dve.total as total,dve.mission_id as mission_id from daily_vas_entries_total dve, price_list pl where dve.user_id='$myid' and dve.date='".date('Y-m-d')."' and dve.mission_id='".$_SESSION['mission_id']."' and dve.vas_id=pl.id and pl.currency='".$value."'");
	while($get_vas_total_for_day=mysql_fetch_array($get_total_each))
	{
		$vas_name_and_price=mysql_fetch_array(mysql_query("select * from price_list where id='".	$get_vas_total_for_day['vas_id']."' and mission_id='".$_SESSION['mission_id']."'"));
		$vas_id=$vas_name_and_price['id'];
		$vas_name = $vas_name_and_price['service_name'];
		$vas_price = $vas_name_and_price['amount'];
		$total_vas_count = $get_vas_total_for_day['total'];
		$total_vas_amount = $vas_price*$total_vas_count;
		$grant_total_amount_VAS = $grant_total_amount_VAS+$total_vas_amount;				  
				  
		$str2_2=$str2_2."<tr><td width='219' valign='top' style='padding-right: 10px;'><p align='right'><strong>". 	$vas_name ."&nbsp; (<label>". $vas_price."</label>&nbsp;".$value.")</strong></p></td><td width='186' valign='top' style='padding-left: 10px;'><p>". $total_vas_count."</p></td><td width='130' valign='top'><p>&nbsp;<label style='font-weight:bold; padding-left: 10px;' >".$total_vas_amount."</label>&nbsp;</p></td></tr>";
	}
	if(!in_array('bts.php',$disabled))
	{
		$get_exchange_rate = mysql_fetch_array(mysql_query("select exchng_rate_wt_gbp from exchange_rates where currency ='".$value."'"));
		$exchange_rate = $get_exchange_rate['exchng_rate_wt_gbp'];
		
		$grand_total_bts=(float)0;
		$getbts_sales_today = "select quantity_sold*gbp_price as total_amount,date_sold,user_id from bts_sales where user_id=$myid and date_sold='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency = '".$value."'";
		$q_ftch = mysql_query($getbts_sales_today);
		while($res_getbts_sales_today= mysql_fetch_array($q_ftch))
		{
			$ttl_roundamount = round($res_getbts_sales_today['total_amount']*$exchange_rate);
			$grand_total_bts=$grand_total_bts+ $ttl_roundamount;
		}
	}
	else
	{
		$grand_total_bts=0;
	}
	
	$total_cash_per_system = $grant_total_amount_VAS+$grand_total_bts;
	
	if(!in_array('bts.php',$disabled))
	{
		$str2_3= "<tr><td width='219' valign='top' style='padding-right: 10px;'><p align='right'><strong>Britain Travel Shop Products</strong></p></td><td width='186' valign='top' style='padding-left: 10px;'><p></p></td><td width='130' valign='top' style='padding-left: 10px;'><p><b><label style='font-weight:bold;' >". $grand_total_bts."</label></b></p></td></tr><tr><td width='301' colspan='2' valign='top' style='padding-right: 10px;'><p align='right'><strong>Total Amount (in ".$value.")</strong></p></td><td width='150' valign='top' style='padding-left: 10px;'><b>&nbsp;".$total_cash_per_system."</b></td></tr></table>";
	}
	else
	{
		$str2_3= "<tr><td width='301' colspan='2' valign='top' style='padding-right: 10px;'><p align='right'><strong>Total Amount (in ".$value.")</strong></p></td><td width='150' valign='top' style='padding-left: 10px;'><b>&nbsp;".$total_cash_per_system."</b></td></tr></table>";
	}
	
				   
					
	$difference_in_cash = round(($total_cash_in_hand- $total_cash_per_system),2);
	if($difference_in_cash > 0)
	{
		$mesg = "<div align='center' style='font-weight: bold; color: red;'><p>There Is An Excess Amount of ".$difference_in_cash." ".$value.".</p></div>";
	}
	else if($difference_in_cash < 0)
	{
		$mesg = "<div align='center' style='font-weight: bold; color: red;'><p>There Is A Shortage Amount of ".$difference_in_cash." ".$value." .</p></div>";
	}
	else if($difference_in_cash == 0)
	{
		$mesg = "<div align='center' style='font-weight: bold; color: green;'><p>The Cash In Hand is Tallying.</p></div>";
	}
	
	$mesg.= "<span style='font-size: 16px;font-weight: bold; color:#4d3319;'><u>Comments</u></span><br><p><span style='font-size: 14px;font-weight: normal; color:#86592d;'>".$_REQUEST[$value.'comments']."</span>";
	
	if(!in_array('bts.php',$disabled))
	{
		$datetoday = date('Y-m-d');
		$user_id = $_SESSION['user_id_ukvac'];
		$allbts_sales_today = "select quantity_sold, gbp_price, quantity_sold*gbp_price as total_amount,product_name from bts_sales where user_id='$user_id' and date_sold='$datetoday' and mission_id='".$_SESSION['mission_id']."' and currency = '".$value."'";
		//echo $allbts_sales_today."<br>";
		if(mysql_num_rows(mysql_query($allbts_sales_today)) >0)
		{
		
		$str4 = "<p style='text-align: center; font-size: 14px; color: blue; font-weight: bold; padding: 10px;'>&nbsp; Travel Product's List &nbsp;</p><table width='100%' border='1' cellpadding='0' cellspacing='0' id='t3'><tr><td width='5%' valign='top'><p align='center'><strong>Sl. No</strong></p></td><td width='30%' valign='top'><p align='center'><strong>Product Name</strong></p></td><td width='10%' valign='top'><p align='center'><strong>Quantity Sold</strong></p></td><td width='10%' valign='top'><p align='center'><strong>Unit Price (".$value.")</strong></p></td><td width='10%' valign='top'><p align='center'><strong>Total Amount(".$value.")</strong></p></td></tr>";
		$str4_1="";
		
		$slno=0;
		$res_allsales = mysql_query($allbts_sales_today);
		while($res1_allsales = mysql_fetch_array($res_allsales))
		{
			$slno++;
			$str4_1=$str4_1."<tr><td valign='top' style='text-align:left; padding: 5px;'>".$slno."</td><td valign='top' style='text-align:left; padding: 5px;'>". $res1_allsales['product_name']."</td><td valign='top' style='text-align:left; padding: 5px;'>". $res1_allsales['quantity_sold']."</td><td valign='top' style='text-align:left; padding: 5px;'>".$res1_allsales['gbp_price']*$exchange_rate."</td><td valign='top' style='text-align:left; padding: 5px;'>". round($res1_allsales['total_amount']*$exchange_rate)."</td></tr>";
		}
					
		$str4_2="</table><p>&nbsp;</p>";
		}
		else
		{
			$str4 = "";
			$str4_1="";
			$str4_2 ="";
		}
		$fulltablebtsdetails = $str4.$str4_1.$str4_2;
	}
	else
	{
		$fulltablebtsdetails="";
	}
	
	
			$myid = $_SESSION['user_id_ukvac'];
			$grant_total = ($count_1000*1000)+($count_500*500)+($count_100*100)+($count_50*50)+($count_20*20)+($count_10*10)+($count_5*5)+($count_1*1)+($count_p500*0.500)+($count_p100*0.100)+($count_p50*0.050);
		
		
			$grant_total_per_submissions = $total_cash_per_system;
		
		
			$qr_chksubmitted = "select * from daily_consolidated_submissions where employee_id ='".$myid."' and date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency = '".$value."'";
			//echo $qr_chksubmitted;
			$rowexist = mysql_num_rows(mysql_query($qr_chksubmitted));
			if($rowexist >0)
			{
				$removeoldexisting = "delete from daily_consolidated_submissions where employee_id ='".$myid."' and date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."'";
				if(mysql_query($removeoldexisting))
				{
					$qry_insertcounts = "insert into daily_consolidated_submissions values (DEFAULT,'".date('Y-m-d')."','$myid',".$count_1000.",".$count_500.",".$count_100.",".$count_50.",".$count_20.",".$count_10.",".$count_5.",".$count_1.",".$count_p500.",".$count_p100.",".$count_p50.",".$grant_total.",".$grant_total_per_submissions.",'".$_REQUEST[$value.'comments']."','".$_SESSION['mission_id']."','".$value."')";
					mysql_query($qry_insertcounts);
				}
				else
				{
					break;
				}
						
			}
			else
			{
				$qry_insertcounts ="insert into daily_consolidated_submissions values (DEFAULT,'".date('Y-m-d')."','$myid',".$count_1000.",".$count_500.",".$count_100.",".$count_50.",".$count_20.",".$count_10.",".$count_5.",".$count_1.",".$count_p500.",".$count_p100.",".$count_p50.",".$grant_total.",".$grant_total_per_submissions.",'".$_REQUEST[$value.'comments']."','".$_SESSION['mission_id']."','".$value."')";
				
				//"insert into daily_consolidated_submissions values (DEFAULT,'".date('Y-m-d')."','$myid',".$_REQUEST[$value.'20s'].",".$_REQUEST[$value.'10s'].",".$_REQUEST[$value.'5s'].",".$_REQUEST[$value.'1s'].",".$_REQUEST[$value.'500s'].",".$_REQUEST[$value.'100s'].",".$grant_total.",".$grant_total_per_submissions.",'".$_REQUEST[$value.'comments']."','".$_SESSION['mission_id']."','".$value."')";
				mysql_query($qry_insertcounts);
		
			}
			$heading = "<hr><span align='center' style='font-weight: bold; color: blue; font-weight: 16px;'><p><u>Cash Tally Sheet -(Currency - ".$value.")  :</u></p><p></p><p></p></span>";
			/*$chk = "<script> checkequal('".$value."'); </script>";
			str_replace($chk,"",$html);*/
			$msg= $msg.$heading."<p style='padding: 15px;'></p>".$str1."<p style='padding: 15px;'></p>".$str2_1.$str2_2.$str2_3."<p style='padding: 15px;'></p>".$mesg.$fulltablebtsdetails."<p style='padding: 15px'></p><p style='padding: 15px'></p></td>";
	}
			
	
	
	
	
	$recievers=""; 
	//$msg= "<p style='padding: 15px;'></p>".$html;
	
	//echo '<pre>';
	///echo htmlspecialchars($msg."<br>--------<br>");
	//echo '</pre>';
			
			
			
	$headers = "MIME-Version: 1.0" . "\r\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 
	$headers .= "From: simon@aletimad.com" . "\r\n"; 
			
	$reciver_emails="";
	$get_rcvr = "select email_address from email_receivers where cash_sheet_mail='1' and mission_id='".$_SESSION['mission_id']."'";
	$get_rcvr1=mysql_query($get_rcvr);
	$cnt_emails=mysql_num_rows($get_rcvr1);
	if($cnt_emails >0)
	{
		while($mailids = mysql_fetch_array($get_rcvr1))
		{
			$reciver_emails=$reciver_emails .",".$mailids['email_address'];
		}
		$reciver_emails = ltrim($reciver_emails,',');
		if(mail($reciver_emails,"Cash Sheet-".$_SESSION["display_name_ukvac"]."- ".$_SESSION['vac']." VAC - ".$_SESSION['vac_city'].",".$_SESSION['vac_country']." -".date('d-m-Y')." - ".$value.":" ,$staffinfo.$msg."</tr></table>",$headers))
		{
			//include('display_consolidated_report.php');
			$_SESSION['response_ukvac']="emailsent";
			header("Location:cash_tally_sheet.php");
					
		}
		else
		{
			$_SESSION['response_ukvac']="emailsentfailed";
			header("Location:cash_tally_sheet.php");
		
		}
	}
	else
	{
		$_SESSION['response_ukvac']="noreceivers";
		header("Location:cash_tally_sheet.php");
	}
	
}


function httpPost($url)
	{
	  
	 
		$ch = curl_init();  
	 
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_HEADER, false); 
	 
		$output=curl_exec($ch);
	 
		curl_close($ch);
		return $output;
	 
	}


function outscan_new()
{
	$msg ="";
	$wrong_status = "";
	$myid = $_SESSION['uid_ukvac'];					
	$gwf = strtoupper($_REQUEST['gwf']);
	$frm = $_REQUEST['frm_number'];
	$glfs = strtoupper($_REQUEST['glfs']);
	date_default_timezone_set("Asia/Bahrain");
	$date_today=date("d-m-Y");
	$date_of_outscan=date("Y-m-d");
	$user_id = $_SESSION['user_id_ukvac'];
	$mission_id=$_SESSION['mission_id'];
	$disabled = explode(",",$_SESSION['disabled_string']);
	$vas_selected = $_REQUEST['vas'];
	
	//Update appointments if it is an FRM number
	if($frm!="no_frm")
	{
		$update_appointment_table = mysql_query("update appointment_schedule set reference_number='".$gwf."' where reference_number = '".$frm."' and application_status = 'shown_up' and mission_id='".$_SESSION['mission_id']."'");
	}
	
	//check if appointment is premium
	if(!in_array('manage_tees.php',$disabled))
	{
		$get_app_type = mysql_fetch_array(mysql_query("select actual_appointment from appointment_schedule where reference_number='$gwf' and date_of_appointment = '$date_of_outscan' and mission_id='".$_SESSION['mission_id']."'"));
		$type = strtoupper($get_app_type['actual_appointment']);
		$ref_id = $_REQUEST['ref_id'];
	}
	else
	{
		$token = $_REQUEST['token'];
		$ref_id = $_REQUEST['ref_id'];
		$get_app_type = mysql_fetch_array(mysql_query("select actual_appointment from appointment_schedule where id='".$ref_id."' and date_of_appointment = '$date_of_outscan' and mission_id='".$_SESSION['mission_id']."'"));
		$type = strtoupper($get_app_type['actual_appointment']);
	}
	
	if(strpos($type,'PREMIUM') !== false) 
	{
		$fail_premium=1;
		$get_premium = mysql_query("select id from price_list where service_name like '%premium%' and mission_id='".$_SESSION['mission_id']."'");
		$i=1;
		while($premium_id = mysql_fetch_array($get_premium))
		{
			if(strpos($vas_selected,$premium_id['id']."_1") !== false) 
			{
				$fail_premium=0;
				break;
			}
			
		}
	}
	else
	{
		$fail_premium=0;
	}
	if($gwf!="" && $glfs!="")
	{
		//$cnt++;	
		$query = "SELECT count(gwf_number) as counts FROM passport_inscan_record WHERE gwf_number='".$gwf."' and mission_id='".$_SESSION['mission_id']."'";
		
		$sql = mysql_query($query);
		$recResult = mysql_fetch_array($sql);
		$existGWF = $recResult["counts"];
		$resubmit_cnt=0;
		if($existGWF=="0") 
		{
			$vas_text="";
			
			if($fail_premium==1)
			{
				print json_encode("premium_missing");
				return;
			}
			else
			{
				if(strlen($vas_selected)>0)
				{
					$product_array = explode(',', $vas_selected);
					foreach($product_array as $ArrayValue)
					{ 
						$itemArray = explode('_', $ArrayValue);
						if($itemArray[1]<=0)
						{
							print json_encode("count_missing");
							return;
						}
						else
						{
							$get_bts_entries= mysql_query("select id, need_resubmission from price_list where id='".$itemArray[0]."'");
							$res_VAS_list=mysql_fetch_array($get_bts_entries);
							if($res_VAS_list['need_resubmission']==1)
							{
								$resubmit_cnt++;
							}
							//$itemArray[0] = product ID
							//$itemArray[1] = total count
							$get_current_totals=mysql_query("select vas_id,total from daily_vas_entries_total where user_id='$user_id' and date='$date_of_outscan' and vas_id='".$itemArray[0]."' and mission_id='".$_SESSION['mission_id']."'");
							$count_total_today = mysql_num_rows($get_current_totals);
							if($count_total_today==0)
							{
								//INSERT
								$insert_first_entry=mysql_query("insert into daily_vas_entries_total (id,user_id,date,vas_id,total,mission_id) values (DEFAULT,'$user_id','$date_of_outscan','".$itemArray[0]."','".$itemArray[1]."','".$_SESSION['mission_id']."')");
							}
							else if($count_total_today > 0)
							{
								$get_total=mysql_fetch_array($get_current_totals);
								$total_vas_for_this_id=	($get_total['total']+$itemArray[1]);			
								$update_daily_total=mysql_query("update daily_vas_entries_total set total='$total_vas_for_this_id'  where user_id='$user_id' and date='$date_of_outscan' and vas_id='".$itemArray[0]."' and mission_id='".$_SESSION['mission_id']."'");
							}
						}
					}
				}
				$vas_text_fin= $vas_selected;
				//echo $vas_text_fin;
		
				if(!in_array('manage_tees.php',$disabled))
				{	
					$get_glfsid= mysql_query("select id from tee_envelop_counts WHERE barcode_no='$glfs' and mission_id='".$_SESSION['mission_id']."'");
					$res_get_glfsid=mysql_fetch_array($get_glfsid);
					$glfs_id_insert = $res_get_glfsid['id'];	
				}
				else
				{
					$glfs_id_insert="0000";
				}
				$insertTable= "insert into passport_inscan_record (id,date_outscan,application_taken_by,gwf_number,date_inscan,delivered_on,delivered_at,delivered_by,current_status,glfs_id,vas_entries,mission_id) values (DEFAULT,'".$date_of_outscan."','$myid','".$gwf."','','','','','outscan','".$glfs_id_insert."','".$vas_text_fin."','".$_SESSION['mission_id']."')";
				$timenow = date("G:i:s");
				
				if(mysql_query($insertTable))
				{
					if(!in_array('manage_tees.php',$disabled))
					{
						//Update appointment_schedule table
						mysql_query("update appointment_schedule set application_status='ro_complete' where reference_number='$gwf' and mission_id='".$_SESSION['mission_id']."'");
					}
					else //if the page is disabled 
					{
						//$id= mysql_fetch_array(mysql_query("select reference_id from submission_timing where token_no = '$token' and mission_id='".$_SESSION['mission_id']."' and  date_created= '$date_of_outscan'"));
						mysql_query("update appointment_schedule set application_status='ro_complete',  reference_number='$gwf' where id ='".$ref_id."' and mission_id='".$_SESSION['mission_id']."' and  date_of_appointment= '$date_of_outscan'");
							
					}
					$ref_id= mysql_fetch_array(mysql_query("select id from appointment_schedule where reference_number = '$gwf' and mission_id='".$_SESSION['mission_id']."'"));
					$update_ro_finish = "update submission_timing set ro_finish_at = '".$timenow."' where reference_id='".$ref_id['id']."' and mission_id='".$_SESSION['mission_id']."'";	
					mysql_query($update_ro_finish);
						
					if(!in_array('manage_tees.php',$disabled))
					{
						$update_glfsid= "update tee_envelop_counts set staff_id='$user_id', status='used' , date_used='".date('Y-m-d')."' where barcode_no='$glfs' and id = '".$res_get_glfsid['id']."' and mission_id='".$_SESSION['mission_id']."'";
						mysql_query($update_glfsid);
					}
						
					$get_currentgwf_id= mysql_fetch_array(mysql_query("select id from passport_inscan_record where gwf_number='$gwf' and mission_id='".$_SESSION['mission_id']."'"));
						
					if($resubmit_cnt>0)
					{
						$need_resubmission= "insert into passport_resubmission values (DEFAULT,'". $get_currentgwf_id['id']."','','','wait_resubmit','".$_SESSION['mission_id']."')";

						mysql_query($need_resubmission);
					}
					print json_encode("added");
				}
			}
		} 
		else
		{
			print json_encode("exists");
		}
	}
}

function delete_email()
{
	$page = $_REQUEST['pn'];
	$id= $_REQUEST['id'];
	$query_changestatus = "delete from email_receivers where id =$id and mission_id='".$_SESSION['mission_id']."'";
	if(mysql_query($query_changestatus))
	{
		mysql_close();
		$_SESSION['response_emaildel']="<p align='center' style='font-weight: bold; color: green;'>This Email Address is deleted.</p>";
	   header("Location:mail_receivers.php?pageno=".$page);
	}
	else
	{
		echo mysql_error();
		mysql_close();
		$_SESSION['response_emaildel']="<p align='center' style='color:red; font-weight: bold;'>Something Went Wrong. Please Contact Admin.". mysql_error()."</p>";
	   	header("Location:mail_receivers.php?pageno=".$page);

	}
}


function add_email()
{
	$page = $_REQUEST['pn'];
	$mail= $_REQUEST['newemail'];
	
	if(isset($_REQUEST['consolidated']))
	{
		$need_cconsolidated = 1;
	}
	else
	{
		$need_cconsolidated = 0;
	}
	if(isset($_REQUEST['cashsheet']))
	{
		$need_cash_sheet = 1;
	}
	else
	{
		$need_cash_sheet = 0;
	}
	if(isset($_REQUEST['alerts']))
	{
		$need_alerts = 1;
	}
	else
	{
		$need_alerts = 0;
	}
	$query_changestatus = "insert into email_receivers values(DEFAULT,'$mail','$need_cash_sheet',$need_alerts,$need_cconsolidated,'".$_SESSION['mission_id']."')";
	if(mysql_query($query_changestatus))
	{
		mysql_close();
		$_SESSION['response_emaildel']="<p align='center' style='font-weight: bold; color: green;'>This Email Address is Added -". $mail.".</p>";
	   header("Location:mail_receivers.php?pageno=".$page);
	}
	else
	{
		echo mysql_error();
		mysql_close();
		$_SESSION['response_emaildel']="<p align='center' style='color:red; font-weight: bold;'>Something Went Wrong. Please Contact Admin.". mysql_error()."</p>";
	   	header("Location:mail_receivers.php?pageno=".$page);

	}

}



function add_service()
{
	$service= $_REQUEST['newservice'];
	$currency = $_REQUEST['currency_used'];
	$amount= $_REQUEST['serviceamount'];
	$vas_category = $_REQUEST['vas_categ'];
	$need_Txt_box= $_REQUEST['multiple_copy'];
	$alert_status_if=$_REQUEST['alert_status'];
	$alert_after=$_REQUEST['alert_days'];
	$need_resubmission=$_REQUEST['resubmission_needed'];
	if($alert_status_if!="select" || $alert_after!="select")
	{
		$need_alert_status_if= $alert_status_if;
		$aert_days_after = $alert_after;
	}
	else
	{
		$need_alert_status_if= "";
		$aert_days_after = "0";
	}
	
	$query_changestatus = "insert into price_list values(DEFAULT,'$service','$amount','$need_Txt_box','$need_alert_status_if','$aert_days_after','$need_resubmission','active','".$_SESSION['mission_id']."','".$currency."','$vas_category')";
	if(mysql_query($query_changestatus))
	{
		mysql_close();
	    print(json_encode("added"));
	}
	else
	{
		print(json_encode("failed"));
		mysql_close();
	}
}


function delete_service()
{
	$page = $_REQUEST['pn'];
	$id= $_REQUEST['id'];
	$query_changestatus = "update price_list set current_status='deleted' where id =$id and mission_id='".$_SESSION['mission_id']."'";
	if(mysql_query($query_changestatus))
	{
		mysql_close();
		$_SESSION['response_service']="<p align='center' style='font-weight: bold; color: green;'>Selected Service is deleted.</p>";
		header("Location:manage_system.php?pageno=".$page);
	}
	else
	{
		echo mysql_error();
		mysql_close();
		$_SESSION['response_service']="<p align='center' style='color:red; font-weight: bold;'>Something Went Wrong. Please Contact Admin.". mysql_error()."</p>";
		header("Location:manage_system.php?pageno=".$page);
	}
}



function daily_submit_report()
{

	unset($_SESSION['date_for_report']);
	unset($_SESSION['date_for_report1']);
	$date_selected = $_REQUEST['inputField'];
	$date_selected1 = $_REQUEST['inputField1'];
	$_SESSION['date_for_report']= $date_selected;
	$_SESSION['date_for_report1']= $date_selected1;
	$link = "open";
	print(json_encode($link));
}



function daily_sales()
{

	unset($_SESSION['date_for_report']);
	unset($_SESSION['date_for_report1']);
	$date_selected = $_REQUEST['inputField'];
	$date_selected1 = $_REQUEST['inputField1'];
	$_SESSION['date_for_report']= $date_selected;
	$_SESSION['date_for_report1']= $date_selected1;
	$link = "open";
	print(json_encode($link));
	
}

function daily_sales_pwise()
{

	unset($_SESSION['date_for_report']);
	unset($_SESSION['date_for_report1']);
	$date_selected = $_REQUEST['inputField'];
	$date_selected1 = $_REQUEST['inputField1'];
	$_SESSION['date_for_report']= $date_selected;
	$_SESSION['date_for_report1']= $date_selected1;
	$link = "open";
	print(json_encode($link));
	
}

function update_service()
{
	$page = $_REQUEST['pno'];
	$id= $_REQUEST['id'];
	$value_update=$_REQUEST['val_toupdate'];
	$query_changestatus = "update price_list set amount=$value_update where id =$id and mission_id='".$_SESSION['mission_id']."'";
	echo $query_changestatus;
	if(mysql_query($query_changestatus))
	{
		mysql_close();
		$_SESSION['response_service']="<p align='center' style='font-weight: bold; color: green;'>Selected Service is Updated.</p>";
		header("Location:manage_system.php?pageno=".$page);
	}
	else
	{
		echo mysql_error();
		mysql_close();
		$_SESSION['response_service']="<p align='center' style='color:red; font-weight: bold;'>Something Went Wrong. Please Contact Admin.". mysql_error()."</p>";
		header("Location:manage_system.php?pageno=".$page);
	
	}
}


function removegwf()
{
	$udxarr = $_POST['dt'];
	$barcode = $_REQUEST['glfsno'];
	$id = $_REQUEST['id'];
	$get_submitted= "";
	$date_submitted=date("Y-m-d");
	$get_userdetals = mysql_fetch_array(mysql_query("select u.user_id as user_id , u.username as username from users u , passport_inscan_record i where i.gwf_number = '".$udxarr."' and i.application_taken_by= u.username"));
	
	//Get appointment ID
	$get_appointment_tbl_id = mysql_fetch_array(mysql_query("select id, applicant_group_id from appointment_schedule where reference_number='$udxarr' and mission_id='".$_SESSION['mission_id']."'"));
	//Update submission timing for disable old token
	$update_sub_timing = mysql_query("update submission_timing set shown_up_remark='reject' where reference_id = '".$get_appointment_tbl_id['id']."' and mission_id='".$_SESSION['mission_id']."'");
	//delete comments , if any
	$delete_comments = mysql_query("delete from submission_comments where app_group_id = '".$get_appointment_tbl_id['applicant_group_id']."' and mission_id='".$_SESSION['mission_id']."'");
	
	
	//file_put_contents('Sample_files/log.txt', "select id from appointment_schedule where reference_number='$udxarr' and mission_id='".$_SESSION['mission_id']."'".PHP_EOL , FILE_APPEND | LOCK_EX);
	//file_put_contents('Sample_files/log.txt', "update submission_timing set reference_id='0' where reference_id = '".$get_appointment_tbl_id['id']."' and mission_id='".$_SESSION['mission_id']."'".PHP_EOL , FILE_APPEND | LOCK_EX);
	
	$taken_by = $get_userdetals['username'];//username of staff
	$user_id= $get_userdetals['user_id'];//id of staff 
	$fullqry1= "delete from passport_inscan_record where gwf_number='$udxarr' and date_outscan='$date_submitted' and application_taken_by='$taken_by' and mission_id='".$_SESSION['mission_id']."'";
	//echo $fullqry1;
		
	$update_glfs = "update tee_envelop_counts set staff_id='0',status='available',date_used='0000-00-00' where barcode_no='$barcode' and mission_id='".$_SESSION['mission_id']."'";
	
	$change_appointment = "update appointment_schedule set applicant_group_id='0', application_status='scheduled' where reference_number='$udxarr' and mission_id='".$_SESSION['mission_id']."'";
	$get_total_vas_to_deduct=mysql_fetch_array(mysql_query("select vas_entries from passport_inscan_record where gwf_number='$udxarr' and mission_id='".$_SESSION['mission_id']."'"));
	$vas_into_array1 = explode(",",$get_total_vas_to_deduct['vas_entries']);
	//print json_encode($vas_into_array1[0]);
	for($i=0; $i< count($vas_into_array1); $i++)
	{
		$individual_vas=explode("_",$vas_into_array1[$i]);
		$get_total_vas= mysql_fetch_array(mysql_query("select id,total from daily_vas_entries_total where user_id='$user_id' and date='$date_submitted' and vas_id='".$individual_vas['0']."' and mission_id='".$_SESSION['mission_id']."'"));
		$new_total =($get_total_vas['total']-$individual_vas[1]);
		if($new_total >=1)
		{
			mysql_query("update daily_vas_entries_total set total = '$new_total' where user_id='$user_id' and date='$date_submitted' and vas_id='".$individual_vas[0]."' and mission_id='".$_SESSION['mission_id']."'");
		}
		else 
		{
			mysql_query("delete from daily_vas_entries_total where user_id='$user_id' and date='$date_submitted' and vas_id='".$individual_vas['0']."' and mission_id='".$_SESSION['mission_id']."'");
		}
	}
	if(mysql_query($fullqry1) && mysql_query($update_glfs) && mysql_query($change_appointment) )
	{
		$update_approval= mysql_query("update  approvals set current_status='completed' where id='".$id."' and mission_id = '".$_SESSION['mission_id']."'");
		print json_encode("removed");
	}
	else
	{
		print json_encode(mysql_error());
	}
}




function email_putcontent()
{
	date_default_timezone_set("Asia/Bahrain");
	$html = $_POST['hiddenhtmlformail'];
	
	$recievers="";
	$msg= "<p style='padding: 15px;'></p>".$html;
	$headers = "MIME-Version: 1.0" . "\r\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 
	$headers .= "From: simon@aletimad.com" . "\r\n"; 
			
	$reciver_emails="";
	$get_rcvr = "select email_address from email_receivers where consolidated=1 and mission_id='".$_SESSION['mission_id']."'";
	$get_rcvr1=mysql_query($get_rcvr);
	$cnt_emails=mysql_num_rows($get_rcvr1);
	if($cnt_emails >0)
	{
		while($mailids = mysql_fetch_array($get_rcvr1))
		{
			$reciver_emails=$reciver_emails .",".$mailids['email_address'];
		}
		$reciver_emails = ltrim($reciver_emails,',');
		if(mail($reciver_emails,"CONSOLIDATED Cash Tally Sheet - ".$_SESSION['vac']."VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']." Date: ".date('d-m-Y h:m:s').")" ,$msg,$headers))
		{
			$_SESSION['response_ukvac_final']="emailsent_c";
			//unset($_SESSION['response_ukvac']);
			header("Location:cash_tally_sheet.php");
			//echo "Done234";
		}
	}
	else
	{
		$_SESSION['response_ukvac']="noreceivers";
		header("Location:cash_tally_sheet.php");
	}
}




function getglfsavaliability()
{
	//$barcode = $_GET['glfsno'];
		//echo $barcode;
	if(isset($_POST['glfsno']))
	{
        $barcode = $_POST['glfsno'];
		//echo $barcode;
		$a = "SELECT id ,status FROM tee_envelop_counts WHERE barcode_no='".$barcode."' and mission_id='".$_SESSION['mission_id']."'";
		$query_gettees = mysql_query($a);
		
		$res = mysql_fetch_array($query_gettees);
		//echo mysql_num_rows($query_gettees);
		if(mysql_num_rows($query_gettees) ==0)
		{
			//echo "<label color='red'>GLFS Not Added In System</label>";
			echo 1;
		}
		else if($res['status']!='available')
		{
			//echo "<label color='red'>GLFS Already Used </label>";
			echo 2;
		}
		else if($res['status']=='available')
		{
			echo 3;
		}
		else
		{
			echo 4;
		}
    }
	else
	{
		echo "Test";
	}
}


function report_teecount()
{
	unset($_SESSION['date_for_report']);
	unset($_SESSION['date_for_report1']);
	unset($_SESSION['date_for_report2']);
	$date_selected1 = $_REQUEST['inputField'];
	$date_selected2 = $_REQUEST['inputField1'];
	$_SESSION['date_for_report1']= $date_selected1;
	$_SESSION['date_for_report2']= $date_selected2;
	$link = "open";
	print(json_encode($link));
}




function updategwf()
{
	$udxarr = $_POST['dt']; // GWF NO
	$barcode = $_REQUEST['glfsno']; // GLFS No
	$vas_selected = rtrim($_REQUEST['vas_selected'],","); // Selected NEW VAS
	$get_submitted= "";
	$date_submitted=date("Y-m-d");
	$date_of_outscan=date("Y-m-d");
	$taken_by = $_SESSION['uid_ukvac'];
	$user_id= $_SESSION['user_id_ukvac'];
	$fullqry1= "update passport_inscan_record set vas_entries = '$vas_selected' where gwf_number='$udxarr' and date_outscan='$date_submitted' and mission_id='".$_SESSION['mission_id']."'";
	//echo $fullqry1;
	
	//$update_glfs = "update tee_envelop_counts set staff_id='',status='available',date_used='' where barcode_no='$barcode'";
	
	///////////*FIRST DEDUCT EXISTING VAS*/////////
	
	$get_total_vas_to_deduct=mysql_fetch_array(mysql_query("select vas_entries from passport_inscan_record where gwf_number='$udxarr' and mission_id='".$_SESSION['mission_id']."'"));
	if($get_total_vas_to_deduct['vas_entries']!="")
	{
		$vas_into_array1 = explode(",",$get_total_vas_to_deduct['vas_entries']);
		//print json_encode($vas_into_array1[0]);
		for($i=0; $i< count($vas_into_array1); $i++)
		{
			$individual_vas=explode("_",$vas_into_array1[$i]);
			$get_total_vas= mysql_fetch_array(mysql_query("select id,total from daily_vas_entries_total where user_id='$user_id' and date='$date_submitted' and vas_id='".$individual_vas['0']."' and mission_id='".$_SESSION['mission_id']."'"));
			$new_total =($get_total_vas['total']-$individual_vas['1']);
			if($new_total >=1)
			{
				mysql_query("update daily_vas_entries_total set total = '$new_total' where user_id='$user_id' and date='$date_submitted' and vas_id='".$individual_vas[0]."' and mission_id='".$_SESSION['mission_id']."'");
			}
			else 
			{
				mysql_query("delete from daily_vas_entries_total where user_id='$user_id' and date='$date_submitted' and vas_id='".$individual_vas['0']."' and mission_id='".$_SESSION['mission_id']."'");
			}
		}
	}
	
	if(mysql_query($fullqry1))
	{
		///////////*TNED DAA NEW VAS*/////////
		
		$get_bts_entries = mysql_query("select * from price_list where current_status='active' and mission_id='".$_SESSION['mission_id']."'");
		while($res_VAS_list=mysql_fetch_array($get_bts_entries))
		{
			$vas_into_array1 = explode(",",$vas_selected); //==> vas_into_array1[0]=1_1
			//print json_encode($vas_into_array1[0]);
			for($i=0; $i< count($vas_into_array1); $i++)
			{
				$individual_vas=explode("_",$vas_into_array1[$i]); // $individual_vas[0] = VAS ID , $individual_vas[1] = Total
				if($individual_vas['0']==$res_VAS_list['id'])
				{
					$get_current_totals=mysql_query("select vas_id,total from daily_vas_entries_total where user_id='$user_id' and date='$date_of_outscan' and vas_id='".$res_VAS_list['id']."' and mission_id='".$_SESSION['mission_id']."'");
					$count_total_today = mysql_num_rows($get_current_totals);
					if($count_total_today==0)
					{
								//INSERT
						$insert_first_entry=mysql_query("insert into daily_vas_entries_total (id,user_id,date,vas_id,total,mission_id) values (DEFAULT,'$user_id','$date_of_outscan','".$res_VAS_list['id']."','$individual_vas[1]','".$_SESSION['mission_id']."')");
					}
					else if($count_total_today > 0)
					{
						$get_total=mysql_fetch_array($get_current_totals);
						$total_vas_for_this_id=	($get_total['total']+$individual_vas[1]);			
						$update_daily_total=mysql_query("update daily_vas_entries_total set total='$total_vas_for_this_id'  where user_id='$user_id' and date='$date_of_outscan' and vas_id='".$res_VAS_list['id']."' and mission_id='".$_SESSION['mission_id']."'");
								
					}
				}
			}
		}
		//Update the approval request to 0
		$get_ref_id = mysql_fetch_array(mysql_query("select id from appointment_schedule where reference_number = '".$udxarr."'"));
		$update_approval= mysql_query("update  approvals set current_status='completed' where ref_id='".$get_ref_id['id']."' and mission_id = '".$_SESSION['mission_id']."'");
		$time = time()+4;
		$cookie = setcookie("gwf", $udxarr, $time);
		$cookie1 = setcookie("status", "v", $time);
		print json_encode("updated");
	}
	else
	{
		print json_encode("failed_update");
	}

}


function insert_reconcil_intotxt()
{
	/*$myfile=fopen("gwfs_reconciliation.txt", "w");
	fwrite($myfile, "");
	fclose($myfile);*/
	$text_to_apend = $_REQUEST['text'];
	$handle = fopen("Sample_files/gwfs_reconciliation.txt", "a");
	if(fwrite($handle, $text_to_apend))
	{ // write it 
		fclose($handle); 
		print json_encode("appended");
	}
	else
	{
		print json_encode("failed_append");	
	}
}


function auto_reconciliation()
{
	$str=rtrim(file_get_contents("Sample_files/gwfs_reconciliation.txt"),",");
	$handle = explode(",",$str);
	$max = sizeof($handle);
	$total_invacs = mysql_fetch_array(mysql_query("select count(*) as total_invac from passport_inscan_record where current_status='inscan' and mission_id='".$_SESSION['mission_id']."'"));
	$inscanned_list="";
	$counter_deliver="";
	$courier_deliver="";
	$outscanned="";
	$missing_list="";
	$invalid_list="";
	$invac_count=0;
	$get_status_indb = mysql_query("select gwf_number, current_status from passport_inscan_record where current_status='inscan' and mission_id='".$_SESSION['mission_id']."' order by glfs_id");
	while($res = mysql_fetch_array($get_status_indb))
	{
		if(in_array($res['gwf_number'],$handle))
		{
			$inscanned_list=$inscanned_list.$res['gwf_number']."_inscan,";
			$invac_count++;
		}
		else 
		{
			$missing_list=$missing_list.$res['gwf_number']."_physicalmissing,";
		}
	}
	
	for($i=0; $i<$max; $i++)
	{
		$get_status_1 = mysql_query("select current_status from passport_inscan_record where gwf_number='".$handle[$i]."' and mission_id='".$_SESSION['mission_id']."'");
		if(mysql_num_rows($get_status_1)==1)
		{
			$get_status=mysql_fetch_array($get_status_1);
			if($get_status['current_status']=="counter_delivery")
			{
				$counter_deliver=$counter_deliver.$handle[$i]."_counterdelivery,";
			}
			else if($get_status['current_status']=="courier_Service")
			{
				$courier_deliver=$courier_deliver.$handle[$i]."_courierdelivery,";
			}
			else if($get_status['current_status']=="outscan")
			{
				$outscanned=$outscanned.$handle[$i]."_outscan,";
			}
		}
		else
		{
			//if the array value not in database, check in the rectification
			$get_rectification_status = mysql_query("select status from rectification_cancellation where gwf_number='".$handle[$i]."' and mission_id='".$_SESSION['mission_id']."'");
			if(mysql_num_rows($get_rectification_status) == 1)
			{
				$res_get_rectification_status = mysql_fetch_array($get_rectification_status);
				if($res_get_rectification_status['status']=="inscan")
				{
					$inscanned_list=$inscanned_list.$handle[$i]."_inscanrectification,";
				}
				else if($res_get_rectification_status['status']=="counter_delivery")
				{
					$counter_deliver=$counter_deliver.$handle[$i]."_counterdeliveryrectification,";
				}
				else if($res_get_rectification_status['status']=="outscan")
				{
					$outscanned=$outscanned.$handle[$i]."_outscanrectification";
				}
			}
			else
			{
				$invalid_list=$invalid_list.$handle[$i]."_invalid,";
			}
		}
	}
	
	
	//print json_encode($notexists_list);
	//print json_encode($inscanned_list);

	$handle = fopen("Sample_files/gwfs_reconciliation_result.txt", "w");
	$text_to_apend =$inscanned_list.$counter_deliver.$courier_deliver.$outscanned.$missing_list.$invalid_list;
	if(fwrite($handle, $text_to_apend))
	{ // write it 
		fclose($handle); 
	}

	if($counter_deliver=="" && $courier_deliver=="" && $outscanned=="" && $missing_list=="" && $invalid_list=="" && $total_invacs['total_invac']==$invac_count)
	{
		print json_encode("success_reconciliation");
		//success Reconciliation
	}
	else 
	{
		print json_encode("failed_reconciliation");
	}
}


function daily_sales_swise()
{

	unset($_SESSION['date_for_report']);
	unset($_SESSION['date_for_report1']);
	$date_selected = $_REQUEST['inputField'];
	$date_selected1 = $_REQUEST['inputField1'];
	$_SESSION['date_for_report']= $date_selected;
	$_SESSION['date_for_report1']= $date_selected1;
	$link = "open";
	print(json_encode($link));
	
}


function get_pp_status()
{
	$gwf=$_REQUEST["gwf"];
	$get_status_qr=mysql_query("select current_status from passport_inscan_record where gwf_number='$gwf' and mission_id='".$_SESSION['mission_id']."'");
	$gwf_status=mysql_fetch_array($get_status_qr);
	print json_encode($gwf_status['current_status']);
}



function get_appointment__status()
{
	$ref_no=$_REQUEST['ref_no'];
	date_default_timezone_set("Asia/Bahrain");
	$date_today=date('Y-m-d');
	$time_now= date("G:i:s");
	$get_app_sts=mysql_query("select * from appointment_schedule where reference_number='$ref_no' and mission_id='".$_SESSION['mission_id']."'");
	$isexists=mysql_num_rows($get_app_sts);
	
	if($isexists==1)
	{
		$get_details=mysql_fetch_array($get_app_sts);
	  	if($get_details['application_status']=="scheduled")
		{
			//Appointment==scheduled 
			
			if($date_today==$get_details['date_of_appointment'])
			{
				//date of appointment== today
				//check the time difference btwn appointment time and actual shown up time
				$time1 = new DateTime($time_now);
				$time2 = new DateTime($get_details['time_appointment'].":00");
				
				
				
				$diff = $time1->diff( $time2 );
				$appointment_time_difference= $diff->format( '%H:%I:%S' ); // -> 00:25:25
				sscanf($appointment_time_difference, "%d:%d:%d", $hours, $minutes, $seconds);
	
				$time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes; //Convert late by time into total seconds
				if($time_seconds > 2700 && $time2 < $time1 ) //Compare with 45 minutes in second
				{
					//Late By $appointment_time_difference
					$data_transfer=$get_details['reference_number'].",".$get_details['date_of_appointment'].",".$get_details['time_appointment'].",".$get_details['appointment_type'].",".$appointment_time_difference.","."late";
				}
				else
				{
					//Early By $appointment_time_difference
					$data_transfer=$get_details['reference_number'].",".$get_details['date_of_appointment'].",".$get_details['time_appointment'].",".$get_details['appointment_type'].",".$appointment_time_difference.","."ontime";
				}
			}
			else
			{
				//Date  of appointment different
				if($get_details['application_status']=="no_noc")
				{
					//Came before but No NOC
					$data_transfer=$get_details['reference_number'].",".$get_details['date_of_appointment'].",".$get_details['time_appointment'].",".$get_details['appointment_type'].",no_noc,late";
				}
				else
				{
					$data_transfer=$get_details['reference_number'].",".$get_details['date_of_appointment'].",".$get_details['time_appointment'].",".$get_details['appointment_type'].",lastday,late";
				}
			}
		}
		else if($get_details['application_status']=="pending")
		{
			$data_transfer="pending_approval";
		}
		else if($get_details['application_status']=="shown_up" && $get_details['date_of_appointment']==$date_today )
		{
			$data_transfer="ao_created";
		}
		else if ($get_details['application_status']=="outscan")
		{
			$data_transfer="submitted";
		}
		else
		{
			//Date  of appointment different
			if($get_details['application_status']=="no_noc")
			{
				//Came before but No NOC
				$data_transfer=$get_details['reference_number'].",".$get_details['date_of_appointment'].",".$get_details['time_appointment'].",".$get_details['appointment_type'].",no_noc,late";
			}
			else
			{
				$time1 = new DateTime($time_now);
				$time2 = new DateTime($get_details['time_appointment'].":00");
				
				
				
				$diff = $time1->diff( $time2 );
				$appointment_time_difference= $diff->format( '%H:%I:%S' ); // -> 00:25:25
				sscanf($appointment_time_difference, "%d:%d:%d", $hours, $minutes, $seconds);
	
				$time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes; //Convert late by time into total seconds
				if($time_seconds > 2700 && $time2 < $time1 ) //Compare with 45 minutes in second
				{
					//Late By $appointment_time_difference
					$data_transfer=$get_details['reference_number'].",".$get_details['date_of_appointment'].",".$get_details['time_appointment'].",".$get_details['appointment_type'].",lastday,late";
				}
				else
				{
					$data_transfer=$get_details['reference_number'].",".$get_details['date_of_appointment'].",".$get_details['time_appointment'].",".$get_details['appointment_type'].",".$appointment_time_difference.","."ontime";
				}
				
			}
		}
	}
	else
	{
		$data_transfer=$ref_no.",No ,Appointment,WALK-IN,0,"."walkin_with_premium";
	}
	print json_encode($data_transfer);
	
}

function update_passport_resubmission()
{
	$new_ref = $_REQUEST['nref'];
	$old_ref = $_REQUEST['oref'];
	$get_ref_id= mysql_fetch_array(mysql_query("select id from passport_inscan_record where gwf_number='$old_ref' and mission_id='".$_SESSION['mission_id']."'"));
	$ref_id = $get_ref_id['id'];
	$date = date('Y-m-d');
	if(mysql_query("update passport_resubmission set new_ref_number='$new_ref' ,resubmitted_on='$date',status='resubmitted' where actual_ref_id='$ref_id' and mission_id='".$_SESSION['mission_id']."'"))
	{
		print json_encode('resubmitted');
	}
	else
	{
		print json_encode('error');
	}
}


function submit_AO()
{
	$fulldataarr = json_decode(str_replace('\\', '', $_POST['dt']));
	$token = $_REQUEST['token'];
	$comments = $_REQUEST['comments'];
	date_default_timezone_set("Asia/Bahrain");
	$date_today=date('Y-m-d');
	$time_now= date("G:i");
	$success_cnt=0;
	$faild_cnt=0;
	$tkn_extist=0;
	$get_max_group = mysql_fetch_array(mysql_query("select max(applicant_group_id) as max_id from appointment_schedule where mission_id='".$_SESSION['mission_id']."'")) ;
	$max_new_id=$get_max_group['max_id']+1;
	$get_token = mysql_fetch_array(mysql_query("select count(*) as token_count from submission_timing where token_no='".$token."' and date_created='".$date_today."' and mission_id='".$_SESSION['mission_id']."'"));
			
	if($get_token['token_count'] ==0)
	{

		for($i=0; $i< count($fulldataarr); $i++)
		{

			if($fulldataarr[$i]!="")
			{
			
				/*
				0--GWF037911257--late--premium--nochange--nochange
				1--GWF037871192--late--premium--walk_in--Testewewe
				2--GWF037915643--ontime--standard--premium--retwstrwt
				0--GWF037894030--lastday--premium--nochange--nochange
				0--GWF037914560--no_noc--standard--nochange--nochange
				
				
				0--GWF037915643--ontime--standard--nochange--nochange
				1--GWF037891576--ontime--standard--premium--plllllll
				2--GWF037806681--ontime--standard--standard--daddfdsfdsf
				3--GWF037901184--lastday--premium--nochange--nochange
				4--GWF037702010--lastday--premium--walk_in--sdfsdfsdf
				5--GWF037886405--lastday--premium--walk_in_premium--123213313
				
				//for walk-in
				0--GWF037021177--walkin_with_premium--premium--premium--Testrea
				
				*/
			
				$eachcolumnineachrow = explode(",_",$fulldataarr[$i]);
				$ref_no=$eachcolumnineachrow[1];
				//Actual_appointment
				$get_app_sts=mysql_query("select * from appointment_schedule where reference_number='$ref_no' and date_of_appointment='".$date_today."' and mission_id='".$_SESSION['mission_id']."'");
				if(mysql_num_rows($get_app_sts) >0)
				{
					$get_details=mysql_fetch_array($get_app_sts);
					if($eachcolumnineachrow[4]=='nochange')
					{
						if($eachcolumnineachrow[3]=='premium')
						{
							$actual = "Premium Lounge Appointment";
						}
						else if($eachcolumnineachrow[3]=='standard')
						{
							$actual = "Standard Appointment";
						}
					}
					else
					{
						if($eachcolumnineachrow[4]=='premium')
						{
							$actual = "Premium Lounge Appointment";
						}
						else if($eachcolumnineachrow[4]=='standard')
						{
							$actual = "Standard Appointment";
						}
						else if($eachcolumnineachrow[4]=='walk_in')
						{
							$actual = "walk-in";
						}
						else if($eachcolumnineachrow[4]=='walk_in_premium')
						{
							$actual = "Premium Lounge Appointment";
						}
						else
						{
							$actual = "Premium Lounge Appointment";
						}
					}
				}
				else //Walk-in
				{
					$actual = "Premium Lounge Appointment";

					$chk_submitted = mysql_query("select * from passport_inscan_record where gwf_number='".$ref_no."' and mission_id = '".$_SESSION['mission_id']."'");
	
					if(mysql_num_rows($chk_submitted)==0)
					{
						$delete_pre = mysql_query("delete from appointment_schedule where reference_number='".$ref_no."'");
		
						//insert into appointment table as walk-in
						$qr = "insert into appointment_schedule values (DEFAULT,'$max_new_id','$ref_no','','$date_today','0','".strtoupper($actual)."','".$actual."','WALK-IN','pending','','".$_SESSION['mission_id']."')";
					
						if(mysql_query($qr))
						{					
							$GET_ID = mysql_fetch_array(mysql_query("select id from appointment_schedule where reference_number='".$ref_no."' and mission_id='".$_SESSION['mission_id']."'"));
							$add_temp = "insert into approvals values(DEFAULT,'".$_SESSION['mission_id']."','".$GET_ID['id']."','".date('Y-m-d')."','".$_SESSION['user_id_ukvac']."','3','Pending','".$eachcolumnineachrow[5]."','')";
							if(mysql_query($add_temp))
							{
								$heading ="Pending Approval - Walk-In Admission";
								$url = '//'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/approvals.php";
								$html_for_email = "
								<div>
									<span style='color: #0181BC; padding:5px;'>
										<h4>New Walk-In Admission Approval</h4>
									</span>
									<span style='color:#000;'>
										<p>Application Reference Number - ".$ref_no." is awaiting for the approval for Walk-In Admission. <br><b>AO Comment:-</b> ".$eachcolumnineachrow[5]."<br><a href='".$url."'>Click here</a> to login and approve</p>
									</span>
								</div>";
								require_once("Classes/mail/class.email.php");
								$sendmail_obj = new _email_function();
								$sendmail_obj -> html= $html_for_email;
								$sendmail_obj -> heading = $heading;
								$sendmail_obj -> receiver_group = 'alert';
								$sendmail_status = $sendmail_obj ->fire_email();
				
							}
						}
					}
				}
						
				//$output = print_r($eachcolumnineachrow[0]."--".$eachcolumnineachrow[1]."--".$eachcolumnineachrow[2]."--".$eachcolumnineachrow[3]."--".$eachcolumnineachrow[4]."--".$eachcolumnineachrow[5], true);
				
				
				//insert a group ID for the applicants
				//Update status as shown_up
				//update actual appointment if there is a change
				//insert remarks if any remarks on appointment change
				
				//insert into the submission_timing table
				//check token already added...
				$get_app_sts=mysql_query("select * from appointment_schedule where reference_number='$ref_no' and date_of_appointment='".$date_today."' and mission_id='".$_SESSION['mission_id']."'");
				$get_details=mysql_fetch_array($get_app_sts);
				$insert_appointment_schedule = "insert into submission_timing values (DEFAULT,'".$get_details['id']."','$token','$max_new_id','$date_today','$time_now','$eachcolumnineachrow[2]','','','".$_SESSION['mission_id']."')";
				
				if(mysql_query($insert_appointment_schedule))
				{
					//update appointment_schedule table
					if($eachcolumnineachrow[2] =="walk-in" || $eachcolumnineachrow[2] =="walkin_with_premium")
					{
						$update_appointment = "update appointment_schedule set applicant_group_id='$max_new_id', actual_appointment='".$actual."',application_status='pending',remarks='".$eachcolumnineachrow[5]."' , date_of_appointment='$date_today' where reference_number='".$ref_no."' and mission_id='".$_SESSION['mission_id']."'\r\n";
					}
					else
					{
						$update_appointment = "update appointment_schedule set applicant_group_id='$max_new_id', actual_appointment='".$actual."',application_status='shown_up',remarks='".$eachcolumnineachrow[5]."' , date_of_appointment='$date_today' where reference_number='".$ref_no."' and mission_id='".$_SESSION['mission_id']."'\r\n";
					}
					if(mysql_query($update_appointment))
					{
						$success_cnt++;
						//$faild_cnt=0;
					}
					else
					{
						//print json_encode('failed_update_app');
						$faild_cnt++;
					}
					
				}
				else
				{
					//print json_encode('failed_insert_schedule');
					$faild_cnt++;
				}
			}
		}
		//END of FOR Loop
		//Insert Comments
		if($comments!="")
		{
			mysql_query("insert into submission_comments values(DEFAULT,'$max_new_id','AO','".$_SESSION['user_id_ukvac']."','".$comments."','".date('Y-m-d')."','".date("H:i:s")."')");
		}
	}
	//END of IF token exists
	else
	{
		$tkn_extist++;
	}
	//Return To Function in appointments page
	if($success_cnt==count($fulldataarr))
	{
		print json_encode('inserted');
	}
	else if($faild_cnt >0)
	{
		print json_encode('failed_insert');
	}
	else if($tkn_extist >0)
	{
		print json_encode('token_exists');
	}
	
	
	
}


function retrive_AO()
{
	$token_no=$_REQUEST['token_no'];
	date_default_timezone_set("Asia/Bahrain");
	$date_today=date('Y-m-d');
	$data_transfer="";
	//$get_rows = mysql_query("select * from submission_timing where token_no='$token_no' and date_created='$date_today'");
	$get_app_sts=mysql_query("select distinct a.applicant_group_id as applicant_group_id, a.id as id, a.reference_number as reference_number,a.actual_appointment as actual_appointment,a.visa_category as visa_category,a.application_status as application_status,a.remarks as remarks,s.shown_up_remark as shown_up_remark from submission_timing s, appointment_schedule a  where s.token_no='$token_no' and s.date_created='$date_today' and s.reference_id=a.id and s.shown_up_remark not in ('reject') and s.mission_id='".$_SESSION['mission_id']."'");
	
	
	if(mysql_num_rows($get_app_sts)>0)
	{
		while($res_ao_status = mysql_fetch_array($get_app_sts))
		{
			$app_remark="";
			$get_approval_remark = mysql_query("select approval_remark from approvals where mission_id='".$_SESSION['mission_id']."' and ref_id = '".$res_ao_status['id']."'");
			if(mysql_num_rows($get_approval_remark) > 0)
			{
				$approval_remark = mysql_fetch_array($get_approval_remark);
				$app_remark = $approval_remark['approval_remark'];
			}
			else
			{
				$app_remark = "";
			}
			$data_transfer=$data_transfer.$res_ao_status['reference_number'].",".$res_ao_status['actual_appointment'].",".$res_ao_status['visa_category'].",".$res_ao_status['application_status'].",".$res_ao_status['remarks'].",".$res_ao_status['shown_up_remark'].",".$app_remark."__";
		}
		
		$get_applicant_group_id = mysql_fetch_array(mysql_query("select distinct a.applicant_group_id as applicant_group_id from appointment_schedule a, submission_timing s where s.token_no='$token_no' and s.date_created='$date_today' and s.reference_id=a.id and s.mission_id='".$_SESSION['mission_id']."' "));
		$get_comments = mysql_query("select c.comment_station as comment_station, c.comment as comment, c.comment_date as comment_date, c.comment_time as comment_time, u.display_name as display_name from submission_comments c, users u where c.staff_id=u.user_id and c.app_group_id='".$get_applicant_group_id['applicant_group_id']."'" );
		
		
		//file_put_contents('Sample_files/log.txt', "select c.comment_station as comment_station, c.comment as comment, c.comment_date as comment_date, c.comment_time as comment_time, u.display_name as display_name from submission_comments c, users u where c.staff_id=u.user_id and c.app_group_id='".$get_applicant_group_id['applicant_group_id']."'".PHP_EOL , FILE_APPEND | LOCK_EX);
		
		
		if(mysql_num_rows($get_comments) >0)
		{
			$comments = mysql_fetch_array($get_comments);
			$comment_data = $comments['comment_station']."*".$comments['comment']."*".$comments['comment_date']."*".$comments['comment_time']."*".$comments['display_name'];
	
			$data_transfer = $data_transfer.$comment_data;
			//file_put_contents('Sample_files/log.txt', $data_transfer.PHP_EOL , FILE_APPEND | LOCK_EX);
			print json_encode(array("a"=> $data_transfer, "b"=> $get_applicant_group_id['applicant_group_id']));
		}
		else
		{
			$data_transfer = $data_transfer."NoComments";
			print json_encode(array("a"=> $data_transfer, "b"=> $get_applicant_group_id['applicant_group_id']));
		}
		
		
	}
	else
	{
		print json_encode("Nodata");
	}
		
	
}



function mark_as_not_submitted()
{
	$ref_no = $_REQUEST['ref'];
	$reason = $_REQUEST['reason'];
	$reject_type = $_REQUEST['rjtype'];
	if($reject_type=="individual")
	{
		$qr = "update appointment_schedule set application_status='$reason' where reference_number='$ref_no' and mission_id='".$_SESSION['mission_id']."'";
		
		//Get appointment ID
		$get_appointment_tbl_id = mysql_fetch_array(mysql_query("select id, applicant_group_id from appointment_schedule where reference_number='$ref_no' and mission_id='".$_SESSION['mission_id']."'"));
		//Update submission timing for disable old token
		$update_sub_timing = mysql_query("update submission_timing set shown_up_remark='reject' where reference_id = '".$get_appointment_tbl_id['id']."' and mission_id='".$_SESSION['mission_id']."'");
		//delete comments , if any
		$delete_comments = mysql_query("delete from submission_comments where app_group_id = '".$get_appointment_tbl_id['applicant_group_id']."' and mission_id='".$_SESSION['mission_id']."'");
		
		if(mysql_query($qr))
		{
			print json_encode("updated");
		}
		else
		{
			print json_encode("not_updated");
		}
	}
	else if($reject_type=="group")
	{
		$qr = "update appointment_schedule set application_status='$reason' where applicant_group_id='$ref_no' and mission_id='".$_SESSION['mission_id']."'";
		
		//Get appointment ID
		//$get_appointment_tbl_id = mysql_fetch_array(mysql_query("select id from appointment_schedule where applicant_group_id='$ref_no' and mission_id='".$_SESSION['mission_id']."'"));
		//Update submission timing for disable old token
		$update_sub_timing = mysql_query("update submission_timing set shown_up_remark='reject' where applicant_group_id = '$ref_no' and mission_id='".$_SESSION['mission_id']."'");
		
		//delete comments , if any
		$delete_comments = mysql_query("delete from submission_comments where app_group_id = '$ref_no' and mission_id='".$_SESSION['mission_id']."'");
		
		if(mysql_query($qr))
		{
			print json_encode("updated_group");
		}
		else
		{
			print json_encode("not_updated");
		}
	}
	
}

////WORK ON REJECT AND DELETE APPLICATION - WHAT HAPPENS FOR OLD AND NEW TOKEN. WHAT HAPPEN IF SEARCH WITH OLD TOKEN?

function get_oyster_status()
{
	//$barcode = $_GET['glfsno'];
		//echo $barcode;
	if(isset($_POST['cardno']))
	{
        $cardno = $_POST['cardno'];
		//echo $barcode;
		$a = "SELECT id ,status FROM oyster_card_stock WHERE card_no='".$cardno."' and mission_id='".$_SESSION['mission_id']."'";
		$query_gettees = mysql_query($a);
		
		$res = mysql_fetch_array($query_gettees);
		//echo mysql_num_rows($query_gettees);
		if(mysql_num_rows($query_gettees) ==0)
		{
			//echo "<label color='red'>GLFS Not Added In System</label>";
			echo 1;
		}
		else if($res['status']!='in_stock')
		{
			//echo "<label color='red'>GLFS Already Used </label>";
			echo 2;
		}
		else if($res['status']=='in_stock')
		{
			echo 3;
		}
		else
		{
			echo 4;
		}
    }
	else
	{
		echo "Test";
	}
}



/*function walk_in_create()
{
	$reference_no1 = rtrim(trim($_REQUEST['ref_no']),',');
	$pieces = explode(",", $reference_no1);
	$dups = "";
	foreach(array_count_values($pieces) as $val => $c)
	{
		if($c > 1) 
		{
			$dups = $dups.$val.",";
		}
	}
	$pieces = array_unique($pieces, SORT_REGULAR);
	//file_put_contents('Sample_files/log.txt', $reference_no1.PHP_EOL , FILE_APPEND | LOCK_EX);
	//file_put_contents('Sample_files/log.txt', $pieces[0].PHP_EOL , FILE_APPEND | LOCK_EX);

	//ref_no='+ref_no+'&appointment='+appointment+'&remark='+remarks
	$appointment = $_REQUEST['appointment'];
	$remark = $_REQUEST['remark'];
	//$appointment_time = date("G:i");
	$date_today=date('Y-m-d');
	$url = '//'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/approvals.php";
	//$chk_has_appointment
	//get max applicant_group_id
	$get_max_group = mysql_fetch_array(mysql_query("select max(applicant_group_id) as max_id from appointment_schedule where mission_id='".$_SESSION['mission_id']."'")) ;
	$max_new_id=$get_max_group['max_id']+1;
	//Delete preexisting appointments
	$resp_array=array();
	$existing="";
	$has_appointment="";
	$error=0;
	$success="";
	foreach($pieces as $item)
	{
		$reference_no= trim($item);
		$chk_has_appointment = mysql_query("select * from appointment_schedule where reference_number='".$reference_no."' and date_of_appointment='".$date_today."' and mission_id = '".$_SESSION['mission_id']."'");

		$chk_submitted = mysql_query("select * from passport_inscan_record where gwf_number='".$reference_no."' and mission_id = '".$_SESSION['mission_id']."'");
	
		if(mysql_num_rows($chk_submitted) > 0)
		{
			$existing = $existing.$reference_no.",";
			//print json_encode("existing");
				//return;
		}
		else if(mysql_num_rows($chk_has_appointment) > 0)
		{
			$has_appointment = $has_appointment.$reference_no.",";
			//print json_encode("has_app");
			//return;
		}
		else
		{
			$delete_pre = mysql_query("delete from appointment_schedule where reference_number='".$reference_no."'");
			//insert into appointment table as walk-in
			$qr = "insert into appointment_schedule values (DEFAULT,'$max_new_id','$reference_no','','$date_today','0','".strtoupper($appointment)."','".$appointment."','WALK-IN','pending','','".$_SESSION['mission_id']."')";
			if(mysql_query($qr))
			{					
				$GET_ID = mysql_fetch_array(mysql_query("select id from appointment_schedule where reference_number='".$reference_no."' and mission_id='".$_SESSION['mission_id']."'"));
				$add_temp = "insert into approvals values(DEFAULT,'".$_SESSION['mission_id']."','".$GET_ID['id']."','".date('Y-m-d')."','".$_SESSION['user_id_ukvac']."','3','Pending','".$remark."','')";
				mysql_query($add_temp);
				$success=$success.$reference_no.",";
			}
			else
			{
				$error++;
				//print json_encode('error1');
				//return;
			}
		}
		
	}//End Foreach
	if($existing!="")
	{
		$existing=rtrim($existing,',');
	}
	
	if($has_appointment!="")
	{
		$has_appointment = rtrim($has_appointment,',');
	}
	
	if($success!="")
	{
		$success = rtrim($success,',');
	}
	
	if($dups!="")
	{
		$dups = rtrim($dups,',');
	}
	
	
	if($error==0 && strlen($success) >0)
	{
		//Send Email all reference Numbers in one email
		$heading ="Pending Approval - Walk-In Admission";
		$html_for_email = "
			<div>
				<span style='color: #0181BC; padding:5px;'>
					<h4>New Walk-In Admission Approval</h4>
				</span>
				<span style='color:#000;'>
				   <p>Application Reference Number(s) - ".$success." are awaiting for the approval for Walk-In Admission. <br><b>AO Comment:-</b> ".$remark."<br><a href='".$url."'>Click here</a> to login and approve</p>
				</span>
			</div>";
		require_once("Classes/mail/class.email.php");
		$sendmail_obj = new _email_function();
		$sendmail_obj -> html= $html_for_email;
		$sendmail_obj -> heading = $heading;
		$sendmail_obj -> receiver_group = 'alert';
		$sendmail_status = $sendmail_obj ->fire_email();
		if($sendmail_status=='sent')
		{
			$resp_array['stus']='added';
			$resp_array['existing']=$existing;
			$resp_array['has_appointment']=$has_appointment;
			$resp_array['duplicate']=$dups;
			print json_encode($resp_array);
			//return;
		}
		else
		{
			
		}
	}
	else if(strlen($success) ==0)
	{
		if(strlen($existing) > 0 && strlen($has_appointment) ==0)
		{
			$resp_array['stus']='existing';
		}
		else if(strlen($has_appointment) >0 && strlen($existing) == 0)
		{
			$resp_array['stus']='has_app';
		}
		else if (strlen($has_appointment) >0 && strlen($existing) > 0)
		{
			$resp_array['stus']='both';
		}
		$resp_array['existing']=$existing;
		$resp_array['has_appointment']=$has_appointment;
		$resp_array['duplicate']=$dups;
		print json_encode($resp_array);
	}
}*/



function check_pending_token()
{
	date_default_timezone_set("Asia/Bahrain");
	$interval = $_REQUEST['intvl'];
	$date = date('Y-m-d');
	$timenow=date('H:i:s');
	$tkn = "";
	$tkn_bio = "";
	
	$query_get_pending = mysql_query("SELECT st.token_no as token_no, (TIME_TO_SEC(TIMEDIFF('$timenow', st.ao_finish_at))/60) as actual_wait, TIMEDIFF('$timenow', st.ao_finish_at) as wait_hours, a.reference_number as reference FROM submission_timing st , appointment_schedule a WHERE st.reference_id=a.id AND a.application_status = 'shown_up' AND st.date_created = '$date' AND (TIME_TO_SEC(TIMEDIFF('$timenow', st.ao_finish_at))/60) >= '$interval' and st.mission_id='".$_SESSION['mission_id']."' GROUP BY st.token_no ORDER BY st.token_no");
	
	$query_get_pending_bio = mysql_query("SELECT st.token_no as token_no ,(TIME_TO_SEC(TIMEDIFF('$timenow', st.ro_finish_at))/60) as actual_wait , TIMEDIFF('$timenow', st.ao_finish_at) as wait_hours, a.reference_number as reference FROM submission_timing st , appointment_schedule a WHERE st.reference_id=a.id AND a.application_status = 'ro_complete' AND st.date_created = '$date' AND st.ro_finish_at !='00:00:00' AND (TIME_TO_SEC(TIMEDIFF('$timenow', st.ro_finish_at))/60) >= '$interval' and st.mission_id='".$_SESSION['mission_id']."' GROUP BY st.token_no ORDER BY st.token_no");
	if(mysql_num_rows($query_get_pending) > 0)
	{
		while($res_tokens = mysql_fetch_array($query_get_pending))
		{
			$tkn=$tkn.$res_tokens['token_no'].",";
		}
	}
	else
	{
		$tkn="nopending";
	}
	
	if(mysql_num_rows($query_get_pending_bio) > 0)
	{
		while($res_tokens_bio = mysql_fetch_array($query_get_pending_bio))
		{
			$tkn_bio=$tkn_bio.$res_tokens_bio['token_no'].",";
		}
	}
	else
	{
		$tkn_bio="nopending";
	}
	print json_encode(array("ro_pending" =>$tkn, "bio_pending" => $tkn_bio));
}




function update_bio_submit()
{
	$ref = $_REQUEST['reference'];
	$timenow = date("G:i:s");
	$q1 = "update appointment_schedule set application_status='outscan' where reference_number='$ref' and mission_id='".$_SESSION['mission_id']."'";
	$ref_id= mysql_fetch_array(mysql_query("select id from appointment_schedule where reference_number = '$ref' and mission_id='".$_SESSION['mission_id']."'"));
	$q2 = "update submission_timing set bio_finish_at = '$timenow' where reference_id='".$ref_id['id']."' and mission_id='".$_SESSION['mission_id']."'";
	
	if(mysql_query($q1) && mysql_query($q2))
	{
		print json_encode("success");
	}
	else
	{
		print json_encode("error");
	}
}

function update_approval()
{
	$id = $_REQUEST['id'];
	$approve = mysql_query("update approvals set current_status = 'approved' where id = '".$id."'");
	if($approve)
	{
		print json_encode("approved");
	}
	else
	{
		print json_encode("failed");
	}
	
}


function reject_approval()
{
	$id = $_REQUEST['id'];//Applicant_group_id from appointment_schedule table
	$rqst = $_REQUEST['rq'];//Reject Walk-in Request by supervisor
	$ref_no = $_REQUEST['dt']; // MAX ID in the group from appointment_schedule table
	//file_put_contents('Sample_files/log.txt', "id =".$id." rqst=".$rqst." ref_no=".$ref_no.PHP_EOL , FILE_APPEND | LOCK_EX);
	if($rqst == '3')
	{
		$rejected=0;
		$failed=0;
		$comment = $_REQUEST['comment'];
		//Walk-In Reject - delete from appointments and submission timing
		$get_appointment_id = mysql_query("select id, reference_number from appointment_schedule where applicant_group_id='".$id."'");
		
		while($res_appopintmnt = mysql_fetch_array($get_appointment_id))
		{
			$delete_timing = "delete from submission_timing where reference_id = '".$res_appopintmnt['id']."'";
			
			if(mysql_query($delete_timing))
			{
				$update_appointment = "update appointment_schedule set application_status= 'rejected' where reference_number='".$res_appopintmnt['reference_number']."'";
				$reject = "update approvals set current_status = 'rejected',approval_remark='".$comment."' where ref_id = '".$res_appopintmnt['id']."'";
				
				//file_put_contents('Sample_files/log.txt', $update_appointment.PHP_EOL , FILE_APPEND | LOCK_EX);
				//file_put_contents('Sample_files/log.txt', $reject.PHP_EOL , FILE_APPEND | LOCK_EX);
				
				if(mysql_query($update_appointment))
				{
					if(mysql_query($reject))
					{
						$rejected++;
						//print json_encode("ao_rejected");
					}
					else
					{
						$failed++;
						//print json_encode("failed");
					}
				}
				else
				{
					$failed++;
					//print json_encode("failed");
				}
			}
			else
			{
				$failed++;
			}
		}
		if($failed==0)
		{
			print json_encode("ao_rejected");
		}
		else
		{
			print json_encode("failed");
		}
	}
	else
	{
		$reject = "update approvals set current_status = 'rejected' where id = '".$id."'";
		if(mysql_query($reject))
		{
			print json_encode("rejected");
		}
		else
		{
			print json_encode("failed");
		}
	}
}


function approve_walk_in()
{
	$id = $_REQUEST['id'];
	$ref_no = $_REQUEST['dt'];
	$app_type = $_REQUEST['app_type'];
	$comment = $_REQUEST['comment'];
	//update approvals table
	$update = "update approvals set current_status = 'approved' , approval_remark='".$comment."' where id = '".$id."'";
	$update_ao = "update appointment_schedule set appointment_type = '".strtoupper($app_type)."', actual_appointment='".$app_type."', application_status='shown_up' where reference_number='".$ref_no."'";
	if(mysql_query($update) && mysql_query($update_ao))
	{
		print json_encode("accepted");
	}
	else
	{
		print json_encode("failed");
	}
}

function download_excel_submitted_today()
{
	$mission_id = $_REQUEST['mid'];
	$date1 = $_REQUEST['d1'];
	$date2 = $_REQUEST['d2'];
	$qr="SELECT pi.gwf_number as `GWF Number` , pi.application_taken_by as `Application Taken By`, pi.date_outscan as `Submitted On`, pi.date_inscan as `Inscanned On`, pi.delivered_on as `Delivered On`, pi.delivered_by as `Delivered By`, pi.current_status as `Current Status`, glfs.barcode_no as `TEE No` FROM passport_inscan_record pi, tee_envelop_counts glfs WHERE pi.date_outscan >= '$date1' and pi.date_outscan <= '$date2' and pi.mission_id='".$mission_id."' and pi.glfs_id = glfs.id  order by pi.current_status ASC";
						
		/*******EDIT LINES 3-8*******/  
		$DB_TBLName = "passport_inscan_record"; //MySQL Table Name   
		$filename = "Applications submitted ".date('Ymd');         //File Name
		/*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
		$result = mysql_query($qr) or die("Couldn't execute query:<br>".mysql_error()."<br>".mysql_errno());    
		$file_ending = "xls";
		//header info for browser
		header("Content-Type: application/xls");    
		header("Content-Disposition: attachment; filename=$filename.xls");  
		header("Pragma: no-cache"); 
		header("Expires: 0");
		$sep = "\t"; //tabbed character
		//start of printing column names as names of MySQL fields
		print("**Note: This list does not have the Rectification passports received\n");    
		print("\n");
		for ($i = 0; $i < mysql_num_fields($result); $i++) 
		{
			echo mysql_field_name($result,$i) . "\t";
		}
		print("\n");
		while($row = mysql_fetch_row($result))
		{
			$schema_insert = "";
			for($j=0; $j<mysql_num_fields($result);$j++)
			{
				$schema_insert .= $row[$j].$sep;
			}
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\t";
			print(trim($schema_insert));
			print "\n";
			//return;
		}   
}



function download_excel_bts_pwise()
{
	$mission_id = $_REQUEST['mid'];
	$date1 = $_REQUEST['d1'];
	$date2 = $_REQUEST['d2'];
	$qr="SELECT a.product_name as `Product Name`, a.date_sold as `Sold On`, b.display_name as `Sold By`, a.quantity_sold as `Quantity Sold`, CONCAT(a.price_per_item,' (',a.currency,')') as `Unit Price`,CONCAT(ROUND((a.quantity_sold*a.price_per_item),3),' (',a.currency,')') as `Total`,a.gbp_price as `Price (GBP)`,CONCAT(a.quantity_sold*a.gbp_price,' (',a.currency,')') as `Total(GBP)`, a.applicant_name as `Applicant Name`, a.applicant_phone as `Applicant Phone` from bts_sales a, users b WHERE a.date_sold >= '$date1' and a.date_sold <= '$date2' and a.user_id=b.user_id and a.mission_id='".$mission_id."' order by b.display_name ASC";
	
	$get_total = "select concat(currency,' - ',sum(ROUND(quantity_sold*price_per_item,3))) as `Total Sale For the Selected Period`, SUM(ROUND((quantity_sold*gbp_price),3))  as `Total Sales (GBP)` from bts_sales where mission_id= '".$mission_id."' and date_sold >= '$date1' and date_sold <= '$date2' group by currency";
						
		/*******EDIT LINES 3-8*******/  
		$DB_TBLName = "bts_sales"; //MySQL Table Name   
		$filename = "BTS Sales - Item Wise ".date('Ymd');         //File Name
		/*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
		$result = mysql_query($qr) or die("Couldn't execute query:<br>".mysql_error()."<br>".mysql_errno());
		
		$result_total = mysql_query($get_total) or die("Couldn't execute query:<br>".mysql_error()."<br>".mysql_errno());    
		$file_ending = "xls";
		//header info for browser
		header("Content-Type: application/xls");    
		header("Content-Disposition: attachment; filename=$filename.xls");  
		header("Pragma: no-cache"); 
		header("Expires: 0");
		$sep = "\t"; //tabbed character
		//start of printing column names as names of MySQL fields
		for ($x = 0; $x < mysql_num_fields($result_total); $x++) 
		{
			echo mysql_field_name($result_total,$x) . "\t";
		}
		print("\n");
		while($row_total = mysql_fetch_row($result_total))
		{
			$schema_insert = "";
			for($j=0; $j<mysql_num_fields($result_total);$j++)
			{
				$schema_insert .= $row_total[$j].$sep;
			}
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\t";
			print(trim($schema_insert));
			print "\n";
			//return;
		} 
		print("\n");
		print("\n");  
		
		//start of printing column names as names of MySQL fields
		for ($i = 0; $i < mysql_num_fields($result); $i++) 
		{
			echo mysql_field_name($result,$i) . "\t";
		}
		print("\n");
		
		while($row = mysql_fetch_row($result))
		{
			$schema_insert = "";
			for($k=0; $k<mysql_num_fields($result);$k++)
			{
				$schema_insert .= $row[$k].$sep;
			}
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\t";
			print(trim($schema_insert));
			print "\n";
			//return;
		}   
}



/*function create_ao_walkin()
{
	$group_id = $_REQUEST['ref_no'];//Applicant_group_id in appointment_schedule Table
	$time_now= date("G:i");
	$token = $_REQUEST['token'];
	$date_today = date('Y-m-d');
	$failed=0;
	$added=0;
	$get_ref_no = mysql_query("select id,reference_number from appointment_schedule where applicant_group_id='$group_id' and mission_id='".$_SESSION['mission_id']."'");
	$get_token = mysql_query("select token_no from submission_timing where token_no='".$token."' and date_created='".$date_today."' and mission_id='".$_SESSION['mission_id']."'");
	$token_count=mysql_num_rows($get_token);
		
	if($token_count ==0)
	{
		while($ref_no_qr = mysql_fetch_array($get_ref_no))
		{
			$ref_no = $ref_no_qr['reference_number'];
			$get_app_sts=mysql_query("select id,actual_appointment from appointment_schedule where reference_number='$ref_no' and mission_id='".$_SESSION['mission_id']."'");
			
			$get_details=mysql_fetch_array($get_app_sts);
			
			if (strpos($get_details['actual_appointment'], 'Premium') !== false) 
			{
				$app_type='walk-in-premium';
			}
			else
			{
				$app_type='walk-in';
			}
			$insert_appointment_schedule = "insert into submission_timing values (DEFAULT,'".$get_details['id']."','$token','".$group_id."','$date_today','$time_now','$app_type','','','".$_SESSION['mission_id']."')";
						
			if(mysql_query($insert_appointment_schedule))
			{
				//update appointment_schedule table
				$update_appointment = "update appointment_schedule set application_status='shown_up', date_of_appointment='$date_today' where reference_number='".$ref_no."' and mission_id='".$_SESSION['mission_id']."'";
				
					
				if(mysql_query($update_appointment))
				{
					$get_ref_id=mysql_fetch_array(mysql_query("select id from appointment_schedule where reference_number='$ref_no'"));					
					$approve = "update approvals set current_status = 'completed' where ref_id = '".$get_ref_id['id']."'";
					if(mysql_query($approve))
					{
						$added++;
						//print json_encode("added");
					}
					else
					{
						$failed++;
						//print json_encode("failed");
					}
				}
				else
				{
					//print json_encode('failed_update_app');
					//print json_encode("failed");
					$failed++;
				}
							
			}
			else
			{
				//print json_encode('failed_insert_schedule');
				//print json_encode("failed");
				$failed++;
			}
		}
		if($failed==0)
		{
			print json_encode("added");
		}
		else
		{
			print json_encode("failed");
		}
	}
	else
	{
		print json_encode("token_exists");
	}
	
}*/


/*function delete_ao_walkin()
{
	
	$ref_no = $_REQUEST['ref_no'];
	$comment = $_REQUEST['reason'];
	$deleted=0;
	$failed=0;
	//file_put_contents('Sample_files/log.txt', $ref_no."&".$comment.PHP_EOL , FILE_APPEND | LOCK_EX);
	//Walk-In Reject - delete from appointments and submission timing
	$get_appointment_id = mysql_query("select id, reference_number from appointment_schedule where applicant_group_id='".$ref_no."'");
	while($res_ref_ids = mysql_fetch_array($get_appointment_id))
	{
		$delete_timing = "delete from submission_timing where reference_id = '".$res_ref_ids['id']."'";
		if(mysql_query($delete_timing))
		{
			$update_appointment = "update appointment_schedule set application_status= 'rejected' where reference_number='".$res_ref_ids['reference_number']."'";
			$reject = "update approvals set current_status = 'rejected',approval_remark='".$comment."' where ref_id = '".$res_ref_ids['id']."'";
			if(mysql_query($update_appointment))
			{
				if(mysql_query($reject))
				{
					$deleted++;
					//print json_encode("deleted");
				}
				else
				{
					//print json_encode("failed");
					$failed++;
				}
			}
			else
			{
				//print json_encode("failed");\
				$failed++;
			}
		}
		else
		{
			//print json_encode("failed");
			$failed++;
		}
	}
	if($failed==0)
	{
		print json_encode("deleted");
	}
	else
	{
		print json_encode("failed");
	}
}*/


function management_set_mission()
{
	$mission = $_REQUEST['mission'];
	if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=='management' || $_SESSION['role_ukvac']=='accounts'))
	{
		if(isset($_SESSION['mission_id']))
		{
			unset($_SESSION['mission_id']);
		}
		$_SESSION['mission_id'] = $mission;
		print json_encode("session added");
	}
	else
	{
		print json_encode("not management");
	}
}

function submit_edit_del_rq()
{
	$request = $_REQUEST['request'];
	$reference = $_REQUEST['reference'];
	$comment = $_REQUEST['comment'];
	$get_ref_id = mysql_fetch_array(mysql_query("select id from appointment_schedule where reference_number= '".$reference."'"));
	$get_approval_status= mysql_query("select current_status from approvals where ref_id='".$get_ref_id['id']."' and mission_id = '".$_SESSION['mission_id']."' and status='1' and current_status IN ('approved','pending')");
	if(mysql_num_rows($get_approval_status) >0)
	{
		$get_approval_status_res = mysql_fetch_array($get_approval_status);
		$status = $get_approval_status_res['current_status'];
	}
	else
	{
		$status = "Zero";
	}
	if($status !="Zero")
	{
		print json_encode("pending_approval");
	}
	else
	{
		if($request=="e")
		{
			//Request to edit
			
			if(mysql_query("insert into approvals values (DEFAULT,'".$_SESSION['mission_id']."','".$get_ref_id['id']."','".date('Y-m-d')."','".$_SESSION['user_id_ukvac']."','1','Pending','".$comment."','')"))
			{
				//Email alert
				$url = '//'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/approvals.php";
					$heading ="Pending Approval - Edit VAS";
					$html_for_email = "
					<div>
						<span style='color: #0181BC; padding:5px;'>
							<h4>Application Edit Approval</h4>
						</span>
						<span style='color:#000;'>
							<p>
							Application Reference Number - ".$reference." is awaiting approval for Editing. <br><a href='".$url."'>Click here</a> to login and approve 
							<br>
							<b>Requested By :</b>".$_SESSION['display_name_ukvac']."
							<br>
							<b>Reason for Edit :</b>".$comment."
							</p>
						</span>
					</div>";
					require_once("Classes/mail/class.email.php");
					$sendmail_obj = new _email_function();
					$sendmail_obj -> html= $html_for_email;
					$sendmail_obj -> heading = $heading;
					$sendmail_obj -> receiver_group = 'alert';
					$sendmail_status = $sendmail_obj ->fire_email();
					if($sendmail_status=='sent')
					{
						print json_encode("submitted");
					}
					else
					{
						print json_encode("error");
					}
			}
		}
		else if($request=="d")
		{
			//Request to edit
			
			if(mysql_query("insert into approvals values (DEFAULT,'".$_SESSION['mission_id']."','".$get_ref_id['id']."','".date('Y-m-d')."','".$_SESSION['user_id_ukvac']."','2','Pending','".$comment."','')"))
			{
				$url ='//'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']."/approvals.php");   
					$heading ="Pending Approval - Delete Application";
					$html_for_email = "
					<div>
						<span style='color: #0181BC; padding:5px;'>
							<h4>Application Deletion Approval</h4>
						</span>
						<span style='color:#000;'>
							<p>
							Application Reference Number - ".$reference." is awaiting for the approval for Deleting. <br><a href='".$url."'>Click here</a> to login and approve
							<br>
							<b>Requested By :</b>".$_SESSION['display_name_ukvac']." 
							<br>
							<b>Reason for Deleting :</b>".$comment."
							</p>
						</span>
					</div>";
					require_once("Classes/mail/class.email.php");
					$sendmail_obj = new _email_function();
					$sendmail_obj -> html= $html_for_email;
					$sendmail_obj -> heading = $heading;
					$sendmail_obj -> receiver_group = 'alert';
					$sendmail_status = $sendmail_obj ->fire_email();
					if($sendmail_status=='sent')
					{
						print json_encode("submitted");
					}
					else
					{
						print json_encode("error");
					}
			}
		}
	}
}

