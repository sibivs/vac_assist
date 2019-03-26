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
	var agree= confirm("Are you sure you want to delete this Conversion rate Permanantly?");
    if(agree)
    {
		$.ajax(
		{
			type: 'post',
			url: 'func/php_func.php',
			data: 'cho=8&id='+id,
			dataType:"json",
			success: function(result)
			{
				if(result=="deleted")
				{
					$("#resp_del").html("<p style='color: purple; font-size: 13px;'>This conversion rate is removed.</p>");
					setTimeout(function(){window.location.href="exchange_rates.php"; /*window.location.reload();*/ }, 1500);
				}
				else if(result=="failed")
				{
					$("#resp_del").html("<p style='color: red; font-size: 13px;'>Failed to remove. Please contact administrator</p>"+result);
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
    	window.location.reload();
    }
}


function submit_update_exch_rate(id)
{
	var edit_exch_rate = $("#editexch").val();
	var edit_currency = $("#editcurr").val();
	var cnt=0;
	if(!(/^[a-zA-Z\s]{2,8}$/.test(edit_currency)))
	{
		
		$('#editcurr').css("border-color", "red");
		cnt++;
	}
	else
	{
		$('#editcurr').css("border-color", "#ccc");
	}
	
	if(!(/^([0-9]*\.[0-9]+|[0-9]+)$/.test(edit_exch_rate)))
	{
		$('#editexch').css("border-color", "red");
		cnt++;
	}
	else
	{
		$('#editexch').css("border-color", "#ccc");
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
				data: 'cho=7&curr='+edit_currency+"&exch="+edit_exch_rate+"&id="+id,
			  	dataType:"json",
				success: function(result)
				{
					if(result=="updated")
					{
						unloadPopupBox1_exch_rate();
						$("#resp1").html("<p style='color: purple; font-size: 13px;'>Conversion details updated</p>");
					}
					else if(result=="failed")
					{
						unloadPopupBox1_exch_rate();
						$("#resp1").html("<p style='color: red; font-size: 13px;'>Failed to update. Please contact administrator</p>"+result);
					}
					
				},
				error: function(request, status, error)
				{
					alert(request.responseText);  
				}
				
			});
	}
}

function submit_add_new_rate(pno)
{
	var currency_name = $('#currency').val();
	var exchange = $('#exchange').val();
	var cnt=0;
	var msg="";
	if(!(/^[a-zA-Z\s]{2,8}$/.test(currency_name)))
	{
		
		$('#currency').css("border-color", "red");
		cnt++;
	}
	else
	{
		$('#currency').css("border-color", "#ccc");
	}
	
	if(!(/^([0-9]*\.[0-9]+|[0-9]+)$/.test(exchange)))
	{
		$('#exchange').css("border-color", "red");
		cnt++;
	}
	else
	{
		$('#exchange').css("border-color", "#ccc");
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
				data: 'cho=6&curr='+currency_name+"&exch="+exchange,
			  	dataType:"json",
				success: function(result)
				{
					if(result=="added")
					{
						unloadPopupBox_add_exch();
						$("#resp").html("<p style='color: purple; font-size: 13px;'>New Conversion Rate for "+currency_name+" is added.</p>");
					}
					else if(result=="currency_exists")
					{
						$("#resp").html("<p style='color: purple; font-size: 13px;'>The Currency- "+currency_name+" is existing.</p>");
					}
					else
					{
						unloadPopupBox_add_exch();
						$("#resp").html("<p style='color: red; font-size: 13px;'>Failed to add new conversion rate. Please contact administrator</p><br>"+result);
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
				<div align="center"> 
                	<label class='text-info h4'>Manage Currency Conversion Rates Againest GBP</label>
                </div>
                <div style="width: 100%; text-align: right;">
                	<input type="button" class="btn btn-danger" value="Add New Conversion Rate" onClick="loadPopupBox_add_exch()">
              	</div>
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
                
                <div style="padding: 5px; width: 100%;">
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
                <div class="text-warning" style="font-size: 14px; text-align: left; border: solid 1px #ccc; padding: 20px; border-radius: 5px;">
                	<label class="text-danger" style="font-size: 16px;">Note: </label>
                    <br>
                	Currency Conversion Rate is used to calculate the Britain Travel Shop product price in the local currency.
                	<br> 
                	The price is calculates as (GBP Price * Exchange Rate)= Price in local currency. Applicable only for UK Mission BTS Price Calculation
                </div>
                <hr style="height:2px; box-shadow:inset;">
                <div style="padding: 5px;" align="center">
                
                        <?php
						
						$query_count_result = "SELECT count(*) FROM exchange_rates";
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
                			$query = "SELECT count(*) FROM exchange_rates where id <> ''";
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
							$qr_t="SELECT * FROM exchange_rates order by currency ASC $limit ";
                        	$query_instance = mysql_query($qr_t) OR die(mysql_error());
                        	$rows=mysql_num_rows($query_instance);
							?>
                            
                            <table class="table" align="center" style="width: 80%;">
                        <thead class="text-danger" style="font-size: 15px;">
                            <tr>
                                <td width="10%">Sl.No</td>
                                <td width="15%">Currency</td>
                                <td width="35%">Conversion Rate For GBP</td>
                                <td width="15%">Edit Rate</td>
                                <td width="15%">Delete</td>
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
                            <td style="text-align:left !important;"><?php echo $recResult['currency']; ?></td>
                            <td id='<?php echo "row_".$recResult['id']; ?>' style="text-align:middle !important;"><?php echo $recResult['exchng_rate_wt_gbp']; ?></td>
                            <td id='<?php echo "row1_".$recResult['id']; ?>' align="left"><input type="button" class="btn btn-info" value="Edit"  onClick="loadPopupBox1_exch_rate('<?php  echo $recResult['id'];?>','<?php  echo $recResult['currency'];?>','<?php echo $recResult['exchng_rate_wt_gbp']; ?>')"></td>
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

<div align="justify">
  <!-- Pop-up-add new mission -->
  
</div>
<div id="popup_box_add_exch" align="center" style="text-align:center; border-radius: 15px;">    <!-- OUR PopupBox DIV-->
    <div class="text-warning text-center h4">
    	<u>Add New Conversion Rate Againest GBP</u>
   	</div>
    <div id="resp" style="height: 30px; vertical-align:middle;"></div>
    <table class="table h5" align="center">
    	<tr >
			<td align="right" style="font-weight:bold; border: 0px;">
            	** Currency :
            </td> 
            <td align="left" style="border: 0px;">
            	<input type="text" id="currency" name="currency" class="form-control" style="width:210px; display: inline;">&nbsp; &nbsp; 2-6 charecters only.
          	</td>
  		</tr>
		<tr>
			<td align="right" style="font-weight:bold; border: 0px;">
            	Conversion Rate Againest GBP : 
            </td> 
            <td align="left" style="border: 0px;">
            	<input type="text" id="exchange" name="exchange" class="form-control" style="width:210px; display: inline;"> &nbsp; &nbsp; (Eg: 0.60, 6.10 etc.)

         	</td>
     	</tr>
		<tr style="height: 50px;">    
       		<td align="middle" colspan="2" style=" height: 50px; padding-top: 35px; vertical-align: bottom;">
            	<input type="button" value="Add New Rate" class="btn btn-success" onClick="submit_add_new_rate('<?php echo $pno_rtn; ?>')">
         	</td>
  		</tr>
	</table>
    <label class="text-info" style="font-size: 11px;">** Currency should be identical with currencies associated with missions</label>
	<a id="popupBoxClose"><img src="../styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox_add_exch()"></a>
</div>
<!-- Close Pop-up-add new mission -->


<!-- Pop-up-edit mission -->

<div id="popup_box_exch_rate" align="center" style="text-align:center; border-radius: 15px;">    <!-- OUR PopupBox DIV-->
    <div class="text-warning text-center h4">
    	<u>Update Currency Conversion Rates With GBP</u>
   	</div>
    <div id="resp1" style="height: 30px; vertical-align:middle;"></div>
    <table class="table h5" align="center">
    	<tr >
			<td align="right" style="font-weight:bold; border: 0px;">
            	**Currency :
            </td> 
            <td align="left" style="border: 0px;">
            	<input type="text" id="editcurr" name="editcurr" class="form-control" style="width:210px; display: inline;">&nbsp; &nbsp; 2-8 charecters only.
          	</td>
  		</tr>
		<tr>
			<td align="right" style="font-weight:bold; border: 0px;">
            	Exchange Rate Againest GBP : 
            </td> 
            <td align="left" style="border: 0px;">
            	<input type="text" id="editexch" name="editexch" class="form-control" style="width:210px; display: inline;"> &nbsp; &nbsp; (Eg: 0.60, 6.10 etc.)
         	</td>
     	</tr>
		<tr style="height: 50px;">    
       		<td align="middle" colspan="2" style=" height: 50px; padding-top: 35px; vertical-align: bottom;">
            	<input type="button" id="update_exch" value="Update Conversion Rate" class="btn btn-success" >
         	</td>
  		</tr>
	</table>
    <label class="text-info" style="font-size: 11px;">** Currency should be identical with currencies associated with missions</label>
	<a id="popupBoxClose"><img src="../styles/images/closebox.png" style="width:30px; height:30px;" onClick="unloadPopupBox1_exch_rate()"></a>
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