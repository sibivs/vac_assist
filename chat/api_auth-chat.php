<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<link rel="stylesheet" href="../styles/style.css">
        <script type="text/javascript" language="javascript" src="../Scripts/jquery-1.6.3.js"></script>
        <script type="text/javascript" language="javascript">
		$(document).ready(function() {
			$("#login_chat").click(function(){
				var userid = $("#login_id_chat").val();
				var password = $("#password_chat").val();
				if(userid == "" || password == "")
				{
					$("#response").html("<label class='text-danger' style='font-size: 12px;'>Login ID and Password are mandatory!</label>");
				}
				else
				{	
					$.ajax(
					{
						type: 'post',
						url: 'functions.php',
						data: 'opt=1&login_id_chat='+userid+'&password_chat='+password,
						dataType:'json',
						success: function(response)
						{
							if(response == 200)
							{
								window.location.href="http://192.168.31.130:3000/home";
							}
							//$("#response").html(response);
						},
						error: function(response)
						{
							$("#result").html(JSON.stringify(result));
						}
								
					});
				}
			}); 
		});
		</script>
    </head>
    <body>
    	<form id="chat" method="post" action="" enctype="multipart/form-data">
        	<div style="border-width: 1px; border-radius: 5px; border-color: #CCC;">
            	<div style="padding: 5px; text-align:center" class="text-info h4">
                	<u>DOSChat Login</u>
                </div>
                <table class='table'>
                	<tr>
                    	<td>
                        	<label class="text-info" style="font-size: 13px; font-weight: 600;">
                            	*Login ID
                            </label>
                        </td>
                        <td>
                        	<input type="text" id="login_id_chat" class="form-control" style="width: 210px;" placeholder="Login ID">
                        </td>
                   	</tr>
                    <tr>
                    	<td>
                        	<label class="text-info" style="font-size: 13px; font-weight: 600;">
                            	*Password
                            </label>
                        </td>
                        <td>
                        	<input type="password" id="password_chat" class="form-control" style="width: 210px;" placeholder="Password">
                        </td>
                   	</tr>
                    <tr>
                    	<td colspan="2" style="text-align:center;" id="response">
                        </td>
                   	</tr>
                    <tr>
                    	<td colspan="2" style="text-align:center;">
                        	<input id="login_chat" type="button" class="btn btn-success" value="Login">
                            <input id="reset_chat" type="reset" class="btn btn-danger" value="Reset" style="display:inline;">	
                        </td>
                   	</tr>
                </table>
            </div>
        </form>
    </body>
</html>