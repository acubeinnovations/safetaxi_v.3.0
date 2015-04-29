<?php
// prevent execution of this page by direct call by browser
if ( !defined('CHECK_INCLUDED') ){
	exit();
}

class Sms {
	private $connection;
	public  $error_description;

	function __construct() {
		require_once dirname(__FILE__) . '/class_connection.php';
		$db = New Connection();
		$this->connection = $db->connect();
	}

	public function send_sms($mobile,$message)
	{

	$url="http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=".$mobile."&msg=".rawurlencode($message)."&msg_type=TEXT&userid=2000133033&auth_scheme=plain&password=7Xncj5YhG&v=1.1&format=text&mask=SFTAXI";

		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output=curl_exec($ch);
		curl_close($ch);                               
		return $output;
	}
    
}
?>
