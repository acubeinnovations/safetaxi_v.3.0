<?php
/**********************************************
Author: Andres Amaya
Name: Customers API
Free software under GNU GPL
***********************************************/

$path_to_root = "../..";

include_once($path_to_root . "/sales/includes/db/customers_db.inc");
include_once($path_to_root . "/includes/db/crm_contacts_db.inc");

function customer_all($from = null) {
	if ($from == null) $from = 0;

	$sql = "SELECT * FROM ".TB_PREF."debtors_master WHERE !inactive LIMIT ".$from.", ".RESULTS_PER_PAGE;
	
	$query = db_query($sql, "error");

	$info = array();

	while($data = db_fetch_assoc($query, "error")) {
		$info[] = $data;
	}

	api_success_response(json_encode($info));
}

function customer_get($id)
{

	$cust = get_customer($id);
	if(!$cust) $cust = array();
	api_success_response(json_encode($cust));

}

function customer_add()
{

	$app = \Slim\Slim::getInstance('SASYS');
	$req = $app->request();
	$info = $req->post();
	//api_success_response(json_encode($info));

	//default values
	global $SysPrefs;
	$info['tax_id'] = '';	
	$info['curr_code'] = get_company_currency();
	$info['credit_status']  = -1;
	$info['payment_terms'] = 4;//cash only
	$info['discount'] = $info['pymt_discount'] = 0;
	$info['credit_limit'] = $SysPrefs->default_credit_limit();
	$info['sales_type'] = -1;

	$info['dimension_id'] = 0;
	$info['dimension2_id'] = 0;

	// Validate Required Fields
	if(!isset($info['custname'])){
		api_error(412, 'Customer Name is required [custname]');
	}
	if(!isset($info['cust_ref'])){
		api_error(412, 'Customer Reference is required [cust_ref]');
	}
	if(!isset($info['address'])){
		api_error(412, 'Address is required [address]');
	}
	if(!isset($info['tax_id'])){
		api_error(412, 'Tax Id is required [tax_id]');
	}
	if(!isset($info['curr_code'])){
		api_error(412, 'Currency Code is required [curr_code]');
	}
	if(!isset($info['credit_status'])){
		// TODO Set a default initial credit status
		api_error(412, 'Credit Status is required [credit_status]');
	}
	if(!isset($info['payment_terms'])){
		api_error(412, 'Payment Terms is required [payment_terms]');
	}
	if(!isset($info['discount'])){
		// TODO Set default discount as 0
		api_error(412, 'Discount is required [discount]');
	}
	if(!isset($info['pymt_discount'])){
		// TODO Set default payment discount as 0
		api_error(412, 'Payment Discount is required [pymt_discount]');
	}
	if(!isset($info['credit_limit'])){
		// TODO Set default credit limit from company configuration
		api_error(412, 'Credit Limit is required [credit_limit]');
	}
	if(!isset($info['sales_type'])){
		api_error(412, 'Sales Type is required [sales_type]');
	}
	if(!isset($info['notes'])){
		$info['notes'] = '';
	}

	// For default branch
	if(!isset($info['salesman'])){
	    $info['salesman'] = '';
	}
	if(!isset($info['area'])){
	    $info['area'] = '';
	}
	if(!isset($info['tax_group_id'])){
	    $info['tax_group_id'] = '1';
	}
	if(!isset($info['location'])){
	    $info['location'] = 'DEF';
	}
	if(!isset($info['ship_via'])){
	    $info['ship_via'] = '1';
	}
	if(!isset($info['phone'])){
	    $info['phone'] = '';
	}
	if(!isset($info['phone2'])){
	    $info['phone2'] = '';
	}
	if(!isset($info['fax'])){
	    $info['fax'] = '';
	}
	if(!isset($info['email'])){
	    $info['email'] = '';
	}

	/*
	$CustName, $cust_ref, $address, $tax_id, $curr_code,
	$dimension_id, $dimension2_id, $credit_status, $payment_terms, $discount, $pymt_discount,
	$credit_limit, $sales_type, $notes
	*/
	add_customer(
		$info['custname'],
		$info['cust_ref'],
		$info['address'],
		$info['tax_id'],
		$info['curr_code'],
		0,
		0,
		$info['credit_status'],
		$info['payment_terms'],
		$info['discount'],
		$info['pymt_discount'],
		$info['credit_limit'],
		$info['sales_type'],
		$info['notes']
	);

	$selected_id = db_insert_id();
	$auto_create_branch = 1;
	if (isset($auto_create_branch) && $auto_create_branch == 1)
	{
		add_branch(
			$selected_id,
			$info['custname'],
			$info['cust_ref'],
			$info['address'],
			$info['salesman'],
			$info['area'],
			$info['tax_group_id'], '1',
			get_company_pref('default_sales_discount_act'),
			get_company_pref('debtors_act'),
			get_company_pref('default_prompt_payment_act'),
			$info['location'],
			$info['address'],
			0,
			0,
			$info['ship_via'],
			$info['notes']
		);

		$selected_branch = db_insert_id();

		add_crm_person(
			$info['cust_ref'],
			$info['custname'],
			'',
			$info['address'],
			$info['phone'],
			$info['phone2'],
			$info['fax'],
			$info['email'],
			'',
			''
		);

		$pers_id = db_insert_id();
		add_crm_contact(
			'cust_branch',
			'general',
			$selected_branch,
			$pers_id
		);

		add_crm_contact(
			'customer',
			'general',
			$selected_id,
			$pers_id
		);
	}
	$cust = get_customer($selected_id);
	if($cust != null){
		api_success_response(json_encode($cust));
	}else {
		api_error(500, 'Could Not Save to Database');
	}

}

function customer_edit($id){

	$app = \Slim\Slim::getInstance('SASYS');
	$req = $app->request();
	$info = $req->post();
	//default values
	global $SysPrefs;

	$cust = get_customer($id);
	if($cust == null){
		api_error(400, 'Invalid Customer ID');
	}else{
		$info['tax_id'] = $cust['tax_id'];
		$info['curr_code'] = $cust['curr_code'];
		$info['credit_status'] = $cust['credit_status'];
		$info['payment_terms'] = $cust['payment_terms'];
		$info['discount'] = $cust['discount'];
		$info['pymt_discount'] = $cust['pymt_discount'];
		$info['credit_limit'] = $cust['credit_limit'];
		$info['sales_type'] = $cust['sales_type'];
	}


	// Validate Required Fields
	if(!isset($info['custname'])){
		api_error(412, 'Customer Name is required [custname]');
	}
	if(!isset($info['cust_ref'])){
		api_error(412, 'Customer Reference is required [cust_ref]');
	}
	if(!isset($info['address'])){
		api_error(412, 'Address is required [address]');
	}
	if(!isset($info['tax_id'])){
		api_error(412, 'Tax Id is required [tax_id]');
	}
	if(!isset($info['curr_code'])){
		api_error(412, 'Currency Code is required [curr_code]');
	}
	if(!isset($info['credit_status'])){
		// TODO Set a default initial credit status
		api_error(412, 'Credit Status is required [credit_status]');
	}
	if(!isset($info['payment_terms'])){
		api_error(412, 'Payment Terms is required [payment_terms]');
	}
	if(!isset($info['discount'])){
		// TODO Set default discount as 0
		api_error(412, 'Discount is required [discount]');
	}
	if(!isset($info['pymt_discount'])){
		// TODO Set default payment discount as 0
		api_error(412, 'Payment Discount is required [pymt_discount]');
	}
	if(!isset($info['credit_limit'])){
		// TODO Set default credit limit from company configuration
		api_error(412, 'Credit Limit is required [credit_limit]');
	}
	if(!isset($info['sales_type'])){
		api_error(412, 'Sales Type is required [sales_type]');
	}
	if(!isset($info['notes'])){
		$info['notes'] = '';
	}

	
	//$customer_id, $CustName, $cust_ref, $address, $tax_id, $curr_code,
	//$dimension_id, $dimension2_id, $credit_status, $payment_terms, $discount, $pymt_discount,
	//$credit_limit, $sales_type, $notes
	
	update_customer(
		$id,
		$info['custname'],
		$info['cust_ref'],
		$info['address'],
		$info['tax_id'],
		$info['curr_code'],
		0,
		0,
		$info['credit_status'],
		$info['payment_terms'],
		$info['discount'],
		$info['pymt_discount'],
		$info['credit_limit'],
		$info['sales_type'],
		$info['notes']
	);

	api_success_response("Customer has been updated");
	

}

function customer_delete($id){

	$app = \Slim\Slim::getInstance('SASYS');
	$req = $app->request();
	$info = $req->post();

	$cust = get_customer($id);
	if($cust == null){
		api_error(400, 'Invalid Customer ID');
	}

	delete_customer($id);

	$cust = null;
	$cust = get_customer($id);

	if($cust != null){
		api_error(500, 'Could Not Delete from Database');
	}else {
		api_success_response("Customer has been deleted");
	}

}

function customer_branches_get($id){

	$sql = "SELECT "
		."b.branch_code, "
		."b.branch_ref, "
		."b.br_name, "
		."p.name as contact_name, "
		."s.salesman_name, "
		."a.description, "
		."p.phone, "
		."p.fax, "
		."p.email, "
		."t.name AS tax_group_name, "
		."b.inactive
		FROM ".TB_PREF."cust_branch b "
		. "LEFT JOIN ".TB_PREF."crm_contacts c
			ON c.entity_id=b.branch_code AND c.type='cust_branch' AND c.action='general'
			LEFT JOIN ".TB_PREF."crm_persons p on c.person_id=p.id,"
			.TB_PREF."areas a, "
			.TB_PREF."salesman s, "
			.TB_PREF."tax_groups t
		WHERE b.tax_group_id=t.id
		AND b.area=a.area_code
		AND b.salesman=s.salesman_code
		AND b.debtor_no = ".db_escape($id)
		." AND !b.inactive GROUP BY b.branch_code ORDER BY branch_ref";

	$result = db_query($sql,"Cannot Get Customer Branches");

	$ret = array();
	while($branch = db_fetch($result)){

		$ret[] = array(
			'branch_code' => $branch['branch_code'],
			'branch_ref' => $branch['branch_ref'],
			'br_name' => $branch['br_name'],
			'contact_name' => $branch['contact_name'],
			'salesman_name' => $branch['salesman_name'],
			'description' => $branch['description'],
			'phone' => $branch['phone'],
			'fax' => $branch['fax'],
			'email' => $branch['email'],
			'tax_group_name' => $branch['tax_group_name']
		);

	}
	api_success_response(json_encode($ret));

}

function customer_n_branch_get($id){

	/*$sql = "SELECT debtor.*," 
		."b.branch_code, "
		."b.default_ship_via, "
		."b.branch_ref, "
		."b.br_name, "
		."p.name as contact_name, "
		."a.description, "
		."p.phone, "
		."p.fax, "
		."p.email, "
		."t.name AS tax_group_name, "
		."b.inactive
		FROM ".TB_PREF."debtors_master debtor, ".TB_PREF."cust_branch b "
		. "LEFT JOIN ".TB_PREF."crm_contacts c
			ON c.entity_id=b.branch_code AND c.type='cust_branch' AND c.action='general'
			LEFT JOIN ".TB_PREF."crm_persons p on c.person_id=p.id,"
			.TB_PREF."areas a, "
			.TB_PREF."tax_groups t
		WHERE b.tax_group_id=t.id
		AND b.area=a.area_code
		AND b.debtor_no = debtor.debtor_no
		AND debtor.debtor_no=".db_escape($id);*/
$sql = "SELECT debtor.* FROM ".TB_PREF."debtors_master debtor WHERE debtor.debtor_no=".db_escape($id);
		
		

	$result = db_query($sql,"Cannot Get Customer");

	$ret = db_fetch($result);

	/*$ref = get_loc_next_reference($loc_code,$trans_type);

	if(!$ret){
		$ret = array();
	} else{
		$ret['next_ref'] = $ref;
	}*/

	api_success_response(json_encode($ret));

}

/* get Customer By name*/
function customer_by_name($name) //sales/includes/db/customers_db.inc
{

	$cust = get_customer_by_name($name);
	$info = array();

	while($data = db_fetch($cust)) {
		array_push($info, $data['name']." (ID:".$data['debtor_no'].")");
	}



	 
	api_success_response(json_encode($info));

}
/* get Customer By name*/




?>
