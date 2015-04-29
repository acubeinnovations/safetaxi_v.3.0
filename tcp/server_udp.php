<?php
set_time_limit(0);
include_once('db.php');
// The time the loop started
date_default_timezone_set('Asia/Kolkata');

$servername = "localhost";
$username = "safe789_user789";
$password = "Winner!2#4";
$dbname = "safe789_server";
$server = NULL;

	if (!$conn = mysql_connect($servername, $username, $password)) {
		echo 'Could not connect to mysql';
		exit;
	}
	
	if (!mysql_select_db($dbname, $conn)) {
		echo 'Could not select database';
		exit;
	}
	
	$server = stream_socket_server("udp://162.144.57.243:10000/", $errno, $errstr, STREAM_SERVER_BIND);
	if (!$server) 
	{
	  echo "$errstr ($errno)\n";
	} 
	else 
	{
		while(true)
		{			
			try
			{
				if (!mysql_ping($conn))
				{
				   mysql_close($conn);
				   $conn = mysql_connect($servername, $username, $password);
				   mysql_select_db($dbname,$conn);
				}				
				if($server == NULL)
					$server = stream_socket_server("udp://162.144.57.243:10000/", $errno, $errstr, STREAM_SERVER_BIND);
					
				$data = stream_socket_recvfrom($server, 128, 0, $peer); 			
				//var_dump($data);
				
				$json_res = json_decode($data);
				if(isset($json_res->app_id) && isset($json_res->lt) && isset($json_res->lg) && isset($json_res->tid)) 
				{
					$app_key= $json_res->app_id;
					$lat= $json_res->lt;
					$lng= $json_res->lg;
					$tid= $json_res->tid;
					logvehiclelocations($conn,$app_key,$lat,$lng,$tid);
				}							
			}
			catch(Exception $e)
			{							
				fclose($server);
				continue;
			}		  		
		}
		
		mysql_close($conn);
		fclose($server);
	}	
?>