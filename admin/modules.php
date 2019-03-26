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
	header("Location:../login.php");
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
include_once("../db_connect.php");
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="power_admin" ))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VAC Assist - <?php echo $_SESSION['vac']; ?></title>
<!-- Main stylesheet and javascripts for the page -->
<script src="../Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../styles/style.css">
<link rel="stylesheet" href="../menu/css/style_menu.css">

<script src="func/script.js"></script> 
<script type="text/javascript">
var array_pids = new Array();
function select_pages_enabled()
{
	var option = $("#mission").val();
	$("#resp_update").html("");
	if(option=="select")
	{
		$("#mission").css("border-color", "red");
		$("#chks tbody").html("");
		$("#display_options").hide();
	}
	else
	{
		$("#mission").css("border-color", "#CCC");
		var x = "";
		<?php 
			$sl=1;
			$get_pages = mysql_query("select * from pages_associated");
			while($res_pages=mysql_fetch_array($get_pages))
			{
		?>
			var x = x+"<tr><td  align='left'><?php echo $sl; ?></td><td style='text-align:left !important;'><?php echo $res_pages['display_name']; ?></td><td id='val'><input type='checkbox' id='<?php echo $res_pages['id'];  ?>' class='form-control' style='width: 15px; height: 15px;' /></td></tr>";
			array_pids[<?php echo ($sl-1); ?>] = '<?php echo $res_pages['id']; ?>';
		<?php
			$sl++;
			}
		?>
		var x = x+"<tr><td  align='center' colspan='3'><input type='button' class='btn btn-info' value='Update' onclick='update_page_disable()' />&nbsp; &nbsp; &nbsp;<input type='reset' class='btn btn-danger' value='Cancel' /></td></tr>"
		$("#chks tbody").html("");
		$("#mission_name").html("The modules which are selected here will be disabled for the mission - <span class='text-danger'>" +$("#mission option:selected").text()+"</span>");
		$("#chks tbody").append(x);
		$("#display_options").show();
		$.ajax(
		{
			type: 'post',
			url: 'func/php_func.php',
			data: 'cho=4&mission='+option,
			dataType:"json",
			success: function(result)
			{
				var disabled_pages = eval(result);
				$.each( disabled_pages, function( key, value ) 
				{
					if(disabled_pages[0]!='nothing')
					{
						$("#"+value).attr("checked","checked");
					  //alert( key + ": " + value );
					}
				});
			},
			error: function(request, status, error)
			{
					alert(request.responseText);  
			}
				
		});
				
	}
}

function update_page_disable()
{
	var disable_pages = new Array();
	var option = $("#mission").val();
	var i=0;
	$.each( array_pids, function( key, value ) 
	{
		if ($('#'+value).is(":checked"))
		{
			disable_pages[i]=value;
			i++;
		}
	});
	$.ajax(
	{
		type: 'post',
		url: 'func/php_func.php',
		data: 'cho=5&data='+JSON.stringify(disable_pages)+"&mission="+option,
		dataType:"json",
		success: function(result)
		{
			if(result == "updated")
			{
				$("#resp_update").html("Module settings updated.");
			}
		},
		error: function(request, status, error)
		{
			alert(request.responseText);  
		}
	});
}
</script>

</head>

<body>
<form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
<div id="templatemo_container">
	<div id="templatemo_header">
    	<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
        <label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
    </div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("../menu/left_menu.php"); ?></div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>   
	<div id="templatemo_content">
		<script type="text/javascript" language="javascript">
				</script>
		<div id="" style="width: 100% !important; " align="center;">
			<div style="width: 100% !important;" align="center;">
				<div align="center"> <label class='text-info h4'>
Manage Modules Associated With Missions</label></div>
      			</div>
                <div style="width: 100%; text-align:center; padding-top: 15px !important;">
                <label class="text-danger h4">Select Mission : </label><select id="mission" class="form-control" style="width:210px; display:inline;" onChange="select_pages_enabled()">
                	<option value="select">Select Mission</option>
                    <?php
						$get_missions = mysql_query("select id, mission_name from missions where status='active'");
						while($result_mission= mysql_fetch_array($get_missions))
						{
							?>
                            <option value="<?php echo $result_mission['id']; ?>"><?php echo $result_mission['mission_name']; ?></option>
                            <?php
						}
						
					?>
                </select>
                </div>
                <div style="height: 15px;"></div>
                
           	<p style="height:30px"></p>
                
               <div style="width: 100%; text-align: center; padding: 15px;" id="resp_update" class="text-success h5"></div> 
                <label class="text-info h5" id="mission_name" style="text-align: center; width: 800px;"></label>
                <div style="padding: 15px; display:none;" align="center" id="display_options">    
           			<table class="table" align="center" style="width: 80%;" id="chks">
                        <thead class="h5 text-info" style="font-weight: bold;">
                            <tr>
                                <td width="10%">No</td>
                                <td width="25%" style="text-align:left !important;">Module Name</td>
                                <td width="25%">Select to Disable</td>
                            </tr>
                        </thead>
                        <tbody>
                        	
       					</tbody>
                    </table>
                    <p style="height:10px"></p>
                </div>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
	</div>

</form>
</body>
</html>
<?php
}
else
{
        header("Location:../php_func.php?cho=2");
}
}
?>