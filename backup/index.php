<?php 
	//error_reporting(0);
define('CHECK_INCLUDED', true);

require_once 'include/conf.php';
require_once 'include/functions.php';
require 'include/libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/**
 * validate-app
 * url - /validate-app
 * method - POST
 * params - app_id
 */
$app->get('/', function() use ($app) {
$response["e"] = ERROR;
ReturnResponse(200, $response);
});
$app->post('/validate-app', function() use ($app) {
	// define response array 
	$response = array();
	//add your class, if required
	require_once dirname(__FILE__) . '/include/class/class_validate_app.php';
	$validate_app = new Validate_app();
	$app_id=$app->request()->post('app_id');
	$imei=$app->request()->post('imei');
	$validate = $validate_app->validate_app($app_id,$imei);
	
	if($validate){
	//  success
			$response["e"] = NO_ERROR;
		
	} else {

	//  error occurred
		$response["e"] = ERROR;
		
		
	}
	ReturnResponse(200, $response);
});

$app->post('/vehicle-loc-logs', function() use ($app) {
	$app_key=$app->request()->post('app_id');
	$lat=$app->request()->post('lt');
	$lng=$app->request()->post('lg');
	$td=$app->request()->post('td');
	//$response['res']=$app_key.' '.$lat.' '.$lng.' '.$td;
	//add your class, if required
	require_once dirname(__FILE__) . '/include/class/class_vehicle_location_log.php';
	require_once dirname(__FILE__) . '/include/class/class_notifications.php';
	require_once dirname(__FILE__) . '/include/class/class_trip.php';
	require_once dirname(__FILE__) . '/include/class/class_driver.php';
	$Driver = new Driver();
	$VehicleLocLog = new VehicleLocationLog();
	$Notifications = new Notifications();
	$Trip = new Trip();	
	

	if($td==LOG_LOCATION){
		$result=$VehicleLocLog->logLocation($app_key,$lat,$lng,$id='-1');
	}else if($td==LOG_LOCATION_AND_TRIP_DETAILS){
		
		$trip_from_lat						=	$app->request()->post('lts');
		$trip_from_lng						=	$app->request()->post('lgs');
		$trip_to_lat						=	$app->request()->post('lte');
		$dataArray['distance_in_km']		=	$app->request()->post('dt');
		$trip_to_lng						=	$app->request()->post('lge');
		$dataArray['trip_start_date_time']	=	$app->request()->post('srt');
		$dataArray['trip_end_date_time']	=	$app->request()->post('end');
		$dataArray['trip_status_id']		=	TRIP_STATUS_COMPLETED;
		$id									=	$app->request()->post('tid');
		$driver_status						=	DRIVER_STATUS_ACTIVE;

		$Trip->update($dataArray,$id);	
		$Driver->changeStatus($app_key,$driver_status);		
		
		$VehicleLocLog->logLocation($app_key,$trip_from_lat,$trip_from_lng,$id);
		$VehicleLocLog->logLocation($app_key,$trip_to_lat,$trip_to_lng,$id);
		$VehicleLocLog->logLocation($app_key,$lat,$lng,$id='-1');
	}

	$newtrips			=	$Notifications->tripNotifications($app_key); 
	$canceledtrips		=	$Notifications->tripCancelNotifications($app_key); 
	$updatedtrips		=	$Notifications->tripUpdateNotifications($app_key); 
	
	$td_for_array=1;

	
	if($canceledtrips!=false && count($canceledtrips)>=1){

		$td_for_array=$td_for_array*CANCEL_TRIP;

	}

	if($updatedtrips!=false && count($updatedtrips)>=1){

		$td_for_array=$td_for_array*UPDATE_FUTURE_TRIP;

	}


	
	if($newtrips!=false){
		if($newtrips['trip_id'] > gINVALID){
			$trips=$Trip->getDetails($newtrips['trip_id']);
			if($trips!=false){
			if($trips['trip_type_id']==INSTANT_TRIP){
			$td_for_array=$td_for_array*NEW_INSTANT_TRIP;
			$response['td']=$td_for_array;
			$response['nct']=array('fr'=>$trips['trip_from'],'nid'=>$newtrips['id'],'sec'=>strtotime($trips['pick_up_date'].' '.$trips['pick_up_time']),'tid'=>$trips['id'],'to'=>$trips['trip_to']);
			
			}if($trips['trip_type_id']==FUTURE_TRIP){
			$td_for_array=$td_for_array*NEW_FUTURE_TRIP;
			$response['td']=$td_for_array;
			$response['nft']=array('fr'=>$trips['trip_from'],'nid'=>$newtrips['id'],'sec'=>strtotime($trips['pick_up_date'].' '.$trips['pick_up_time']),'tid'=>$trips['id'],'to'=>$trips['trip_to']);
			
					
			}	
				$data=array('notification_status_id'=>NOTIFICATION_STATUS_NOTIFIED);
				$Notifications->updateNotifications($data,$newtrips['id']);

				$driver_status=DRIVER_STATUS_ENGAGED;
				$Driver->changeStatus($app_key,$driver_status);	
			}
		}
	}else{
	
		$td_for_array=$td_for_array*NO_NEW_TRIP;
		$response['td']=$td_for_array;

	} 

	if($canceledtrips!=false){
		$response['clt']=$canceledtrips;
	}
	
	if($updatedtrips!=false){
		for($updated_trips_index=0;$updated_trips_index<count($updatedtrips);$updated_trips_index++){
			$trips=$Trip->getDetails($updatedtrips[$updated_trips_index]);	
				if($trips!=false){
				$trips_updated[$updated_trips_index]=array('fr'=>$trips['trip_from'],'sec'=>strtotime($trips['pick_up_date'].' '.$trips['pick_up_time']),'tid'=>$trips['id'],'to'=>$trips['trip_to']);
				}
			
			}
			$response['upt']=$trips_updated;
		}
	
	ReturnResponse(200, $response);
});

$app->post('/reset', function() use ($app) {
	$app_key=$app->request()->post('app_id');
	$driver_status=DRIVER_STATUS_ACTIVE;
	//add your class, if required
	require_once dirname(__FILE__) . '/include/class/class_driver.php';
	$Driver = new Driver();
	$res=$Driver->changeStatus($app_key,$driver_status);
	if($res==true){
		$response["e"]=NO_ERROR;
	}else{
		$response["e"]=ERROR;
	}

	ReturnResponse(200, $response);
});

$app->post('/user-responds', function() use ($app) {
	$app_key=$app->request()->post('app_id');
	$trip_id=$app->request()->post('tid');
	$notification_id=$app->request()->post('nid');
	$ac=$app->request()->post('ac');
	//add your class, if required
	require_once dirname(__FILE__) . '/include/class/class_driver.php';
	$Driver = new Driver();
	require_once dirname(__FILE__) . '/include/class/class_notifications.php';
	require_once dirname(__FILE__) . '/include/class/class_trip.php';
	$Notifications = new Notifications();
	$Trip = new Trip();	
	if($ac==TRIP_NOTIFICATION_REJECTED){
		$data=array('notification_status_id'=>NOTIFICATION_STATUS_RESPONDED,'notification_view_status_id'=>NOTIFICATION_VIEWED_STATUS);
		$Notifications->updateNotifications($data,$notification_id);
		$response['ac']=TRIP_REJECTED;
		$driver_status=DRIVER_STATUS_ACTIVE;
		$Driver->changeStatus($app_key,$driver_status);

	}else if($ac==TRIP_NOTIFICATION_ACCEPTED){
		$data=array('notification_status_id'=>NOTIFICATION_STATUS_RESPONDED,'notification_view_status_id'=>NOTIFICATION_VIEWED_STATUS);
		$Notifications->updateNotifications($data,$notification_id);
		$trips=$Trip->getDetails($trip_id);
		if($trips['driver_id']==gINVALID){
			$driver_id=$Driver->getDriver($app_key);
			if($driver_id!=false){
				$dataArray=array('driver_id'=>$driver_id['id']);
				$res=$Trip->update($dataArray,$trip_id);
				if($res==true){
					require_once dirname(__FILE__) . '/include/class/class_customer.php';
					$Customer = new Customer();
					$Customers=$Customer->getUserById($trips['customer_id']);
					$response['ac']=TRIP_AWARDED;
					$response['cn']=$Customers['name'];
					$response['cm']=$Customers['mobile'];
					if($trips['trip_type_id']==FUTURE_TRIP){
						$driver_status=DRIVER_STATUS_ACTIVE;
						$Driver->changeStatus($app_key,$driver_status);
					}
				}else{
					$response['ac']=TRIP_ERROR;
				}		
			}else{
				$response['ac']=TRIP_ERROR;
			}
		}else{
			$response['ac']=TRIP_REGRET;

		}

	}else if($ac==TRIP_NOTIFICATION_TIME_OUT){
		$data=array('notification_status_id'=>NOTIFICATION_STATUS_EXPIRED,'notification_view_status_id'=>NOTIFICATION_NOT_VIEWED_STATUS);
		$Notifications->updateNotifications($data,$notification_id);
		$response['ac']=TRIP_TIME_OUT;
		$driver_status=DRIVER_STATUS_ACTIVE;
		$Driver->changeStatus($app_key,$driver_status);
	}
	ReturnResponse(200, $response);
});


function checkFutureOrInstantTrip($tripdatetime){

$date1 = date_create(date('Y-m-d H:i:s'));
$date2 = date_create($tripdatetime);
$diff= date_diff($date1, $date2);
if(($diff->d == 0 && ($diff->h==0 || $diff->i > 30)) || ($diff->d == 0 && ($diff->h > 0)) || $diff->d > 0) {

return INSTANT_TRIP;

}else{

return FUTURE_TRIP;

}

}


function ReturnResponse($http_response, $response) {
	//return response : json
    $app = \Slim\Slim::getInstance();
    $app->status($http_response);
    $app->contentType('application/json');
    echo json_encode($response);
}

$app->run();
?>
