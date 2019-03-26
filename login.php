<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac']))
{
	header("Location:index.php");
}
else
{
	include_once("db_connect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>VAC Assist - Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Main stylesheet and javascripts for the page -->
    <link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
    <!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
    <link rel="stylesheet" href="styles/style.css">
    <script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
    <script type="text/javascript" src="Scripts/jquery.style_en.js"></script>
    <script type="text/javascript" src="Scripts/login.js"> </script>
</head>

<body>
		<div id="templatemo_login" align="center">   
			<form id="form1" name="form1" method="post">
         		<br />
			<div style="display:inline;">
		<img src="" style=" width: 120px; height: auto; padding-top: 10px; float: left; padding-left: 15px;" />
        
    </div><!-- End Of Header --> 
			<br /> <br />
            <table style="width: 380px; height: 230px; color:#FFFFFF;">
            	<tr>
                	<td colspan="2"><label style="text-align:center; font-size:16px;">User Login<br /></label></td>
                </tr>
                <tr>
                	<td style="text-align: left;">
                    	Mission Name :
                   	</td>
                    <td>
                    	<select name="mission_select" id="mission_select" class="form-control" style="width:220px; display: inline;">
                            <option value="mission">Select Mission</option>
                            <option value="0">Power Admin</option>
                            <option value="0">Management User</option>
                            <?php
                                $get_mission= mysql_query("select id, mission_name from missions where status='active' order by mission_name ASC");
                                while($result_mission_list = mysql_fetch_array($get_mission))
                                {
                                    
                            ?>
                                <option value="<?php echo $result_mission_list['id']; ?>"><?php echo $result_mission_list['mission_name']; ?></option>
                            <?php
                                }
                            ?>
                		</select>
                	</td>
                </tr>
                <tr>
                	<td style="text-align: left;">
                    	User Name :
                    </td>
                    <td>
                    	<input name="username" id="username" value="" type="text" placeholder="Username" class="form-control" style="width:220px; display: inline;"/>
                    </td>
                </tr>
                <tr>
                	<td style="text-align: left;">
                    	Password :
                    </td>
                    <td>
                    	<input name="password" id="password" placeholder="Password" value="" type="password" onkeydown="if(event.keyCode ==13) validate();" class="form-control" style="width:220px; display: inline;">
                    </td>
                </tr>
                <tr>
                	<td colspan="2">
                    	<input style="background-color:#037499;" type="button" name="Search" value="Login" class="btn btn-default" onclick="validate()" />
                   
                    	<input style="background-color:#037499;" type="button" name="reset" value="Cancel" class="btn btn-default" onclick="window.location.reload()" style="display: inline; padding-left: 30px;"/>
                    </td>
                </tr>
            </table>
                  <div id="error_message" style="color:#F00; font-family:Verdana, Geneva, sans-serif; font-size: 11px; border:2px; FF0000;">
                 <label id="error_msg_lbl" style="height:30px; color:#C03; white-space: pre-wrap; text-align:left; padding-left:30%;">
                </label>
                </div>
    		</form>
		</div>
</body>
</html>

<?php
}
?>