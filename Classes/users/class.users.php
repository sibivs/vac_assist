<?php
class _user_functions
{
	public $staff_login;
	public $display_name;
	public $new_pswd;
	public $user_role;
	public $mission_name;
	public $dateof_birth;
	
	public function add_new_user()
	{
		$qr_chk_duplicate=mysql_query("select count(*) as count from users where username ='".$this->staff_login."'");
		$result_duplicate = mysql_fetch_array($qr_chk_duplicate);
		if($result_duplicate['count']>0)
		{
			mysql_close();
			return "exists";
		}
		else
		{
			$missions_selected = explode(",",$this->mission_name);
			$yes=0;
			$no=0;
			for($i=0; $i< count($missions_selected); $i++)
			{
				$qr="INSERT INTO users VALUES ('NULL','".$this->staff_login."','".$this->new_pswd."', '".$this -> display_name."','".$this-> user_role."','active','".$missions_selected[$i]."','".$this->dateof_birth."')";
				if(mysql_query($qr))
				{
					$yes++;
				}
				else
				{
					$no++;
				}
			}
			if($no==0)
			{
				mysql_close();	
				return "true";
			}
			else
			{
				mysql_close();
				return "failed";
			}
		}
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
	$page = $_REQUEST['pn'];
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

	}
}

}
?>