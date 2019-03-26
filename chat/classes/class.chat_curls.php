<?php
class _curls_chat
{
	public function chat_login($login_id,$password)
	{
		$service_url = 'http://192.168.31.130:3000/api/login/';
		$curl = curl_init($service_url);
		$curl_post_data = array("password" => $password,"user" => $login_id,);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($curl_post_data));
		$curl_response = curl_exec($curl);
		$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		//$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		//$header = substr($curl_response, 0, $header_size);
		//$body = substr($curl_response, $header_size);
		curl_close($curl);
		return $http_status;
	}
}
?>