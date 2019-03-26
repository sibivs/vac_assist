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
if(isset($_SESSION['role_ukvac'])&& ($_SESSION['role_ukvac']=="administrator" ||$_SESSION['role_ukvac']=="management" || $_SESSION['role_ukvac']=="supervisor" || $_SESSION['role_ukvac']=="accounts")  &&  isset($_SESSION['vac']))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VAC Assist - <?php echo $_SESSION['vac']; ?></title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="styles/style.css">
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<style type="text/css">
#loading_div {
  height: 100%;
  width: 100%;
  position: relative;
  background-color: rgba(0,0,0,0.3); /* for demonstration */
}
.ajax-loader {
  position: absolute;
  left: 50%;
  top: 50%;
  margin-left: -32px; /* -1 * image width / 2 */
  margin-top: -32px; /* -1 * image height / 2 */
}
</style>

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
	var report_generate_type= $("#pp_reports").val();
	if(report_generate_type == 'vas_compare')
	{
		$("select#product_vas").prop('selectedIndex', 0);
		$("#product_name_vas").show();
		$("#display_date").hide();
		$("#display_date1").show();
		$("#display_date1").css("display","inline");
		$("#product_name_bts").hide();
		$("select#product_bts").prop('selectedIndex', 0);
	}
	else if(report_generate_type == 'bts_compare')
	{
		$("#display_date").hide();
		$("#display_date1").show();
		$("#display_date1").css("display","inline");
		$("select#product_vas").prop('selectedIndex', 0);
		$("#product_name_vas").hide();
		$("#product_name_bts").show();
		$("select#product_bts").prop('selectedIndex', 0);
	}
	else if(report_generate_type == 'appln_details')
	{
		$("#display_date").hide();
		$("#display_date1").show();
		$("#display_date1").css("display","inline");
		$("select#product_vas").prop('selectedIndex', 0);
		$("#product_name_vas").hide();
		$("#product_name_bts").hide();
		$("select#product_bts").prop('selectedIndex', 0);
	}
	else
	{
		$("#display_date").hide();
		$("#display_date1").hide();
		$("#product_name_bts").hide();
		$("select#product_bts").prop('selectedIndex', 0);
		$("#product_name_vas").hide();
		$("select#product_vas").prop('selectedIndex', 0);
		$("select#select_period").prop('selectedIndex', 0);
	}

}

var label_month = "<label id='label_month' class='text-warning h5'>Select Month : </label>";
var html_month = "<select name='get_month' id='get_month' class='form-control' style='width: 100px; display:inline;'><option value='select'>Select</option><option value='01'>January</option><option value='02'>February</option><option value='03'>March</option><option value='04'>April</option><option value='05'>May</option><option value='06'>June</option><option value='07'>July</option><option value='08'>August</option><option value='09'>September</option><option value='10'>October</option><option value='11'>November</option><option value='12'>December</option></select>";

function period_selected()
{
	var period_selected =  $("#select_period").val();
	var currentTime = new Date();
	var year = currentTime.getFullYear();
	var option="";
	var option_month = "";
	var report_generate_type= $("#pp_reports").val();
	if(period_selected=="select")
	{
		$("#select_period").css('border-color','red');
		$("#display_date2").html("");
		$("#product_vas").attr('selectedIndex', 0);
	}
	else
	{
		$("#select_period").css('border-color','#CCC');
		var label = "<label class='text-warning h5'>Select Year : </label>";
		var html = "<select id='get_year' class='form-control' style='width: 130px; display:inline;' onchange='year_changed()'><option value='select'>Select</option><option value='all'>All Years</option>";
		for(i=2014; i <= year; i++)
		{
			var option = option+ "<option value='"+i+"'>"+i+"</option>";
		}
		if(report_generate_type=="vas_compare")
		{
			$("#product_name_vas").show();
			$("#product_name_bts").hide();
		}
		else if(report_generate_type=="bts_compare")
		{
			$("#product_name_vas").hide();
			$("#product_name_bts").show();
		}
		if(period_selected== "yearly")
		{
			var total_html = label+html+option+"</select>";
		}
		if(period_selected== "monthly")
		{
			var total_html = label+html+option+"</select>&nbsp; &nbsp;";
			//label_month+html_month;
		}
		else
		{
			var total_html	="";
		}
		$("#display_date2").html(total_html);
	}	
}

function year_changed()
{
	var period_selected =  $("#select_period").val();
	if($("#show_month").val()!="select")
	{
		$("#get_month").prop('selectedIndex',0);
	}
	if($("#get_quarter").val()!="select")
	{
		$("#get_quarter").prop('selectedIndex',0);
	}
	
	if(period_selected== "monthly")
	{
		if($("#get_year").val()=="all")
		{
			$("#display_date2").append(label_month+html_month);
		}
		else
		{
			if($("#get_month").closest("html").length == 1)
			{
				$("#get_month").remove();
				$("#label_month").remove();
			}
		}
	}
}


function retrive_report()
{
	var mission_id = $('#mission_id').find(":selected").val();
	var report_generate_type = $("#pp_reports").val();
	//var date_selected = document.getElementById("inputField").value;
	if(report_generate_type == 'select')
	{
		alert("Select A Report Type");
		$("#display_date").hide();
		$("#display_date1").hide();	
		$("#display_date2").html("");
		$("#pp_reports").hide();	
	}
	else if(report_generate_type == 'outscan_details')
	{
		
	}
	else if(report_generate_type == 'vas_compare')
	{

		var cnt1=0;
		var rep_period = $("#select_period").val();
		var product_name = $("#product_vas").val();
		if(rep_period=="select")
		{
			alert("Please select a report period");
			$("#select_period").css("border-color",'red');
			return false;
		}
		else
		{
			$("#select_period").css("border-color",'#CCC');
			if(rep_period=="yearly")
			{
				if(product_name == "select")
				{
					$("#product_vas").css("border-color",'red');
					cnt1++;
				}
				else
				{
					$("#product_vas").css("border-color",'#CCC');
				}
				
				if(cnt1==0)
				{
					$("#loading_div").html('<img src="styles/images/preloader.gif" class="ajax-loader" style=""/>');
					$("#loading_div").show();
					$.ajax({
						url : "swf_chart/vas_yearly.php",
						data: 'pname='+product_name+'&mid='+mission_id,
						dataType: "json",
						success : function (data1) {
						},
						error: function(request, status, error) 
						{
							alert(request.responseText);
						}
					});
							
					$.ajax({
						url : "swf_chart/vas_yearly_growth.php",
						data: 'pname1='+product_name+'&mid='+mission_id,
						dataType: "json",
						success : function (data2) {
						},
						error: function(request, status, error) 
						{
							alert(request.responseText);
						}
					});
							
					$('#btns').html('<label class="text-info" style="font-size:12px;">To Save an image, Please right click on the image and save it.</label>');
					$("#grph").html('<object id="htmlval" style="height:950px; width:1050px;" data="swf_chart/vas_yearly.html?option='+product_name+'"/>');
					loadPopupBox_chart();
					$("#loading_div").hide();
				}
				else
				{
					alert("Please select correct options for report");
				}
			}
			else if(rep_period=="monthly")
			{
				var cnt2=0;
				var year = $("#get_year").val();
				var product_name = $("#product_vas").val();
				var month = $("#get_month").val();
				if(year=="select")
				{
					//alert("Please select an year.");
					$("#get_year").css("border-color",'red');
					cnt2++;
				}
				else if(year=="all")
				{
					if(month=="select")
					{
						$("#get_month").css("border-color",'red');
						cnt2++;
					}
					else
					{
						$("#get_month").css("border-color",'#ccc');
					}
					$("#get_year").css("border-color",'#CCC');
				}
				else
				{
					var month = "all";
					$("#get_year").css("border-color",'#CCC');
				}
				
				if(product_name == "select")
				{
					$("#product_vas").css("border-color",'red');
					cnt2++;
				}
				else
				{
					$("#product_vas").css("border-color",'#CCC');
				}
				
				if(cnt2==0)
				{
					$("#loading_div").html('<img src="styles/images/preloader.gif" class="ajax-loader" style=""/>');
					$("#loading_div").show();
					var datatosend = "year="+year+"&month="+month+'&pname='+product_name;
					$.ajax({
						url : "swf_chart/vas_monthly.php",
						data: datatosend,
						dataType: "json",
						success : function (data1) {
						},
						error: function(request, status, error) 
						{
							alert(request.responseText);
						}
					});
							
					$.ajax({
						url : "swf_chart/vas_monthly_growth.php",
						data: datatosend,
						dataType: "json",
						success : function (data2) {
						},
						error: function(request, status, error) 
						{
							alert(request.responseText);
						}
					});
							
					$('#btns').html('<label class="text-info" style="font-size:12px;">To Save an image, Please right click on the image and save it.</label>');
					$("#grph").html('<object id="htmlval" style="height:950px; width:1050px;" data="swf_chart/vas_monthly.html?option='+product_name+'"/>');
					loadPopupBox_chart();
					$("#loading_div").hide();
				}
				else
				{
					alert("Please select correct options for report");
				}
			}
		}
	}
	
/////////////////////////
//////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////
	else if(report_generate_type == 'bts_compare')
	{
		var cnt1=0;
		var rep_period = $("#select_period").val();
		var product_name = $("#product_bts").val();
		if(rep_period=="select")
		{
			alert("Please select a report period");
			$("#select_period").css("border-color",'red');
			return false;
		}
		else
		{
			$("#select_period").css("border-color",'#CCC');
			if(rep_period=="yearly")
			{
				if(product_name == "select")
				{
					$("#product_bts").css("border-color",'red');
					cnt1++;
				}
				else
				{
					$("#product_bts").css("border-color",'#CCC');
				}
				
				if(cnt1==0)
				{
					$("#loading_div").html('<img src="styles/images/preloader.gif" class="ajax-loader" style=""/>');
					$("#loading_div").show();
					$.ajax({
						url : "swf_chart/bts_yearly.php",
						data: 'pname='+product_name,
						dataType: "json",
						success : function (data1) {
						},
						error: function(request, status, error) 
						{
							alert(request.responseText);
						}
					});
							
					$.ajax({
						url : "swf_chart/bts_yearly_growth.php",
						data: 'pname1='+product_name,
						dataType: "json",
						success : function (data2) {
						},
						error: function(request, status, error) 
						{
							alert(request.responseText);
						}
					});
							
					$('#btns').html('<label class="text-info" style="font-size:12px;">To Save an image, Please right click on the image and save it.</label>');
					$("#grph").html('<object id="htmlval" style="height:950px; width:1050px;" data="swf_chart/bts_yearly.html?option='+product_name+'"/>');
					loadPopupBox_chart();
					$("#loading_div").hide();
				}
				else
				{
					alert("Please select correct options for report");
				}
			}
			else if(rep_period=="monthly")
			{
				var cnt2=0;
				var year = $("#get_year").val();
				var product_name = $("#product_bts").val();
				var month = $("#get_month").val();
				if(year=="select")
				{
					//alert("Please select an year.");
					$("#get_year").css("border-color",'red');
					cnt2++;
				}
				else if(year=="all")
				{
					if(month=="select")
					{
						$("#get_month").css("border-color",'red');
						cnt2++;
					}
					else
					{
						$("#get_month").css("border-color",'#ccc');
					}
					$("#get_year").css("border-color",'#CCC');
				}
				else
				{
					var month = "all";
					$("#get_year").css("border-color",'#CCC');
				}
				
				if(product_name == "select")
				{
					$("#product_bts").css("border-color",'red');
					cnt2++;
				}
				else
				{
					$("#product_bts").css("border-color",'#CCC');
				}
				
				if(cnt2==0)
				{
					$("#loading_div").html('<img src="styles/images/preloader.gif" class="ajax-loader" style=""/>');
					$("#loading_div").show();
					var datatosend = "year="+year+"&month="+month+'&pname='+product_name;
					$.ajax({
						url : "swf_chart/bts_monthly.php",
						data: datatosend,
						dataType: "json",
						success : function (data1) {
						},
						error: function(request, status, error) 
						{
							alert(request.responseText);
						}
					});
							
					$.ajax({
						url : "swf_chart/bts_monthly_growth.php",
						data: datatosend,
						dataType: "json",
						success : function (data2) {
						},
						error: function(request, status, error) 
						{
							alert(request.responseText);
						}
					});
							
					$('#btns').html('<label class="text-info" style="font-size:12px;">To Save an image, Please right click on the image and save it.</label>');
					$("#grph").html('<object id="htmlval" style="height:950px; width:1050px;" data="swf_chart/bts_monthly.html?option='+product_name+'"/>');
					loadPopupBox_chart();
					$("#loading_div").hide();
				}
				else
				{
					alert("Please select correct options for report");
				}
			}
		}
	}
/////////////////////////
//////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////
	else if(report_generate_type == 'appln_details')
	{
		var cnt1=0;
		var rep_period = $("#select_period").val();
		if(rep_period=="select")
		{
			alert("Please select a report period");
			$("#select_period").css("border-color",'red');
			return false;
		}
		else
		{
			$("#select_period").css("border-color",'#CCC');
			if(rep_period=="yearly")
			{
			$("#loading_div").html('<img src="styles/images/preloader.gif" class="ajax-loader" style=""/>');
				$("#loading_div").show();
				$.ajax({
					url : "swf_chart/submission_yearly.php",
					data: '',
					dataType: "json",
					success : function (data1) {
					},
					error: function(request, status, error) 
					{
						alert(request.responseText);
					}
				});
							
				$.ajax({
					url : "swf_chart/submission_yearly_growth.php",
					data: '',
					dataType: "json",
					success : function (data2) {
					},
					error: function(request, status, error) 
					{
						alert(request.responseText);
					}
				});
							
				$('#btns').html('<label class="text-info" style="font-size:12px;">To Save an image, Please right click on the image and save it.</label>');
				$("#grph").html('<object id="htmlval" style="height:950px; width:1050px;" data="swf_chart/submission_yearly.html?option='+product_name+'"/>');
				loadPopupBox_chart();
				$("#loading_div").hide();
			}
			else if(rep_period=="monthly")
			{
				var cnt2=0;
				var year = $("#get_year").val();
				var month = $("#get_month").val();
				if(year=="select")
				{
					//alert("Please select an year.");
					$("#get_year").css("border-color",'red');
					cnt2++;
				}
				else if(year=="all")
				{
					if(month=="select")
					{
						$("#get_month").css("border-color",'red');
						cnt2++;
					}
					else
					{
						$("#get_month").css("border-color",'#ccc');
					}
					$("#get_year").css("border-color",'#CCC');
				}
				else
				{
					var month = "all";
					$("#get_year").css("border-color",'#CCC');
				}
				if(cnt2==0)
				{
					$("#loading_div").html('<img src="styles/images/preloader.gif" class="ajax-loader" style=""/>');
					$("#loading_div").show();
					var datatosend = "year="+year+"&month="+month;
					$.ajax({
						url : "swf_chart/submission_monthly.php",
						data: datatosend,
						dataType: "json",
						success : function (data1) {
						},
						error: function(request, status, error) 
						{
							alert(request.responseText);
						}
					});
							
					$.ajax({
						url : "swf_chart/submission_monthly_growth.php",
						data: datatosend,
						dataType: "json",
						success : function (data2) {
						},
						error: function(request, status, error) 
						{
							alert(request.responseText);
						}
					});
							
					$('#btns').html('<label class="text-info" style="font-size:12px;">To Save an image, Please right click on the image and save it.</label>');
					$("#grph").html('<object id="htmlval" style="height:950px; width:1050px;" data="swf_chart/submission_monthly.html?option='+product_name+'"/>');
					loadPopupBox_chart();
					$("#loading_div").hide();
				}
				else
				{
					alert("Please select correct options for report");
				}
			}
		}
	}
	else if(report_generate_type == 'sales')
	{
		
	}
	else
	{
	}
}


function pg_reload()
{
	document.form1.action="passport_delivery_reports.php";
	document.form1.submit();
}

function mission_select()
{
	var mission_id = $("#mission_id").val();
	document.form1.action="reports_chart.php?mission_id="+mission_id;
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
					<table class="table" style="padding:20px 0 25px 0;">
						<tr >
							<td style="float:right; border-top: 0px; width: 50%;" class="text-danger h5">Select Mission : </td>
							<td style="border-top: 0px; width: 50%;">
                                <select class="form-control" name="mission_id" id="mission_id" style="width: 220px;" onChange="mission_select()">
                                	<option value="select">Select Report Type</option>
                                    <?php
                                    $get_missions = mysql_query("SELECT m.id AS id , m.mission_name AS mission_name,m.currencies AS currencies , c.city_name  AS city_name FROM country_cities c , missions m WHERE m.status='active' AND m.city = c.id");
								while($get_mission_name = mysql_fetch_array($get_missions))
								{
									?>
									<option value='<?php echo $get_mission_name['id']; ?>' <?php if(isset($_REQUEST['mission_id']) && $_REQUEST['mission_id'] == $get_mission_name['id'] ) { ?> selected <?php } ?> ><?php echo $get_mission_name['mission_name']." - ". $get_mission_name['city_name']; ?></option>
                                    <?php
								}
								?>
                                </select>
                            </p>                       
                            </td>
                         </tr>
                        <tr >
							<td style="float:right; border-top: 0px; width: 50%;" class="text-danger h5">Select a report type : </td>
							<td style="border-top: 0px; width: 50%;">
                                <select class="form-control" name="pp_reports" id="pp_reports" style="width: 220px;" onChange="check()" >
                                	<option value="select">Select Report Type</option>
                                    <option value="appln_details">Submission Report</option>
                                    <option value="vas_compare">VAS Report</option>
                                    <option value="bts_compare">Travel Product's Sales</option>
                                    <!--option value="sales_pwise">BTS Sales Product-Wise Report</option>
                                    <option value="sales_swise">BTS Sales Staff-Wise Report</option-->
                                </select>
                            </p>                       
                            </td>
                         </tr>
                         <tr id="display_date" style="display:none;">
                            <td style="" >Select Date : </td>
                            <td style="">
                            <input type="text" id="inputField" name="inputField" value="<?php date_default_timezone_set("Asia/Bahrain"); echo date('Y-m-d'); ?>" class="inputtext" style="float:none;" />
                            </td>	
                       	</tr>
                        <tr>
                            <td colspan="2" class="text-warning h5">
                            <div style="display: none;" id="display_date1">
                            Select Report Period :
                            <select id="select_period" name="select_period" class="form-control" style="width: 150px; display: inline;" onChange="period_selected()">
                            	<option value="select">Select</option>
                                <option value="yearly">Year On Year</option>
                                <option value="monthly">Month On Month</option>
                            </select>
                            </div>
                            <div id="display_date2" style="display:inline;">
                           	</div>
                            </td>	
                       	</tr>
                        <tr style="display: none;" id="product_name_vas">
                        	<td colspan="2" style="border-top: 0px;">
                            <?php 
							if(isset($_REQUEST['mission_id'])) 
							{ 
							?>
                            <label class="text-danger h5">Select A Service : </label><select id="product_vas" name="product_vas" class="form-control" style="width: 260px; display: inline;">
                            	<option value="select">Select</option>
                                <option value="all">All Services</option>
                                <?php
								$get_vas_items = mysql_query("select id, service_name from price_list where current_status ='active' and mission_id='".$_REQUEST['mission_id']."' ORDER BY service_name");
								while($get_vas_name = mysql_fetch_array($get_vas_items))
								{
									
									?>
									<option value='<?php echo $get_vas_name['id']; ?>'><?php echo $get_vas_name['service_name']; ?></option>
                                    <?php
								}
								?>
                            </select>
                            <?php
							}
							?>
                            </td>
                        </tr>
                        <tr style="display:none;" id="product_name_bts">
                        	<td colspan="2" style="border-top: 0px;">
                            <?php 
							if(isset($_REQUEST['mission_id'])) 
							{ 
							?>
                            <label class="text-danger h5">Select A Travel Product : </label>
                            	<select id="product_bts" name="product_bts" class="form-control" style="width: 260px; display: inline;">
                            	<option value="select">Select</option>
                                <?php
								$get_vas_items = mysql_query("select distinct(product_name) as product_name from bts_sales where mission_id='".$_REQUEST['mission_id']."'ORDER BY product_name");
								while($get_vas_name = mysql_fetch_array($get_vas_items))
								{
									?>
									<option value='<?php echo rawurlencode($get_vas_name['product_name']); ?>'><?php echo $get_vas_name['product_name']; ?></option>
                                    <?php
								}
								?>
                            </select>
                            <?php
							}
							?>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-top:0px; text-align:right;"><input type="button" name="get_report" class="btn btn-success" id="get_report" onClick="retrive_report()" value="Submit"/></td>
                            <td style="border-top:0px; text-align:left;"><input type="button" value="Cancel" name="reset" class="btn btn-danger" id="get_report" onClick="pg_reload()"/></td>
						</tr>
                        
                        <tr>
                        	<td colspan="2">
                            </td>
                        </tr>
                    </table>
                    
                    </form>
					<div align="center" id="loading_div" style=" width: 100%; height: 100%; vertical-align: central !important;">
                    	
                    </div>
				</div>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
	</div>
</div>

<div id="popup_box_chart" align="center" style="text-align:center;">    <!-- OUR PopupBox DIV-->
	<div style="overflow-y:auto; display:block; width: 95%; height: 95%;">
        <div id="grph" style="height:900px; width:1010px;"></div>
    	<div id="btns"></div>
        <!-- div id="popup_box_chart1"></div -->  
    	<a id="popupBoxClose"><img src="styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox_chart()"></a> 
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