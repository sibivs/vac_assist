<?php

$option=$_REQUEST['opt'];
if($option==1)
{
	$path = "Sample_files/existing_gwf_list.txt";
	header("Content-Type: application/octet-stream");
	header("Content-Length: " . filesize($path));    
	header('Content-Disposition: attachment; filename='.$path);
	readfile($path); 
}
else if($option==2)
{
	$path = "Sample_files/not_existing_gwf_list.txt";
	header("Content-Type: application/octet-stream");
	header("Content-Length: " . filesize($path));    
	header('Content-Disposition: attachment; filename='.$path);
	readfile($path); 	
}
else if($option==4)
{
	$path = "Sample_files/wrong_status.txt";
	header("Content-Type: application/octet-stream");
	header("Content-Length: " . filesize($path));    
	header('Content-Disposition: attachment; filename='.$path);
	readfile($path); 	
}
else if($option==5)
{
	$path = "Sample_files/sample_import_file_outscan.xls";
	header("Content-Type: application/octet-stream");
	header("Content-Length: " . filesize($path));    
	header('Content-Disposition: attachment; filename='.$path);
	readfile($path); 	
}
else if($option==7)
{
	$path = "Sample_files/notexisting.txt";
	header("Content-Type: application/octet-stream");
	header("Content-Length: " . filesize($path));    
	header('Content-Disposition: attachment; filename='.$path);
	readfile($path); 	
}


?>