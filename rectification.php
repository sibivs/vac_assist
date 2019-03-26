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
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator" ||$_SESSION['role_ukvac']=="staff" )&&  isset($_SESSION['vac']))
{
	$disabled = explode(",",$_SESSION['disabled_string']);
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
<link rel="stylesheet" type="text/css" href="styles/style.css" />
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script src="Scripts/script.js"></script>
<script type="text/javascript">

function pg_reload()
{
	document.form1.action="rectification.php";
	document.form1.submit();
}
	//loadPopupBox_manage_submission();
	//setTimeout(function(){unloadPopupBox_manage_submission(); }, 3000);
function deliver_rectification(gwf_num, pno)
{
        var agree= confirm("Confirm the Reference Number -"+gwf_num+" Before Delivering the Passport");
        if(agree)
        {
			$.ajax(
			{
				type: 'post',
				url: 'php_func.php',
				data: 'cho=9&gwf='+gwf_num,
				dataType:"json",
				success: function(json)
				{
					if(json=="success")
					{
						$("#popup_data").html("<span  class='h4' style='color:green !important;'>Passport is delivered to applicant</span>")
					}
					else if(json=="failed")
					{
						$("#popup_data").html("<span style='color:red !important;' class='h4'>Failed to complete the action</span>")
					}
					else
					{
						$("#popup_data").html("<span style='color:red !important;' class='h4'>Something went wrong. Contact Administrator</span>")
					}
					loadPopupBox_rect();
					setTimeout(function(){unloadPopupBox_rect(); }, 3000);
				},
				error: function(request, status, error)
				{
					alert(request.responseText);  
				}
			});
        	//document.form1.action="php_func.php?cho=9&gwf="+gwf_num;
           // document.form1.submit();
        }
        else
        {
        window.location.reload();
        }
}


function inscan_rectification(gwf_num)
{
        var agree= confirm("Confirm the Reference Number -"+gwf_num+" Before Inscanning the Passport");
        if(agree)
        {
			$.ajax(
			{
				type: 'post',
				url: 'php_func.php',
				data: "cho=7&gwf="+gwf_num,
				dataType:"json",
				success: function(json)
				{
					if(json=="success")
					{
						$("#popup_data").html("<span  class='h4' style='color:green !important;'>Rectification inscan Record Updated</span>")
					}
					else if(json=="failed")
					{
						$("#popup_data").html("<span style='color:red !important;' class='h4'>Failed to complete the action</span>")
					}
					else
					{
						$("#popup_data").html("<span style='color:red !important;' class='h4'>Something went wrong. Contact Administrator</span>")
					}
					loadPopupBox_rect();
					setTimeout(function(){unloadPopupBox_rect(); }, 3000);
				},
				error: function(request, status, error)
				{
					alert(request.responseText);  
				}
			});
			
			
        	//document.form1.action="php_func.php?cho=7&gwf="+gwf_num+"&pn="+pno;
           // document.form1.submit();
        }
        else
        {
        window.location.reload();
        }
}


function add_outscan_rectification()
{
	var gwf=document.getElementById('txtbx_rectification').value.toUpperCase();;
	var e = document.getElementById("select_rectification");
	var type = e.options[e.selectedIndex].value;
	var count=0;
	if(gwf=="")
	{
		$("#txtbx_rectification").css("border-color","red");
		count++;
	}
	else
	{
		$("#txtbx_rectification").css("border-color","#ccc");
	}
	if(type=="select")
	{
		$("#select_rectification").css("border-color","red");
		count++;
	}
	else
	{
		$("#select_rectification").css("border-color","#ccc");
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
				data: "cho=8&gwf="+gwf+"&type="+type,
				dataType:"json",
				success: function(json)
				{
					if(json=="success")
					{
						$("#popup_data").html("<span  class='h4' style='color:green !important;'>Rectification Outscan Record Updated</span>")
					}
					else if(json=="failed")
					{
						$("#popup_data").html("<span style='color:red !important;' class='h4'>Failed to complete the action</span>")
					}
					else if(json =="ref_existing")
					{
						$("#popup_data").html("<span style='color:red !important;' class='h4'>This Reference Number Already existing</span>")
					}
					else
					{
						$("#popup_data").html("<span style='color:red !important;' class='h4'>Something went wrong. Contact Administrator</span>")
					}
					loadPopupBox_rect();
					setTimeout(function(){unloadPopupBox_rect(); }, 3000);
				},
				error: function(request, status, error)
				{
					alert(request.responseText);  
				}
			});
		
		
		
		
		
		
		
		
        //document.form1.action="php_func.php?cho=8";
        //document.form1.submit();
    }

}

function show_add_service()
{
	$("#loginScreen").show();
	$("#cover").css('display','block');
	$("#cover").css('opacity','1');
}

</script>

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
	height:250px; 
	width:600px; 
	margin:0 auto; 
	position:absolute; 
	z-index:10; 
	display:none;
	padding: 10px; 
	border:5px solid #cccccc; 
	border-radius:10px; 
	background-color: #FFF;
	left: 20%;
}  
</style>

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
				<div align="center"> <label class='text-info h4'>MANAGE RECTIFICATION / CANCELLATION</label></div>
      			</div>
                <div id="rectification" align="center" style="margin-top: 5px;">
                	<form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                        <div class="coverStyle" id="cover" onClick="window.location.reload()"> </div>
<div style="text-align: right; width: 100%; float:right;"><u><a onClick="show_add_service()" class="text-warning h4" id="show_walkin">+ Add New Rectification</a></u></div>

                	<div class="loginScreenStyle" id="loginScreen" align="center" style="text-align:center;">
                        	<label class="text-warning" style="font-size: 16px;">
                            	<u>Accept a Passport for Rectification Or Cancellation</u>
                            </label>
                			<table class="table" align="center">
                                <tr>
                                	<td>
                                    	Reference Number :
                                    </td>
                                    <td>
                                    	<input type="text" class="form-control" placeholder="Reference Number" style="width: 210px;" name="txtbx_rectification" id="txtbx_rectification"> 
                                    </td>
                                </tr>
                                <tr>
                                	<td>
                                    	Type :
                                    </td>
                                    <td>
                                    	<select class="form-control" style="width: 210px;" name="select_rectification" id="select_rectification">
                                            <option value="select">Select Type</option>
                                            <option value="Rectification">Rectification</option>
                                            <option value="Cancellation">Cancellation</option>
                                        </select>
                                    </td>
                                </tr>
                				<tr>
                                	<td class="text-info h4" colspan="2">
                                    	<input type="button" value="Submit" onClick="add_outscan_rectification()" class="btn btn-success"> 
                                       	<input type="button" class="btn btn-danger" onclick="pg_reload()" value="Cancel"> 
                                    </td>
                                </tr>
                        	</table>
                    	</div>
                  		
                    	<div style="width:100%;">
                        <table style="width: 90%;" class="table table-striped">
                        	<thead class="text-danger" style="font-size: 16px;">    
                                <tr>
                                    <td>Sl. No</td>
                                    <td>Ref. Number</td>
                                    <td>Dispatched To Embassy</td>
                                    <td>Received At VAC ON</td>
                                    <td>Type</td>
                                    <td>Option</td>
                                    <td></td>
                                </tr>
                        	</thead>
                            <tbody>
                            <?php
                        $cnt=0;
						$query_count_result = "SELECT count(*) FROM rectification_cancellation WHERE (status = 'inscan' or status = 'outscan') and mission_id='".$_SESSION['mission_id']."' ";
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
                			$query = "SELECT count(*) FROM rectification_cancellation WHERE (status = 'inscan' or status = 'outscan') and mission_id='".$_SESSION['mission_id']."' and id <> ''";
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
							$qr_t="SELECT * FROM rectification_cancellation WHERE (status = 'inscan' or status = 'outscan') and mission_id='".$_SESSION['mission_id']."' order by status  ASC $limit ";
                        	$query_instance = mysql_query($qr_t) OR die(mysql_error());
                        	$rows=mysql_num_rows($query_instance);
							while($recResult = mysql_fetch_array($query_instance))
							{
								$cnt++;
						?>
                        
						<tr>
							<td > <?php echo $cnt; ?></td>
                            <td ><?php echo $recResult['gwf_number']; ?></td>
                            <td ><?php echo $recResult['outscan_date']; ?></td>
                            <td ><?php echo $recResult['inscanned_on']; ?></td>
                            <td ><?php echo $recResult['type']; ?></td>
                            <td >
                            <?php
							//if(!in_array("passport_deliver.php",$disabled))
							//{
								if($recResult['status']== "inscan")
								{
								?>
								<input type="button" value="Deliver Passport" onClick="deliver_rectification('<?php echo $recResult['gwf_number'];  ?>','<?php echo $pageno;?>')">
								<?php
								}
								else if($recResult['status']== "outscan")
								{
								?>
								<a style="color:#00C; cursor:pointer;" onClick="inscan_rectification('<?php echo $recResult['gwf_number'];  ?>','<?php echo $pageno;?>')">Inscan Passport</a>
								<?php
								}
							//}
							?>
                            </td>
						</tr>
                        <?php
							}
						}
						?>
                        </tbody>
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
                    <p style="height:30px"></p>

                    </div>
                    </form>

				</div>
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
	</div>
</div>



<!-- POP-UP-Response MESSAGE -->
	<div id="PopupBox_manage_submission" align="center" style="text-align:center; border-radius: 15px;">
		<div class="text-warning text-center h4" id="popup_data">
   		</div>
    	<a id="popupBoxClose">
        	<img src="../styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox_manage_submission()">
        </a>
	</div>
	<!-- END POP-UP-ResponseO MESSAGE -->


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