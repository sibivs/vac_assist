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
    	<div align="center" style="font-weight: bold; color: blue; font-weight: 16px;"><p><u>Passport Status Alert</u></div>
    </div>';
       $msg1_2='<table class="table table-bordered" style="width: 80%;">
            <thead class="text-info h4">
                <tr>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>Sl.No</h4></b></td>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>Reference Number</h4></b></td>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>Date Outscan</h4></b></td>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>Date Inscan</h4></b></td>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>VAS Selected</h4></b></td>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>Days Elapsed</h4></b></td>
                    <td style="border: 1px solid; border-collapse: collapse;"><b><h4>Current Status</h4></b></td>
                </tr>
            </thead>
            <tbody>';
                include_once("db_connect.php");
                $cnt=0;
                $query_get_pricelist=mysql_query("select * from price_list where current_status='active' and alert_if_status_is != ''");
                while($price_list=mysql_fetch_array($query_get_pricelist))//Getting all services require alert email
                {
                    $alert_after_days=$price_list['alert_after'];
                    $alert_if_status_is=$price_list['alert_if_status_is'];
                    $vas_check_string=$price_list['id']."_";
                    $vas_selected = $price_list['service_name'];
                    $get_reference_nmbr=mysql_query("select gwf_number,date_inscan,date_outscan,current_status from passport_inscan_record where current_status='$alert_if_status_is' and mission_id='".$_SESSION['mission_id']."' and INSTR(vas_entries, '$vas_check_string') > 0");
                    //if(mysql_num_rows($get_reference_nmbr)>0)
					//{
						while($result_in_current_status=mysql_fetch_array($get_reference_nmbr))
						{
							//getting all ref.nos which is in current status for more than allowed time
							if($alert_if_status_is=='inscan')
							{
								$get_ref_nos_cross_limit = mysql_query("select a.gwf_number as gwf_number ,a.date_inscan as date_inscan,a.date_outscan as date_outscan,b.barcode_no as barcode_no, a.current_status as current_status, DATEDIFF(now(),a.date_inscan) as days from passport_inscan_record a, tee_envelop_counts b where DATEDIFF(now(),a.date_inscan) >= $alert_after_days  and a.gwf_number='".$result_in_current_status['gwf_number']."' and a.glfs_id=b.id and a.mission_id='".$_SESSION['mission_id']."' ");
							}
							else if($alert_if_status_is=='outscan')
							{
								$get_ref_nos_cross_limit = mysql_query("select a.gwf_number as gwf_number ,a.date_inscan as date_inscan,a.date_outscan as date_outscan,b.barcode_no as barcode_no, a.current_status as current_status, DATEDIFF(now(),a.date_outscan) as days from passport_inscan_record a, tee_envelop_counts b where DATEDIFF(now(),a.date_outscan) >= $alert_after_days and a.gwf_number='".$result_in_current_status['gwf_number']."' and a.glfs_id=b.id and a.mission_id='".$_SESSION['mission_id']."'");
							}
							if(mysql_num_rows($get_ref_nos_cross_limit)>0)
							{
								$cnt++;
								$result_ref_no_cross_limit=mysql_fetch_array($get_ref_nos_cross_limit);
							   $msg2= $msg2." <tr>
									<td style='border: 1px solid; border-collapse: collapse;'>". $cnt." </td>
									<td style='border: 1px solid; border-collapse: collapse;'>". $result_ref_no_cross_limit['gwf_number']." </td>
									<td style='border: 1px solid; border-collapse: collapse;'>". $result_ref_no_cross_limit['date_outscan']." </td>";
									if($result_ref_no_cross_limit['date_inscan']=="0000-00-00")
									{
										$date_inscan="";
									}
									else
									{
										$date_inscan=$result_ref_no_cross_limit['date_inscan'];
									}
									$msg2= $msg2."<td style='border: 1px solid; border-collapse: collapse;'>".$date_inscan ."</td>
									<td style='border: 1px solid; border-collapse: collapse;'>".$vas_selected."</td>
									<td style='border: 1px solid; border-collapse: collapse;'>". $result_ref_no_cross_limit['days'] ."Days</td>";
									if($result_ref_no_cross_limit['current_status']=="inscan")
									{
										$status="Inscan at VAC";
									}
									else if($result_ref_no_cross_limit['current_status']=="outscan")
									{
										$status="Outscan to Embassy";
									}
									$msg2= $msg2."<td style='border: 1px solid; border-collapse: collapse;'>".$status."</td>
								</tr>";
							}
						}
						$msg1=$msg1_1.$msg1_2;
					//}
					//else
					//{
					//	$msg1=$msg1_1;
					//	$msg2= "<label>Looks good, All applications in proper processing time</label>";
					//}
                }
        	$msg3="</tbody>
        </table>
        </div>";
		$footer = "<br><br><br><br><br><br>Regards<br>---<br><div style='color: purple; font-size: 13px; font-weight: bold; height: 50px; vertical-align: bottom;'>Etimad System Admin Team<br><br><br><br></div>Please Note this is a system generated email.";
		$msg= $msg1.$msg2.$msg3.$footer;
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
			mail($reciver_emails,"Passport status alert",$msg,$headers);
		}
		mysql_close();
	$x='</body>
</html>';