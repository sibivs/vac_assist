var total_scanned=0;
var appointment_changed = 0;
var scanned_references=[];
var scanned_full_details=[];

function get_appointment_details()
{
	var ref_no=$("#ao_ref_no").val();
	var changeappointment="";
	if(ref_no =="")
	{
		alert("Please enter application reference number.");
	}
	else if(ref_no !="" && ($.inArray(ref_no, scanned_references) == -1))
	{
		$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data: 'cho=37&ref_no='+ref_no,
			dataType:"json",
			success: function(json)
			{
				if(json!="ao_created")
				{
					var ref_details=json.split(",");
					//$data_transfer=$get_details['reference_number'].",".$get_details['date_of_appointment'].",".$get_details['time_appointment'].",".$get_details['appointment_type'].",".$appointment_time_difference.","."walkin_without_premium";
					//alert(ref_details[0]);
					scanned_references[total_scanned]=ref_details[0];
					$("#ao_selected_gwfs").show();
					var app_ch_no = 0;
					/*
					1=no standard
					2=premium
					*/
					if(ref_details[3]=="PREMIUM LOUNGE")
					{
						app_ch_no=2;
						remark="";
					}
					else
					{
						if(ref_details[5]=="late")
						{
							if(ref_details[4]=='lastday')
							{
								var remark="Prev. Day";
								app_ch_no = 2;
							}
							else if(ref_details[4]=='no_noc')
							{
								var remark="NOC Missing";
								app_ch_no=1;
							}
							else
							{
								var remark="Late "+ref_details[4];
								app_ch_no=2;
							}
						}
						else if(ref_details[5]=="ontime")
						{
							var remark="";
							app_ch_no=1;
						}
						else if(ref_details[5]=="walkin_without_premium")
						{
							var remark="Missing NOC";
							app_ch_no=1;
						}
						else if(ref_details[5]=="walkin_with_premium")
						{
							var remark="Walk-In";
							app_ch_no=2;
						}
						else if(ref_details[5]=="walkin")
						{
							var remark="Walk-In";
							app_ch_no=1;
						}
					}
					if(ref_details[3]=="STANDARD APPOINTMENT")
					{
						appnt_typ="Standard";
					}
					else if(ref_details[3]=="PREMIUM LOUNGE")
					{
						appnt_typ="Premium";
					}
					else if(ref_details[3]==="No Appointment")
					{
						alert("This Reference Number - "+ref_no+" is not existing in today's appointment. Please navigate to Add Walk-In page to add this user as a walk-in");
						return false;
					}
					else
					{
						appnt_typ=ref_details[3];
					}
					
					var tablerow="<tr><td>"+(total_scanned+1)+"</td><td>"+ref_details[0]+"</td><td>"+ref_details[1]+" "+ref_details[2]+"</td><td>"+appnt_typ+"</td><td style='color:red;'>"+remark+"</td><td><select disabled='true' id='appointmnt_typ_"+total_scanned+"' class='form-control' style='width: 120px;' onchange='reason_for_changing("+(total_scanned)+")'><option value='select'>Change Appointment To</option><option value='standard'>Standard Appointment</option><option value='premium'>Premium Lounge Appointment</option><option value='walk_in_premium'>Walk-In Premium Lounge</option><option value='walk_in'>Walk-In Standard Appointment</option></select></td><td style='vertical-align: middle;'><input type='text' class='form-control' style='width:130px; height: 34px !important; margin:0 0 0 0;' id='reason_changing_"+total_scanned+"' onKeyPress='keyup("+total_scanned+")'></td><td><input type='button' class='btn btn-danger' value='Reject' onclick='loadPopupBox2(&quot;"+ref_details[0]+"&quot;)'/></td></tr>";
					$('#ao_selected_gwfs').append(tablerow);
					$("#reason_changing_"+total_scanned).attr("readonly", true); 
					if(app_ch_no==1)
					{
						$('#appointmnt_typ_'+total_scanned).val('standard');
					}
					else if(app_ch_no==2)
					{
						$('#appointmnt_typ_'+total_scanned).val('premium');
					}
						
					if(total_scanned>1)
					{
						$("#ao_selected_gwfs tbody td").css("border-top-width",'1px');
						$("#ao_selected_gwfs tbody td").css({"border-top-color": "#ddd", 
								"border-top-width":"1px", 
								"border-top-style":"solid"});
					}
					$("#dsply_another").show();
					$('#for_saveandContinue').show();
					$("#submit").attr("value","Add Another Application");
					$("#ao_ref_no").val("");
					$("#app_ref_no").hide();
					$("#dsply_another_refno").hide();
					$("#for_saveandContinue").show();
					if($("#reason_changing_"+total_scanned).attr("readonly")==true)
					{
						changeappointment="nochange";
					}
					else
					{
						changeappointment = $('#appointmnt_typ_'+total_scanned).val();
					}
						
					//array (slno,ref_no,calculated_appnmnt_cat,changed_appnmnt_cat)
					if(ref_details[4]=='lastday')
					{
						var tosend="lastday";
					}
					else if(ref_details[4]=='no_noc')
					{
						var tosend="no_noc";
					}
					else
					{
						var tosend= ref_details[5];
					}
					scanned_full_details[total_scanned]= total_scanned+",_"+ref_no+",_"+tosend+",_"+changeappointment;
						
					total_scanned = (total_scanned+1);
					
				}
				else
				{
					alert("AO Already Created");
					return false;
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
		alert("This Reference number is added in the list!");
	}
}


function addanother()
{
	var fullstring = scanned_full_details[(total_scanned-1)];
	var data = fullstring.split(",_");
	if(test_reason(data[0])==true)
	{
		var tblhtml= $("#app_ref_no").html();
		$("#dsply_another_refno").html(tblhtml);
		$("<label style='padding-left: 50px;'><input type='button' class='btn btn-warning' value='Cancel And Go Back' onClick='cancelanother()'></label>").appendTo('#td_sbmt_btn');
		$("#dsply_another_refno").css('height','160px');
		$("#dsply_another_refno").show();
		$("#app_ref_no").html();
		$("#dsply_another").hide();
		$("#for_saveandContinue").hide();
	}
	else
	{
		//alert('Please correct the errors');
		return false;
	}
	
}


function test_reason(id)
{
	var fullstring = scanned_full_details[(total_scanned-1)];
	var data = fullstring.split(",_");
	if(appointment_changed ==1)
	{
		var reason_for_changing = $('#reason_changing_'+id).val();
		if(reason_for_changing=="")
		{
			$('#reason_changing_'+id).removeClass('form-control');
			$('#reason_changing_'+id).addClass('textfield_error');
			alert("Please Enter The Reason For Appointment Type Change");
			$("#for_saveandContinue").hide();
			return false;
		}
		else
		{
			$('#reason_changing_'+id).removeClass('textfield_error');
			$('#reason_changing_'+id).addClass('form-control');
			appointment_changed =0;
			$("#for_saveandContinue").show();
			return true;
		}
	}
	else
	{
		return true;
	}
}

function cancelanother()
{
	$("#dsply_another_refno").html();
	$("#dsply_another_refno").hide();
	$("#dsply_another_refno").hide();
	$("#for_saveandContinue").show();
	$("#dsply_another").show();
}

function reason_for_changing(txtid)
{
	$("#reason_changing_"+(txtid)).attr("readonly", false); 
	appointment_changed = 1;
	test_reason(txtid);
}



function submit_appointment_details()
{
	var tokennos= $("#token_no").val();
	if(tokennos.length ==0 )
	{
		$('#token_no').removeClass('form-control');
		$('#token_no').addClass('textfield_error');
	}
	else
	{
		$("#for_saveandContinue").hide();
		$("#dsply_another").hide();
		$.each( scanned_full_details, function( key, value ) 
		{
			var data = value.split(",_");
			var reason_for_changing = $('#reason_changing_'+data[0]).val();
			var new_app_type = $("#appointmnt_typ_"+data[0]).val();
			if($('#reason_changing_'+data[0]).val().length <=0)
			{
				var newval = scanned_full_details[key]+",_nochange,_nochange";
				scanned_full_details[key]= newval;
			}
			else
			{
				var newval = scanned_full_details[key]+",_"+new_app_type+",_"+reason_for_changing;
				scanned_full_details[key]= newval;
			}
		});

		var stringed = JSON.stringify(scanned_full_details);
		$.ajax(
		{
			type: 'post',
			url: 'php_func.php',
			data: 'cho=39&dt='+stringed+'&token='+tokennos,
			dataType:"json",
			success: function(json)
			{
				if(json=="inserted")
				{
					window.location.href = "admission_officer.php?r=3422";
				}
				else if(json=="failed_insert")
				{
					$("#response").html("<label class='text-danger h4'>AO Creation Failed (Database Error). Please contact Adinistrator</label>")
					$("#response").show();
					event.preventDefault();
					$("html, body").animate({ scrollTop: 0 }, "slow");
  					return false;
				}
				else if(json=="token_exists")
				{
					$("#response").html("<label class='text-danger h4' style='border: 0px;'>Token Number Already Used. Please Enter new Token Number</label>")
					$("#response").show();
					$("#for_saveandContinue").show();
					event.preventDefault();
					$("html, body").animate({ scrollTop: 0 }, "slow");
  					return false;
					
				}
			},
			error: function(request, status, error)
			{
				alert(request.responseText);  
			}
					
		});
	}
}



function keyup(id)
{
	$('#reason_changing_'+id).bind('keyup mouseup cut paste blur click onsearch', function () 
	{
		
		var reason_for_changing = $('#reason_changing_'+id).val();
		if(reason_for_changing.length > 5 )
		{
			$('#reason_changing_'+id).removeClass('textfield_error');
			$('#reason_changing_'+id).addClass('form-control');
			appointment_changed =0;
			$("#for_saveandContinue").show();
		}
		else
		{
			$('#reason_changing_'+id).removeClass('form-control');
			$('#reason_changing_'+id).addClass('textfield_error');
			//alert("Please Enter The Reason For Appointment Type Change");
			$("#for_saveandContinue").hide();
		}
	});
}

function cancel()
{
	location.reload(true);
}
