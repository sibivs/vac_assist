<?php
class _login_function
{
	public $uname;
	public $pwd;
	public $mission_select;
	public function login()
	{
		//include_once("../../db_connect.php");
		$qry=mysql_query("select * from users where username='".$this ->uname."' and password='".$this ->pwd."' and mission_id='".$this ->mission_select."' and status='active'");
		$value=mysql_fetch_array($qry);
		if($value['username']===$this ->uname && $value['password']===$this ->pwd && $value['mission_id']== $this ->mission_select)
		{
			//$_SESSION['mission_id']=$value['mission_id'];
			$_SESSION['mission_id']=$this -> mission_select;
			$_SESSION['home'] = "index_home.php";
			if($value['role']=='administrator' || $value['role']=='staff')
			{
				$get_mission_name = mysql_fetch_array(mysql_query("select mn.mission_name as mission_name, c.country AS country , cn.city_name as city_name from missions mn, country c, country_cities cn  where mn.id='".$this -> mission_select."' and mn.country_id=c.id and mn.city=cn.id"));
				$_SESSION['vac']=$get_mission_name['mission_name'];
				$_SESSION['vac_city']=$get_mission_name['city_name'];
				$_SESSION['vac_country']=$get_mission_name['country'];
				$disabled_string = "";
				$get_disabled_pages = mysql_query("select pa.file_name as filename from mission_page_disabled pd, pages_associated pa where pd.mission_id='".$value['mission_id']."' and pd.page_id=pa.id and pd.status='pg_disabled'");
				if(mysql_num_rows($get_disabled_pages) >0)
				{
					while($disabled_pgs = mysql_fetch_array($get_disabled_pages))
					{
						$disabled_string = $disabled_string.$disabled_pgs['filename'].",";
					}
					$disabled_string = rtrim($disabled_string, ',');
					$_SESSION['disabled_string'] = $disabled_string;
				}
				else
				{
					$_SESSION['disabled_string'] = "nothing";
				}
			}
			else
			{
				$_SESSION['vac_city']="General";
				$_SESSION['vac_country']="Global";
				$_SESSION['vac']='Management Console';
			}
			$_SESSION['last_activity_ukvac'] = time();
			
			
			if($value['role']=='administrator')
			{
				$_SESSION['user_id_ukvac'] =$value['user_id']; 
				$_SESSION['uid_ukvac']=$value['username'];
				$_SESSION['display_name_ukvac']=$value['display_name'];
				$_SESSION['role_ukvac']="administrator";
				return "auth_true";
	
			}
			else if($value['role']=='power_admin')
			{
				$_SESSION['user_id_ukvac'] =$value['user_id']; 
				$_SESSION['uid_ukvac']=$value['username'];
				$_SESSION['display_name_ukvac']=$value['display_name'];
				$_SESSION['role_ukvac']="power_admin";
				return "auth_true_power";
	
			}
			else if($value['role']=='staff')
			{
				$_SESSION['user_id_ukvac'] =$value['user_id']; 
				$_SESSION['uid_ukvac']=$value['username'];
				$_SESSION['display_name_ukvac']=$value['display_name'];
				$_SESSION['role_ukvac']="staff";
				return "auth_true";
			}
			else if($value['role']=='passback')
			{
				$_SESSION['user_id_ukvac'] =$value['user_id']; 
				$_SESSION['uid_ukvac']=$value['username'];
				$_SESSION['display_name_ukvac']=$value['display_name'];
				$_SESSION['role_ukvac']="passback";
				return "auth_true";
			}
			else if($value['role']=='management')
			{
				$_SESSION['user_id_ukvac'] =$value['user_id']; 
				$_SESSION['uid_ukvac']=$value['username'];
				$_SESSION['display_name_ukvac']=$value['display_name'];
				$_SESSION['role_ukvac']="management";
				return "auth_true_mg";
	
			}
			else if($value['role']=='accounts')
			{
				$_SESSION['user_id_ukvac'] =$value['user_id']; 
				$_SESSION['uid_ukvac']=$value['username'];
				$_SESSION['display_name_ukvac']=$value['display_name'];
				$_SESSION['role_ukvac']="accounts";
				return "auth_true_mg";
			}
		}
		else
		{
			return "auth_false";
		}
	//END FOR NORMAL LOGIN USING MYSQL
	
	}
	
	public function __logout()
	{
		$expire_time = 3600; //expire time
		if((isset($_SESSION['last_activity_ukvac'] )) && ($_SESSION['last_activity_ukvac'] < (time()-$expire_time))) 
		{
			unset($_SESSION['last_activity_ukvac']);
			unset($_SESSION['user_id_ukvac']);
			unset($_SESSION['uid_ukvac']);
			unset($_SESSION['display_name_ukvac']);
			unset($_SESSION['role_ukvac']);
			unset($_SESSION['err_sts_ukvac']);
			unset($_SESSION['response_ukvac']);
			unset($_SESSION['vac']);
			unset($_SESSION['mission_id']);
			unset($_SESSION['disabled_string']);
			unset($_SESSION['home']);
			unset($_SESSION['vac_city']);
			unset($_SESSION['vac_country']);
			return "timeout";
		}
		else if(isset($_SESSION['last_activity_ukvac'])&& ($_SESSION['last_activity_ukvac'] >= (time()-$expire_time)))
		{
			unset($_SESSION['last_activity_ukvac']);
			unset($_SESSION['user_id_ukvac']);
			unset($_SESSION['uid_ukvac']);
			unset($_SESSION['display_name_ukvac']);
			unset($_SESSION['role_ukvac']);
			unset($_SESSION['err_sts_ukvac']);
			unset($_SESSION['response_ukvac']);
			unset($_SESSION['vac']);
			unset($_SESSION['mission_id']);
			unset($_SESSION['disabled_string']);
			unset($_SESSION['home']);
			unset($_SESSION['vac_city']);
			unset($_SESSION['vac_country']);
			return "end_true";
		}
		else
		{
			return "timeout";
		}
	}
}
?>