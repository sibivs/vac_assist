function validate()
{
        var uname=$('#username').val();
        var data1=$.md5($('#password').val());
		var mission = $("#mission_select").val();
        if(uname== '' || uname=="username" || data1== '')
        {
        	$("#error_msg_lbl").text('Username and Password are mandatory');
		}
		else if (mission=="mission")
		{
			$("#error_msg_lbl").text('Mission Name is mandatory');
		}
        else
        {
			$.ajax(
			{
				type: 'post',
				url: 'php_func.php',
				data: 'cho=1&data='+data1+'&name='+uname+'&mission_selected='+mission,
				dataType:'json',
				success: function(result)
				{
					if(result==="auth_true")
					{
						window.location.href ="index.php";
					}
					else if(result==="auth_true_mg")
					{
						//window.location.href ="management/index.php";
						window.location.href ="index.php";
					}
					else if(result==="auth_true_power")
					{
						window.location.href ="admin/index.php";
					}
					else					
					{
						$("#error_msg_lbl").text('* Username and Password is mandatory.\n* Verify the Mission Selected');
					}
				},
				error: function(result)
				{
     				alert(JSON.stringify(result));
  				}
								
			});
			
         	//document.form1.action="php_func.php?cho=1&d1="+data1+"&d2="+uname;
         	//document.form1.submit();
        }
}