<?php
class _copyFromExcel_inscan
{
	public $datacopied_inscan;
	public function copyAndInsertInscan()
	{
		date_default_timezone_set("Asia/Bahrain");
		$date_today = date('Y-m-d');
		$data = preg_split("/[\r\n]+/", $this->datacopied_inscan, -1, PREG_SPLIT_NO_EMPTY);
		$msg ="";
		$err="";
		$total_inscan=0;
		$existing_gwfs="";
		$inscanned_before="";
		$wrong_status = "";
		$resubmission_error="";
		$rectification_list="";
		$resubmission_ref_no="";
		
		foreach($data as $key)
      	{
        	if( strpos($key, "\t\t" ) !== false ) //replaces all blank tabs
          	{
             	$key_replacedTab = str_replace("\t\t", "\t",$key);
        	}
			else
			{
				$key_replacedTab = $key;
			}
         	$each_row=explode("\t",$key_replacedTab);
        	//$each_row[0] = gwf Number 
			$gwf = strtoupper(trim($each_row[0]));
			$query = mysql_query("SELECT id,gwf_number,current_status FROM passport_inscan_record WHERE gwf_number = '".$gwf."' and mission_id='".$_SESSION['mission_id']."'");
			if(mysql_num_rows($query) >0) 
			{
				$recResult = mysql_fetch_array($query);
				$existGWF = $recResult["gwf_number"];
				if($recResult["current_status"]=="outscan")
				{
					$qr = "update passport_inscan_record set date_inscan = '".$date_today."',current_status = 'inscan' where gwf_number='".$gwf."' and mission_id='".$_SESSION['mission_id']."'";
					//Check if this gwf need passport resubmission
					$get_resubmsn = mysql_query("select id,status from passport_resubmission where actual_ref_id='".$recResult['id']."' and mission_id='".$_SESSION['mission_id']."'");
					if(mysql_num_rows($get_resubmsn)>0)
					{
						$details=mysql_fetch_array($get_resubmsn);
						if($details['status']=="resubmitted")
						{
							$update_resubmission = mysql_query("update passport_resubmission set status='inscan' where actual_ref_id='".$details['id']."' and mission_id='".$_SESSION['mission_id']."'");
							if(mysql_query($qr))
							{
								$total_inscan++;
							}
							else
							{
								$err= $err.mysql_error();
							}
						}
						else
						{
							$resubmission_error=$resubmission_error.$gwf." - Pending passport resubmission<br>";
						}
					}
					else
					{
						if(mysql_query($qr))
						{
							$total_inscan++;
						}
						else
						{
							$err= $err.mysql_error();
						}
					}
				}
				else if($recResult["current_status"]=="inscan")
				{
					$inscanned_before=$inscanned_before.$gwf." - Inscanned Before<br>";
				}
				else if($recResult["current_status"]=="counter_delivery" || $recResult["current_status"]=="courier_Service")
				{
					$inscanned_before=$inscanned_before.$gwf." - Passport delivered<br>";
				}
			}
			else // Rectification
			{
				//If not existing in passport_inscan_records, check in Rectification and resubmission tables
				$chk_rectification =mysql_query("select * from rectification_cancellation where gwf_number='".$gwf."' and mission_id='".$_SESSION['mission_id']."'");
				$chk_resubmission = mysql_query("select actual_ref_id from passport_resubmission where new_ref_number='".$gwf."' and mission_id='".$_SESSION['mission_id']."'");
				if(mysql_num_rows($chk_rectification) >0)
				{
					$rectification_list=$rectification_list.$gwf." - Rectification<br> ";
				}
				else if(mysql_num_rows($chk_resubmission) >0)
				{
					$old_ref_id= mysql_fetch_array($chk_resubmission);
					$new_ref=mysql_fetch_array(mysql_query("select gwf_number from passport_inscan_record where id='".$old_ref_id['actual_ref_id']."' and mission_id='".$_SESSION['mission_id']."'"));
					$resubmission_ref_no = $resubmission_ref_no.$gwf." - Resubmission Ref. number for - ".$new_ref['gwf_number']."<br>";
				}
			}
		}//End For Each
		//Return Values
		if($err=="")
		{
			$msg='<font color="#009900">'.$total_inscan. ' Records has been Imported.</font><br>';
		}
		else
		{
			$msg='<font color="#009900">'.$total_inscan. ' Records has been Imported. Errors:'.$err.' </font><br>';
		}
		$full_return = $msg.$resubmission_error.$inscanned_before.$rectification_list.$resubmission_ref_no;
		return $full_return;
	}
}


?>