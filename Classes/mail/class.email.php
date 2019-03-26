<?php
class _email_function
{
	public $html;
	public $receiver_group;
	public $heading;
	public function fire_email()
	{
		date_default_timezone_set("Asia/Bahrain"); 
		$msg= "<p style='padding: 15px;'></p>".$this -> html;
		$headers = "MIME-Version: 1.0" . "\r\n"; 
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 
		$headers .= "From: simon@aletimad.com" . "\r\n"; 
			
		$reciver_emails="";
		if($this -> receiver_group == "alert")
		{
			$get_rcvr = "select email_address from email_receivers where alert_mail=1 and mission_id='".$_SESSION['mission_id']."'";
		}
		else if($this -> receiver_group == "cash_sheet")
		{
			$get_rcvr = "select email_address from email_receivers where cash_sheet_mail=1 and mission_id='".$_SESSION['mission_id']."'";
		}
		$get_rcvr1=mysql_query($get_rcvr);
		$cnt_emails=mysql_num_rows($get_rcvr1);
		if($cnt_emails >0)
		{
			while($mailids = mysql_fetch_array($get_rcvr1))
			{
				$reciver_emails=$reciver_emails .",".$mailids['email_address'];
			}
			$reciver_emails = ltrim($reciver_emails,',');
			if(mail($reciver_emails,$this->heading ,$msg,$headers))
			{
				return "sent";
			}
			else
			{
				return "failed";
			}
		}
		else
		{
			return "noreceivers";
		}
	}
}
?>