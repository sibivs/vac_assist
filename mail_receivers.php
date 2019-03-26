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
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Email Receipients</title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script src="Scripts/jquery-1.4.4.js" type="text/javascript"></script>
<link rel="stylesheet" href="styles/style.css">
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>
<script type="text/javascript">

function delete_user(id, pno)
{
        var agree= confirm("Are you sure you want to delete this Email Receipient?");
        if(agree)
        {
        document.form1.action="php_func.php?cho=20&id="+id+"&pn="+pno;
                //alert("php_func.php?cho=9&member_id="+id);
                document.form1.submit();
        }
        else
        {
        window.location.reload();
        }
}

function submit_addmail(pn)
{
	var cnt=0;
	var email = document.getElementById('newemail').value;
	if (!document.getElementById('consolidated').checked &&!document.getElementById('cashsheet').checked && !document.getElementById('alerts').checked) 
	{
		document.getElementById('consolidated').style.borderColor="red";
		document.getElementById('cashsheet').style.borderColor="red";
		document.getElementById('alerts').style.borderColor="red";
		cnt++
 	} 
	if(email!="")
	{
		if(!validEmail(email))
		{
			document.getElementById('newemail').style.borderColor="red";
			//msg=msg+"-> Please enter new employee's surname as given in the passport.\n";
			cnt++;
			//return;
			//alert(email);
		}
	}
	else
	{
		cnt++;
	}
	if(cnt==0)
	{
		document.getElementById('newemail').style.borderColor="#ccc";
		document.getElementById('consolidated').style.borderColor="#ccc";
		document.getElementById('cashsheet').style.borderColor="#ccc";
		document.getElementById('alerts').style.borderColor="#ccc";
		document.form1.action="php_func.php?cho=21&pno="+pn;
		document.form1.submit();
	}
	else
	{
		alert("All fields are mandatory!");
		return;
	}
}

function validEmail(e) {
    var filter = /^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/;
    return String(e).search (filter) != -1;
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
$get_custom_permission_qr = mysql_query("select manage_email_recievers from custom_permissions where user_id='".$_SESSION['user_id_ukvac']."'");
	if(mysql_num_rows($get_custom_permission_qr) >0)
	{
		$get_custom_mail = mysql_fetch_array($get_custom_permission_qr);
	}
?>

<form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
  <div id="templatemo_container">
    <div id="templatemo_header" style=""> 
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
				<div align="center"> <label class='text-info h4'>MANAGE EMAIL RECEIPIENTS</label></div>
          </div>
          <!---End Page Heading -->
          
          <p style="height: 60px;"></p>
          <div id="" style="width: 100%;" align="center">
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
            <table class="tbl1" align="center" cellspacing="0" style="border-color: #e4ecf7 !important; width: 70%;">
						<tr style="background-color:#e4ecf7; border-color: #e4ecf7 !important;">
							<td width="15%"  style="font:bold 12px tahoma, arial, sans-serif; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #e4ecf7;" align="left">Enter New E-Mail ID</td>
							<td colspan="2" width="25%" style="font:bold 12px tahoma, arial, sans-serif; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #e4ecf7;" align="left"><input type="text" id="newemail" name="newemail" class="form-control" style="width: 220px;"></td>
                       	</tr>
                        <tr style="background-color:#e4ecf7; border-color: #e4ecf7 !important;">
							<td style="font:bold 12px tahoma, arial, sans-serif; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #e4ecf7;" align="left"><input type="checkbox" id="consolidated" name="consolidated" value="cashsheet">&nbsp; &nbsp;Consolidated Report</td>
                            <td style="font:bold 12px tahoma, arial, sans-serif; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #e4ecf7;" align="left"><input type="checkbox" id="cashsheet" name="cashsheet" value="cashsheet">&nbsp; &nbsp;Cash Sheet</td>
							<td style="font:bold 12px tahoma, arial, sans-serif; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #e4ecf7;" align="left"><input type="checkbox" name="alerts" id="alerts" value="alerts">&nbsp; &nbsp; Alerts</td>
                       	</tr>
                      	<tr >
                       		<td colspan="3" style="font:bold 12px tahoma, arial, sans-serif; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #e4ecf7;" align="left"><input type="button" class="btn btn-danger" onClick="submit_addmail('<?php echo $pno_rtn; ?>')" value="Add New Email" <?php if($get_custom_mail['0']=="0"){ ?> disabled <?php } ?>></td>
                        </tr>
                	</table>
          </div>
          <p style="height:30px"></p>
          <div style="padding: 15px; width: 100%;">
            <?php 
                if(isset($_SESSION['response_emaildel']))
				{
					echo $_SESSION['response_emaildel'];
					unset($_SESSION['response_emaildel']);
				}
				else
				{
					echo "";
					unset($_SESSION['response_service']);
				}
				?>
          </div>
          <div style="padding: 15px; min-height: 300px; vertical-align:top;" align="center">
					<table class="table" align="center" style="width: 80%;">
                    <thead class="text-info">
						<tr>
							<td width="5%" style="font-weight:bold;">Sl.No</td>
							<td width="25%" style="font-weight:bold;">Email Address</td>
                            <td width="15%" style="font-weight:bold;">Cash Sheet</td>
                            <td width="15%" style="font-weight:bold;">Consolidated</td>
                            <td width="15%" style="font-weight:bold;">Alerts</td>                            
                            <td width="25%" style="font-weight:bold;">Delete Email</td>
						</tr>
                    </thead>
                        <?php
						$cnt=0;
						$query_count_result = "SELECT count(*) FROM email_receivers where mission_id = '".$_SESSION['mission_id']."'";
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
                			$query = "SELECT count(*) FROM email_receivers where id <> '' and mission_id = '".$_SESSION['mission_id']."'";
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
							$qr_t="SELECT * FROM email_receivers where mission_id = '".$_SESSION['mission_id']."' order by email_address  ASC $limit ";
                        	$query_instance = mysql_query($qr_t) OR die(mysql_error());
                        	$rows=mysql_num_rows($query_instance);
							while($recResult = mysql_fetch_array($query_instance))
							{
								$cnt++;
						?>
                        <tbody style="border: 1px;">
						<tr>
							<td align="left"> <?php echo $cnt; ?></td>
                            <td style="text-align:left !important;"><?php echo $recResult['email_address']; ?></td>
                             <td style="text-align:center !important;">
							 	<?php 
								if($recResult['cash_sheet_mail']==1)
								{
									echo "Yes";
								}
								else
								{
									echo "No";
								}
								?>
                             </td>
                             <td style="text-align:center !important;">
							 	<?php 
								if($recResult['consolidated']==1)
								{
									echo "Yes";
								}
								else
								{
									echo "No";
								}
								?>
                             </td>
                             <td style="text-align:center !important;">
							 	<?php 
								if($recResult['alert_mail']==1)
								{
									echo "Yes";
								}
								else
								{
									echo "No";
								}
								?>
                             </td>
                            <td align="left"><input type="button" class="btn btn-info"  onClick="delete_user('<?php  echo $recResult['id'];  ?>','<?php echo $pageno;?>')" value="Delete" <?php if($get_custom_mail['0']=="0"){ ?> disabled <?php } ?>></td>
                           
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
							echo "Sorry, No Result Found. Please Add New Email Recepients.";
						}		
					?>
                    
                    	
              	</div>
        </div>
      </div>
      <p>&nbsp;</p>
      <div id="templatemo_right_content_bottom" style="text-align:center; vertical-align: middle; padding-top: 10px;;"> <span class="h5" style="font-family:'Times New Roman', Times, serif; color:#FFFFFF; vertical-align:middle;">Powered By </span><br>
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