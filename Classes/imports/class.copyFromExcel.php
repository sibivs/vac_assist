<?php
class _copyFromExcel
{
	public $datacopied;
	public $appointment_type;
	public function copyAndInsertAppointment()
	{
		 $date_today = date('Y-m-d');
		 $data = preg_split("/[\r\n]+/", $this->datacopied, -1, PREG_SPLIT_NO_EMPTY);
		 //var_dump($import_obj_status);
         $added_already="";
		 $qr=mysql_query("update appointment_schedule set application_status = 'cancelled' where (application_status = 'scheduled' OR application_status = 'shown_up') and mission_id='".$_SESSION['mission_id']."' and scheduled_date_app NOT IN ('".date('Y-m-d')."')");
		foreach($data as $key)
      	{
        	if( strpos($key, "\t\t" ) !== false ) //replaces all blank tabs
          	{
             	$key_replacedTab = str_replace("\t\t", "\t",$key);
        	}
         	$each_row=explode("\t",$key_replacedTab);
        	//$each_row[0] = Time , $each_row[1] = Visa Category, $each_row[2] = Reference Number
			//echo $each_row[0]."--".$each_row[1]."--".$each_row[2]."<br>";
        	//check if the reference number existing or submitted
         		$chk_for_cancelled = mysql_num_rows(mysql_query("select * from appointment_schedule where reference_number='".$each_row[2]."' and mission_id='".$_SESSION['mission_id']."' and application_status NOT IN('outscan')"));
			if($chk_for_cancelled >0)
			{
				mysql_query("delete from appointment_schedule where reference_number='".$each_row[2]."' and mission_id='".$_SESSION['mission_id']."' and application_status NOT IN('outscan')");
			}
			$get_existing_appointment = mysql_num_rows(mysql_query("select * from appointment_schedule where reference_number='".$each_row[2]."' and mission_id='".$_SESSION['mission_id']."'"));
			if($get_existing_appointment ==0)
			{
				//$qr = "insert into appointment_schedule values (DEFAULT,'0','$gwf','$date_today','$date_today','$appointment_time','$app_category','$lounge_type','$visa_type','scheduled','','".$_SESSION['mission_id']."')";	
				$add_appointment = mysql_query("insert into appointment_schedule values (DEFAULT,'0','".$each_row[2]."','".date('Y-m-d')."','".date('Y-m-d')."','".$each_row[0]."','".strtoupper($this->appointment_type)."','".$this->appointment_type."','".strtoupper($each_row[1])."','scheduled','','".$_SESSION['mission_id']."')");	
				//Add appointment
			}
			else
			{
				//Update already outscanned application which has appointment today as appointment date today=> for current appointment status popup
				$update_already_submitted = mysql_query("update appointment_schedule set scheduled_date_app='".date('Y-m-d')."' where reference_number='".$each_row[2]."' and mission_id='".$_SESSION['mission_id']."' and application_status ='outscan'");
				
				$added_already = $added_already.",".$each_row[2];
				//Already added
			}
 		}
		if(strlen($added_already) <=0)
		{
			return "added";
		}
		else
		{
			return $added_already;
		}
	}
}


?>