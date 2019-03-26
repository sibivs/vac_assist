<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
$expire_time = 60*60; //expire time
if((isset($_SESSION['last_activity_ukvac'] )) && ($_SESSION['last_activity_ukvac'] < (time()-$expire_time))) 
{
	session_destroy();
	print json_encode("loggedout");
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
if(isset($_SESSION['role_ukvac'])&&  isset($_SESSION['vac']))
{
	include_once("db_connect.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Daily Reconciliation Report</title>
<link rel="stylesheet" href="styles/style.css">
<script type="text/javascript" src="Scripts/jquery-1.6.3.js"></script>
<script type="text/javascript" src="Scripts/jquery_export_csv.min.js"></script>

<script type="text/javascript">

function get_reconciliation_report()
{
	var date_sel = "<?php date_default_timezone_set("Asia/Bahrain"); echo date("Y-m-d"); ?>";
	var rp_type = "daily_reconciliation";
	$.ajax(
	{
		type: 'post',
		url: "php_func.php",
		data: 'cho=10&inputField='+date_sel+'&pp_reports='+rp_type,
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
		error: function(request, status, error)
		{
			alert(request.responseText);  
		}
							
	});
}

function markasdelivered(i,status,gwf)
{
	//alert(i);
	//alert(status);
	$.ajax(
	{
		type: 'post',
		url: "php_func.php",
		data: 'cho=36&gwf='+gwf,
		dataType:"json",
		success: function(json)
		{
			if(json=="counter_delivery" || json=="courier_Service")
			{
				$('#lbl'+i).html("Delivered - Counter Delivery");
				$('#lbl'+i).css("color","blue");
				$('#tbltd'+i).html("");
			}
			else if(json=='timeout')
			{
				alert('Session Expired. Please Login!')
				window.location.href='login.php';
				window.location.reload();
			}
			else
			{
				alert("Passport status is not updated as delivered. \nPlease deliver the passport and then change the status.");
			}
		},
		error: function(request, status, error)
		{
			alert(request.responseText);  
		}
							
	});	
	
}

function markasinscan(i,status,gwf)
{
	//alert(i);
	//alert(status);
	$.ajax(
	{
		type: 'post',
		url: "php_func.php",
		data: 'cho=36&gwf='+gwf,
		dataType:"json",
		success: function(json)
		{
			if(json=="inscan")
			{
					$('#lbl'+i).html("Brought Forward");
					$('#lbl'+i).css("color","green");
					$('#tbltd'+i).html("");
			}
			else if(json=='timeout')
			{
				alert('Session Expired. Please Login!')
				window.location.href='login.php';
				window.location.reload();
			}
			else
			{
				alert("Passport status is -"+ json+". \n So, you cannot mark it as Brought Forward.");
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

<body contextmenu="false">
	<form id="form1" name="form1"> 
    <?php
		date_default_timezone_set("Asia/Bahrain");
		$date_today=date("Y-m-d");
		$status= $_REQUEST['st'];
		$filename="Sample_files/gwfs_reconciliation_result.txt";
		if(file_exists($filename) && filesize($filename))
		{
			$str=rtrim(file_get_contents($filename),",");
			$handle = explode(",",$str);
			$max = sizeof($handle);
			$status_to_display = "";
		?>
        <iframe id="myFrame" style="display:none"></iframe>
        <div id="hidden_div" style="display:none"></div>
		<div id="printd" align="center" style="background-color:white;">
		<div align="center" style="width:100%; padding:10px 0 20px 0; font:bold 14px Verdana;">
			Passport Reconciliation List For <?php echo $date_today; ?>
		</div>
		<div align="center" style="width:100%; padding:10px 0 20px 0;" id="msg">
		<?php
			if($status=='f')
			{
				?>
				<label style="color:red; font:normal 12px Verdana;">Error In Passport Reconciliation.</label>
				<?php
			}
			else if($status=='s')
			{
				?>
				<label style="color:green; font:normal 12px Verdana;">Reconciliation Success. All physical passports match with the records in the system.</label>
				<?php
			}
		?>
			
		</div>
		<table id="table" name="table" class="table" style="width: 80% !important; font:normal 12px Verdana;">
			<thead >
				<tr style="height: 20px; font-weight:bold;">
					<td >Sl. No</td>
					<td >Ref. No</td>
					<td >Envelope No</td>
					<td >Status</td>
                    <td id="updt">Update</td>
				</tr>
			</thead>
			<tbody>
			<?php
			for($i=0;$i<$max; $i++)
			{
				$glfs_no="";
				$enable_edit=0;
				$gwfandstatus=explode("_",$handle[$i]);
				if($gwfandstatus[1]=="inscan")
				{
					$status_to_display="<label id='lbl".$i."' style='color:green;'>Brought Forward</label>";
					$enable_edit=1;
					$get_glfs_bcode = "select tee.barcode_no as teeno from tee_envelop_counts tee , passport_inscan_record pp where pp.gwf_number='".$gwfandstatus[0]."' and pp.glfs_id=tee.id and pp.mission_id='".$_SESSION['mission_id']."'";
					$get_glfs_no=mysql_fetch_array(mysql_query($get_glfs_bcode));
					$glfs_no=$get_glfs_no['teeno'];
				}
				else if($gwfandstatus[1]=="physicalmissing")
				{
					$status_to_display="<label id='lbl".$i."' style='color:red;'>Passport Missing</label>";
					$enable_edit=2;
					$get_glfs_no=mysql_fetch_array(mysql_query("select tee.barcode_no as teeno from tee_envelop_counts tee , passport_inscan_record pp where pp.gwf_number='".$gwfandstatus[0]."' and pp.glfs_id=tee.id and pp.mission_id='".$_SESSION['mission_id']."'"));
					$glfs_no=$get_glfs_no['teeno'];
				}
				else if($gwfandstatus[1]=="counterdelivery")
				{
					$status_to_display="<label style='color:blue;'>Delivered - Counter Delivery</label>";
					$get_glfs_no=mysql_fetch_array(mysql_query("select tee.barcode_no as teeno from tee_envelop_counts tee , passport_inscan_record pp where pp.gwf_number='".$gwfandstatus[0]."' and pp.glfs_id=tee.id and pp.mission_id='".$_SESSION['mission_id']."'"));
					$glfs_no=$get_glfs_no['teeno'];
				}
				else if($gwfandstatus[1]=="courierdelivery" )
				{
					$status_to_display="<label style='color:blue;'>Delivered - Courier Service</label>";
					$get_glfs_no=mysql_fetch_array(mysql_query("select tee.barcode_no as teeno from tee_envelop_counts tee , passport_inscan_record pp where pp.gwf_number='".$gwfandstatus[0]."' and pp.glfs_id=tee.id and pp.mission_id='".$_SESSION['mission_id']."'"));
					$glfs_no=$get_glfs_no['teeno'];
				}
				else if($gwfandstatus[1]=="outscan")
				{
					$status_to_display="<label style='color:black;'>Passport In Embassy</label>";
					$get_glfs_no=mysql_fetch_array(mysql_query("select tee.barcode_no as teeno from tee_envelop_counts tee , passport_inscan_record pp where pp.gwf_number='".$gwfandstatus[0]."' and pp.glfs_id=tee.id and pp.mission_id='".$_SESSION['mission_id']."'"));
					$glfs_no=$get_glfs_no['teeno'];
				}
				else if($gwfandstatus[1]=="inscanrectification" )
				{
					$status_to_display="<label id='lbl".$i."' style='color:green;'>Brought Forward (Rectification)</label>";
					$glfs_no="";
					$enable_edit=1;
				}
				else if($gwfandstatus[1]=="counterdeliveryrectification" )
				{
					$status_to_display="<label style='color:blue;'>Delivered - Counter Delivery (Rectification)</label>";
					$glfs_no="";
				}
				else if($gwfandstatus[1]=="outscanrectification" )
				{
					$status_to_display="<label style='color:black;'>Passport In Embassy (Rectification)</label>";
					$glfs_no="";
				}
				else if($gwfandstatus[1]=="invalid" )
				{
					$status_to_display="<label style='color:red;'>Invalid Reference Number Entered</label>";
					$glfs_no="";
				}
	
			?>
				<tr>
					<td><?php echo $i+1; ?></td>
					<td><?php echo $gwfandstatus[0]; ?></td>
					<td><?php echo $glfs_no; ?></td>
					<td><?php echo $status_to_display; ?></td>
                    <td id="<?php echo "tbltd".$i; ?>"><?php if($enable_edit==1){ ?> <a style="cursor:pointer" onClick="markasdelivered('<?php echo $i; ?>','<?php echo $enable_edit; ?>','<?php echo $gwfandstatus[0]; ?>')">Mark As Delevered</a>  <?php } else if($enable_edit==2) {?> <a style="cursor:pointer" onClick="markasinscan('<?php echo $i; ?>','<?php echo $enable_edit; ?>','<?php echo $gwfandstatus[0]; ?>')">Mark As Brought Forward</a>  <?php } else { echo ""; } ?> </td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
		</div>
		<div align="center" style="width:100%">
		<br>
			<input id="btnExport" type="button" class="btn btn-block btn-danger" style="width: 140px; display:inline; vertical-align: bottom;" onClick="call_export()" value="Export to Excel">
			<?php
			if($status=="s")
			{
				?>
					<input type="button" onclick="get_reconciliation_report()" class="btn btn-block btn-info" style="width: 180px; display:inline;" value="Get Reconciliation Report">
				<?php
			}
			?>
			<input type="button" value="Close" class="btn btn-block btn-warning" style="width: 140px; display:inline" onclick="window.close();" >
		</div>
		<br><br>
		
		<?php
			$f = @fopen("Sample_files/gwfs_reconciliation.txt", "r+");
			if ($f !== false) 
			{
				ftruncate($f, 0);
				fclose($f);
			}
			
			$f = @fopen("Sample_files/gwfs_reconciliation_result.txt", "r+");
			if ($f !== false) 
			{
				ftruncate($f, 0);
				fclose($f);
			}
		}//End of IF the file empty...
		else
		{
			?>
            <script type='text/javascript'>
            		window.self.close()
        	</script>

            <?php
		}
	?>
    <script type='text/javascript'>
	function call_export()
	{
		$oldvaltable= $("#table").html();
		$("#table").find('a').remove();
		$("label").contents().unwrap();
		$("#updt").html("");
		var ua = window.navigator.userAgent;
		var msie = ua.indexOf("MSIE ");
		var table = $("#table")[0];
		
		//Get number of rows/columns
		var rowLength = table.rows.length;
		var colLength = table.rows[0].cells.length;
	
		//Declare string to fill with table data
		var tableString = "";
	
		//Get column headers
		for (var i = 0; i < colLength; i++) 
		{
			tableString += table.rows[0].cells[i].innerHTML.split(",").join("") + ",";
		}
	
		tableString = tableString.substring(0, tableString.length - 1);
		tableString += "\r\n";
	
		//Get row data
		for (var j = 1; j < rowLength; j++) 
		{
			for (var k = 0; k < colLength; k++) 
			{
				tableString += table.rows[j].cells[k].innerHTML.split(",").join("") + ",";
			}
			tableString += "\r\n";
		}
		$("#table").html($oldvaltable);
		var d = new Date();
		var strDate = d.getDate() + "-" + (d.getMonth()+1) + "-" + d.getFullYear();
		//Save file
		if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) 
		{
			//Optional: If you run into delimiter issues (where the commas are not interpreted and all data is one cell), then use this line to manually specify the delimeter
			tableString = 'sep=,\r\n' + tableString;
			myFrame.document.open("text/html", "replace");
			myFrame.document.write(tableString);
			myFrame.document.close();
			myFrame.focus();
			myFrame.document.execCommand('SaveAs', true, 'Passport_reconciliation_list_'+strDate+'.csv');
		} 
		else 
		{
			//window.location = 'data:application/csv;charset=utf-8,' + encodeURIComponent(tableString);
						
			$('<a></a>')
				.attr('id','downloadFile')
				.attr('href','data:text/csv;charset=utf8,' + encodeURIComponent(tableString))
				.attr('download','Passport_reconciliation_list_'+strDate+'.csv')
				.appendTo('body');
	
			$('#downloadFile').ready(function() {
				$('#downloadFile').get(0).click();
			});
		}
	}
	</script>
	</form>    
</body>
</html>
<?php
}
else
{
       session_destroy();
	   print json_encode("loggedout");
}
}
?>