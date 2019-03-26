<?php
class _import_Inventory_TEEs
{
	public $datacopied_tee_import;
	public function import_Inventory_TEEs_Function()
	{
		date_default_timezone_set("Asia/Bahrain");
		$date_today = date('Y-m-d');
		$data = preg_split("/[\r\n]+/", $this->datacopied_tee_import, -1, PREG_SPLIT_NO_EMPTY);
		$msg ="";
		$insert=0;
		$insert_error="";
		$inscanned_before="";
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
        	//$each_row[0] = TEE Barcode 
			$teebarcode = strtoupper(trim($each_row[0]));
			$query = "SELECT barcode_no,status FROM tee_envelop_counts WHERE barcode_no = '".$teebarcode."' and mission_id='".$_SESSION['mission_id']."'";
			$sql = mysql_query($query);
			$recResult = mysql_num_rows($sql);
			if($recResult ==0) 
			{
				$qr = "insert into tee_envelop_counts values (DEFAULT,'small','$teebarcode','','available','','".$_SESSION['mission_id']."')";
				if(mysql_query($qr))
				{
					$insert++;
				}
				else
				{
					$insert_error=$insert_error.mysql_error()."<br>";
				}
			}
			else
			{
				$inscanned_before=$inscanned_before.$teebarcode." - Already Imported<br>";
			}
		}//End For Each
		//Return Values
		if($insert_error=="")
		{
			$msg='<font color="#009900">'.$insert. ' Records has been Imported.</font><br>';
		}
		else
		{
			$msg='<font color="#009900">'.$insert. ' Records has been Imported. Errors:'.$insert_error.' </font><br>';
		}
		$full_return = $msg.$inscanned_before;
		return $full_return;
	}
}


?>