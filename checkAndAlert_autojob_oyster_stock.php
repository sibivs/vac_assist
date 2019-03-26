    <?php
	date_default_timezone_set("Asia/Bahrain");
	include_once("db_connect.php");
	$get_oyster_cnt= mysql_query("select count(id) as count from oyster_card_stock WHERE  status='in_stock' and mission_id='".$_SESSION['mission_id']."'");
	$res_oyster_cnt=mysql_fetch_array($get_oyster_cnt);
	if($res_oyster_cnt['count'] <=100)
	{
		$a = '<!DOCTYPE html>
				<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					</head>
					<body>
						<div id="for_email">';
		$msg1_1= '<div class="nav-divider" style="text-align:center; width:80%;">
			<div align="center" style="font-weight: bold; color: blue; font-weight: 16px;"><p><u>Oyster Card Stock Status Alert</u></div>
		</div>';
		   $msg1_2="<label style='color: black; font-size: 13px;'>The Oyster Card stock in VAC is less than 100 (".$res_oyster_cnt['count']." numbers).<br>Please place the order for Oystter Card or please upload new stock list.</label>";
			$msg3="</tbody>
			</table>
			</div><br><br><br><br><br><br>Regards<br>---<br><div style='color: purple; font-size: 13px; font-weight: bold; height: 50px; vertical-align: bottom;'>Etimad System Admin Team<br><br><br><br></div>Please Note this is a system generated email.";
			$x='</body>
			</html>';
			$msg= $a.$msg1_1.$msg1_2.$msg3.$x;
			$recievers="";
			$headers = "MIME-Version: 1.0" . "\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 
			$headers .= "From: simon@aletimad.com" . "\r\n"; 
						
			$reciver_emails="";
			$get_rcvr = "select email_address from email_receivers where alert_mail=1 and mission_id='".$_SESSION['mission_id']."'";
			$get_rcvr1=mysql_query($get_rcvr);
			$cnt_emails=mysql_num_rows($get_rcvr1);
			if($cnt_emails >0)
			{
				while($mailids = mysql_fetch_array($get_rcvr1))
				{
					$reciver_emails=$reciver_emails .",".$mailids['email_address'];
				}
				$reciver_emails = ltrim($reciver_emails,',');
				mail($reciver_emails,"Oyster Card Status Alert",$msg,$headers);
			}
			mysql_close();
		
	}