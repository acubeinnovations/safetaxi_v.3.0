<?php 
class Sms{

function sendSms($phone,$message){

//$url="http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=".$phone."&msg=".urlencode($message)."&msg_type=TEXT&userid=2000136385&auth_scheme=plain&password=sms13k&v=1.1&format=text"; OLD ONE

$url="http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=".$phone."&msg=".rawurlencode($message)."&msg_type=TEXT&userid=2000133033&auth_scheme=plain&password=7Xncj5YhG&v=1.1&format=text&mask=SFTAXI";

		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output=curl_exec($ch);
		curl_close($ch);                                
		return $output;

}

}
?>
