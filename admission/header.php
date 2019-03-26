<?php 
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
echo '
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript">
	$(document).ready( function() 
	{
		$("#lg_out").click(function()
		{
			$.ajax(
			{
				type: "post",
				url: "../php_func.php",
				data: "cho=2",
				dataType:"json",
				success: function(result)
				{
					if(result==="end_true")
					{
						window.location.href ="../login.php";
					}
					else if(json=="timeout")
					{
						alert("Session Expired. Please Login!")
						window.location.href="../login.php";
						window.location.reload();
					}
					else					
					{
						alert("Something went wrong. Contact Administrator")
					}
				},
				error: function(error)
				{
					alert("Something went wrong. Contact Administrator");
				}
													
			});
		});
	});
</script>';
?>
<label>Welcome, <?php echo $_SESSION['display_name_ukvac']." | " ?><a id="lg_out">Logout</a></label>
<script type="text/javascript" language="javascript">
$(document).ready(function() 
{
	setInterval(function () { 
		$.ajax(
		{
			type: 'post',
			url: '../php_func.php',
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
});
</script>