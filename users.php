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
<link rel="stylesheet" href="styles/style.css">
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>
<script type="text/javascript" src="Scripts/jquery.style_en.js"></script>
<script type="text/javascript">
function delete_user(id, pno)
{
        var agree= confirm("Are you sure you want to delete this User?");
        if(agree)
        {
			$.ajax(
			{
				type: 'post',
			  	url: 'php_func.php',
				data: 'cho=5&member_id='+id,
			  	dataType:"json",
				success: function(result)
				{
					$("#reset_pwd_div").html("<span class='text-success h4'>User Removed Succesfully</span>");
					$("#reset_pwd_div").height('50px');
					loadPopupBox_update_pwd();
					setTimeout(function(){unloadPopupBox_update_pwd(); }, 1500);
				
				},
				error: function(request, status, error)
				{
					$("#reset_pwd_div").html("<span class='text-warning h4'>Something Went Wrong. Please contact Administrator!</span>");
					loadPopupBox_update_pwd();
					setTimeout(function(){unloadPopupBox_update_pwd(); }, 1500);
				}
				
			});
        }
        else
        {
        window.location.reload();
        }
}


function reset_password(id,name)
{
	
	loadPopupBox_update_pwd();
	//document.getElementById("reset_pwd").style.display= "block";
	$("#user_id_hidden").val(id);
	$("#displayname").html(name);
}

function submit_reset_pwd()
{
	var user_id=$("#user_id_hidden").val();
	var new_pswd = $.md5($("#password1").val());
	var new_pswd2 = $.md5($("#password2").val());
	if(new_pswd !="" && new_pswd2 !="" && new_pswd==new_pswd2)
	{
		$('#password1').css("border-color", "#CCC");
		$('#password2').css("border-color", "#CCC");
		var agree= confirm("Are you sure you want to reset password for this User?");
		if(agree)
        {
			alert('cho=4&user='+user_id+'&passwd='+new_pswd);
			$.ajax(
			{
				type: 'post',
			  	url: 'php_func.php',
				data: 'cho=4&user='+user_id+'&passwd='+new_pswd,
			  	dataType:"json",
				success: function(result)
				{
					if(result=="changed")
					{
						setTimeout(function(){unloadPopupBox_update_pwd(); }, 1500);
						$("#resp").html("<p style='color: purple; font-size: 13px;'>Password is updated successfully.</p>");
					}
					else if(result=="failed")
					{
						$("#resp").html("<p style='color: red; font-size: 13px;'>Failed to update the password. Please contact administrator</p>");
						setTimeout(function(){unloadPopupBox_update_pwd(); }, 1500);
					}
					else if(result=='timeout')
					{
						alert('Session Expired. Please Login!')
						window.location.href='login.php';
						window.location.reload();
					}
				},
				error: function(request, status, error)
				{
					alert(request.responseText);
				}
				
			});
        	//document.form1.action="php_func.php?
            //document.form1.submit();
        }
        else
        {
        	unloadPopupBox_update_pwd();
        }
	}
	else
	{
		$('#password1').css("border-color", "red");
		$('#password2').css("border-color", "red");
		alert("New password and the retyped new password should match");
	}
}


function pg_reload(pno)
{
	document.form1.action="users.php?pageno="+pno;
	document.form1.submit();
}
</script>

</head>

<body>
<?php
	$get_custom_permission_qr = mysql_query("select manage_users_edit from custom_permissions where user_id='".$_SESSION['user_id_ukvac']."'");
	if(mysql_num_rows($get_custom_permission_qr) >0)
	{
		$get_custom_users_manage = mysql_fetch_array($get_custom_permission_qr);
		
	}
?>
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
				<div align="center"> <label class='text-info h4'>MANAGE USERS</label></div>
      			</div>
                <?php
				if($_SESSION['role_ukvac']=="power_admin")
				{
					?>
					<div id="display_users" align="center" style="margin-top: 5px;" >
                    <form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                    <div style="height: 35px; color:#F00;">
                    <?php
                    if (isset($_SESSION['response_user'])) 
                    {
                        echo "<label style='vertical-align:middle;'>".$_SESSION['response_user']."</label>";
                        unset($_SESSION['response_user']);
                    }
                    ?>
                    </div>
                    <table class="table" >
                        <thead class="h5 text-danger" style="font-weight: bold;">
                            <tr>
                                <td width="5%">Sl.No</td>
                                <td width="25%">Login ID</td>
                                <td width="30%">Display Name</td>
                                <td width="10%">Role</td>
                                <td width="15%">Mission</td>
                                <td width="15%">Delete</td>
                                <td width="15%">Reset Password</td>
                            </tr>
                        </thead>
                            <?php
                            $cnt=0;
                            $query_count_result = "SELECT count(*) FROM users WHERE status = 'active' and mission_id NOT IN ('0')";
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
                            if($result_count_result[0]>0)
                            { 
                                $query = "SELECT count(*) FROM users WHERE  status = 'active' and mission_id NOT IN ('0') and user_id <> ''";
                                $result = mysql_query($query);
                                $query_data = mysql_fetch_row($result);
                                $numrows = $query_data[0];
                                $rows_per_page = 10;
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
                                $qr_t="SELECT u.user_id as user_id,u.username as username,u.display_name as display_name,u.role as role, m.mission_name as mission_name FROM users u, missions m WHERE u.status = 'active' and u.mission_id=m.id order by u.role, u.display_name  ASC $limit ";
                                $query_instance = mysql_query($qr_t) OR die(mysql_error());
                                $rows=mysql_num_rows($query_instance);
                                while($recResult = mysql_fetch_array($query_instance))
                                {
                                    $cnt++;
                            ?>
                            <tr>
                                <td align="left"> <?php echo $cnt; ?></td>
                                <td align="left"><?php echo $recResult['username']; ?></td>
                                <td align="left"><?php echo $recResult['display_name']; ?></td>
                                <td align="left"><?php echo $recResult['role']; ?></td>
                                <td align="left"><?php echo $recResult['mission_name']; ?></td>
                                <?php
                                if($recResult['user_id']==$_SESSION['user_id_ukvac'] )
                                {
                                    echo "<td width='15%' align='left'>It Is Me</td>";
                                }
                                else
                                {
                                ?>
                                <td align="left"><input type="button" class="btn btn-danger"  onClick="delete_user('<?php  echo $recResult['user_id'];  ?>','<?php echo $pageno;?>')" value="Delete User" ></td>
                                 <?php
                                }
                                ?>
                                <td align="left"><input type="button" class="btn btn-info" onClick="reset_password('<?php echo $recResult['user_id'];  ?>','<?php echo $recResult['display_name']; ?>')" value="Change Password"></td>
                               
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
                                echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1' style='font-size:13px; cursor:pointer;'>FIRST</a> ";
                                $prevpage = $pageno-1;
                                echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage' style='font-size:13px; cursor:pointer;'>PREV</a> ";
                            } 
                            echo " ( Page $pageno of $lastpage ) ";
                            if ($pageno == $lastpage)
                            {
                                echo " NEXT LAST ";
                            }
                            else
                            {
                                $nextpage = $pageno+1;
                                echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage' style='font-size:13px; cursor:pointer;'>NEXT</a> ";
                                echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage' style='font-size:13px; cursor:pointer;'>LAST</a> ";
                            }
                            echo "</div>";
                                    
                        ?>
                        </form>
    
                    </div>
                <?php
				}
				else if($_SESSION['role_ukvac']=="administrator")
				{
					?>
                    <div id="display_users" align="center" style="margin-top: 5px;" >
					<form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                    <div style="height: 35px; color:#F00;">
                    <?php
                    if (isset($_SESSION['response_user'])) 
                    {
                        echo "<label style='vertical-align:middle;'>".$_SESSION['response_user']."</label>";
                        unset($_SESSION['response_user']);
                    }
                    ?>
                    </div>
                    <table class="table" >
                        <thead class="h5 text-danger" style="font-weight: bold;">
                            <tr>
                                <td width="5%">Sl.No</td>
                                <td width="25%">Login ID</td>
                                <td width="30%">Display Name</td>
                                <td width="10%">Role</td>
                                <td width="15%">Mission</td>
                                <td width="15%">Delete</td>
                                <td width="15%">Reset Password</td>
                            </tr>
                        </thead>
                            <?php
                            $cnt=0;
                            $query_count_result = "SELECT count(*) FROM users WHERE role='staff' and status = 'active' and mission_id='".$_SESSION['mission_id']."'";
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
                            if($result_count_result[0]>0)
                            { 
                                $query = "SELECT count(*) FROM users WHERE role='staff'and status='active' and mission_id='".$_SESSION['mission_id']."' and user_id <> ''";
                                $result = mysql_query($query);
                                $query_data = mysql_fetch_row($result);
                                $numrows = $query_data[0];
                                $rows_per_page = 10;
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
                                //$qr_t="SELECT user_id,username,display_name,role FROM users WHERE status = 'active' and role='staff' order by role, display_name  ASC $limit ";
								$qr_t="SELECT u.user_id as user_id,u.username as username,u.display_name as display_name,u.role as role, m.mission_name as mission_name FROM users u, missions m WHERE u.status = 'active' and u.mission_id=m.id and u.mission_id='".$_SESSION['mission_id']."' order by u.role, u.display_name  ASC $limit ";
                                $query_instance = mysql_query($qr_t) OR die(mysql_error());
                                $rows=mysql_num_rows($query_instance);
                                while($recResult = mysql_fetch_array($query_instance))
                                {
                                    $cnt++;
                            ?>
                            <tr>
                                <td align="left"> <?php echo $cnt; ?></td>
                                <td align="left"><?php echo $recResult['username']; ?></td>
                                <td align="left"><?php echo $recResult['display_name']; ?></td>
                                <td align="left"><?php echo $recResult['role']; ?></td>
                                <td align="left"><?php echo $recResult['mission_name']; ?></td>
                                <?php
                                if($recResult['user_id']==$_SESSION['user_id_ukvac'] )
                                {
                                    echo "<td width='15%' align='left'>It Is Me</td>";
                                }
                                else
                                {
                                ?>
                                <td align="left"><input type="button" class="btn btn-danger"  onClick="delete_user('<?php  echo $recResult['user_id'];  ?>','<?php echo $pageno;?>')" value="Delete" <?php if($get_custom_users_manage['0']=="0"){ ?> disabled <?php } ?>></td>
                                 <?php
                                }
                                ?>
                                <td align="left"><input type="button" class="btn btn-info" onClick="reset_password('<?php echo $recResult['user_id'];  ?>','<?php echo $recResult['display_name']; ?>')" value="Change Password" <?php if($get_custom_users_manage['0']=="0"){ ?> disabled <?php } ?>></td>
                               
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
                                echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1' style='font-size:13px; cursor:pointer;'>FIRST</a> ";
                                $prevpage = $pageno-1;
                                echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage' style='font-size:13px; cursor:pointer;'>PREV</a> ";
                            } 
                            echo " ( Page $pageno of $lastpage ) ";
                            if ($pageno == $lastpage)
                            {
                                echo " NEXT LAST ";
                            }
                            else
                            {
                                $nextpage = $pageno+1;
                                echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage' style='font-size:13px; cursor:pointer;'>NEXT</a> ";
                                echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage' style='font-size:13px; cursor:pointer;'>LAST</a> ";
                            }
                            echo "</div>";
                                    
                        ?>                        
                        </form>
                    </div>
				<?php
				}
				?>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
	</div>

<!-- POP-UP-RESET PASSWORD -->
<div id="reset_pwd_div" align="center" style="text-align:center; border-radius: 15px;">
	<div class="text-warning text-center h4">
    	<u>Password Reset Console</u>
   	</div>
    <div id="resp" style="height: 30px; vertical-align:middle;"></div>
    <input type="hidden" id="user_id_hidden" name="user_id_hidden" value="">
    <table align="center" class="table" style="width: 70% !important;" >
    	<thead class="h5 text-primary">
        	<tr >
				<td width="5%" align="left" style="border-top: 0px;">
                	Name :
              	</td>
                <td align="left" style="border-top: 0px;">
                	<label id="displayname" class="h5 text-danger"></label>
              	</td>
    		</tr>
		</thead>
		<tbody class="h5 text-primary">
        	<tr>
				<td width="5%" align="left" style="border-top: 0px;">
                	Enter New Password :
              	</td>
				<td width="25%" align="left" style="border-top: 0px;">
                	<input type="password" id="password1" name="password1" class="form-control" style="width: 210px;">
               	</td>
          	</tr>
          	<tr>
            	<td width="30%"  align="left" style="border-top: 0px;">
                	Re-enter New Password :
              	</td>
				<td width="10%" align="left" style="border-top: 0px;">
                	<input type="password" id="password2" name="password2" class="form-control" style="width: 210px;">
              	</td>
        	</tr>
         	<tr>
         		<td width="15%">
                	<input type="button" class="btn btn-info" onClick="submit_reset_pwd()" value="Update Password" <?php if($get_custom_users_manage['0']=="0"){ ?> disabled <?php } ?>>
              	</td>
               	<td width="15%"  align="left">
                	<input type="button" class="btn btn-danger" value="Cancel" onclick="pg_reload()">
              	</td>
			</tr>
        </tbody>
    </table>
    <a id="popupBoxClose"><img src="../styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox_update_pwd()"></a>
</div>
<!-- END POP UP RESET PASSWORD -->


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