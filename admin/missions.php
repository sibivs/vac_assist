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
<title>VAC Assist</title>
<!-- Main stylesheet and javascripts for the page -->
<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="../Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<!--script src="Scripts/jquery-1.4.4.js" type="text/javascript"></script-->
<link rel="stylesheet" href="../styles/style.css">
<link rel="stylesheet" href="../menu/css/style_menu.css">

<script src="func/script.js"></script> 
<script type="text/javascript">

function delete_mission(id)
{
	var agree= confirm("Are you sure you want to delete this Service Permanantly?");
    if(agree)
    {
		$.ajax(
		{
			type: 'post',
			url: 'func/php_func.php',
			data: 'cho=2&id='+id,
			dataType:"json",
			success: function(result)
			{
				if(result=="deleted")
				{
					$("#resp_del").html("<p style='color: purple; font-size: 13px;'>This mission is deacctivated.</p>");
					setTimeout(function(){window.location.href="missions.php"; /*window.location.reload();*/ }, 1500);
				}
				else if(result=="failed")
				{
					$("#resp_del").html("<p style='color: red; font-size: 13px;'>Failed to de-activate. Please contact administrator</p>");
				}
					
			},
			error: function(request, status, error)
			{
					alert(request.responseText);  
			}
				
		});
        	//document.form1.action="func/php_func.php?cho=2&id="+id+"&pn="+pno;
            //alert("php_func.php?cho=9&member_id="+id);
            //document.form1.submit();
	}
    else
   	{
    	window.location.reload();
    }
}


function submit_update_mission(id)
{
	var editmission = $("#editmission").val();
	//var editcurrency = $("#editcurrency").val();
	var currency1 = $('#editcurrency').val();
	var currency2 = $('#editcurrency1').val();
	if(currency1!="select" && currency2!="select")
	{
		var editcurrency = currency1+","+currency2;
	}
	else if(currency1!="select")
	{
		var editcurrency = currency1;
	}
	else if(currency2!="select")
	{
		var editcurrency = currency2;
	}
	else
	{
		var editcurrency="";
	}
	var cnt=0;
	if(!(/^[a-zA-Z\s]{2,25}$/.test(editmission)))
	{
		alert(editmission);
		$('#editmission').css("border-color", "red");
		cnt++;
	}
	else
	{
		$('#editmission').css("border-color", "#ccc");
	}
	
	if(currency1 == "select" && currency2 == "select" )
	{
		$('#editcurrency').css("border-color", "red");
		$('#editcurrency1').css("border-color", "red");
		alert("Please select the primary currency.");
		cnt++;
	}
	else
	{
		$('#editcurrency').css("border-color", "#ccc");
		$('#editcurrency1').css("border-color", "#ccc");
	}
	
	
	if((currency1 == currency2) && currency1 !="select" && currency2 !="select")
	{
		$('#editcurrency').css("border-color", "red");
		$('#editcurrency1').css("border-color", "red");
		alert("Both currencies cannot be same.");
		cnt++;
	}
	else
	{
		$('#editcurrency').css("border-color", "#ccc");
		$('#editcurrency1').css("border-color", "#ccc");
	}
	
	if(cnt>0)
	{
		//return false;
		
	}
	else
	{
		$.ajax(
			{
				type: 'post',
			  	url: 'func/php_func.php',
				data: 'cho=3&name='+editmission+"&currency="+editcurrency+"&id="+id,
			  	dataType:"json",
				success: function(result)
				{
					if(result=="updated")
					{
						unloadPopupBox1();
						$("#resp1").html("<p style='color: purple; font-size: 13px;'>Mission details updated</p>");
					}
					else if(result=="failed")
					{
						unloadPopupBox1();
						$("#resp1").html("<p style='color: red; font-size: 13px;'>Failed to update. Please contact administrator</p>");
					}
					
				},
				error: function(request, status, error)
				{
					alert(request.responseText);  
				}
				
			});
		//document.form1.action="php_func.php?cho=22&pno="+pno;
		//document.form1.submit();
	}
}

function submit_add_new_mission(pno)
{
	var newmission = $('#newmission').val();
	var currency1 = $('#currency').val();
	var currency2 = $('#currency1').val();
	var country = $('#country').val();
	var city = $('#city').val();
	
	if(currency1!="select" && currency2!="select")
	{
		var currency = currency1+","+currency2;
	}
	else if(currency1!="select")
	{
		var currency = currency1;
	}
	else if(currency2!="select")
	{
		var currency = currency2;
	}
	else
	{
		currency="";
	}
	var cnt=0;
	var msg="";
	if(!(/^[a-zA-Z\s]{2,25}$/.test(newmission)))
	{
		$('#newmission').css("border-color", "red");
		cnt++;
	}
	else
	{
		$('#newmission').css("border-color", "#ccc");
	}
	
	if(currency1 == "select" && currency2 == "select" )
	{
		$('#currency').css("border-color", "red");
		$('#currency1').css("border-color", "red");
		alert("Please select the primary currency.");
		cnt++;
	}
	else
	{
		if(currency1 == currency2)
		{
			$('#currency').css("border-color", "red");
			$('#currency1').css("border-color", "red");
			alert("Both currencies cannot be same.");
			cnt++;
		}
		else
		{
			$('#currency').css("border-color", "#ccc");
			$('#currency1').css("border-color", "#ccc");
		}
	}
	
	
	if(country == "select" )
	{
		$('#country').css("border-color", "red");
		cnt++;
	}
	else
	{
		if(city == "select" )
		{
			$('#city').css("border-color", "red");
			cnt++;
		}
		else
		{
			$('#city').css("border-color", "#ccc");
		}
		$('#country').css("border-color", "#ccc");
	}
	
	
	
	if(cnt>0)
	{
		//return false;
		
	}
	else
	{
		$.ajax(
			{
				type: 'post',
			  	url: 'func/php_func.php',
				data: 'cho=1&name='+newmission+"&currency="+currency+"&country="+country+"&city="+city,
			  	dataType:"json",
				success: function(result)
				{
					if(result=="added")
					{
						unloadPopupBox();
						$("#resp").html("<p style='color: purple; font-size: 13px;'>New Mission- "+newmission+" is added.</p>");
					}
					else if(result=="failed")
					{
						unloadPopupBox();
						$("#resp").html("<p style='color: red; font-size: 13px;'>Failed to add new mission. Please contact administrator</p>");
					}
					
				},
				error: function(request, status, error)
				{
					alert(request.responseText);  
				}
				
			});
		//document.form1.action="php_func.php?cho=22&pno="+pno;
		//document.form1.submit();
	}
	
}


function country_selected()
{
	var country_selected = $("#country").val();
	if(country_selected == "select")
	{
		$('#country').css("border-color", "red");
	}
	else
	{
		$('#country').css("border-color", "#ccc");
		$.ajax(
			{
				type: 'post',
			  	url: 'func/php_func.php',
				data: 'cho=9&country_id='+country_selected,
			  	dataType:"json",
				success: function(result)
				{
					$("#city_tr").html(result);
					$("#display_city").show();
				},
				error: function(request, status, error)
				{
					alert(request.responseText);  
				}
				
			});
	}
	
	
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
				<div align="center"> <label class='text-info h4'>Manage Missions</label></div>
      			</div>
                <div style="width: 100%; text-align: right; float: right; padding-top: 15px !important;"><input type="button" class="btn btn-danger" value="Add New Mission" onClick="loadPopupBox()"></div>
                <div style="height: 15px;"></div>
                <?php
				if(isset($_REQUEST['pageno']))
				{
					$pno_rtn = $_REQUEST['pageno'];
				}
				else
				{
					$pno_rtn=1;
				}
				?>
                
               <div style="width: 100%; text-align: center; padding: 5px;" id="resp_del"></div> 
                
                <div style="padding: 15px; width: 100%;">
                <?php 
                if(isset($_SESSION['response_service']))
				{
					echo $_SESSION['response_service'];
					unset($_SESSION['response_service']);
				}
				else
				{
					unset($_SESSION['response_service']);
				}
				?>
                </div>
                <div style="padding: 15px;" align="center">
                
                        <?php
						
						$query_count_result = "SELECT count(*) FROM missions where status='active'";
        				$result_count_result = mysql_fetch_array(mysql_query( $query_count_result));

                		if (isset($_GET['pageno'])) 
						{
                			$pageno = $_GET['pageno'];
							if($pageno >1)
							{
								$cnt=($_GET['pageno']-1)*8;
							}
							else
							{
								$cnt=0;
							}
                		}
                		else
                		{
                        	$pageno = 1;
							$lastpage = 1;
							$cnt=0;

                		}
						if($result_count_result[0]>0)
        				{ 
                			$query = "SELECT count(*) FROM missions where status='active' and id <> ''";
                			$result = mysql_query($query);
                			$query_data = mysql_fetch_row($result);
                			$numrows = $query_data[0];
                			$rows_per_page = 5;
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
							$qr_t="SELECT * FROM missions where status='active' order by mission_name ASC $limit ";
                        	$query_instance = mysql_query($qr_t) OR die(mysql_error());
                        	$rows=mysql_num_rows($query_instance);
							?>
                            
                            <table class="table" align="center" style="width: 100%;">
                        <thead class="text-danger" style="font-size: 16px;">
                            <tr>
                                <td width="8%">Sl.No</td>
                                <td style="text-align:left !important;">Mission</td>
                                <td>Country</td>
                                <td>City</td>
                                <td>Currencies Used</td>
                                <td>Edit Details</td>
                                <td>Delete Service</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							
							while($recResult = mysql_fetch_array($query_instance))
							{
								$cnt++;
						?>
                        
						<tr>
							<td  align="left"> <?php echo $cnt; ?></td>
                            <td style="text-align:left !important;"><?php echo $recResult['mission_name']; ?></td>
                            <?php
								$get_country = mysql_fetch_array(mysql_query("select country from country where id='".$recResult['country_id']."'"));
								
								$get_city = mysql_fetch_array(mysql_query("select city_name from country_cities where id='".$recResult['city']."'"));
							?>
                            <td style="text-align:left !important;"><?php echo $get_country['country']; ?></td>
                           	<td style="text-align:left !important;"><?php echo $get_city['city_name']; ?></td>
                            <td id='<?php echo "row_".$recResult['id']; ?>' style="text-align:middle !important;"><?php echo $recResult['currencies']; ?></td>
                            <td id='<?php echo "row1_".$recResult['id']; ?>' align="left"><input type="button" class="btn btn-info" value="Edit"  onClick="loadPopupBox1('<?php  echo $recResult['id'];  ?>','<?php echo $recResult['mission_name'];?>','<?php echo $recResult['currencies'];?>')"></td>
                            <td align="left"><input type="button" class="btn btn-danger"  onClick="delete_mission('<?php  echo $recResult['id'];  ?>')" value="Delete"> </td>
                           
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
							echo "Sorry, No Result Found. Please Add mission.";
						}		
					?>
                </div>
                
               
 			</div>
		</div>
		<div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;">
        	<span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
            
		</div>
	</div>

<!-- Pop-up-add new mission -->

<div id="popup_box_new_mission" align="center" style="text-align:center; border-radius: 15px;">    <!-- OUR PopupBox DIV-->
    <div class="text-warning text-center h4">
    	<u>Add New Mission</u>
   	</div>
    <div id="resp" style="height: 30px; vertical-align:middle;"></div>
    <table class="table h5" align="center">
    	<tr >
			<td align="right" style="font-weight:bold; border: 0px;">
            	Mission Name :
            </td> 
            <td align="left" style="border: 0px;">
            	<input type="text" id="newmission" name="newmission" class="form-control" style="width:210px; display: inline;">&nbsp; &nbsp; 4-25 charecters only.
          	</td>
  		</tr>
		<tr>
			<td align="right" style="font-weight:bold; border: 0px;">
            	Primary Currency used * : 
            </td> 
            <td align="left" style="border: 0px;">
            	<select id="currency" name="currency" class="form-control" style="width: 210px;">
                	<option value="select">Select Primary Currency</option>
            	<?php
				$get_currency = mysql_query("select currency from note_values");
				while($res_curr=mysql_fetch_array($get_currency))
				{
				?>
                	<option value="<?php echo $res_curr['currency']; ?>"><?php echo $res_curr['currency']; ?></option>
                <?php
				}
                ?>
                </select>
         	</td>
     	</tr>
        <tr>
			<td align="right" style="font-weight:bold; border: 0px;">
            	Second Currency used : 
            </td> 
            <td align="left" style="border: 0px;">
                <select id="currency1" name="currency1" class="form-control" style="width: 210px;">
                	<option value="select">Select Second Currency</option>
            	<?php
				$get_currency1 = mysql_query("select currency from note_values");
				while($res_curr1=mysql_fetch_array($get_currency1))
				{
				?>
                	<option value="<?php echo $res_curr1['currency']; ?>"><?php echo $res_curr1['currency']; ?></option>
                <?php
				}
                ?>
                </select>
         	</td>
     	</tr>
        <tr>
			<td align="right" style="font-weight:bold; border: 0px;">
            	Country : 
            </td> 
            <td align="left" style="border: 0px;">
                <select id="country" name="country" class="form-control" style="width: 210px;" onChange="country_selected()">
                	<option value="select">Select Country</option>
            	<?php
				$get_country = mysql_query("select * from country");
				while($res_country1=mysql_fetch_array($get_country))
				{
				?>
                	<option value="<?php echo $res_country1['id']; ?>"><?php echo $res_country1['country']; ?></option>
                <?php
				}
                ?>
                </select>
         	</td>
     	</tr>
        <tr style="display:none" id="display_city">
			<td align="right" style="font-weight:bold; border: 0px;">
            	City : 
            </td> 
            <td align="left" style="border: 0px;" id="city_tr">
                
         	</td>
     	</tr>
		<tr style="height: 50px;">    
       		<td align="middle" colspan="2" style=" height: 50px; padding-top: 35px; vertical-align: bottom;">
            	<input type="button" value="Add New Mission" class="btn btn-success" onClick="submit_add_new_mission('<?php echo $pno_rtn; ?>')">
         	</td>
  		</tr>
	</table>
	<a id="popupBoxClose"><img src="../styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox()"></a>
</div>
<!-- Close Pop-up-add new mission -->


<!-- Pop-up-edit mission -->

<div id="popup_box_edit_mission" align="center" style="text-align:center; border-radius: 15px;">    <!-- OUR PopupBox DIV-->
    <div class="text-warning text-center h4">
    	<u>Update Mission Details</u>
   	</div>
    <div id="resp1" style="height: 30px; vertical-align:middle;"></div>
    <table class="table h5" align="center">
    	<tr >
			<td align="right" style="font-weight:bold; border: 0px;">
            	Mission Name :
            </td> 
            <td align="left" style="border: 0px;">
            	<input type="text" id="editmission" name="editmission" class="form-control" style="width:210px; display: inline;">&nbsp; &nbsp; 4-25 charecters only.
          	</td>
  		</tr>
		<tr>
			<td align="right" style="font-weight:bold; border: 0px;">
            	Primary Currency used * : 
            </td> 
            <td align="left" style="border: 0px;">
            	<select id="editcurrency" name="editcurrency" class="form-control" style="width: 210px;">
                	<option value="select">Select Primary Currency</option>
            	<?php
				$get_currency = mysql_query("select currency from note_values");
				while($res_curr=mysql_fetch_array($get_currency))
				{
				?>
                	<option value="<?php echo $res_curr['currency']; ?>"><?php echo $res_curr['currency']; ?></option>
                <?php
				}
                ?>
                </select>
         	</td>
     	</tr>
        <tr>
			<td align="right" style="font-weight:bold; border: 0px;">
            	Second Currency used : 
            </td> 
            <td align="left" style="border: 0px;">
                <select id="editcurrency1" name="editcurrency1" class="form-control" style="width: 210px;">
                	<option value="select">Select Second Currency</option>
            	<?php
				$get_currency1 = mysql_query("select currency from note_values");
				while($res_curr1=mysql_fetch_array($get_currency1))
				{
				?>
                	<option value="<?php echo $res_curr1['currency']; ?>"><?php echo $res_curr1['currency']; ?></option>
                <?php
				}
                ?>
                </select>
         	</td>
     	</tr>
        
		<tr style="height: 50px;">    
       		<td align="middle" colspan="2" style=" height: 50px; padding-top: 35px; vertical-align: bottom;">
            	<input type="button" id="update_mission_values" value="Update Mission Details" class="btn btn-success" >
         	</td>
  		</tr>
	</table>
	<a id="popupBoxClose"><img src="../styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox()"></a>
</div>
<!-- Close Pop-up-Edit mission -->

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