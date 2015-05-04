<?php
/**********************************************
Author: Swapna
Name: Purchase
Free software under GNU GPL
***********************************************/

$path_to_root = "../..";

include_once($path_to_root . "/purchasing/includes/db/purchasing_db.inc");


function purchase_all($filter, $from = null) {

	if ($from == null) $from = 0;

	$info = array();

	$sql = get_sql_for_supplier_inquiry($filter, '12/16/2014', '01/15/2015');

	$query = db_query($sql, "error");

	while($purchase = db_fetch($query, "error")) {
		$info[] = $purchase;
	}

	api_success_response(json_encode($info));
}


function purchase_search() {

	$app	= \Slim\Slim::getInstance('SASYS');
	$req	= $app->request();
	$data	= $req->post();

	if(isset($data['from_date']) && isset($data['to_date'])){
		$todate = $data['to_date'];
		$fromdate = $data['from_date'];
	}else if(isset($data['from_date'])){
		$fromdate = $data['from_date'];
		$todate = add_days($fromdate, 30);
		
	}else if(isset($data['to_date'])){
		$todate = $data['to_date'];
		$fromdate = add_days($todate, -30);
	}else{
		$todate = Today();
		$fromdate = add_days($todate, -30);
	}
	


	$sql = get_sql_for_supplier_inquiry($data['filter'], $fromdate, $todate, ALL_TEXT,$data['loc_code']);

	$query = db_query($sql, "error");

	while($purchase = db_fetch($query, "error")) {
		$info[] = $purchase;
	}

	api_success_response(json_encode($info));
}

?>
