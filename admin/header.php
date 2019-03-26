<script src="../Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript">
	$(document).ready( function() 
	{
		$("#lg_out").click(function()
		{
			$.ajax(
			{
				type: "post",
				url: "func/php_func.php",
				data: "cho=10",
				dataType:"json",
				success: function(result)
				{
					
					if(result==="end_true")
					{
						var response = $.ajax({
								url: 'login.php',
								type: 'HEAD',
								async: false
							}).status;
						if (response == "404") 
						{
							window.location.href ="../login.php";
						}
						else
						{
							window.location.href ="login.php";
						}
					}
					else					
					{
						alert("Something went wrong. Contact Administrator")
					}
				},
				error: function(xhr, status, error)
				{
					alert("Something went wrong. Contact Administrator")
				}
			});
		});
	});

	setInterval(function () { 
		$.ajax(
		{
			type: 'post',
			url: 'func/php_func.php',
			data: 'cho=00',
			dataType:"json",
			success: function(result)
			{
				if(result=="timedout")
				{
					window.location.href="../php_func.php?cho=2";
				}
			},
			error: function(ts)
			{
				alert(ts.responseText); 
			}
		});
	
	}, 1000*30);//check in every 30 seconds interval 
</script>
<label>Welcome, <?php echo $_SESSION['display_name_ukvac']." | " ?><a id="lg_out">Logout</a></label>
