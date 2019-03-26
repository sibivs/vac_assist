    <?php 

$a = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="styles/style.css">
        <script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
    </head>
	<body>
    <div id="for_email">';
	$msg2="";
    $msg1_1= '<div class="nav-divider" style="text-align:center; width:80%;">
    	<div align="center" style="font-weight: bold; color: blue; font-weight: 16px;"><p><u>Passports in VAC for more 20 days</u></div>
    </div>';
       $msg1_2='<table class="table table-bordered" style="width: 80%;">
            <thead class="text-info h4">
                <tr>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>Sl.No</h4></b></td>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>Reference Number</h4></b></td>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>Date Outscan</h4></b></td>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>Date Inscan</h4></b></td>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>Received Before</h4></b></td>
					<td style="border: 1px solid; border-collapse: collapse;"><b><h4>Current Status</h4></b></td>
                </tr>
            </thead>
            <tbody>';
                include_once("db_connect.php");
                $cnt=0;
                $get_reference_nmbr=mysql_query("select gwf_number,date_outscan, date_inscan,TIMESTAMPDIFF(DAY, date_inscan, CURDATE()) AS total_days from passport_inscan_record where current_status='inscan' and date_inscan != '0000-00-00' AND (TIMESTAMPDIFF(DAY, date_inscan, CURDATE()) >=20) and mission_id='".$_SESSION['mission_id']."'");
                if(mysql_num_rows($get_reference_nmbr)>0)
				{
					while($result_in_current_status=mysql_fetch_array($get_reference_nmbr))
					{
						$cnt++;
						$msg2= $msg2." <tr>
						<td style='border: 1px solid; border-collapse: collapse;'>". $cnt." </td>
						<td style='border: 1px solid; border-collapse: collapse;'>". $result_in_current_status['gwf_number']." </td>
						<td style='border: 1px solid; border-collapse: collapse;'>". $result_in_current_status['date_outscan']." </td>
						<td style='border: 1px solid; border-collapse: collapse;'>".$result_in_current_status['date_inscan'] ."</td>
						<td style='border: 1px solid; border-collapse: collapse;'>". $result_in_current_status['total_days']." Days</td>
						<td style='border: 1px solid; border-collapse: collapse;'>Inscan At VAC</td>
						</tr>";
					}
					$msg1=$msg1_1.$msg1_2;
        			$msg3="</tbody>
        		</table>
        		</div><br><br><br><br><br><br>Regards<br>---<br><div style='color: purple; font-size: 13px; font-weight: bold; height: 50px; vertical-align: bottom;'>Etimad System Admin Team<br><br><br><br></div>Please Note this is a system generated email.";
				$msg= $msg1.$msg2.$msg3;
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
					mail($reciver_emails,"Passports In VAC for more than 20 days",$msg,$headers);
				}
			}
		mysql_close();
	$x='</body>
</html>';