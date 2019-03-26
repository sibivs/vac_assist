<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
$expire_time = 3600; //expire time
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
if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac']))
{
	if($_SESSION['role_ukvac'] == "staff" || $_SESSION['role_ukvac'] == "administrator" || $_SESSION['role_ukvac'] == "passback" )
	{
		$disabled = explode(",",$_SESSION['disabled_string']);
	}
	else
	{
		$disabled[0]="";
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VAC Assist - <?php echo $_SESSION['vac']; ?></title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->


<!-- for new Calandar control -->

<link rel="stylesheet" type="text/css" media="all" href="styles/jsDatePick_ltr.min.css" />
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script type="text/javascript" src="Scripts/jsDatePick.min.1.3.js"></script>

<!-- End for new Calandar control -->
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>


<script type="text/javascript">

function check()
{
	var e = document.getElementById("pp_reports");
	var report_generate_type = e.options[e.selectedIndex].value;
	if(report_generate_type == 'daily_reconciliation')
	{
		$("#display_date1").hide();
		$("#display_date").show();
			input = new JsDatePick({
			useMode:2,
			target:"inputField",
			dateFormat:"%Y-%F-%d",
			cellColorScheme:"pink"
			});
	}
	else if(report_generate_type == 'tee_report'|| report_generate_type == 'delivery_details' || report_generate_type == 'daily_details' || report_generate_type == 'daily_sales' || report_generate_type == 'sales_pwise' || report_generate_type == 'sales_swise')
	{
		$("#display_date").hide();
		$("#display_date1").show();
		new JsDatePick({
			useMode:2,
			target:"inputField1",
			dateFormat:"%Y-%F-%d",
			cellColorScheme:"armygreen"
			});
		
		new JsDatePick({
			useMode:2,
			target:"inputField2",
			dateFormat:"%Y-%F-%d",
			cellColorScheme:"armygreen"
			});
	}
	else
	{
		$("#display_date").hide();
		$("#display_date1").hide();
	}

}



function retrive_report()
{
	var e = document.getElementById("pp_reports");
	var report_generate_type = e.options[e.selectedIndex].value;
	//var date_selected = document.getElementById("inputField").value;
	if($('#mission_id').find(":selected").val()=="select")
	{
		alert("Select Mission");
		return;
	}
	else
	{
		var mission_id = $('#mission_id').find(":selected").val();
	}

	if(report_generate_type == 'select')
	{
		alert("Select A Report Type");
		$("#display_date").hide();
		$("#display_date1").hide();		
		
	}
	else if(report_generate_type == 'daily_reconciliation')
	{
		var date_sel = $("#inputField").val();
		var rp_type = $("#pp_reports").val();
		$.ajax(
		{
			type: 'post',
			url: "php_func.php",
			data: 'cho=10&inputField='+date_sel+'&pp_reports='+rp_type+'&mid='+mission_id,
			dataType:"json",
			success: function(json)
			{
				if(json=="open")
				{
					window.open('display_reconciliation_report.php', '_blank', 'width=900,height=550, left=50, top=20, resizable=0, scrollbars=0, addressbar=0');
				}
				else if(json=='timeout')
				{
					alert('Session Expired. Please Login!')
					window.location.href='login.php';
					window.location.reload();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$("#hid").val(XMLHttpRequest.responseText)
				}
						
		});
	}
	else if(report_generate_type == 'gwfs_in_vac')
	{
		window.open('display_inscan_passports.php?mid='+mission_id, '_blank', 'width=1000,height=550, left=50, top=20, resizable=1, scrollbars=1,addressbar=1');
	}
	else if(report_generate_type == 'delivery_details')
	{
		var date_sel = $("#inputField1").val();
		var date_sel1 = $("#inputField2").val();
		var rp_type = $("#pp_reports").val();
		$.ajax(
		{
			type: 'post',
			url: "php_func.php",
			data: 'cho=12&inputField='+date_sel+'&pp_reports='+rp_type+'&inputField1='+date_sel1,
			dataType:"json",
			success: function(json)
			{
				if(json=="open")
				{
					window.open('display_delivery_report.php?mid='+mission_id, '_blank', 'width=1000,height=550, left=50, top=20, resizable=1, scrollbars=1');
				}
				else if(json=='timeout')
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
	}
	else if(report_generate_type == 'outscan_details')
	{
		window.open('display_outscan_passports.php?mid='+mission_id, '_blank', 'width=1000,height=550, left=50, top=20, resizable=1, scrollbars=1,addressbar=1');
	}
	else if(report_generate_type == 'daily_details')
	{
		var date_sel = $("#inputField1").val();
		var date_sel1 = $("#inputField2").val();
		var rp_type = $("#pp_reports").val();
		$.ajax(
		{
			type: 'post',
			url: "php_func.php",
			data: 'cho=24&inputField='+date_sel+'&pp_reports='+rp_type+'&inputField1='+date_sel1,
			dataType:"json",
			success: function(json)
			{
				if(json=="open")
				{
					window.open('display_daily_report.php?mid='+mission_id, '_blank', 'width=1000,height=550, left=50, top=20, resizable=1, scrollbars=1');
				}
				else if(json=='timeout')
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
	}
	else if(report_generate_type == 'daily_sales')
	{
		var date_sel = $("#inputField1").val();
		var date_sel1 = $("#inputField2").val();
		var rp_type = $("#pp_reports").val();
		$.ajax(
		{
			type: 'post',
			url: "php_func.php",
			data: 'cho=25&inputField='+date_sel+'&pp_reports='+rp_type+'&inputField1='+date_sel1,
			dataType:"json",
			success: function(json)
			{
				if(json=="open")
				{
					window.open('display_daily_sales.php?mid='+mission_id, '_blank', 'left=50, top=20, resizable=1, scrollbars=1');
				}
				else if(json=='timeout')
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
	}
	else if(report_generate_type == 'tee_report')
	{
		var date_sel = $("#inputField1").val();
		var date_sel1 = $("#inputField2").val();
		var rp_type = $("#pp_reports").val();
		$.ajax(
		{
			type: 'post',
			url: "php_func.php",
			data: 'cho=33&inputField='+date_sel+'&pp_reports='+rp_type+'&inputField1='+date_sel1,
			dataType:"json",
			success: function(json)
			{
				if(json=="open")
				{
					window.open('display_tee_report.php?mid='+mission_id, '_blank', 'width=1000,height=550, left=50, top=20, resizable=1, scrollbars=1');
				}
				else if(json=='timeout')
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
	}
	else if(report_generate_type == 'sales_pwise')
	{
		var date_sel = $("#inputField1").val();
		var date_sel1 = $("#inputField2").val();
		var rp_type = $("#pp_reports").val();
		$.ajax(
		{
			type: 'post',
			url: "php_func.php",
			data: 'cho=15&inputField='+date_sel+'&pp_reports='+rp_type+'&inputField1='+date_sel1,
			dataType:"json",
			success: function(json)
			{
				if(json=="open")
				{
					window.open('sales_product_wise.php?mid='+mission_id, '_blank', 'left=50, top=20, resizable=1, scrollbars=1');
				}
				else if(json=='timeout')
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
	}
	else if(report_generate_type == 'sales_swise')
	{
		var date_sel = $("#inputField1").val();
		var date_sel1 = $("#inputField2").val();
		var rp_type = $("#pp_reports").val();
		$.ajax(
		{
			type: 'post',
			url: "php_func.php",
			data: 'cho=35&inputField='+date_sel+'&pp_reports='+rp_type+'&inputField1='+date_sel1,
			dataType:"json",
			success: function(json)
			{
				if(json=="open")
				{
					window.open('sales_staff_wise.php?mid='+mission_id, '_blank', 'left=50, top=20, resizable=1, scrollbars=1');
				}
				else if(json=='timeout')
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
	}
	
	
}

function pg_reload()
{
	document.form1.action="passport_delivery_reports.php";
	document.form1.submit();
}
</script>

</head>

<body>
<div id="templatemo_container">
	<div id="templatemo_header">
		<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
        <label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
    </div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("menu/left_menu.php"); ?></div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>   
	<div id="templatemo_content">
		<script type="text/javascript" language="javascript">
				
				</script>
		<div id="" style="width: 100% !important; " align="center;">
			<div style="width: 100% !important;" align="center;">
				<div align="center"> <label class='text-info h4'>GENERATE REPORTS</label></div>
      			</div>
                <div id="div_reports" align="center" style="margin-top: 5px; height:auto; min-height: 500px;">
                <form id="form1" name="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                 
					<table style="width: 50%" class="table">
						<tr >
							<td  style="font-size: 16px; width: 50%;" class="text-danger">Select a report type : </td>
							<td style="">
                           
                                <select name="pp_reports" id="pp_reports" class="form-control" onChange="check()" style="width: 210px;" >
                                	<option value="select">Select Report Type</option>
                                    <?php 
									if(!in_array("passport_deliver.php",$disabled))
									{
									?>
                                    <option value="daily_reconciliation">Daily Reconciliation Report</option>
                                	<option value="gwfs_in_vac">Passports in VAC</option>
                                	<option value="delivery_details">Passport Delivery Details</option>
									<option value="outscan_details">Passports In Embassy Report</option>
                                    <?php
                                    }
									?>
                                    <option value="daily_details">Applications Submission Report</option>
                                    <?php 
                                    if(!in_array("bts.php",$disabled))
                                    {
                                    ?>
                                    <option value="daily_sales">Travel Products Sales Report</option>
                                    <option value="sales_pwise">Travel Products Sales Product-Wise</option>
                                    <option value="sales_swise">Travel Products Staff-Wise</option>
                                    <?php
                                    }
									if(!in_array("manage_tees.php",$disabled))
									{
                                    ?>
                                    <option value="tee_report">TEE Envelop Usage Report</option>
                                    <?php
                                    }
									?>
                                </select>
                            </p>                       
                            </td>
                         </tr>
                         <tr id="display_date" style="display:none;">
                            <td width="50%" style="font-size: 13px;" class="text-info" >Select Date : </td>
                            <td width="50%" style="">
                            <input type="text" id="inputField" name="inputField" value="<?php date_default_timezone_set("Asia/Bahrain"); echo date('Y-m-d'); ?>" class="form-control" style="float:none; width: 210px;" />
                            </td>	
                       	</tr>
                        <tr id="display_date1" style="display:none;">
                            <td width="50%" style="font-size: 13px;" class="text-info">Select From Date :
                            <input type="text" id="inputField1" name="inputField1" value="<?php date_default_timezone_set("Asia/Bahrain"); echo date('Y-m-d'); ?>" class="form-control" style="float:none; width:210px;" />
                            </td>
                            <td width="50%" style="font-size: 13px;" class="text-info">Select To Date : 
                            <input type="text" id="inputField2" name="inputField2" value="<?php date_default_timezone_set("Asia/Bahrain"); echo date('Y-m-d'); ?>" class="form-control" style="float:none; width: 210px;" />
                            </td>	
                       	</tr>
                        <tr>
                        <td width="50%" style="font-size:15px;" class="text-danger">
                        	Select Mission Name :
                      	</td>									
                        <td width="50%" style="">
                        	<select class="form-control" name="mission_id" id="mission_id" style="width:210px;">
                            		<?php 
									if($_SESSION['role_ukvac'] == "staff" || $_SESSION['role_ukvac'] == "administrator" || $_SESSION['role_ukvac'] == "passback" ) 
									{
										echo '<option value='.$_SESSION['mission_id'].' >'. $_SESSION['vac']." VAC -".$_SESSION['vac_city'].'</option>';
									}
									else
									{
									?>
                                	<option value="select">Select Mission</option>
                                    <?php
                                    $get_missions = mysql_query("SELECT m.id AS id , m.mission_name AS mission_name,m.currencies AS currencies , c.city_name  AS city_name FROM country_cities c , missions m WHERE m.status='active' AND m.city = c.id");
								while($get_mission_name = mysql_fetch_array($get_missions))
								{
									?>
									<option value='<?php echo $get_mission_name['id']; ?>' ><?php echo $get_mission_name['mission_name']." - ". $get_mission_name['city_name']; ?></option>
                                    <?php
									}
								}
								?>
                                </select>
                           </td>
                        </tr>
                        <tr>
                            <td  width="50%" style="text-align:center;"><input type="button" name="get_report" class="btn btn-success" id="get_report" onClick="retrive_report()" value="Retrive Report"/></td>
                            <td  width="50%" style=""><input type="button" name="reset" class="btn btn-danger" id="get_report" onClick="pg_reload()" value="Cancel"/></td>
						</tr>
                        <tr>
                        	<td colspan="2">
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" id="hid"/>
                    </form>

				</div>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
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