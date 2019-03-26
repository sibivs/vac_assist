<?php
$ch = $_REQUEST['opt'];
switch($ch)
{
	case 1:
		curl_login_chat();	
		break;	
}


function curl_login_chat()
{
	$login_id = $_REQUEST['login_id_chat'];
	$password = $_REQUEST['password_chat'];
	require_once("classes/class.chat_curls.php");
	$chat_login = new _curls_chat();
	$chat_login_status = $chat_login ->chat_login($login_id,$password);
	print json_encode($chat_login_status);
}
?>