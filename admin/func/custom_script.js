// JavaScript Document
	var rowcount="";
	function get_mission_list()
	{
		var display_name=$("#user_list").val();
		$("#permission_list_div").hide();
		$("#submit_button_tr").hide();
		$("#display_mission").hide();
		if(display_name!="select")
		{
			$.ajax(
			{
				type: 'post',
				url: 'func/php_func.php',
				data: 'cho=11&display_name='+display_name,
				dataType:"json",
				success: function(result)
				{
					$("#display_mission").show();
					$("#mission_list_span").html(result);
				},
				error: function(request, status, error)
				{
					$("#mission_list_span").html("<span class='text-warning h4'>Something Went Wrong. Please contact Administrator!</span>");
				}
					
			});
		}
		else
		{
			$("#display_mission").hide();
			$("#mission_list_span").html("");
			$("#submit_button_tr").hide();
		}
	}
	
	function show_button()
	{
		
		$("#permission_list_div").hide();
		var mission=$("#mission").val();
		if(mission == "select")
		{
			$("#submit_button_tr").hide();
		}
		else
		{
			$("#submit_button_tr").show();
		}
		
		//get user id and store in the hidden variable
		var display_name=$("#user_list").val();
		var mission=$("#mission").val();
		$.ajax(
			{
				type: 'post',
			  	url: 'func/php_func.php',
				data: 'cho=14&display_name='+display_name+'&mission_id='+mission,
			  	dataType:"json",
				success: function(result)
				{
					$("#user_id_hidden").val(result[0]);
				},
				error: function(request, status, error)
				{
					$("#permission_list_div").html("<span class='text-warning h4'>Something Went Wrong. Please contact Administrator!</span>");
				}
				
			});
	}
	
    function retrive_user_permissions()
	{
		var user_id=$("#user_id_hidden").val();
		var mission=$("#mission").val();
		$.ajax(
			{
				type: 'post',
			  	url: 'func/php_func.php',
				data: 'cho=12&user_id='+user_id+'&mission_id='+mission,
			  	dataType:"json",
				success: function(result)
				{
					
					if(result=="rowcountzero")
					{
						rowcount = "0";
						//$("#permission_list_div").html("No custom permissions are set for the selected User");
						$("#approval_manage").prop("checked",false);
						$("#approval_view").prop("checked",false);
						$("#appointment_manage").prop("checked",false);
						$("#inscan_manage").prop("checked",false);
						$("#tee_manage").prop("checked",false);
						$("#tee_view").prop("checked",false);
						$("#oyster_manage").prop("checked",false);
						$("#oyster_view").prop("checked",false);
						$("#vas_manage").prop("checked",false);
						$("#vas_view").prop("checked",false);
						$("#email_manage").prop("checked",false);
						$("#email_view").prop("checked",false);
						$("#user_list_manage").prop("checked",false);
						$("#user_list_view").prop("checked",false);
						$("#user_manage").prop("checked",false);
						$("#permission_list_div").show();
					}
					else
					{
						//alert(result[0]);
						if(result[3]==1)
						{
							$("#approval_manage").prop("checked",true);
						}
						if(result[4]==1)
						{
							$("#approval_view").prop("checked",true);
						}
						if(result[5]==1)
						{
							$("#appointment_manage").prop("checked",true);
						}
						if(result[6]==1)
						{
							$("#inscan_manage").prop("checked",true);
						}
						if(result[7]==1)
						{
							$("#tee_manage").prop("checked",true);
						}
						if(result[8]==1)
						{
							$("#tee_view").prop("checked",true);
						}
						if(result[9]==1)
						{
							$("#oyster_manage").prop("checked",true);
						}
						if(result[10]==1)
						{
							$("#oyster_view").prop("checked",true);
						}
						if(result[11]==1)
						{
							$("#vas_manage").prop("checked",true);
						}
						if(result[12]==1)
						{
							$("#vas_view").prop("checked",true);
						}
						if(result[13]==1)
						{
							$("#email_manage").prop("checked",true);
						}
						if(result[14]==1)
						{
							$("#email_view").prop("checked",true);
						}
						if(result[15]==1)
						{
							$("#user_list_manage").prop("checked",true);
						}
						if(result[16]==1)
						{
							$("#user_list_view").prop("checked",true);
						}
						if(result[17]==1)
						{
							$("#user_manage").prop("checked",true);
						}
					
						rowcount = "1";
						$("#permission_list_div").show();
					}
					
				
				},
				error: function(request, status, error)
				{
					$("#permission_list_div").html("<span class='text-warning h4'>Something Went Wrong. Please contact Administrator!</span>");
				}
				
			});
		//alert(user_id);
	}
	
	
	
	function update_user_permissions()
	{
		var user_id=$("#user_id_hidden").val();
		var mission=$("#mission").val();
		if ($("#appointment_manage").is(':checked')) 
		{
			appointment_manage="1";
		}
		else
		{
			appointment_manage="0";
		}
		if ($("#inscan_manage").is(':checked')) 
		{
			inscan_manage="1";
		}
		else
		{
			inscan_manage="0";
		}
		
		if ($("#user_manage").is(':checked')) 
		{
			user_manage="1";
		}
		else
		{
			user_manage="0";
		}
		
		if ($("#user_list_view").is(':checked')) 
		{
			user_list_view="1";
		}
		else
		{
			user_list_view="0";
		}
		
		if ($("#user_list_manage").is(':checked')) 
		{
			user_list_manage="1";
		}
		else
		{
			user_list_manage="0";
		}
		if ($("#approval_view").is(':checked')) 
		{
			approval_view="1";
		}
		else
		{
			approval_view="0";
		}
		if ($("#approval_manage").is(':checked')) 
		{
			approval_manage="1";
		}
		else
		{
			approval_manage="0";
		}
		if ($("#email_view").is(':checked')) 
		{
			email_view="1";
		}
		else
		{
			email_view="0";
		}
		if ($("#email_manage").is(':checked')) 
		{
			email_manage="1";
		}
		else
		{
			email_manage="0";
		}
		if ($("#vas_view").is(':checked')) 
		{
			vas_view="1";
		}
		else
		{
			vas_view="0";
		}
		if ($("#vas_manage").is(':checked')) 
		{
			vas_manage="1";
		}
		else
		{
			vas_manage="0";
		}
		if ($("#oyster_view").is(':checked')) 
		{
			oyster_view="1";
		}
		else
		{
			oyster_view="0";
		}
		if ($("#oyster_manage").is(':checked')) 
		{
			oyster_manage="1";
		}
		else
		{
			oyster_manage="0";
		}
		if ($("#tee_view").is(':checked')) 
		{
			tee_view="1";
		}
		else
		{
			tee_view="0";
		}
		if ($("#tee_manage").is(':checked')) 
		{
			tee_manage="1";
		}
		else
		{
			tee_manage="0";
		}
		
		var data = "user_id="+user_id+"&mission_id="+mission+"&appointment_manage="+appointment_manage+"&inscan_manage="+inscan_manage+"&user_manage="+user_manage+"&user_list_view="+user_list_view+"&user_list_manage="+user_list_manage+"&approval_view="+approval_view+"&approval_manage="+approval_manage+"&email_view="+email_view+"&email_manage="+email_manage+"&vas_view="+vas_view+"&vas_manage="+vas_manage+"&oyster_view="+oyster_view+"&oyster_manage="+oyster_manage+"&tee_view="+tee_view+"&tee_manage="+tee_manage+"&rowcount="+rowcount;
		
		if(confirm("Are you sure, to update the access permissions for the user?"))
		{
			$.ajax(
			{
				type: 'post',
				url: 'func/php_func.php',
				data: 'cho=13&'+data,
				dataType:"json",
				success: function(result)
				{
					if(result=="updated_custom_permissions")
					{
						$("#custom").html("<span class='text-success h4'>Custom Permissions updated!</span>");
						loadPopupBox_custom_permission();
						setTimeout(function(){unloadPopupBox_custom_permission(); }, 2000);
					}
					else if(result=="added_custom_permissions")
					{
						$("#custom").html("<span class='text-success h4'>Custom Permissions Added!</span>");
						loadPopupBox_custom_permission();
						setTimeout(function(){unloadPopupBox_custom_permission(); }, 2000);
					}
					else if(result=="error_custom_permissions")
					{
						$("#custom").html("<span class='text-danger h4'>Something Went wrong! Please contact administrator</span>");
						loadPopupBox_custom_permission();
						setTimeout(function(){unloadPopupBox_custom_permission(); }, 2000);
					}
					else
					{
						$("#custom").html("<span class='text-danger h4'>Something Went wrong! Please contact administrator<br>Ref:"+result+"</span>");
						loadPopupBox_custom_permission();
						setTimeout(function(){unloadPopupBox_custom_permission(); }, 2000);
					}
				},
				error: function(request, status, error)
				{
					//$("#mission_list_span").html("<span class='text-warning h4'>Something Went Wrong. Please contact Administrator!</span>");
				}
					
			});
		}
	}