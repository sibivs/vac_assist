<?php
	date_default_timezone_set("Asia/Bahrain");
	include_once("db_connect.php");
	$get_glfsid= mysql_query("select count(id) as count from tee_envelop_counts WHERE  status='available'  and tee_type = 'small'");
	$res_get_glfsid=mysql_fetch_array($get_glfsid);
	if($res_get_glfsid['count'] <=500000)
	{
		$html = "<label style='color: pink; font-size: 13px;'>The Small TEE Envelop Count is less than 5000.<br>Please place an order for Small TEE Envelops or update the stock into the system</label><br><br><br><br><br>Regards<br>---<br><div style='color: purple; font-size: 13px; font-weight: bold; height: 50px; vertical-align: bottom;'>Etimad System Admin Team<br><br><br><br></div>Please Note this is a system generated email.";
		$heading = "<div align='center' style='font-weight: bold; color: blue; font-weight: 16px;'><p><u>Available TEE Envelops are less than 5000</u></p><p></p><p></p></div>";
		$recievers="";
		//$myfile = fopen("Sample_files/consolidated_report.txt", "r") or die("Unable to open file!");
		//$fulltablebtsdetails = fread($myfile,filesize("Sample_files/consolidated_report.txt"));
		//fclose($myfile); 
		$msg= $heading."<p style='padding: 15px;'></p>".$html;
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
			mail($reciver_emails,"ALERT - TEE Envelop Count" ,$msg,$headers);
			
		}
		mysql_close();
	}