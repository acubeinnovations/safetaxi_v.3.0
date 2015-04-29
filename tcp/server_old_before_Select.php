<?php
include_once('db.php');
date_default_timezone_set('Asia/Kolkata');

$servername = "localhost";
$username = "safe789_user789";
$password = "Winner!2#4";
$dbname = "safe789_server";

	$conn = new mysqli($servername, $username, $password, $dbname);
	
	$socket = stream_socket_server("tcp://162.144.57.243:10000", $errno, $errstr);
	if (!$socket) 
	{
	  echo "$errstr ($errno)\n";
	} 
	else 
	{
	  	while(true)
		{
			try
			{
				$client = stream_socket_accept($socket,-1);
				stream_set_blocking($client,1);
				stream_set_timeout($client, 60);
				$info = stream_get_meta_data($client);
				
				if (!feof($client) && !$info['timed_out'])
				{
					$input = fgets($client, 1024);
					if(!isset($input))
					{
						fclose($client);
						continue;
					}						
					
					if($input)
					{	
						$json_res = json_decode($input);
						if(isset($json_res->app_id) && isset($json_res->lt) && isset($json_res->lg) && isset($json_res->tid)) 
						{
							$app_key= $json_res->app_id;
							$lat= $json_res->lt;
							$lng= $json_res->lg;
							$tid= $json_res->tid;
							logvehiclelocations($conn,$app_key,$lat,$lng,$tid);
						}
					}	
				}			
					
				fclose($client);
			}
			catch(Exception $e)
			{
				if($client)
					fclose($client);
				continue;
			}		  		
		}
		
		$conn->close();
		fclose($socket);
	}
?>