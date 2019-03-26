<?php
class _import_Inventory_oyster
{
	public $datacopied_tee_import;
	public function import_Inventory_TEEs_Function()
	{
		date_default_timezone_set("Asia/Bahrain");
		$date_today = date('Y-m-d');
		$data = preg_split("/[\r\n]+/", $this->datacopied_tee_oyster, -1, PREG_SPLIT_NO_EMPTY);
		$msg ="";
		$insert=0;
		$insert_error="";
		$uploaded_before="";
		$wrong_status = "";
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
        	//$each_row[0] = Oyster card no. 
			$card_no = strtoupper(trim($each_row[0]));
			$query = "SELECT id,card_no,status FROM oyster_card_stock WHERE card_no= '".$card_no."' and mission_id='".$_SESSION['mission_id']."'";
			$sql = mysql_query($query);
			$recResult = mysql_num_rows($sql);
			if($recResult ==0) 
			{
				$date_today=date("Y-m-d");
				$qr = "insert into oyster_card_stock values (DEFAULT,'$card_no','','in_stock','".$_SESSION['mission_id']."','".$date_today."')";
				if(mysql_query($qr))
				{
					$insert++;
				}
				else
				{
					$insert_error=$insert_error.$card_no."- ".mysql_error()."<br>";
				}
			}
			else
			{
				$uploaded_before=$uploaded_before.$card_no." - Uploaded Before<br>";
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
		$full_return = $msg.$uploaded_before;
		return $full_return;
	}
}


?>