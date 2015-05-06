<?php
/**********************************************
Author: Swapna
Name: Administrator Functions
Free software under GNU GPL
***********************************************/

$path_to_root = "../..";

include_once($path_to_root . "/admin/db/company_db.inc");
include_once($path_to_root . "/includes/prefs/userprefs.inc");

function company_get()
{
	$company = get_company_prefs();
	api_success_response(json_encode($company));
}


function admin_get()
{

	$user = get_user_by_login('admin');
	api_success_response(json_encode($user));
}

?>
