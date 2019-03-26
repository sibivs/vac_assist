<?php
	include_once("db_connect.php");
	date_default_timezone_set("Asia/Bahrain");
	$interval = $_REQUEST['intvl'];
	$date = date('Y-m-d');
	$timenow=date('H:i:s');
	$sendemail=0;
	$tokens_foremail = "<table style='width: 800px;border: 1px solid black; border-collapse: collapse;'><thead style='border: 1px solid black; border-collapse: collapse; font-weight: bold; color: red;'><tr style='border: 1px solid black; border-collapse: collapse;'><td style='padding: 8px; border: 1px solid black; border-collapse: collapse;'>Reference No</td><td style='padding: 8px; border: 1px solid black; border-collapse: collapse;'>Token No</td><td style='padding: 8px; border: 1px solid black; border-collapse: collapse;'>Waiting Time</td><td>Waiting For</td></tr></thead><tbody>";
	$tokens_foremail_end = "</tbody></table>";
	$query_get_pending = mysql_query("SELECT st.token_no as token_no, (TIME_TO_SEC(TIMEDIFF('$timenow', st.ao_finish_at))/60) as actual_wait, TIMEDIFF('$timenow', st.ao_finish_at) as wait_hours, a.reference_number as reference FROM submission_timing st , appointment_schedule a WHERE st.reference_id=a.id AND a.application_status = 'shown_up' AND st.date_created = '$date' AND (TIME_TO_SEC(TIMEDIFF('$timenow', st.ao_finish_at))/60) >= '$interval' and st.mission_id='".$_SESSION['mission_id']."'  ORDER BY st.token_no");
	
	$query_get_pending_bio = mysql_query("SELECT st.token_no as token_no ,(TIME_TO_SEC(TIMEDIFF('$timenow', st.ro_finish_at))/60) as actual_wait , TIMEDIFF('$timenow', st.ao_finish_at) as wait_hours, a.reference_number as reference FROM submission_timing st , appointment_schedule a WHERE st.reference_id=a.id AND a.application_status = 'ro_complete' AND st.date_created = '$date' AND st.ro_finish_at !='00:00:00' AND (TIME_TO_SEC(TIMEDIFF('$timenow', st.ro_finish_at))/60) >= '$interval' and st.mission_id='".$_SESSION['mission_id']."' ORDER BY st.token_no");
	if(mysql_num_rows($query_get_pending) > 0)
	{
		while($res_tokens = mysql_fetch_array($query_get_pending))
		{
			if($res_tokens['actual_wait'] >= 25)
			{
				$tokens_foremail=$tokens_foremail."<tr style='border: 1px solid black; border-collapse: collapse;'><td style='padding: 8px; border: 1px solid black; border-collapse: collapse;'>".$res_tokens['reference']."</td><td style='padding: 8px; border: 1px solid black; border-collapse: collapse;'>".$res_tokens['token_no']."</td><td style='padding: 8px; border: 1px solid black; border-collapse: collapse;'>".($res_tokens['wait_hours'])." Hours</td><td style='padding: 8px; border: 1px solid black; border-collapse: collapse;'>Submission</td></tr>";
				$sendemail = 1;
			}
		}
	}
	
	if(mysql_num_rows($query_get_pending_bio) > 0)
	{
		while($res_tokens_bio = mysql_fetch_array($query_get_pending_bio))
		{
			if($res_tokens_bio['actual_wait'] >= 25)
			{
				$tokens_foremail=$tokens_foremail."<tr style='border: 1px solid black; border-collapse: collapse;'><td style='padding: 8px; border: 1px solid black; border-collapse: collapse;'>".$res_tokens_bio['reference']."</td><td style='padding: 8px; border: 1px solid black; border-collapse: collapse;'>".$res_tokens_bio['token_no']."</td><td style='padding: 8px; border: 1px solid black; border-collapse: collapse;'>".$res_tokens_bio['wait_hours']." Hours</td><td style='padding: 8px; border: 1px solid black; border-collapse: collapse;'>Biometrics</td></tr>";
				$sendemail = 1;
			}
		}
	}
	
	if($sendemail == 1)
	{
		date_default_timezone_set("Asia/Bahrain");
		$heading = "<div align='center' style='font-weight: bold; color: blue; font-weight: 16px;'><p>Applicants waiting for Submission Or Biometrics  :</p><p></p><p></p></div>";
		$recievers="";
		$msg= $heading."<p style='padding: 15px;'></p>".$tokens_foremail.$tokens_foremail_end;
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
			mail($reciver_emails,"Applicants waiting:" ,$msg,$headers);
		}
	}
?>