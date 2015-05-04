<?php
/**********************************************
Author: Andres Amaya
Name: Suppliers API
Free software under GNU GPL
***********************************************/

$path_to_root = "../..";

include_once($path_to_root . "/sales/includes/db/sales_groups_db.inc");
//include_once($path_to_root . "/includes/db/crm_contacts_db.inc");

/*function supplier_all($from = null) {
	if ($from == null) $from = 0;

	$sql = "SELECT * FROM ".TB_PREF."suppliers  LIMIT ".$from.", ".RESULTS_PER_PAGE;

	$query = db_query($sql, "error");

	$info = array();

	while($data = db_fetch_assoc($query, "error")) {
		$info[] = $data;
	}

	api_success_response(json_encode($info));
}

function supplier_get($id)
{

	$sup = get_supplier($id);
	if(!$sup) $sup = array();
	api_success_response(json_encode($sup));

}*/

function salesman_add()
{

	$app = \Slim\Slim::getInstance('SASYS');
	$req = $app->request();
	$info = $req->post();//api_success_response(json_encode($info));return;
	//print_r($app); exit;
	// Validate Required Fields
	if(!isset($info['salesman_name'])){
		api_error(412, 'Salesman Name  is required [salesman_name]');
	}
	if(!isset($info['salesman_phone'])){
		api_error(412, 'Sales Man  Phone is required [salesman_phone]');
	}
	if(!isset($info['salesman_fax'])){
		api_error(412, 'Sales Man Fax is required [salesman_fax]');
	}
	if(!isset($info['salesman_email'])){
		api_error(412, 'Sales Man Email is required [salesman_email]');
	}
	if(!isset($info['provision'])){
		api_error(412, 'Provision required [provision]');
	}
	if(!isset($info['break_pt'])){
		api_error(412, 'Break Point is required [break_pt]');
	}
	if(!isset($info['provision2'])){
		api_error(412, 'Provision 2 is required [provision2]');
	}
	if(!isset($info['loc_code'])){
		api_error(412, 'Warehouse Code is required [warehouse_code]');
	}
	if(!isset($info['inactive'])){
		api_error(412, 'Inactive is required [inactive]');
	}



	
	add_salesman(
		$info['salesman_name'],
		$info['salesman_phone'],
		$info['salesman_fax'],
		$info['salesman_email'],
		0,
		0,
		0,
		$info['loc_code'],
		$info['inactive']
		);

	$id = db_insert_id();
	

	if($id){
		api_success_response(json_encode($id));
	}else {
		api_error(500, 'Could Not Save to Database');
	}

}


function salesman_edit($id){

	$app = \Slim\Slim::getInstance('SASYS');
	$req = $app->request();
	$info = $req->post();
	


	$sman = get_salesman($id);

	if($sman == null){
		api_error(400, 'Invalid Customer ID');
	}

	if(!isset($info['salesman_name'])){
		api_error(412, 'Salesman Name  is required [salesman_name]');
	}
	if(!isset($info['salesman_phone'])){
		api_error(412, 'Sales Man  Phone is required [salesman_phone]');
	}
	if(!isset($info['salesman_fax'])){
		api_error(412, 'Sales Man Fax is required [salesman_fax]');
	}
	if(!isset($info['salesman_email'])){
		api_error(412, 'Sales Man Email is required [salesman_email]');
	}
	if(!isset($info['provision'])){
		api_error(412, 'Provision required [provision]');
	}
	if(!isset($info['break_pt'])){
		api_error(412, 'Break Point is required [break_pt]');
	}
	if(!isset($info['provision2'])){
		api_error(412, 'Provision 2 is required [provision2]');
	}
	if(!isset($info['loc_code'])){
		api_error(412, 'Warehouse Code is required [warehouse_code]');
	}
	

	$update = update_salesman($id, $info['salesman_name'], $info['salesman_phone'], $info['salesman_fax'], $info['salesman_email'], $info['provision'], $info['break_pt'], $info['provision2'],$info['loc_code']);

api_success_response(json_encode($update));return;
		
	//api_success_response("Salesman has been updated");

}



?>
