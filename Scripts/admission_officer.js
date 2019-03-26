var total_scanned=0;
var appointment_changed = 0;
var scanned_references=[];
var scanned_full_details=[];
var global_ref = "";
function get_appointment_details()
{
	var returnvalue="";
	if(global_ref=="")
	{
		var ref_no=$.trim($("#ao_ref_no").val());
	}
	else
	{
		var ref_no=global_ref;
	}
	var changeappointment="";
	if(ref_no =="")
	{
		alert("Please enter application reference number or FRM Number.");
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
				if(json=="ao_created")
				{
					$("#td_sbmt_btn").html("<label class='text-danger h4'>AO Already Created for This Reference Number</label><br><input type='button' class='btn btn-info' value='Add Another Application' onClick='window.location.reload()'>");
				}
				else if (json=="submitted")
				{
					$("#td_sbmt_btn").html("<label class='text-danger h4'>Application Reference Number Already Submitted</label><br><input type='button' class='btn btn-info' value='Add Another Application' onClick='window.location.reload()'>");
				}
				else if (json=="pending_approval")
				{
					$("#td_sbmt_btn").html("<label class='text-danger h4'>AO Created. Pending Supervisor Approval</label><br><input type='button' class='btn btn-info' value='Add Another Application' onClick='window.location.reload()'>");
				}
				else
				{
					var ref_details=json.split(",");
						//$data_transfer=$get_details['reference_number'].",".$get_details['date_of_appointment'].",".$get_details['time_appointment'].",".$get_details['appointment_type'].",".$appointment_time_difference.","."walkin_without_premium";
					//alert(ref_details[0]);
					scanned_references[total_scanned]=ref_details[0];
					var app_ch_no = 0;
					/*
					1=no standard
					2=premium
					*/
					if(ref_details[3]=="PREMIUM LOUNGE APPOINTMENT")
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
							var remark="Up On approval";
							app_ch_no=2;
						}
						else if(ref_details[5]=="walkin")
						{
							var remark="Up On approval";
							app_ch_no=1;
						}
					}
					/*if(ref_details[3]=="STANDARD APPOINTMENT")
					{
						appnt_typ=ref_details[3];
					}
					else if(ref_details[3]=="PREMIUM LOUNGE APPOINTMENT")
					{
						appnt_typ="Premium Lounge";
					}
					else */
					
						$("#ao_selected_gwfs").show();
						$("#ao_selected_gwfs_div").show();
						appnt_typ=ref_details[3];
						var tablerow="<tr id='"+total_scanned+"'><td id='slno"+(total_scanned+1)+"'>"+(total_scanned+1)+"</td><td id='ref"+total_scanned+"'>"+ref_details[0]+"</td><td>"+ref_details[1]+" "+ref_details[2]+"</td><td>"+appnt_typ+"</td><td style='color:red;'>"+remark+"</td><td><select id='appointmnt_typ_"+total_scanned+"' class='form-control' style='width: 120px;' onchange='reason_for_changing("+(total_scanned)+")'><option value='select'>Change Appointment To</option><option value='standard'>Standard Appointment</option><option value='premium'>Premium Lounge Appointment</option><option value='walk_in_premium'>Walk-In Premium Lounge</option><option value='walk_in'>Walk-In Standard Appointment</option></select></td><td style='vertical-align: middle;'><input type='text' class='form-control' style='width:130px; height: 34px !important; margin:0 0 0 0;' id='reason_changing_"+total_scanned+"' onKeyPress='keyup("+total_scanned+")'></td><td><input type='button' id='btn_rjct_"+total_scanned+"' class='btn btn-danger' value='Reject' onclick='loadPopupBox2(&quot;"+ref_details[0]+"&quot;,&quot;individual&quot;)'></td><td id='td"+total_scanned+"'></td></tr>";
						$('#ao_selected_gwfs').append(tablerow);
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
						//$('#for_saveandContinue').show();
						
						$("#submit").attr("value","Add Another Application");
						$("#ao_ref_no").val("");
						$("#app_ref_no").hide();
						$("#dsply_another_refno").hide();
						//$("#for_saveandContinue").show();
						
						if(app_ch_no==2)
						{
							$('#appointmnt_typ_'+total_scanned).attr("disabled",true);
						}
						if($("#reason_changing_"+total_scanned).attr("disabled")==true)
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
						
						if(ref_details[5]=="walkin_with_premium" || ref_details[5]=="walkin")
						{
							$("#for_saveandContinue").hide();
							$("#reason_changing_"+total_scanned).attr("disabled", false);
							$('#reason_changing_'+total_scanned).removeClass('form-control');
							$('#reason_changing_'+total_scanned).addClass('textfield_error');
							$("#btn_rjct_"+total_scanned).attr("disabled", true); 
							
						}
						else
						{
							$("#reason_changing_"+total_scanned).attr("disabled", true); 
						}
						
						//FOr Remove Button Enable/ Disable
						
						//$( "#ao_selected_gwfs tr:last td#"+total_scanned ).css({ backgroundColor: "yellow", fontWeight: "bolder" });
						$( "#td"+total_scanned ).html("<img src='styles/images/delete.png' style='cursor: pointer; height: 40px; width:40px;' alt='Remove' onclick='removeref(&quot;"+ref_details[0]+"&quot;,&quot;"+total_scanned+"&quot;)'/>");
						//$( "#td"+(total_scanned-1) ).html("");
						scanned_full_details[total_scanned]= total_scanned+",_"+ref_no+",_"+tosend+",_"+changeappointment;
						
						total_scanned = (total_scanned+1);
						count_error_addnew=0;
						for(j=0; j<=total_scanned; j++)
						{
							if($('#reason_changing_'+j).prop('disabled')==false)
							{
								var reason_for_changing = $('#reason_changing_'+j).val();
								if(reason_for_changing.length > 5 )
								{
									$('#reason_changing_'+j).removeClass('textfield_error');
									$('#reason_changing_'+j).addClass('form-control');
									appointment_changed =0;
									
								}
								else
								{
									$('#reason_changing_'+j).removeClass('form-control');
									$('#reason_changing_'+j).addClass('textfield_error');
									//alert("Please Enter The Reason For Appointment Type Change");
									count_error_addnew++;
								}
							}
							
						}
						if(count_error_addnew >=1)
						{
							$("#for_saveandContinue").hide();
						}
						else
						{
							$("#for_saveandContinue").show();
						}
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
		$("#ao_ref_no").focus();
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
	error_count_reason = 0;
	for(var i=0; i<scanned_full_details.length; i++)
	{
		var fullstring = scanned_full_details[i];
		var data = fullstring.split(",_");
		if(appointment_changed ==1)
		{
			var reason_for_changing = $('#reason_changing_'+i).val();
			if(reason_for_changing=="" && $('#reason_changing_'+j).prop('disabled')==false)
			{
				$('#reason_changing_'+i).removeClass('form-control');
				$('#reason_changing_'+i).addClass('textfield_error');
				error_count_reason++;
			}
			else
			{
				$('#reason_changing_'+i).removeClass('textfield_error');
				$('#reason_changing_'+i).addClass('form-control');
				appointment_changed =0;
			}
		}
		else
		{
			return true;
		}
	}
	
	if(error_count_reason >=1)
	{
		$("#for_saveandContinue").hide();
		
	}
	else
	{
		$("#for_saveandContinue").show();
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
	$("#reason_changing_"+txtid).attr("disabled", false); 
	appointment_changed = 1;
	test_reason(txtid);
}



function submit_appointment_details()
{
	var tokennos= $.trim($("#token_no").val());
	var comments = $("#comment_txt").val();
	if(tokennos.length ==0  )
	{
		$('#token_no').removeClass('form-control');
		$('#token_no').addClass('textfield_error');
	}
	else
	{
		if(keyup())
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
				data: 'cho=39&dt='+stringed+'&token='+tokennos+'&comments='+comments,
				dataType:"json",
				success: function(json)
				{
					if(json=="inserted")
					{
						//window.location.href = "admission_officer.php?r=3422";
						$("#PopupBox_ao_msg").html("<span class='text-success h4'>AO Record Added!</span><br><span class='text-warning h5'>Note: Walk-In requests will be sent for Manager approval</span>");
						loadPopupBox_ao_msg();
						setTimeout(function(){unloadPopupBox_ao_msg(); }, 4000);
					}
					else if(json=="failed_insert")
					{
						$("#PopupBox_ao_msg").html("<span class='text-success h4'>AO Creation Failed (Database Error). Please contact Adinistrator</span>");
						$("html, body").animate({ scrollTop: 0 }, "slow");
						loadPopupBox_ao_msg();
						setTimeout(function(){
								$('#PopupBox_ao_msg').fadeOut("slow");
								$("#templatemo_container").fadeTo( "slow", 1);
								$("body").find('#opo1').remove();
							}, 2000);
						$("#for_saveandContinue").show();
						
					}
					else if(json=="token_exists")
					{
						$("#PopupBox_ao_msg").html("<span class='text-danger h4'>This Token Number Already Used.</span>");
						$("html, body").animate({ scrollTop: 0 }, "slow");
						loadPopupBox_ao_msg();
						$("body").find('#opo1').prop("onclick", null);
						$('#token_no').removeClass('form-control');
						$('#token_no').addClass('textfield_error');
						setTimeout(function(){
								$('#PopupBox_ao_msg').fadeOut("slow");
								$("#templatemo_container").fadeTo( "slow", 1);
								$("body").find('#opo1').remove();
							}, 2000);
						$("#for_saveandContinue").show();
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



function keyup()
{
	count_error=0;
	for(j=0; j<scanned_full_details.length; j++)
	{
		if($('#reason_changing_'+j).prop('disabled')==false)
		{
			var reason_for_changing = $('#reason_changing_'+j).val();
			if(reason_for_changing.length > 5 )
			{
				$('#reason_changing_'+j).removeClass('textfield_error');
				$('#reason_changing_'+j).addClass('form-control');
				appointment_changed =0;
					
			}
			else
			{
				$('#reason_changing_'+j).removeClass('form-control');
				$('#reason_changing_'+j).addClass('textfield_error');
					//alert("Please Enter The Reason For Appointment Type Change");
				count_error++;
			}
		}
			
	}
	if(count_error >=1)
	{
		$("#for_saveandContinue").hide();
		return false
	}
	else
	{
		$("#for_saveandContinue").show();
		return true
	}
	
}

function cancel()
{
	location.reload(true);
}

function removeref(ref,row)
{
	var tocntrow = 0;
	$('#ao_selected_gwfs > tbody > tr').each(function() 
	{
		tocntrow=(tocntrow+1);
	});
	if(tocntrow<=1)
	{
		window.location.reload();
	}
	else
	{
		scanned_full_details.length=0;
		scanned_references.length=0;;
		var ref_frm_row_array =[];
		total_scanned=0;
		var commentsarr =[];
		//$('#ao_selected_gwfs tbody').empty();
		var cnt=0;
		var rowcnt=0;
		var array_cnt=0;
		$('#ao_selected_gwfs > tbody > tr').each(function() 
		{
			ref_frm_row_array[cnt] = $("#ref"+rowcnt).html();
			if(!$("#reason_changing_"+cnt).attr("readonly")==true)
			{
				commentsarr[array_cnt]= ref_frm_row_array[cnt]+","+$("#reason_changing_"+cnt).val()+","+$("#appointmnt_typ_"+cnt).val();
				array_cnt = (array_cnt+1);
			}
			cnt=(cnt+1);
			rowcnt = (rowcnt+1);
			
		});
			
		$("#tbodyid").html("");
		$('#ao_selected_gwfs').hide();
		$("#ao_selected_gwfs_div").hide();
		$.each( ref_frm_row_array, function( key, value ) 
		{
			if(value!=ref)
			{
				global_ref= value;
				get_appointment_details();
			}
		});
		
		if(commentsarr.length > 0)
		{
			alert("Selected Reference Number is Removed!");
			$.each( commentsarr, function( key2, value2 )
			{
				var commentsarraystring = value2;
				var comment_data = commentsarraystring.split(",");
				var ref_chk = comment_data[0];
				//alert("SC Full2\n"+commentsarr.join('\n'));
				var cnt_ro = 0;
				$('#ao_selected_gwfs > tbody > tr').each(function ()
				{
					exist_ref = $(this).closest('tr').children('#ref'+cnt_ro).text();
					cnt_ro = (cnt_ro+1);
					if(exist_ref==ref_chk)
					{
						var id_of_element = $(this).find("td:first").next().prop('id');
						var lastChar = id_of_element.substr(id_of_element.length - 1);
						$("#reason_changing_"+lastChar).val(comment_data[1]);
						$("#reason_changing_"+lastChar).attr("readonly",false);
						$("#appointment_typ_"+lastChar).val(comment_data[2]);
					}
				});
			});
		}
		global_ref="";
		returnvalue="";
		commentsarr.length=0;
		count_error_remove=0;
		for(m=0; m<scanned_full_details.length; m++)
		{
			if($('#reason_changing_'+m).prop('disabled')==false)
			{
				var reason_for_changing = $('#reason_changing_'+m).val();
				if(reason_for_changing.length > 5 )
				{
					$('#reason_changing_'+m).removeClass('textfield_error');
					$('#reason_changing_'+m).addClass('form-control');
					appointment_changed =0;
					
				}
				else
				{
					$('#reason_changing_'+m).removeClass('form-control');
					$('#reason_changing_'+m).addClass('textfield_error');
					//alert("Please Enter The Reason For Appointment Type Change");
					count_error_remove++;
				}
			}
			
		}
		if(count_error_remove >=1)
		{
			$("#for_saveandContinue").hide();
		}
		else
		{
			$("#for_saveandContinue").show();
		}
	}
}
