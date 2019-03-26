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
<title>VAC Assist - <?php echo $_SESSION['vac']; ?></title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script src="Scripts/jquery-1.4.4.js" type="text/javascript"></script>
<link rel="stylesheet" href="styles/style.css">
<link rel="stylesheet" href="menu/css/style_menu.css">
<script src="Scripts/script.js"></script>

<script type="text/javascript">

fulldetailsarray= [];
fulldetailsarray[0]="test";
fulldetailsarray[1]="test1";
var count=1;
var ex_rt =0;
var last_btn_remove = 0;
function addbts()
{
	var e = document.getElementById("select_product");
	var item_selected = e.options[e.selectedIndex].value;
	var fulldetails = "";
	if(item_selected!= "PRODUCT NAME_BHD Price_GBP Price")
	{
		var visibility_status = document.getElementById('display_bts_selection').style.display;
		if(visibility_status=="none")
		{
			document.getElementById('display_bts_selection').style.display="table";
			document.getElementById("display_bts_selection").style.width="100%";
		}
		var next_last_rowid = document.getElementById("display_bts_selection").rows.length+1;
		//alert(next_last_rowid);
		$product_details = item_selected.split("_");
		//alert($product_details.toString());
		if ($product_details[0].toUpperCase().indexOf("OYSTER CARD") >= 0)
		{
			var prd = $product_details[0]+"<p> <input class='form-control' style='width: 150px;' id='card_no_"+next_last_rowid+"' placeholder='Enter Card No' onkeyup='check_oyster_card_valdity(&quot;"+next_last_rowid+"&quot;)'><div id='rslt_"+next_last_rowid+"'></div>";
			var oystr="1";
		}
		else
		{
			var prd = $product_details[0];
			var oystr="0";
		}
		var curr_exch_str = $product_details[3].slice( 1 );
		var curr_str = curr_exch_str.split(",");
		var curr_selected = $("#curncy").val();
		for(var a=0; a< curr_str.length; a++)
		{
			if(curr_str[a].indexOf(curr_selected) > -1)
			{
				var ex_rt = curr_str[a].split("#");
				//ex_rt[1] is the exchange rate for the selectred currency
				var price = (parseFloat(ex_rt[1])*parseFloat($product_details[2])).toFixed(2);
			}
		}
		
		var html_tbl = "<tr id='"+count+"'><td class='view_btscls' >"+prd+"</td><td class='view_btscls'>"+price+"</td><td class='view_btscls'>"+$product_details[2]+"</td><td class='view_btscls'><input type='text' style='width:50px;' id='qty_"+next_last_rowid+"' class='form-control' onkeyup='calculate_total("+next_last_rowid+","+price+",&quot;"+$product_details[0]+"&quot;)'/></td><td class='view_btscls' id='ttl_"+next_last_rowid+"' style='font-weight:bold;' >0</td><td class='view_btscls' ><input type='button' id='add_each_"+next_last_rowid+"' value='Remove' onClick='remove_this(&quot;"+next_last_rowid+"&quot;,&quot;"+count+"&quot;,&quot;"+item_selected+"&quot;)' class='btn btn-danger' style='width: 90px;'></input></td></tr>";
		$("#display_bts_selection > tbody").append(html_tbl);
		fulldetails=$product_details[0]+"|"+price+"|"+$product_details[2]+"|";
		//alert(fulldetails);
		fulldetailsarray[next_last_rowid]=fulldetails;
		$("#issue_bts").hide();
		$('#card_no_'+next_last_rowid).focus();
		
		//Remove all previous "Remove" buttons
		//var current_remove_btn_id = next_last_rowid;
		for(var m=1; m < next_last_rowid; m++)
		{
			$("#add_each_"+m).hide();
		}
		count++;
		last_btn_remove = next_last_rowid;
	}
	$("#select_product").val(0);
}


function remove_this(array_count,rowid,product)
{
	$('table#display_bts_selection tr#'+rowid).remove();
	var isnull=0;
	fulldetailsarray[array_count]="";
	for(var i=2; i<fulldetailsarray.length;i++)
	{
		if(fulldetailsarray[i]!="")
		{
			isnull++;
		}
		$("#issue_bts").show();	
		 	
	}
	if(isnull==0)
	{
		$('table#display_bts_selection').hide();
	}
	count--;
	$("#add_each_"+count).show();
	last_btn_remove = count;
}

function check_oyster_card_valdity(box_id)
{
	var val= $("#card_no_"+box_id).val();
	if(val.length >0)
	{
		if(!(/^[0-9]{10}\s{1}[0-9]{2}$/.test(val)))
		{
			document.getElementById('card_no_'+box_id).style.borderColor="red";
			document.getElementById('qty_'+box_id).style.borderColor="red";
			$("#rslt_"+box_id).html("<label style='color:red'>Format is wrong</label>");
			$('#qty_'+box_id).val("");
			$("#issue_bts").hide();
		}
		else
		{
			//
			$.ajax(
			{
				type: 'post',
			  	url: 'php_func.php',
				data: 'cho=42&cardno='+val,
			  	dataType:"json",
				success: function(result)
				{
					//alert(result)
					if(result==1)
					{
						$("#rslt_"+box_id).html("<label style='color:red'>Card not in inventory</label>");
						document.getElementById('card_no_'+box_id).style.borderColor="red";
						$('#card_no_'+box_id).focus();
						$("#issue_bts").hide();
					}
					else if(result==2)
					{
						$("#rslt_"+box_id).html("<label style='color:red'>Card already sold</label>");
						document.getElementById('card_no_'+box_id).style.borderColor="red";
						$('#card_no_'+box_id).focus();
						$("#issue_bts").hide();	
					}
					else if(result==3)
					{
						for(r=2; r<= box_id; r++)
						{
							if(r!= box_id)
							{
								if($("#card_no_"+r).length)
								{
									var val_of_a_txtbx = $("#card_no_"+r).val();
									if(val_of_a_txtbx != val)
									{
										$("#rslt_"+box_id).html("<label style='color:purple'>Card is available</label>");
										document.getElementById('card_no_'+box_id).style.borderColor="#DDD";	
										$("#issue_bts").show();
									}
									else
									{
										$("#rslt_"+box_id).html("<label style='color:red'>Number already typed</label>");
										document.getElementById('card_no_'+box_id).style.borderColor="red";
										$('#card_no_'+box_id).focus();
										$('#card_no_'+box_id).val("");
										$("#issue_bts").hide();	
									}
								}
							}
							else
							{
							$("#rslt_"+box_id).html("<label style='color:purple'>Card is available</label>");
								document.getElementById('card_no_'+box_id).style.borderColor="#DDD";	
								$("#issue_bts").show();
							}
							break;
						}
					}
					else if(result=='timeout')
					{
						alert('Session Expired. Please Login!')
						window.location.href='login.php';
						window.location.reload();
					}
					else
					{
					}
					event.preventDefault();
					$("html, body").animate({ scrollTop: 0 }, "slow");
					return false;
				},
				error: function(request, status, error)
				{
					alert(request.responseText);  
				}
				
			});
		}
	}
	else
	{
		document.getElementById('card_no_'+box_id).style.borderColor="red";
		$("#issue_bts").hide();
	}
}



/////////////////////////////////////

function calculate_total(id, amount,product)
{
	var qty_entered=document.getElementById('qty_'+id).value;
	if (product.toUpperCase().indexOf("OYSTER CARD") >= 0)
	{
		//Selected Oyster
		if(qty_entered >1)
		{
			alert("Please enter 1 as quantity.");
			document.getElementById('qty_'+id).style.borderColor="red";
			document.getElementById('ttl_'+id).innerHTML="<b>0</b>";
			$("#issue_bts").hide();
		}
		else
		{
			var card_num = $('#card_no_'+id).val();
			if(!(/^[0-9]{10}\s{1}[0-9]{2}$/.test(card_num)))
			{
				document.getElementById('card_no_'+id).style.borderColor="red";
				document.getElementById('qty_'+id).style.borderColor="red";
				$('#qty_'+id).val("");
				if(card_num=="")
				{
					$('#card_no_'+id).focus();
				}
				else
				{
					$('#qty_'+id).focus();
				}
				$("#issue_bts").hide();
			}
			else
			{
				if(qty_entered!="")
				{
					document.getElementById('qty_'+id).style.borderColor="#DDD";
					document.getElementById('card_no_'+id).style.borderColor="#DDD";
					var round_amount = Math.round(amount * 100) / 100;
					var total_amount = Math.round((round_amount*qty_entered)* 100) / 100;
					document.getElementById('ttl_'+id).innerHTML=total_amount;
					var details = fulldetailsarray[id];
					var newfulldetails= details+qty_entered+"|"+card_num;
					fulldetailsarray[id]=newfulldetails;
					$("#issue_bts").show();
					$('#card_no_'+id).attr("readonly","true");
					$('#qty_'+id).attr("readonly","true");
				}
				else
				{
					document.getElementById('qty_'+id).style.borderColor="red";
					document.getElementById('ttl_'+id).innerHTML="<b>0</b>";
					$("#issue_bts").hide();
				}
			}
		}
	}
	else
	{
		if((/^[1-9]{1,}$/.test(qty_entered)))
		{
			document.getElementById('qty_'+id).style.borderColor="#DDD";
			var round_amount = Math.round(amount * 100) / 100;
			var total_amount = Math.round((round_amount*qty_entered)* 100) / 100;
			document.getElementById('ttl_'+id).innerHTML=total_amount;
			var details = fulldetailsarray[id];
			var newfulldetails= details+qty_entered;
			fulldetailsarray[id]=newfulldetails;
			$("#issue_bts").show();
			$('#qty_'+id).attr("readonly","true");
		}
		else
		{
			document.getElementById('qty_'+id).style.borderColor="red";
			document.getElementById('ttl_'+id).innerHTML="<b>0</b>";
			$("#issue_bts").hide();
		}
	}
}

function reset_page()
{
	document.form1.action="bts.php";
	document.form1.submit();
}


function calculate_total_bts()
{
	$("#add_each_"+last_btn_remove).hide();
	$("#select_product").attr("disabled",true);
	var err_cnt_bts=0;
	//alert(count);
	for(i=2; i <= count ; i++ )
	{
		var value_cnt = document.getElementById("qty_"+i).value;
		if(value_cnt=="")
		{
			document.getElementById('qty_'+i).style.borderColor="red";
			err_cnt_bts++;
		}
		else
		{
			document.getElementById('qty_'+i).style.borderColor="#DDD";
		}
	}
	
	if(err_cnt_bts > 0)
	{
		$("#issue_bts").hide();
		return false;
	}
	else
	{
		var last_rowid = document.getElementById("display_bts_selection").rows.length;
		document.getElementById('view_totals').style.display="block";
		//document.getElementById('display_bts_selection').style.visibility="hidden";
		//document.getElementById('bts_input').style.visibility="hidden";
		document.getElementById('issue_bts').style.display="none";
		document.getElementById('lastrowoftable').value=last_rowid;
		var total_amt = parseFloat(0);
		for(i=2; i<= count; i++)
		{
			if ($("#ttl_"+i).length)
			{
				var val = document.getElementById('ttl_'+i).innerHTML;
				total_amt = (parseFloat(total_amt)+parseFloat(val));
			}
			else
			{
				alert(count);
			}
		}
		document.getElementById('total_pay_final').innerHTML="<b>"+total_amt+"</b>";
		//alert (total_amt);
	}
}

function add_bts_final()
{
	var applnt_name = $('#bts_applicnt_name').val();
	var applnt_phone_number = $('#bts_applicnt_no').val();
	var total_topay = $('#total_pay_final').val();
	var currency = $("#curncy").val();
	var isNumeric = /^[-+]?(\d+|\d+\.\d*|\d*\.\d+)$/;
	var cnt = 0;
	var msg="Some Error Occured\n";
	if(applnt_name =="")
	{
		cnt++;
		msg=msg+"Please Enter Applicants Name\n";
		document.getElementById('bts_applicnt_name').style.borderColor="red";
	}
	else
	{
		document.getElementById('bts_applicnt_name').style.borderColor="gray";
	}
	
	if(applnt_phone_number =="" || !isNumeric.test(applnt_phone_number))
	{
		msg=msg+"Please Enter Applicants Contact Number\n";
		document.getElementById('bts_applicnt_no').style.borderColor="red";
		cnt++;
	}
	else
	{
		document.getElementById('bts_applicnt_no').style.borderColor="gray";
	}
	
	
	if(cnt>0)
	{
		alert(msg);
	}
	else
	{
		//alert(fulldetailsarray.toString());
		$(function () 
		{
			
			var stringed = JSON.stringify(fulldetailsarray);
			$.ajax(
			{
				type: 'post',
			  	url: 'php_func.php',
				data: 'cho=17&dt='+stringed+'&nme='+applnt_name+'&no='+applnt_phone_number+'&curr='+currency,
			  	dataType:"json",
				success: function(json)
				{
					if(json=="inserted")
					{
						alert("Travel Product Sale is Added Into The System");
						reset_page();
						
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
		});
		
		//document.form1.action="php_func.php?cho=17";
		//document.form1.submit();
	}
}

function enable_prd()
{
	$("#show_prd").show();
	$('input[name=curncy]').attr('disabled', 'disabled')
	//$("#curncy").attr('disabled',true);
}



$(document).ready(function()
{
	if($('input[name=curncy]').is(':checked')) 
  	{ 
  		enable_prd(); 
	}
});

</script>

</head>

<body>
<form name="form1" id="form1" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
<div id="templatemo_container" style="height:850px;">
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
		<div id="" style="width: 100% !important; height:850px;" align="center;">
			<div style="width: 100% !important;" align="center;">
				<div align="center"> <label class='text-info h4'>BRITAIN TRAVEL SHOP PRODUCTS SALES</b>
                        </font>
                    </p>
      			</div>
                <div id="bts_input" align="center" style="margin-top: 5px; display: inline;">

                <br>
                <div style="text-align:center;">
                <?php
					$curr1 = mysql_query("select currencies from missions where id=".$_SESSION['mission_id']);
					$numrow = mysql_num_rows($curr1);
					$curr = mysql_fetch_array($curr1);
					if(sizeof($curr) >1)
					{
					$currs = explode(',',$curr['currencies']);
					$rates = "";
					foreach($currs as $key => $value)
					{
						
						$get_conversion = mysql_fetch_array(mysql_query("select exchng_rate_wt_gbp as exch from exchange_rates where currency='".$value."'"));
						$rates = $rates.",".$value."#".$get_conversion['exch'];
					}
					}
					?>
                    <label class="text-warning h4" style="padding-left: 10px;">Select Currency</label>
                <?php
				foreach($currs as $key => $value)
				{
					?>
                    <input class="" type="radio" id="curncy" name="curncy" value="<?php echo $value; ?>" onClick="enable_prd()" onChange="enable_prd()" <?php if($numrow ==1){ ?> checked='checked' <?php }?>/> <?php echo $value; ?> 
					
                <?php
				}
				?>
                <hr>
                <br>
                <div id="show_prd" style="display:none">
                <label align="center" style="display: inline;" class="text-info h4">Select A Product : </label>
                <select id="select_product" name="select_product" style="width: 350px; display: inline;" onChange="addbts()" class="form-control">
                <?php
				
				include 'Classes/PHPExcel/IOFactory.php';
	 
				// This is the file path to be uploaded.
				$inputFileName = 'Sample_files/BTS_Products.xlsx';
	 
				try 
				{
					$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
				} 
				catch(Exception $e) 
				{
					die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
				}
	 
				$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
			 
				for($i=1;$i<=$arrayCount;$i++)
				{
					$productname = strtoupper(trim($allDataInSheet[$i]["A"]));
					$amount_gbp = trim($allDataInSheet[$i]["C"]);
					$amount_bd = trim($allDataInSheet[$i]["D"]);
					if($productname!="")
					{	
						
						?>
                        <option style="max-width: 250px; " value="<?php echo $productname."_".($amount_gbp*$rates)."_".$amount_gbp."_".$rates; ?>" ><?php echo $productname; ?></option>
                        <?php
					
					}
				}
				?>
                </select>
                </div>
                </div>
                <div style="overflow-y: scroll; height:350px; padding-top: 15px;">
                
                <table width="100%" id="display_bts_selection" style=" border:0px solid #CCC; display: none;" cellspacing="0">
                            <tr>
                                <td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left">Product Selected</td>
                                <td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left">Unit Prce</td>
                                <td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left">Unit Price(GBP)</td>
                                <td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left">Quantity</td>
                                <td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left">Total(BD)</td>
                                <td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left">Manage</td>
                                
                            </tr>
                </table>
                </div>
                
                <div id="issue_bts" align="center" style="display:none">
                <input type="button" name="Search" value= "Save And Continue" class="btn btn-info" onclick="calculate_total_bts()" />
                    	&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    	<input type="button" name="reset"  value= "Cancel" class="btn btn-warning" onclick="reset_page()"/>
                </div>
				</div>
                
                
 
                <div id="view_totals" style="display:none; padding-top: 20px;" align="center">
                <table width="80%" style="background:#CCC; border:0px solid #CCC;" cellspacing="0">
                	<tr>
                    	<td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left">Applicants Name</td>
                    	<td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left"><input type="text" class="form-control" id="bts_applicnt_name" style="width:210px;"  name="bts_applicnt_name"/></td>
                
                	</tr>
                  	<tr>
                    	<td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left">Contact Number</td>
                       	<td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left"><input type="text" class="form-control" id="bts_applicnt_no" style="width:210px;" name="bts_applicnt_no" /></td>
                 	</tr>
                    <tr>
                    	<td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left">Total Amount To Be Paid</td>
                       	<td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left"><label id="total_pay_final"></label></td>
                 	</tr>
                    <input type="hidden" name="lastrowoftable" id="lastrowoftable" value=""/>
                    <input type="hidden" name="fulldetailsprds" id="fulldetailsprds" value=""/>
                    
                    <tr>
                    	<td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="right"><input type="button" name="Search" class="btn btn-success" value="Add A Sale" onclick="add_bts_final()"></td>
                       	<td style="font: 13px tahoma, arial, sans-serif ; font-weight:bold; text-align:middle; padding:15px 10px 5px 10px; border-bottom:1px solid #eee;" align="left"><input type="button" name="reset"  value= "Cancel" class="btn btn-danger" onclick="reset_page()"/></td>
                 	</tr>
                    
            	</table>
                
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