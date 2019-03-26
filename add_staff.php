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
	header("Location:login.php");
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
include_once("db_connect.php");
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator" || $_SESSION['role_ukvac']=="power_admin")&&  isset($_SESSION['vac']))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VAC Assist - <?php echo $_SESSION['vac']; ?></title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script type="text/javascript" src="Scripts/jquery.style_en.js"></script>
<link rel="stylesheet" href="styles/style.css">
<link rel="stylesheet" href="menu/css/style_menu.css">


<!-- for new Calandar control -->

<link rel="stylesheet" type="text/css" media="all" href="styles/jsDatePick_ltr.min.css" />
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script type="text/javascript" src="Scripts/jsDatePick.min.1.3.js"></script>

<!-- End for new Calandar control -->



<script src="Scripts/script.js"></script>
<script type="text/javascript">

$(document).ready(function(e) {
    changed_role();
	load_calendar();
});

function changed_role()
{
	var user_role = $("#add_user_role").val();
	if(user_role=="administrator" || user_role=="staff" || user_role=="passback")
	{
		$("#disply_mission").show();
	}
	else
	{
		$("#disply_mission").hide();
		//$("#add_user_role").prop('selectedIndex', 0);
	}
}

function add_user()
{
	var mission = $("#mission_name").val();
	alert(mission)
	var user_id=$("#add_user_uid").val();
	var display_name = $("#add_user_name").val();
	var new_pswd = $("#add_user_pwd1").val();
	var new_pswd2 = $("#add_user_pwd2").val();
	var user_role = $("#add_user_role").val();
	var dob = $("#dob_staff").val();
	var count=0;

//alert(mission); result //- 5, 12

	if(user_role=="administrator" || user_role=="staff" || user_role=="passback" )
	{
		if(mission=="" || mission==null)
		{
			$("#mission_name").css('border-color','red');
			$("#add_user_role").css('border-color','#ccc'); // clear error in user role since user role is selected
			count++;
		}
		else
		{
			$("#mission_name").css('border-color','#ccc');
			$("#add_user_role").css('border-color','#ccc');
		}
	}
	else if(user_role=="accounts" || user_role=="management" || user_role=="power_admin")
	{
		mission = "0";
	}
	else if(user_role=="select")
    {
   		$("#add_user_role").css('border-color','red');
    	count++;
    }
	else
	{
		$("#add_user_role").css('border-color','#ccc');
	}
	
	if(user_id=="")
    {
   		$("#add_user_uid").css('border-color','red');
    	count++;
    }
	else if(user_id.match(/\s/g))
    {
    	$("#add_user_uid").css('border-color','red');
    	count++;
	}
	else
	{
		$("#add_user_uid").css('border-color','#ccc');
	}
	
	if(display_name=="")
    {
    	$("#add_user_name").css('border-color','red');
        count++;
    }
	else
	{
		$("#add_user_name").css('border-color','#ccc');
	}
	
	if(new_pswd=="")
    {
		
		$("#add_user_pwd1").css('border-color','red');
        count++;
    }
	else
	{
		$("#add_user_pwd1").css('border-color','#ccc');
	}
	
	if(new_pswd!=new_pswd2 || new_pswd2 == "")
    {
     	$("#add_user_pwd2").css('border-color','red');
        count++;
    }
	else
	{
		$("#add_user_pwd2").css('border-color','#ccc');
	}
	
	if(dob=="")
    {
		
		$("#dob_staff").css('border-color','red');
        count++;
    }
	else
	{
		$("#dob_staff").css('border-color','#ccc');
	}
	
	if(count>=1)
    {
      return;
    }
    else
    {
		$.ajax(
			{
				type: 'post',
				url: 'php_func.php',
				data: 'cho=3&add_user_uid='+user_id+'&add_user_name='+display_name+'&add_user_pwd1='+new_pswd+'&add_user_role='+user_role+'&mission_name='+mission+'&dob='+dob,
				dataType:'json',
				success: function(result)
				{
					if(result==="true")
					{
						$("#result").html("<label style='color:green;'> User is Added </label>");
					}
					else if(result==="failed")
					{
						$("#result").html("<label style='color:red;'> Something went wrong.</label>");
					}
					else if(result==="exists")					
					{
						$("#result").html('Please choose another username');
					}
					else if(result=='timeout')
					{
						alert('Session Expired. Please Login!')
						window.location.href='login.php';
						window.location.reload();
					}
					setTimeout(function(){ window.location.reload();}, 2000);
				},
				error: function(result)
				{
     				$("#result").html(JSON.stringify(result));
  				}
								
			});
    }
}

function load_calendar()
{
	input = new JsDatePick({
			useMode:2,
			limitToToday:true,
			target:"dob_staff",
			dateFormat:"%Y-%F-%d",
			cellColorScheme:"ocean_blue"
			});
}

function pg_reload()
{
	document.form1.action="add_staff.php";
	document.form1.submit();
}

</script>

</head>

<body>
<div id="templatemo_container">
	<div id="templatemo_header">
		<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">VAC ASSIST
        <?php
		if($_SESSION['role_ukvac']=="power_admin")
		{
			?>
			 - Management Console
            <?php
		}
		else
		{
		?>
        <br><label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label>
        <?php
		}
		?>
        </label>
    </div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("menu/left_menu.php"); ?></div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>   
	<div id="templatemo_content">
		<script type="text/javascript" language="javascript">
				
				</script>
		<div id="" style="width: 100% !important; " align="center;">
			<div style="width: 100% !important;" align="center;">
				<div align="center"> <label class='text-info h4'>ADD A NEW STAFF</label></div>
      			</div>
                <div align="center" style="margin-top: 5px;">
                <form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
				<div id="result" style="height: 35px; vertical-align:middle;">
                </div>
                <div style="width:100%;">    
                    <table class="table" style="border-top: 0px !important; width: 60%;" >
						<tr>
							<td width="15%">Login ID</td>
							<td width="5%">:</td>
                            <td align="left" width="40%">
                            	<input type="text" name="add_user_uid" id="add_user_uid" class="form-control" style="width:220px;display:inline;">
                            </td>
						</tr>
                       	<tr>
							<td >Display Name</td>
							<td >:</td>
                            <td align=left>
                            	<input type="text" name="add_user_name" id="add_user_name" class="form-control" style="width:220px; display:inline;">
                         	</td>
						</tr>
                        <tr>
							<td>Password</td>
							<td >:</td>
                            <td align=left >
                            	<input type="password" name="add_user_pwd1" id="add_user_pwd1" class="form-control" style="width:220px; display:inline;">
                         	</td>
						</tr>
                        <tr>
							<td>Retype Password</td>
							<td >:</td>
                            <td align=left >
                            	<input type="password" name="add_user_pwd2" id="add_user_pwd2" class="form-control" style="width:220px; display:inline;">
                          	</td>
						</tr>
                        <tr>
							<td>User Role</td>
							<td >:</td>
                            <td align=left >
                            <?php
                            if($_SESSION['role_ukvac']=="power_admin")
                            {
							?>
                            	<select name="add_user_role" id="add_user_role" class="form-control" style="width:220px; display:inline;" onChange="changed_role()">
                                	<option value="select">Select Role</option>
                                    <option value="accounts">Accounts Staff</option>
                                    <option value="administrator">Supervisor</option>
                                    <option value="management">Management User</option>
                                    <option value="staff" >RO Staff</option>
                                    <option value="passback" >Passback Staff</option>
                                    <option value="power_admin">Power Administrator</option>
                    			</select>
                            <?php
							}
							else if($_SESSION['role_ukvac']=="administrator")
                            {
							?>
                            <select name="add_user_role" id="add_user_role" class="form-control" style="width:220px; display:inline;" onChange="changed_role()">
                            	<option value="select">Select Role</option>
								<option value="staff">RO Staff</option>	
                                <option value="passback" >Passback Staff</option>
                           	</select>
							<?php
							}
							?>
                            </td>
						</tr>
                        <tr id="disply_mission">
							<td>Mission Name</td>
							<td >:</td>
                            <td align="left">
                            	<select multiple id="mission_name" name="mission_name" class="form-control" style="width: 220px; display:inline;" >
									<?php
                                    if($_SESSION['role_ukvac']=="power_admin")
                                    {
                                        $get_missions = mysql_query("select mn.id as id, mn.mission_name as mission_name , c.city_name as city_name from missions mn, country_cities c where mn.status='active' and mn.city=c.id");
                                        while($get_result = mysql_fetch_array($get_missions))
                                        {
                                            ?>
                                            <option value="<?php echo $get_result['id'] ?>"><?php echo $get_result['mission_name']." VAC - ".$get_result['city_name']; ?></option>
                                            <?php
                                        }
                                    
                                    }
                                    else if($_SESSION['role_ukvac']=="administrator")
                                    {
                                        $mymissionid=$_SESSION['mission_id'];
                                        $get_missions = mysql_query("select mn.id as id, mn.mission_name as mission_name , c.city_name as city_name from missions mn, country_cities c where mn.id='$mymissionid' and mn.city=c.id");
                                        $get_result = mysql_fetch_array($get_missions);
                                        ?>
                                        <option value="<?php echo $get_result['id'] ?>"><?php echo $get_result['mission_name']." VAC - ".$get_result['city_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                            	</select>
                                <label class="text-info" style="font-size: 12px;">Press and Hold "Ctrl" Button while selecting multiple Missions</label>
                            </td>
						</tr>
            			<tr>
							<td>Date Of Birth</td>
                            <td>:</td>
                            <td><input type="text" class="form-control" style="width:220px;" name="dob_staff" id="dob_staff" placeholder="Select Date Of Birth"></td>
						</tr>
                        <tr>
							<td colspan="3" height="10px" style="border-top: 0px;"></td>
						</tr>
                        <tr>
							<td colspan="3" style="text-align: center;" >
                            	<input type="button" onClick="add_user()" value="Add New Staff" class="btn btn-info"> &nbsp; &nbsp; &nbsp;
                            	<input type="button" class="btn btn-danger" value="Cancel" onclick="pg_reload()">
                          	</td>
						</tr>
                        <tr>
							<td colspan="3" height="10px" style="border-top: 0px;"></td>
						</tr>
                    </table>
                    </div>
                    </form>

				</div>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            <!--img src="styles/images/powered-by3.png" style=" width: 156px; height: auto; padding-top: 10px;" /-->
		</div>
	</div>
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