<?php
function logvehiclelocations($conn,$app_key,$lat,$lng,$id)
{
	if($id!='-1')
	{
		$sql = "INSERT INTO trip_vehicle_locations_logs (app_key, lat, lng,trip_id,created) VALUES ('".$app_key."', '".$lat."', '".$lng."', '".$id."', '".date('Y-m-d H:i:s')."')";
	
	}
	else if($id=='-1')
	{	
		$sql ="INSERT INTO vehicle_locations_logs( app_key, lat, lng, trip_id, created )
		VALUES (
		'".$app_key."', '".$lat."' , '".$lng."', '".$id."' , '".date('Y-m-d H:i:s')."'
		) ON DUPLICATE
		KEY UPDATE id=LAST_INSERT_ID(id), lat = VALUES (lat), lng =VALUES (lng), trip_id =VALUES (trip_id), created =VALUES (created)";	
	}
	
	//$conn->query($sql);
	mysql_query($sql);	
}



/*

function locarray($input){

$servername = "localhost";
$username = "safe789_user789";
$password = "Winner!2#4";
$dbname = "safe789_server";

$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "INSERT INTO locarray (input)
VALUES ('".$input."')";
$conn->query($sql);

$conn->close();
}

*/


?>
