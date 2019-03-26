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
if(isset($_SESSION['role_ukvac']) && $_SESSION['role_ukvac']=="administrator" &&  isset($_SESSION['vac']))
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
<!-- script src="Scripts/jquery-1.4.4.js" type="text/javascript"></script -->
<link rel="stylesheet" href="menu/css/style_menu.css">
<link rel="stylesheet" href="styles/style.css">
<script src="Scripts/script.js"></script> 
<style type="text/css">
.coverStyle{ 
	position:fixed; 
	top:0; 
	left:0;
	background:rgba(0,0,0,0.6); 
	z-index:5; 
	width:100%; 
	height:100%; 
	display:none; 
} 
.loginScreenStyle { 
	height:550px; 
	width:800px; 
	margin:0 auto; 
	position:absolute; 
	z-index:10; 
	display:none;
	padding: 10px; 
	border:5px solid #cccccc; 
	border-radius:10px; 
	background-color: #FFF;
	left: 25%;
}  
</style>
<script type="text/javascript">
function reset_page()
{
	window.location.reload();
}


function delete_service(id, pno)
{
        var agree= confirm("Are you sure you want to delete this Service Permanantly?");
        if(agree)
        {
        document.form1.action="php_func.php?cho=23&id="+id+"&pn="+pno;
                //alert("php_func.php?cho=9&member_id="+id);
                document.form1.submit();
        }
        else
        {
        window.location.reload();
        }
}


function edit_service(id, pageno)
{
	var inpid = "inp_"+id;
	document.getElementById('row_'+id).innerHTML="<input class='form-control' id='"+inpid+"' type='text' style='width:50px;'/>";
	document.getElementById('row1_'+id).innerHTML='<input type="button" class="btn btn-success"  onClick="update_service('+id+','+pageno+')" value="update">';
	
}


function update_service(id, pno)
{
	
	var newdata = document.getElementById("inp_"+id).value;
	if(newdata !="" && !isNaN(newdata))
	{
		document.form1.action="php_func.php?cho=26&pno="+pno+"&id="+id+"&val_toupdate="+newdata;
		document.form1.submit();
	}
	
	else
	{
		alert("Numbers Only!")
	}
}

function submit_addnewservice()
{
	var newservice = $('#newservice').val();
	var currency = $('#currency_used').val();
	var price = $('#serviceamount').val();
	var vas_category = $('#vas_categ').val();
	var selected_yes_no = $("#multiple_copy").val();
	var resubmission_needed = $("#resubmission_needed").val();
	var alert_status = $("#alert_status").val(); ;
	var alert_days = $("#alert_days").val();
	var cnt=0;
	var msg="";
	if(newservice=="")
	{
		$("#newservice").css("border-color","red");
		cnt++;
	}
	else
	{
		$("#newservice").css("border-color","#ccc");
	}
	
	if(currency=="select")
	{
		$("#currency_used").css("border-color","red");
		cnt++;
	}
	else
	{
		$("#currency_used").css("border-color","#ccc");
	}
	
	if(price=="")
	{
		$("#serviceamount").css("border-color","red");
		cnt++;
	}
	else
	{
		$("#serviceamount").css("border-color","#ccc");
	}
	
	if(vas_category=="select")
	{
		$("#vas_categ").css("border-color","red");
		cnt++;
	}
	else
	{
		$("#vas_categ").css("border-color","#ccc");
	}
	
	if(selected_yes_no=="select")
	{
		$("#multiple_copy").css("border-color","red");
		cnt++;
	}
	else
	{
		$("#multiple_copy").css("border-color","#ccc");
	}
	if(resubmission_needed=="select")
	{
		$("#resubmission_needed").css("border-color","red");
		cnt++;
	}
	else
	{
		$("#resubmission_needed").css("border-color","#ccc");
	}
	if(cnt>0)
	{
		alert("Please Fill All Details");
		
	}
	else
	{
		$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data:"cho=22&newservice="+newservice+"&currency_used="+currency+"&serviceamount="+price+"&vas_categ="+vas_category+"&multiple_copy="+selected_yes_no+"&alert_status="+alert_status+"&alert_days="+alert_days+"&resubmission_needed="+resubmission_needed,
			dataType:"json",
			success: function(json)
			{
				if(json=="added")
				{
					$("#loginScreen").html("<span class='text-success'>Nwe Service is added!</span>");
					window.setTimeout(function(){window.location.reload()},2000)
				}
				else
				{
					$("#response1").html("<span class='text-warning'>Something Went Wrong. Contact Administrator!</span>");
				}
			},
			error: function(request, status, error)
			{
				$("#response1").html("<span class='text-warning'>Something Went Wrong. Contact Administrator!</span>");
			}
		});
	}
	
}

function show_add_service()
{
	$("#loginScreen").show();
	$("#cover").css('display','block');
	$("#cover").css('opacity','1');
}
</script>

</head>

<body>
<?php
$get_custom_permission_qr = mysql_query("select manage_service_change from custom_permissions where user_id='".$_SESSION['user_id_ukvac']."'");
	if(mysql_num_rows($get_custom_permission_qr) >0)
	{
		$get_custom_mg_service = mysql_fetch_array($get_custom_permission_qr);
	}
?>

<form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
<div id="templatemo_container" style="height:1100px;">
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
				<div align="center"> <label class='text-info h4'>MANAGE VALUE ADDED SERVICES</label></div>
      			</div>
                
                <p style="height: 10px;"></p>
                                <div class="loginScreenStyle" id="loginScreen" align="center" style="text-align:center;">

                	<table class="table" align="center" cellspacing="0" style="border-color: #e4ecf7 !important; width: 90%;">
                    	<tr>
                           <td colspan="3" style="text-align: center; border-top: 0px;" class="text-danger h4">
                                <u>Add New Service</u>
                           </td>
                    	</tr>
						<tr>
							<td width="25%" align="left">New Service Name</td>
							<td style="width: 5%;">: </td>
                            <td align="left"><input type="text" id="newservice" name="newservice" class="form-control" style="width: 220px;"></td>
                       	</tr>
                        <tr>
							<td width="25%" align="left">Currency Used For The Service</td>
							<td style="width: 5%;">: </td>
                            <td width="25%" align="left">
                            	<select id="currency_used" name="currency_used" class="form-control" style="width: 220px">
                                	<option value="select">Select</option>
									<?php
                                        $get_currencies = mysql_fetch_array(mysql_query("select currencies from missions where id='".$_SESSION['mission_id']."' and status='active'"));
                                        $curr = preg_replace('/\s+/', '', $get_currencies['currencies']);
                                        if (preg_match('/,/',$curr))
                                        {
                                            $currency = explode(",",$curr);
                                        }
                                        else
                                        {
                                            $currency[0]=$curr;
                                        }
                                        foreach($currency as $key=> $value)
                                        {
                                    ?>
                                	<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                    <?php
										}
									?>
                                </select>
                                	
                            </td>
                       	</tr>
                        <tr>
							<td width="25%" align="left">Amount For The Service</td>
							<td style="width: 5%;">: </td>
                            <td width="25%" align="left"><input type="text" id="serviceamount" name="serviceamount" class="form-control" style="width: 220px;"></td>
                       	</tr>
                        <tr>
							<td width="25%" align="left">VAS Category</td>
							<td style="width: 5%;">: </td>
                            <td width="25%" align="left">
                            	<select id="vas_categ" name="vas_categ" class="form-control" style="width: 220px">
                                	<option value="select">Select</option>
                                	<option value="appointment">Appointments</option>
                                    <option value="visa fees">Visa Fees</option>
                                   	<option value="submission">Application Submission</option>
                                </select>
                            </td>
                       	</tr>
                        <tr >
							<td width="25%" align="left">Add A Textbox To Enter No. Of Copies? <br><span style="font-size: 10px;" class="text-danger">(Eg. option for entering no. of photocopy pages)</span> </td>
							<td style="width: 5%;">: </td>
                            <td width="25%" align="left">
                            	<select id="multiple_copy" name="multiple_copy" class="form-control" style="width:220px;">
                                	<option value="select">Select An Option</option>
                                    <option value="0">No</option> 
                                    <option value="1">Yes</option>
                            	</select>
                            </td>
                       	</tr>
                        <tr>
							<td width="25%" align="left">Need Passport resubmission?</span> </td>
							<td style="width: 5%;">: </td>
                            <td width="25%" align="left">
                            	<select id="resubmission_needed" name="resubmission_needed" class="form-control" style="width:220px;">
                                	<option value="select">Select An Option</option>
                                    <option value="0">No</option> 
                                    <option value="1">Yes</option>
                            	</select>
                            </td>
                       	</tr>
                        <tr>
							<td colspan="3" align="left" class="text-info" >
                            **Send Reminder emails if passport in <select id="alert_status" name="alert_status" class="form-control" style="width:100px; display: inline;">
                                	<option value="select">Select</option>
                                    <option value="inscan">Inscan</option> 
                                    <option value="outscan">Outscan</option>
                            	</select> for more than <select id="alert_days" name="alert_days" class="form-control" style="width:100px; display: inline;">
                                	<option value="select">Select</option>
                                    <option value="5">5</option> 
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                            	</select> days
                            	
                            </td>
                       	</tr>
                        <tr>    
                            <td width="25%" align="middle"><input type="button" class="btn btn-success" onClick="submit_addnewservice()" value="Add Service" <?php if($get_custom_mg_service['0']=="0"){ ?> disabled <?php } ?>></td>
                            <td style="width: 5%;" ></td>
                            <td width="25%" align="middle"><input type="button" class="btn btn-danger" onClick="reset_page()" value="Cancel"></td>
                        </tr>
                	</table>
                    <div id="response1"></div>
                    </div> 
<div class="coverStyle" id="cover" onClick="window.location.reload()"> </div>
<div style="text-align: right; width: 100%; float:right;"><u><a onClick="show_add_service()" class="text-warning h4" id="show_walkin">+ Add New Service</a></u></div>
<hr />
                <div style="padding: 15px;" align="center">
                <table class="table" align="center" style="width: 100%; font-size: 13px;">
                    <thead class="text-info" style="font-weight: bold;">
						<tr>
							<td >Sl.No</td>
							<td >Service Name</td>
                            <td >VAS Type</td>
                            <td >Service Charge</td>
                            <td >Send Alert if</td>
                            <td >Need Resubmission</td>
                            <td >Edit Details</td>
                            <td >Delete Service</td>
						</tr>
                    </thead>
                        <?php
						if(isset($_GET['pageno'])&& $_GET['pageno'] >1)
						{
							$cnt=($_GET['pageno']-1)*8;
						}
						else
						{
							$cnt=0;
						}
						$query_count_result = "SELECT count(*) FROM price_list where current_status='active' and mission_id='".$_SESSION['mission_id']."'";
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
                			$query = "SELECT count(*) FROM price_list where current_status='active' and mission_id='".$_SESSION['mission_id']."' and id <> ''";
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
							$qr_t="SELECT * FROM price_list where current_status='active' and mission_id='".$_SESSION['mission_id']."' order by id ASC $limit ";
                        	$query_instance = mysql_query($qr_t) OR die(mysql_error());
                        	$rows=mysql_num_rows($query_instance);
							while($recResult = mysql_fetch_array($query_instance))
							{
								$cnt++;
						?>
                        <tbody>
						<tr>
							<td  align="left"> <?php echo $cnt; ?></td>
                            <td style="text-align:left !important;"><?php echo $recResult['service_name']; ?></td>
                            <td style="text-align:left !important;">
							<?php 
								if($recResult['vas_category']=="visa fees")
								{
									echo "Visa Fees";
								}
								else if($recResult['vas_category']=="appointment")
								{
									echo "Appointments";
								}
								else if($recResult['vas_category']=="submission")
								{
									echo "Application Submission";
								}
								else
								{
									echo "Others";
								}
								
							?>
                            </td>
                            <td id='<?php echo "row_".$recResult['id']; ?>' style="text-align:middle !important;"><?php echo $recResult['amount']; ?></td>
                            <td style="text-align:left !important;"><?php if($recResult['alert_if_status_is']!="" ) { echo $recResult['alert_if_status_is']." status for more than ".$recResult['alert_after']." days"; }?></td>
                            <td style="text-align:center !important;"><?php if($recResult['need_resubmission']==1) { echo "Yes";} else { echo "No"; } ?></td>
                            <td id='<?php echo "row1_".$recResult['id']; ?>' align="left"><input type="button" class="btn btn-danger" value="Edit"  onClick="edit_service('<?php  echo $recResult['id'];  ?>','<?php echo $pageno;?>')" <?php if($get_custom_mg_service['0']=="0"){ ?> disabled <?php } ?>></td>
                            <td align="left"><?php if($recResult['id'] > 10){ ?> <input type="button" class="btn btn-warning" value="Remove"  onClick="delete_service('<?php  echo $recResult['id'];  ?>','<?php echo $pageno;?>')" <?php if($get_custom_mg_service['0']=="0"){ ?> disabled <?php } ?>> <?php } ?></td>
                           
						</tr>
                        <?php
							}
						}
						?>
                        </tbody>
                    </table>
                    <p style="height:10px"></p>
                    	<?php
						if($result_count_result[0]>0)
        				{ 
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
						}
						else
						{
							echo "Sorry, No Result Found. Please Add new Service.";
						}		
					?>
                </div>
                
               
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
	</div>
</div>
</form>
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