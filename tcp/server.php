<?php
set_time_limit(0);
include_once('db.php');
// The time the loop started
date_default_timezone_set('Asia/Kolkata');

$servername = "localhost";
$username = "safe789_user789";
$password = "Winner!2#4";
$dbname = "safe789_server";

	//$conn = new mysqli($servername, $username, $password, $dbname);
	if (!$conn = mysql_connect($servername, $username, $password)) {
		echo 'Could not connect to mysql';
		exit;
	}
	
	if (!mysql_select_db($dbname, $conn)) {
		echo 'Could not select database';
		exit;
	}
	
	$server = stream_socket_server("tcp://162.144.57.243:10000", $errno, $errstr);
	if (!$server) 
	{
	  echo "$errstr ($errno)\n";
	} 
	else 
	{
	  	$client_socks = array();
		//$i=0;
		while(true)
		{
			//$i++;
			//echo $i."--------".date("Y-m-d G:i:s",time())."---------\n";
			//echo "While start\n";
			try
			{
				if (!mysql_ping($conn))
				{
				   mysql_close($conn);
				   $conn = mysql_connect($servername, $username, $password);
				   mysql_select_db($dbname,$conn);
				}	
				
				$read_socks = $client_socks;
				$read_socks[] = $server;
				//start reading and use a large timeout
				if(false === ($num = stream_select ( $read_socks, $write, $except, 60)))
				{
					continue;
				}
				
				if($num > 0)
				{				
					//echo "After Select\n";					
					 
					if(in_array($server, $read_socks))
					{
						$new_client = stream_socket_accept($server);
						//echo "After Accept\n";
						stream_set_blocking($new_client,0);
						if ($new_client) 
						{						 
							$client_socks[] = $new_client;
						}
						 
						unset($read_socks[ array_search($server, $read_socks) ]);
					}
					
					foreach($read_socks as $sock)
					{
						$data = fread($sock, 128);
						//echo "After Read\n";
						
						if(!$data)
						{
							unset($client_socks[ array_search($sock, $client_socks) ]);
							@fclose($sock);
							continue;
						}
						
					//	var_dump($data);
						//echo ">>>>>>>>>>>>>>>>>>>>\n";
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
				}							
			}
			catch(Exception $e)
			{
				if($read_socks)
				{
					foreach($read_socks as $sock)
					{
						@fclose($sock);
					}
				}	
							
				continue;
			}		  		
		}
		
		mysql_close($conn);
		//$conn->close();
		fclose($server);
	}	
?>
