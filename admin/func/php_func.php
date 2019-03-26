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
			if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="power_admin"))){ add_mission(); }else{ header("Location:index.php"); }
			break;
			
		case 2:
			if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="power_admin"))){ delete_mission(); }else{ header("Location:index.php"); }
			break;

			case 3:
			if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="power_admin"))){ update_mission(); }else{ header("Location:index.php"); }
			break;
			
			case 4:
			if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="power_admin"))){ get_disabled(); }else{ header("Location:index.php"); }
			break;
			
			case 5:
			if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="power_admin"))){ disable_pages(); }else{ header("Location:index.php"); }
			break;
			
			case 6:
			if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="power_admin"))){ add_new_conversion(); }else{ header("Location:index.php"); }
			break;
			
			case 7:
			if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="power_admin"))){ update_conversion(); }else{ header("Location:index.php"); }
			break;
			
			case 8:
			if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="power_admin"))){ delete_conversion(); }else{ header("Location:index.php"); }
			break;
			
			case 9:
			if(isset($_SESSION['role_ukvac']) && (($_SESSION['role_ukvac']=="power_admin"))){ get_cities(); }else{ header("Location:index.php"); }
			break;
			
			case 10:
			if(isset($_SESSION['role_ukvac'])){ logout(); }else{ header("Location:index.php"); }
			break;
			
			case 11:
			if(isset($_SESSION['role_ukvac'])){ get_mission_list(); }else{ header("Location:index.php"); }
			break;
			
			case 12:
			if(isset($_SESSION['role_ukvac'])){ get_custom_permissions(); }else{ header("Location:index.php"); }
			break;
			
			case 13:
			if(isset($_SESSION['role_ukvac'])){ update_custom_permissions(); }else{ header("Location:index.php"); }
			break;
			
			case 14:
			if(isset($_SESSION['role_ukvac'])){ get_user_id(); }else{ header("Location:index.php"); }
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


function logout()
{
	//session_destroy();
	require_once("../../Classes/login/class.login.php");
	$login_obj = new _login_function();
	$logout_status =$login_obj ->__logout();
	print json_encode($logout_status);	
}

function get_currencies()
{
	$mission = $_REQUEST['name'];
	$currency_array= "";
	$get_currency = mysql_fetch_array(mysql_query("select currencies from missions where id='$mission'"));
	print json_encode($get_currency['currencies']);	
}



function add_mission()
{
	//name='+newmission']; $currency= $_REQUEST['currency
	$new_name = $_REQUEST['name'];
	$currency = $_REQUEST['currency'];
	$country = $_REQUEST['country'];
	$city = $_REQUEST['city'];
	$query_add_mission = "insert into missions values(DEFAULT,'$new_name','$currency','$country','$city','active')";
	if(mysql_query($query_add_mission))
	{
		print json_encode("added");
	}
	else
	{
		print json_encode("failed");
	}
}

function update_mission()
{ 
	$edit_mission= $_REQUEST['name'];
	$edit_currency=$_REQUEST['currency'];
	$row_id = $_REQUEST['id'];
	$query_changestatus = "update missions set mission_name='$edit_mission', currencies='$edit_currency' where id =$row_id ";
	if(mysql_query($query_changestatus))
	{
		mysql_close();
		print (json_encode("updated"));
	}
	else
	{
		echo mysql_error();
		mysql_close();
		print (json_encode("failed"));
	}
}


function delete_mission()
{
	$mission_id = $_REQUEST['id'];
	$query_deactivate = "update missions set status='inactive' where id='$mission_id'";
	if(mysql_query($query_deactivate))
	{
		mysql_close();
		print (json_encode("deleted"));
	}
	else
	{
		echo mysql_error();
		mysql_close();
		print (json_encode("failed"));
	}
}

function get_disabled()
{
	$mission = $_REQUEST['mission'];
	$disabled = array();
	$i=0;
	$chk_disabled_now = mysql_query("select page_id from mission_page_disabled where mission_id ='$mission' and status='pg_disabled'");
	if(mysql_num_rows($chk_disabled_now) > 0)
	{
		while($res_disabled = mysql_fetch_array($chk_disabled_now))
		{
			$disabled[$i]=$res_disabled['page_id'];
			$i++;
		}
		print json_encode($disabled);
	}
	else
	{
		$disabled[$i]="nothing";
		$disabled[$i+1]="nothing1";
		print json_encode($disabled);
	}
}



function disable_pages()
{
	$data = json_decode($_REQUEST['data']);
	$mission = $_REQUEST['mission'];
	if(count($data)==0)
	{
		$b = "update mission_page_disabled set status='pg_enabled' where status='pg_disabled' and mission_id='$mission'";
		mysql_query($b);
		//Enable All
	}
	else
	{
		$pages= mysql_query("select id from pages_associated");
		if(mysql_num_rows($pages) > 0)
		{
			while($res_pagges = mysql_fetch_array($pages))
			{
				$page_exist = mysql_query("select page_id , status from mission_page_disabled where mission_id='$mission' and page_id='".$res_pagges['id']."'");
				$rows = mysql_num_rows($page_exist);
				$res = mysql_fetch_array($page_exist);
				if(in_array($res_pagges['id'],$data) &&  $rows >0 && $res['status']=="pg_disabled")
				{
					
				}
				if(in_array($res_pagges['id'],$data) &&  $rows >0 && $res['status']=="pg_enabled")
				{
					$a = "update mission_page_disabled set status='pg_disabled' where status='pg_enabled' and mission_id='$mission' and page_id='".$res_pagges['id']."'";
					mysql_query($a);
					//disable the page ie pg_disabled
				}
				else if(!in_array($res_pagges['id'],$data) && $rows >0 && $res['status']=="pg_disabled")
				{
					$b = "update mission_page_disabled set status='pg_enabled' where status='pg_disabled' and mission_id='$mission' and page_id='".$res_pagges['id']."'";
					mysql_query($b);
					//enable the page ie pg_enabled
				}
				else if(in_array($res_pagges['id'],$data) && $rows ==0)
				{
					$c ="insert into mission_page_disabled values(DEFAULT,'$mission','".$res_pagges['id']."','pg_disabled')";
					mysql_query($c);
					//insert
				}
				
			}
			print json_encode("updated");
		}
	}
}


function add_new_conversion()
{
	$curr= $_REQUEST['curr'];
	$exch =$_REQUEST['exch'];
	$get_existing = mysql_num_rows(mysql_query("select * from exchange_rates where currency= '$curr'"));
	if($get_existing >0)
	{
		print json_encode("currency_exists");
	}
	else
	{
		$insert_conv_rate = "insert into exchange_rates values(DEFAULT,'$curr','$exch')";
		if(mysql_query($insert_conv_rate))
		{
			print json_encode("added");
		}
		else
		{
			print json_encode(mysql_error());
		}
	}
	
}

function update_conversion()
{
	$id = $_REQUEST['id'];
	$curr= $_REQUEST['curr'];
	$exch =$_REQUEST['exch'];
	$update = "update exchange_rates set currency='$curr', exchng_rate_wt_gbp='$exch' where id='$id'";
	if(mysql_query($update))	
	{
		print json_encode("updated");
	}
	else
	{
		print json_encode(mysql_error());
	}
}

function delete_conversion()
{
	$id = $_REQUEST['id'];
	$delete = "delete from exchange_rates where id='$id'";
	if(mysql_query($delete))	
	{
		print json_encode("deleted");
	}
	else
	{
		print json_encode(mysql_error());
	}
}


function get_cities()
{
	$country_id=$_REQUEST['country_id'];
	$str = '<select id="city" name="city" class="form-control" style="width: 210px;"><option value="select">Select City</option>';
	$str1="";
	$get_currency1 = mysql_query("select * from country_cities where country_id='$country_id'");
	while($res_curr1=mysql_fetch_array($get_currency1))
	{
    	$str1 .="<option value=".$res_curr1['id'].">". $res_curr1['city_name']."</option>";
	}
	$str2="</select>";
	$full_dropedwen = $str.$str1.$str2;
	print json_encode($full_dropedwen);
}



function get_mission_list()
{
	$display_name = $_REQUEST['display_name'];
	$get_mission_list_qr = mysql_query("select u.user_id as user_id, m.id as id, m.mission_name as mission_name from missions m , users u where u.display_name='".$display_name."' and u.mission_id=m.id ORDER BY m.mission_name ASC");
	$str = '<select id="mission" name="mission" class="form-control" style="width: 210px;" onchange="show_button()"><option value="select">Select Mission</option>';
	$str1="";
	while($get_mission_list_res=mysql_fetch_array($get_mission_list_qr))
	{
    	$str1 .="<option value=".$get_mission_list_res['id'].">". $get_mission_list_res['mission_name']."</option>";
	}
	$str2="</select>";
	$full_dropedown = $str.$str1.$str2;
	print json_encode($full_dropedown);
	
}


function get_user_id()
{
	$display_name = $_REQUEST['display_name'];
	$mission_id = $_REQUEST['mission_id'];
	$get_user_id = mysql_fetch_array(mysql_query("select user_id from users where display_name='$display_name' and mission_id='$mission_id'"));
	print json_encode($get_user_id);
	
}


function get_custom_permissions()
{
	$user_id = $_REQUEST['user_id'];
	$mission_id = $_REQUEST['mission_id'];
	$getcustom_permissions = mysql_query("select * from custom_permissions where user_id='$user_id' and mission_id='$mission_id'");
	if(mysql_num_rows($getcustom_permissions) <=0)
	{
		print json_encode("rowcountzero");
	}
	else
	{
		$get_result = mysql_fetch_array($getcustom_permissions);
		print json_encode($get_result);
	}
}


function update_custom_permissions()
{
	$user_id = $_REQUEST['user_id'];
	$mission_id = $_REQUEST['mission_id'];
	$rowcount = $_REQUEST['rowcount'];
	
	$approval_manage= $_REQUEST['approval_manage']; 
	$approval_view= $_REQUEST['approval_view'];
	$appointment_manage= $_REQUEST['appointment_manage']; 
	$inscan_manage= $_REQUEST['inscan_manage']; 
	$tee_manage= $_REQUEST['tee_manage'];
	$tee_view= $_REQUEST['tee_view'];
	$oyster_manage= $_REQUEST['oyster_manage'];
	$oyster_view= $_REQUEST['oyster_view'];
	$vas_manage= $_REQUEST['vas_manage'];
	$vas_view= $_REQUEST['vas_view'];
	$email_manage= $_REQUEST['email_manage'];
	$email_view= $_REQUEST['email_view'];
	$user_list_manage= $_REQUEST['user_list_manage'];
	$user_list_view= $_REQUEST['user_list_view'];
	$user_add= $_REQUEST['user_manage']; 
	

	if($rowcount==0)
	{
		//insert
		$insert_custom_permission = "insert into custom_permissions values(DEFAULT,'$user_id','$mission_id','$approval_manage','$approval_view','$appointment_manage','$inscan_manage','$tee_manage','$tee_view','$oyster_manage','$oyster_view','$vas_manage','$vas_view','$email_manage','$email_view','$user_list_manage','$user_list_view','$user_add')";
		if(mysql_query($insert_custom_permission))
		{
			print json_encode("added_custom_permissions");
		}
		else
		{
			print json_encode("error_custom_permissions");
		}
	}
	else
	{
		//update
		$query_update_custom_permission = "update custom_permissions set approval_walk_in='$approval_manage',approval_view_pending='$approval_view',appointment_upload='$appointment_manage',inscan_passports='$inscan_manage',tee_env_upload='$tee_manage',tee_env_view='$tee_view',oyster_upload='$oyster_manage',oyster_stock_view='$oyster_view',manage_service_change='$vas_manage',manage_service_view='$vas_view',manage_email_recievers='$email_manage',view_email_recepients='$email_view',manage_users_edit='$user_list_manage',manage_users_view='$user_list_view',add_users='$user_add' where user_id=$user_id and mission_id='$mission_id'";
		if(mysql_query($query_update_custom_permission))
		{
			print json_encode("updated_custom_permissions");
		}
		else
		{
			print json_encode("error_custom_permissions");
		}
	}
}
		
?> 
