<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
$page_security = 'SA_FORMSETUP';
$path_to_root = "..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "Forms Setup"));

include($path_to_root . "/includes/ui.inc");

//-------------------------------------------------------------------------------------------------
$loc_code = get_post('loc_code');
if (list_updated('loc_code')) {

	//clear_data();
	$_POST['loc_code'] = $loc_code;
	$Ajax->activate('details');
	$Ajax->activate('controls');
}


if (isset($_POST['setprefs'])) 
{

	$systypes = get_systypes();
	begin_transaction();

	while ($type = db_fetch($systypes)) {

		save_loc_next_reference($_POST['loc_code'],$type["type_id"], $_POST['id' . $type["type_id"]]);
	}

	commit_transaction();
	display_notification_centered(_("Forms settings have been updated."));

	/*$systypes = get_systypes();

	begin_transaction();

	while ($type = db_fetch($systypes)) 
	{
		save_next_reference($type["type_id"], $_POST['id' . $type["type_id"]]);
	}

	commit_transaction();

	display_notification_centered(_("Forms settings have been updated."));
	*/
}



start_form();
start_table(TABLESTYLE_NOBORDER);
locations_list_row(_(" Select Warehouse"), 'loc_code', null, false, true);
end_row();
end_table();

echo "<hr>";



div_start('details');

$refs = get_loc_references($_POST['loc_code']);

start_outer_table(TABLESTYLE2);


table_section(1);

$th = array(_("Form"), _("Next Reference"));
table_header($th);
$i = 0;
while ($type = db_fetch($refs)) 
{
	if ($i++ == ST_CUSTCREDIT)
	{
		table_section(2);
		table_header($th);
	}
	
	if($type["next_reference"] == '')
		$next_reference = 1;
	else
		$next_reference = $type["next_reference"];

	$_POST['id' . $type["type_id"]] = $next_reference;
	
	ref_row($systypes_array[$type["type_id"]], 'id' . $type["type_id"]);
}

end_outer_table(1);
div_end();

div_start('controls');

submit_center('setprefs', _("Update"), true, '', 'default');
div_end();

end_form(2);

//-------------------------------------------------------------------------------------------------

end_page();

?>
