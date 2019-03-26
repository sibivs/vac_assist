<?php
session_start();
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
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator" )&&  isset($_SESSION['vac']))
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
<link rel="stylesheet" href="menu/css/style_menu.css">
<link rel="stylesheet" href="styles/style.css">
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script src="Scripts/script.js"></script>
<style type="text/css">
#cover{ 
	position:fixed; 
	top:0; 
	left:0;
	background:rgba(0,0,0,0.6); 
	z-index:5; 
	width:100%; 
	height:100%; 
	display:none; 
} 
#loginScreen { 
	height:400px; 
	width:550px; 
	margin:0 auto; 
	position:absolute; 
	z-index:10; 
	display:none;
	padding: 10px; 
	border:5px solid #cccccc; 
	border-radius:10px; 
	background-color: #FFF;
	left: 35%;
}  
</style>

<script type="text/javascript">
function click_on_approval(request_for,ref_no,glfs,id,app_type)
{
	if(request_for==3)
	{
		$("#loginScreen").show();
		$('#heading_lbl').html("<u>Approve Request</u>");
		$('#approve_btn').val('Approve');
		$("#appointment option").filter(function() 
		{
			return $(this).text() == app_type; 
		}).prop('selected', true);
		
		$("#approve_btn").click(function(){ approve(request_for,ref_no,glfs,id,app_type); });
		$("#cover").css('display','block');
		$("#cover").css('opacity','1');
	}
	else
	{
		approve(request_for,ref_no,glfs,id);
	}
}

function approve(request_for,ref_no,glfs,id,app_type)
{
	//alert(id)id -> approval table id
	if(request_for == "1") //Edit
	{
		if(confirm("Please Confirm that you want to approve the edit request for the Reference Number - "+ref_no+""))
		{
			$.ajax(
			{
					type: 'post',
					url: 'php_func.php',
					data: 'cho=46&id='+id,
					dataType:"json",
					success: function(json)
					{
						//alert(json);
						if(json=="approved")
						{
							alert("This request is approved for editing");
							window.location.reload();
							
						}
						else if(json=='timeout')
						{
							alert('Session Expired. Please Login!')
							window.location.href='login.php';
							window.location.reload();
						}
						else
						{
							alert("Something went wrong.");
						}
					},
					error: function(request, status, error)
					{
						alert(request.responseText);  
					}
						
				});
			}
			else
			{
				alert("This request is cancelled.");
			}
			
	}
	else if(request_for == "2")//Delete
	{
		if(confirm("Please Confirm that you want to remove \n the Reference Number - "+ref_no+" - from the system"))
		{
			var stringed = ref_no;
			//alert(stringed);
			$.ajax(
				{
					type: 'post',
					url: 'php_func.php',
					data: 'cho=27&dt='+ref_no+'&status=r&glfsno='+glfs+"&id="+id,
					dataType:"json",
					success: function(json)
					{
						//alert(json);
						if(json=="removed")
						{
							alert("This Reference number is removed from the system");
							window.location.reload();
							
						}
						else if(json=='timeout')
						{
							alert('Session Expired. Please Login!')
							window.location.href='login.php';
							window.location.reload();
						}
						else
						{
							alert("Something Went Wrong");
						}
					},
					error: function(request, status, error)
					{
						alert(request.responseText);  
					}
						
				});
			}
			else
			{
				alert("This request is cancelled.");
			}
	}
	else if(request_for == "3")//walkin accept
	{
		var comment = $("#remark").val();
		var app_type = $("#appointment").val();
		if(comment =="")
		{
			$("#remark").css('border-color','red');
		}
		else
		{
			$("#remark").css('border-color','#ccc');
			if(confirm("Please Confirm to Accept the Walk-in \n Reference Number - "+ref_no+" - from the system"))
			{
				$.ajax(
				{
					type: 'post',
					url: 'php_func.php',
					data: 'cho=49&dt='+ref_no+'&id='+id+'&comment='+comment+'&app_type='+app_type,
					dataType:"json",
					success: function(json)
					{
						//alert(json);
						if(json=="accepted")
						{
							alert("This Application is accepted as Walk-In");
							window.location.reload();
								
						}
						else if(json=='timeout')
						{
							alert('Session Expired. Please Login!')
							window.location.href='login.php';
							window.location.reload();
						}
						else
						{
							alert("Something Went Wrong");
						}
					},
					error: function(request, status, error)
					{
						alert(request.responseText);  
					}
							
				});
			}
			else
			{
				alert("This request is cancelled.");
			}
		}
	}
}	


function click_reject(ref_no,id,rq)
{
	if(rq==3)
	{
		$("#loginScreen").show();
		$('#heading_lbl').html("<u>Reject Request</u>");
		$('#approve_btn').val('Reject');
		$("#tbl1 .hideme").hide();
		$("#approve_btn").click(function(){ reject(ref_no,id,rq); });
		$("#cover").css('display','block');
		$("#cover").css('opacity','1');
	}
	else
	{
		reject(ref_no,id,rq);
	}
}

function reject(ref_no,id,rq)
{
	if(rq==3)
	{
		var comment = $("#remark").val();
		if(comment =="")
		{
			$("#remark").css('border-color','red');
		}
		else
		{
			$("#remark").css('border-color','#ccc');
			if(confirm("Confirm to reject edit/ delete request for Reference Number - "+ref_no))
			{
				//alert(stringed);
				$.ajax(
				{
					type: 'post',
					url: 'php_func.php',
					data: 'cho=47&id='+id+'&rq='+rq+'&dt='+ref_no+'&comment='+comment,
					dataType:"json",
					success: function(json)
					{
						//alert(json);
						
						if(json=="rejected")
						{
							alert("Request for Edit / Delete is rejected");
							window.location.reload();
						}
						else if(json=="ao_rejected")
						{
							alert("Request for Walk-In is rejected");
							window.location.reload();
						}
						else if(json=='timeout')
						{
							alert('Session Expired. Please Login!')
							window.location.href='login.php';
							window.location.reload();
						}
						else
						{
							//alert("Something Went Wrong");
							alert(request.responseText);  
							
						}
					},
					error: function(request, status, error)
					{
						alert(request.responseText);  
					}
						
				});
			}
			else
			{
				alert("This request is cancelled.");
			}
		}
	}
	else
	{
		if(confirm("Confirm to reject edit/ delete request for Reference Number - "+ref_no))
		{
			//alert(stringed);
			$.ajax(
			{
				type: 'post',
				url: 'php_func.php',
				data: 'cho=47&id='+id+'&rq='+rq+'&dt='+ref_no,
				dataType:"json",
				success: function(json)
				{
					//alert(json);
						
					if(json=="rejected")
					{
						alert("Request for Edit / Delete is rejected");
						window.location.reload();
					}
					else if(json=="ao_rejected")
					{
						alert("Request for Walk-In is rejected");
						window.location.reload();
					}
					else if(json=='timeout')
					{
						alert('Session Expired. Please Login!')
						window.location.href='login.php';
						window.location.reload();
					}
					else
					{
						//alert("Something Went Wrong");
						alert(request.responseText);  
							
					}
				},
				error: function(request, status, error)
				{
					alert(request.responseText);  
				}
						
			});
		}
	}
}

</script>

</head>

<body>
<?php
$get_custom_permission_qr = mysql_query("select approval_walk_in from custom_permissions where user_id='".$_SESSION['user_id_ukvac']."'");
	if(mysql_num_rows($get_custom_permission_qr) >0)
	{
		$get_custom_permission = mysql_fetch_array($get_custom_permission_qr);
	}
?>
<div id="templatemo_container">
	<div id="templatemo_header">
		<img src="" style=" width: 156px; height: auto; padding-top: 10px;" />
        <label style="text-shadow:1px 3px 1px rgba(3,3,3,1);font-weight:normal;color:#FFFFFF;letter-spacing:3pt;word-spacing:-2pt;font-size:23px;text-align:left;font-family:lucida sans unicode, lucida grande, sans-serif;line-height:1; float:right; padding-top: 30px; padding-right: 100px;">DOS - Daily Operations System<br>
        <label style="font-size:15px;"><?php echo $_SESSION['vac']." VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?></label></label>
    </div><!-- End Of Header -->
            <div id="menu" style="width:1200px; padding-top: 5px;"><?php require_once("menu/left_menu.php"); ?></div>
			<div id="templatemo_userinfo"><?php require_once("header.php"); ?></div>  
	<div id="templatemo_content">
		<div id="" style="width: 100% !important; " align="center;">
			<div style="width: 100% !important;" align="center;">
				<div align="center"> <label class='text-info h4'>
                        	MANAGE PENDING APPROVALS</label></div>
      			</div>
                <div id="div_reports" align="center" style="margin-top: 5px;">
                    <form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                    <div id="loginScreen">
                    	<label class="h4 text-info" id="heading_lbl"></label>
                        <br>
                        <table id="tbl1" class="table" style="border:none;">
                        	<tr class='hideme'>
                            	<td class="text-info" style="border:none;">
                                	Appointment Type
                                </td>
                                <td style="border:none;">
                                	<select id="appointment" name="appointment" class="form-control" style="width: 210px; display:inline;">
                                        <option value="Select">Select Appointment Type</option>
                                        <?php
                                        $get_appointment_types = mysql_query("select type from appointment_types where mission_id='".$_SESSION['mission_id']."' and status='active' order by type");
                                        while($get_types = mysql_fetch_array($get_appointment_types))
                                        {
                                        ?>
                                                
                                        <option value="<?php echo $get_types['type']; ?>"><?php echo $get_types['type']; ?></option>
                                        <?php
                                        }
                                        ?>
                                	</select>
                                </td>
                            </tr>
                            <tr>
                            	<td class="text-info" style="border:none;">
                                	Supervisor's Comments :
                                </td>
                                <td style="border:none;">
                                	<textarea id="remark" value="" class="form-control" style="width: 210px; height: 100px; display:inline;"></textarea>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="2" style="border:none; text-align:center;">
                                	<input type="button" class="btn btn-info" id="approve_btn" value="" onClick="" style="padding: 10px;" >
                        			<input type="button" class="btn btn-danger" value="Cancel" onClick="window.location.href='approvals.php'" style="padding: 10px;" >
                                </td>
                            </tr>
                        </table>
                	</div> 
                <div id="cover" onClick="window.location.reload()"> </div>
                        <?php
				if (isset($_GET['pageno'])) 
				{
					$pageno = $_GET['pageno'];
				}
				else
				{
					$pageno = 1;
					$lastpage = 1;
				}
				$query = "SELECT count(*) FROM approvals WHERE mission_id='".$_SESSION['mission_id']."' and current_status NOT IN ('completed') and rqst_date = '".date('Y-m-d')."' and id <> ''";
				$result = mysql_query($query);
				$query_data = mysql_fetch_row($result);
                $numrows = $query_data[0];
				if($numrows >0)
				{
				?>
                    <table class="table">
                        <thead class="text-info" style="">
                            <tr>
                                <td >Ref. Number</td>
                                <!--td style="width: 10%;">TEE No.</td-->
                                <td >Requested By</td>
                                <td >Request</td>
                                <td >Remarks</td>
                                <td >Supervisor Remarks</td>
                                <td >Status</td>
                                <td >Approve</td>
                                <td >Reject</td>
                            </tr>
                        </thead>
                    	<?php 
                			$rows_per_page = 30;
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
							if($pageno == 1 ||$pageno =="" )
							{
								$cnt=0;
							}
							else if($pageno >1)
							{
								$cnt= ($pageno-1)*$rows_per_page;
							}
                			$limit = 'LIMIT ' .($pageno-1) * $rows_per_page .',' .$rows_per_page;
							$qr_t="SELECT a.id as id, a.ref_id as ref_id, a.remark as remark, a.approval_remark as approval_remark, u.display_name as staff,a.status  as status, a.current_status as current FROM approvals a, users u where a.mission_id='".$_SESSION['mission_id']."' and a.current_status NOT IN ('completed') and a.request_by = u.user_id and a.rqst_date = '".date('Y-m-d')."' order by a.id ASC $limit ";
							$query_instance = mysql_query($qr_t) OR die(mysql_error());
                        	$rows=mysql_num_rows($query_instance);
							while($recResult = mysql_fetch_array($query_instance))
							{
								if($recResult['status']=='3')
								{
									//AO WALK-IN APPROVAL
									$get_ref_no = mysql_fetch_array(mysql_query("select reference_number as ref_no, actual_appointment from appointment_schedule where id ='". $recResult['ref_id']."'"));
									
									$get_glfs['glfs']="";
								}
								else
								{
									$get_ref_no = mysql_fetch_array(mysql_query("select reference_number as ref_no, actual_appointment from appointment_schedule where id ='". $recResult['ref_id']."'"));
									$get_glfs = mysql_fetch_array(mysql_query("select g.barcode_no as glfs from tee_envelop_counts g , passport_inscan_record i where i.glfs_id = g.id and i.gwf_number='".$get_ref_no['ref_no']."'"));
								}
								
								if($recResult['status']==1)
								{
									$request_for = "Edit RO";
								}
								else if($recResult['status']==2)
								{
									$request_for = "Delete RO";
								}
								else if($recResult['status']==3)
								{
									$request_for = "Walk-IN AO";
								}
								?>
								<tbody style="border-top-width: 1px; ">
									<tr>
										<td align="left"> 
											<?php echo strtoupper($get_ref_no['ref_no']); ?>
                                       	</td>
                                        <!--td align="left"-->
											<?php //echo $get_glfs['glfs']; ?>
                                        <!--/td-->
										<td align="left">
											<?php echo $recResult['staff']; ?>
                                        </td>
										<td align="left">
											<?php echo $request_for; ?>
                                       	</td>
                                        <td align="left">
											<?php echo $recResult['remark']; ?>
                                       	</td>
                                        <td align="left">
											<?php echo $recResult['approval_remark']; ?>
                                       	</td>
                                        <td align="left">
											<?php 
											if($recResult['current']=="Pending")
											{
												echo "<span style='color: red'>Pending</span>"; 
											}
											else if($recResult['current']=="approved")
											{
												echo "<span style='color: green'>Approved</span>"; 
											}
											?>
                                        </td>
										<td align="left">
											<?php
												if($recResult['current']=="Pending")
												{
											?>
                                            <input type="button" class="btn btn-info" value="Approve" onClick="click_on_approval('<?php echo $recResult['status']; ?>','<?php echo $get_ref_no['ref_no']; ?>','<?php echo $get_glfs['glfs']; ?>','<?php echo $recResult['id']; ?>','<?php echo $get_ref_no['actual_appointment']; ?>')" <?php if($get_custom_permission['0']==0){ ?> disabled <?php } ?>>
                                            
                                            <?php
											}
											?>
                                      	</td>
										<td align="left">
                                        	<?php
												if($recResult['current']=="Pending")
												{
											?>
                                        	<input type="button" class="btn btn-danger" value="Reject" onClick="click_reject('<?php echo $get_ref_no['ref_no']; ?>','<?php echo $recResult['id']; ?>','<?php echo $recResult['status']; ?>')" <?php if($get_custom_permission['0']==0){ ?> disabled <?php } ?> >
                                            	<?php
												}
												?>
										</td>
									</tr>
								</tbody>
								<?php
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
								echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage' style='font-size:13px; cursor:pointer;'>PREVIOUS</a> ";
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
                    <p style="height:30px"></p>
                    </tbody>
                </table>
                <?php
                 }
				else
				{
				?>
					<div class="text-info" style="text-align:center; font-weight: 600;">Nothing is pending for approval....!!</div>
				<?php
                } 
				?>  
                            
                   	</form>
				</div>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
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