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
	//header("");
	 $url='login.php';
   	echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$url.'">';
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
include_once("db_connect.php");
if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac']))
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
<title>VAC Assist - <?php echo $_SESSION['vac']; ?></title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>
<link rel="stylesheet" href="styles/style.css">

<script type="text/javascript">

window.onbeforeunload = function() 
{
  	<?php
	$f = @fopen("Sample_files/gwfs_reconciliation.txt", "r+");
	if ($f !== false) 
	{
		ftruncate($f, 0);
		fclose($f);
	}
	?>
  	return '';
};

function validate(sts)
{
	var cnt = 0;
	var str = "";
	for(i=1; i <29; i++)
	{
		var input_id="gwf"+i;
		var gwf = $('#'+input_id).val().toUpperCase();
		if(gwf!="")
		{
			cnt++;
			str=str+gwf+",";				
		}
	}
	if(cnt >0)
	{
		$.ajax(
		{
			type: 'post',
			url: "php_func.php",
			data: 'cho=11&text='+str,
			dataType:"json",
			success: function(json)
			{
				if(json=="failed_append")
				{
					alert("Something went wrong!. \nPleaseContact the administrator")
				}
				else if(json=="appended")
				{
					if(sts =="reconcil")
					{
						$.ajax(
						{
							type: 'post',
							url: "php_func.php",
							data: 'cho=34',
							dataType:"json",
							success: function(json1)
							{
								if(json1=="success_reconciliation")
								{
									window.open('display_reconciliation_list.php?st=s', '_blank', 'width=900,height=auto, left=30, top=20, resizable=1, scrollbars=1, addressbar=0');
								}
								else if(json1=="loggedout")
								{
									alert("Please login");
									window.location.href="php_func.php?cho=2";
								}
								else
								{
									window.open('display_reconciliation_list.php?st=f', '_blank', 'width=1000,height=auto, left=30, top=20, resizable=1, scrollbars=1, addressbar=0');
								}
								
								/*for(i=1; i <29; i++)
								{
									var input_id="gwf"+i;
									$('#'+input_id).val("");
								}*/
							},
							error: function(request, status, error)
							{
								//alert(request.responseText);  
							}
										
						});
					}
					else
					{
						for(i=1; i <29; i++)
						{
							var input_id="gwf"+i;
							$('#'+input_id).val("");
						}
					}
				}
				else if(json=="loggedout")
				{
					alert("Please login");
					window.location.href="php_func.php?cho=2";
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
				alert("Please login");
				window.location.href="php_func.php?cho=2";
			}
						
		});
	}
}



function reset_page()
{
	document.form1.action="index.php";
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
				<div align="center"> <label class='text-info h4'>PASSPORT RECONCILIATION</label></div>
      			</div>
                <div style="height: 35px; color:#F00;">


                </div>
                <div id="counterdelivery" align="center" style="width:100%;">
                <form name="form1" id="form1" action="" method="post" enctype="multipart/form-data">
             
			<table id="enter_gwf_tbl" width="100%" style="border:0px solid #CCC; padding:10px 10px 10px 10px;">
            <tr>
                    <td colspan="4" style="height: 20px;">
                     </td>
                </tr>
                <tr>
                    <td colspan="4" align="center" style="text-align:center;">
                    <label class='text-danger h4' style='border: 0px; padding-bottom: 20px;'>Enter The Reference Numbers Brought Forward</label>                     </td>
                </tr>
				<tr>
	<td style="padding: 5px;"><input name="gwf1" id="gwf1" type="text" class="form-control" style="width: 150px;" autofocus/></td>
					<td style="padding: 5px;"><input name="gwf2" id="gwf2" type="text" class="form-control" style="width: 150px;"/></td>
                    <td style="padding: 5px;"><input name="gwf3" id="gwf3" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf4" id="gwf4" type="text" class="form-control" style="width: 150px;"/></td>
				</tr>
                <tr>
					<td style="padding: 5px;"><input name="gwf5" id="gwf5" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf6" id="gwf6" type="text" class="form-control" style="width: 150px;"/></td>
                    <td style="padding: 5px;"><input name="gwf7" id="gwf7" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf8" id="gwf8" type="text" class="form-control" style="width: 150px;"/></td>
				</tr>
                <tr>
					<td style="padding: 5px;"><input name="gwf9" id="gwf9" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf10" id="gwf10" type="text" class="form-control" style="width: 150px;"/></td>
                    <td style="padding: 5px;"><input name="gwf11" id="gwf11" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf12" id="gwf12" type="text" class="form-control" style="width: 150px;"/></td>
				</tr>
                <tr>
					<td style="padding: 5px;"><input name="gwf13" id="gwf13" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf14" id="gwf14" type="text" class="form-control" style="width: 150px;"/></td>
                    <td style="padding: 5px;"><input name="gwf15" id="gwf15" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf16" id="gwf16" type="text" class="form-control" style="width: 150px;"/></td>
				</tr>
                <tr>
					<td style="padding: 5px;"><input name="gwf17" id="gwf17" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf18" id="gwf18" type="text" class="form-control" style="width: 150px;"/></td>
                    <td style="padding: 5px;"><input name="gwf19" id="gwf19" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf20" id="gwf20" type="text" class="form-control" style="width: 150px;"/></td>
				</tr>
                <tr>
					<td style="padding: 5px;"><input name="gwf21" id="gwf21" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf22" id="gwf22" type="text" class="form-control" style="width: 150px;"/></td>
                    <td style="padding: 5px;"><input name="gwf23" id="gwf23" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf24" id="gwf24" type="text" class="form-control" style="width: 150px;"/></td>
				</tr>
                <tr>
					<td style="padding: 5px;"><input name="gwf25" id="gwf25" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf26" id="gwf26" type="text" class="form-control" style="width: 150px;"/></td>
                    <td style="padding: 5px;"><input name="gwf27" id="gwf27" type="text" class="form-control" style="width: 150px;"/></td>
					<td style="padding: 5px;"><input name="gwf28" id="gwf28" type="text" class="form-control" style="width: 150px;"/></td>
				</tr>
                <tr style="height:20px;">
					<td colspan="4"></td>
				</tr>
                <tr>
					<td align="right" valign="middle" colspan="2"><input type="button" name="Search" value= "Save And Scan Next Batch" class="btn btn-info" onclick="validate('append')" />&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td align="right" valign="middle" ><input type="button" name="Search" value= "Save And Continue" class="btn btn-success" onclick="validate('reconcil')"/>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td align="left" valign="middle">&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="reset"  value= "Cancel" class="btn btn-danger" onclick="reset_page()"/></td>
				</tr>
                <tr>
                <td colspan="4">
                <p>
                <?php
				if(isset($_SESSION['response_delivery']))
				{
					echo $_SESSION['response_delivery'];
					unset($_SESSION['response_delivery']);
				}
				?>

                </td>
                </tr>
            </table>
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