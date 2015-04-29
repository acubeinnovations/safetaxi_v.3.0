<?php
include_once('db.php');
date_default_timezone_set('Asia/Kolkata');

$host = "162.144.57.243";
$port = 10000;

$servername = "localhost";
$username = "safe789_user789";
$password = "Winner!2#4";
$dbname = "safe789_server";

$conn = new mysqli($servername, $username, $password, $dbname);

set_time_limit(0);

	$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
	
	// bind socket to port
	$result = socket_bind($socket, $host, $port) or die("Could not bind to socket\n");
	// put server into passive state and listen for connections
	$result = socket_listen($socket) or die("Could not set up socket listener\n");

	while(true)
	{
	   	try
		{
			$client = socket_accept( $socket );
			
			$input = socket_read( $client, 1024000 );
			//locarray($input);	
			if(!isset($input))
			{
				if(isset($client))	
					socket_close($client);
				$client = NULL;	
				continue;
			}	
				
			$json_res = json_decode($input);
			if(!isset($json_res))
			{
				if(isset($client))	
					socket_close($client);
				$client = NULL;	
				continue;	
			}	
			
			if(isset($json_res->app_id) && isset($json_res->lt) && isset($json_res->lg) && isset($json_res->tid)) 
			{
				$app_key= $json_res->app_id;
				$lat= $json_res->lt;
				$lng= $json_res->lg;
				$tid= $json_res->tid;
				logvehiclelocations($conn,$app_key,$lat,$lng,$tid);
			}	
			
			if(isset($client))	
				socket_close($client);
			$client = NULL;			
		}	
		catch(Exception $e)
		{
			continue;
		}	
	}

	if(isset($conn))
		$conn->close();
	if(isset($socket))	
		socket_close($socket);
?>
