<?php
$host = "162.144.57.243";
$port = 10000;
$message = "Hello Client your message is ";
set_time_limit(0);
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");

// bind socket to port
$result = socket_bind($socket, $host, $port) or die("Could not bind to socket\n");
// put server into passive state and listen for connections
$result = socket_listen($socket, 3) or die("Could not set up socket listener\n");

while(true){
    $com = socket_accept($socket) or die("Could not accept incoming connection\n");
if($com){
// read client input
$input = socket_read($com, 1024); 
//or die("Could not read input\n");
// clean up input string
//print_r($input);
$resp_arr = array(
              'client_msg'=>trim($input),
              'time'=>date("F j, Y, g:i a"),
              'server_ip'=>$host,
              'port'=>$port
              //'msg'=>file_get_contents('message.info')
              );
$json_res = json_encode($resp_arr);
$file = fopen("test.txt","a+");
fwrite($file,$json_res."\n");
fclose($file);
//echo "Client says: ".$input."</br>";
socket_write($com, $json_res, strlen ($json_res)) or die("Could not write output\n");
}
}
// close sockets
socket_close($com);
socket_close($socket);
?>
