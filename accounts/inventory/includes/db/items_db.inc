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
function update_item($stock_id, $description, $long_description, $category_id, 
	$tax_type_id, $units='', $mb_flag='', $sales_account, $inventory_account, 
	$cogs_account, 	$adjustment_account, $assembly_account, $dimension_id, 
	$dimension2_id, $no_sale, $editable)
{
	$sql = "UPDATE ".TB_PREF."stock_master SET long_description=".db_escape($long_description).",
		description=".db_escape($description).",
		category_id=".db_escape($category_id).",
		sales_account=".db_escape($sales_account).",
		inventory_account=".db_escape($inventory_account).",
		cogs_account=".db_escape($cogs_account).",
		adjustment_account=".db_escape($adjustment_account).",
		assembly_account=".db_escape($assembly_account).",
		dimension_id=".db_escape($dimension_id).",
		dimension2_id=".db_escape($dimension2_id).",
		tax_type_id=".db_escape($tax_type_id).",
		no_sale=".db_escape($no_sale).",
		editable=".db_escape($editable);

	if ($units != '')
		$sql .= ", units=".db_escape($units);

	if ($mb_flag != '')
		$sql .= ", mb_flag=".db_escape($mb_flag);

	$sql .= " WHERE stock_id=".db_escape($stock_id);

	db_query($sql, "The item could not be updated");

	update_item_code(-1, $stock_id, $stock_id, $description, $category_id, 1, 0);
}

function add_item($stock_id, $description, $long_description, $category_id, 
	$tax_type_id, $units, $mb_flag,	$sales_account, $inventory_account, 
	$cogs_account, $adjustment_account,	$assembly_account, $dimension_id, 
	$dimension2_id, $no_sale, $editable)
{
	$sql = "INSERT INTO ".TB_PREF."stock_master (stock_id, description, long_description, category_id,
		tax_type_id, units, mb_flag, sales_account, inventory_account, cogs_account,
		adjustment_account, assembly_account, dimension_id, dimension2_id, no_sale, editable)
		VALUES (".db_escape($stock_id).", ".db_escape($description).", ".db_escape($long_description).",
		".db_escape($category_id).", ".db_escape($tax_type_id).", "
		.db_escape($units).", ".db_escape($mb_flag).",
		".db_escape($sales_account).", ".db_escape($inventory_account)
		.", ".db_escape($cogs_account).",".db_escape($adjustment_account)
		.", ".db_escape($assembly_account).", "
		.db_escape($dimension_id).", ".db_escape($dimension2_id).","
		.db_escape($no_sale).","
		.db_escape($editable).")";

	db_query($sql, "The item could not be added");

	$sql = "INSERT INTO ".TB_PREF."loc_stock (loc_code, stock_id)
		SELECT ".TB_PREF."locations.loc_code, ".db_escape($stock_id)
		." FROM ".TB_PREF."locations";

	db_query($sql, "The item locstock could not be added");

	add_item_code($stock_id, $stock_id, $description, $category_id, 1, 0);
}

function delete_item($stock_id)
{
	$sql="DELETE FROM ".TB_PREF."stock_master WHERE stock_id=".db_escape($stock_id);
	db_query($sql, "could not delete stock item");

	/*and cascade deletes in loc_stock */
	$sql ="DELETE FROM ".TB_PREF."loc_stock WHERE stock_id=".db_escape($stock_id);
	db_query($sql, "could not delete stock item loc stock");

	/*and cascade deletes in purch_data */
	$sql ="DELETE FROM ".TB_PREF."purch_data WHERE stock_id=".db_escape($stock_id);
	db_query($sql, "could not delete stock item purch data");

	/*and cascade deletes in prices */
	$sql ="DELETE FROM ".TB_PREF."prices WHERE stock_id=".db_escape($stock_id);
	db_query($sql, "could not delete stock item prices");

	/*and cascade delete the bill of material if any */
	$sql = "DELETE FROM ".TB_PREF."bom WHERE parent=".db_escape($stock_id);
	db_query($sql, "could not delete stock item bom");

	delete_item_kit($stock_id);
}

function get_item($stock_id)
{
	$sql = "SELECT ".TB_PREF."stock_master.*,".TB_PREF."item_tax_types.name AS tax_type_name
		FROM ".TB_PREF."stock_master,".TB_PREF."item_tax_types
		WHERE ".TB_PREF."item_tax_types.id=".TB_PREF."stock_master.tax_type_id
		AND stock_id=".db_escape($stock_id);
	$result = db_query($sql,"an item could not be retreived");

	return db_fetch($result);
}


////////////////////////////////
/****************INVENTORY BY LOCATION CODE LOW STOCK STARTS************************/
function inventory_by_loc_code_lowstock($loc_code)
{
	$sql="SELECT C.description as Category,SMT.stock_id as Product_code,SMT.description as Product_name,SMT.units as UOM,SMO.loc_code as Warehouse_name, SMO.price as Product_price,sum(SMO.qty) as Product_Quantity,SMO.standard_cost as Standard_cost FROM ".TB_PREF."stock_moves AS SMO LEFT JOIN ".TB_PREF."stock_master AS SMT ON SMT.stock_id=SMO.stock_id LEFT JOIN ".TB_PREF."stock_category AS C ON C.category_id=SMT.category_id WHERE SMO.loc_code=".db_escape($loc_code)." GROUP BY SMT.stock_id HAVING SUM(SMO.qty) < 20";

	//$sql="SELECT C.description as Category,SMT.stock_id as Product_code,SMT.description as Product_name,SMT.units as UOM,SMO.loc_code as Warehouse_name, SMO.price as Product_price,sum(SMO.qty) as Product_Quantity,SMO.standard_cost as Standard_cost FROM 0_stock_moves AS SMO LEFT JOIN 0_stock_master AS SMT ON SMT.stock_id=SMO.stock_id LEFT JOIN 0_stock_category AS C ON C.category_id=SMT.category_id WHERE SMO.loc_code='DEF' GROUP BY SMT.stock_id";

	return db_query($sql,"an item could not be retreived");

	
}
/****************INVENTORY BY LOCATION CODE LOW STOCK ENDS************************/
////////////////////////////////


/****************INVENTORY BY LOCATION CODE STARTS************************/
function inventory_by_loc_code($loc_code)
{
	$sql="SELECT C.description as Category,SMT.stock_id as Product_code,SMT.description as Product_name,SMT.units as UOM,SMO.loc_code as Warehouse_name, SMO.price as Product_price,sum(SMO.qty) as Product_Quantity,SMO.standard_cost as Standard_cost FROM ".TB_PREF."stock_moves AS SMO LEFT JOIN ".TB_PREF."stock_master AS SMT ON SMT.stock_id=SMO.stock_id LEFT JOIN ".TB_PREF."stock_category AS C ON C.category_id=SMT.category_id WHERE SMO.loc_code=".db_escape($loc_code)." GROUP BY SMT.stock_id";

	//$sql="SELECT C.description as Category,SMT.stock_id as Product_code,SMT.description as Product_name,SMT.units as UOM,SMO.loc_code as Warehouse_name, SMO.price as Product_price,sum(SMO.qty) as Product_Quantity,SMO.standard_cost as Standard_cost FROM 0_stock_moves AS SMO LEFT JOIN 0_stock_master AS SMT ON SMT.stock_id=SMO.stock_id LEFT JOIN 0_stock_category AS C ON C.category_id=SMT.category_id WHERE SMO.loc_code='DEF' GROUP BY SMT.stock_id";

	return db_query($sql,"an item could not be retreived");

	
}
/****************INVENTORY BY LOCATION CODE ENDS************************/

/****************INVENTORY BY LOCATION CODE AND CATEGORY ID STARTS************************/
function inventory_by_loc_code_catid($loc_code, $category_id)
{
	
	$sql="SELECT SMT.stock_id as stock_id,SMT.description as Product_name,SMT.units as UOM,SMT.category_id as Category_id,SMO.loc_code as Warehouse_name, SMO.price as Product_price,sum(SMO.qty) as Product_Quantity,SMO.standard_cost as Standard_cost FROM ".TB_PREF."stock_moves AS SMO LEFT JOIN ".TB_PREF."stock_master AS SMT ON SMT.stock_id=SMO.stock_id 
	WHERE SMO.loc_code=".db_escape($loc_code)."
	AND SMT.category_id=".db_escape($category_id)."
	GROUP BY SMT.stock_id";

	return db_query($sql,"an item could not be retreived");

	
}
/****************INVENTORY BY LOCATION CODE AND CATEGORY ID ENDS************************/

function get_items_by_category($category_id)
{
	$sql = "SELECT ".TB_PREF."stock_master.*,".TB_PREF."item_tax_types.name AS tax_type_name
		FROM ".TB_PREF."stock_master,".TB_PREF."item_tax_types
		WHERE ".TB_PREF."item_tax_types.id=".TB_PREF."stock_master.tax_type_id
		AND category_id=".db_escape($category_id);
	return db_query($sql,"an item could not be retreived");
}

function get_items()
{
	$sql = "SELECT * FROM ".TB_PREF."stock_master";
	return db_query($sql,"items could not be retreived");
}

/*
****************
*/
function get_items_bycatid($category_id)
{
	$sql = "SELECT * FROM ".TB_PREF."stock_master WHERE category_id=".$category_id;
	return db_query($sql,"items could not be retreived");
}
/* 
***************
*/

function item_in_foreign_codes($stock_id)
{
	$sqls=  array(
	"SELECT COUNT(*) FROM "
		.TB_PREF."stock_moves WHERE stock_id=".db_escape($stock_id) =>
	 _('Cannot delete this item because there are stock movements that refer to this item.'),
	"SELECT COUNT(*) FROM "
		.TB_PREF."bom WHERE component=".db_escape($stock_id)=>
	 _('Cannot delete this item record because there are bills of material that require this part as a component.'),
	"SELECT COUNT(*) FROM "
		.TB_PREF."sales_order_details WHERE stk_code=".db_escape($stock_id) =>
	 _('Cannot delete this item because there are existing purchase order items for it.'),
	"SELECT COUNT(*) FROM "
		.TB_PREF."purch_order_details WHERE item_code=".db_escape($stock_id)=>
	 _('Cannot delete this item because there are existing purchase order items for it.')
	);

	$msg = '';

	foreach($sqls as $sql=>$err) {
		$result = db_query($sql, "could not query stock usage");
		$myrow = db_fetch_row($result);
		if ($myrow[0] > 0) 
		{
			$msg = $err; break;
		}
	}
	if ($msg == '') {	

		$kits = get_where_used($stock_id);
		$num_kits = db_num_rows($kits);
		if ($num_kits) {
			$msg = _("This item cannot be deleted because some code aliases 
				or foreign codes was entered for it, or there are kits defined 
				using this item as component")
				.':<br>';

			while($num_kits--) {
				$kit = db_fetch($kits);
				$msg .= "'".$kit[0]."'";
				if ($num_kits) $msg .= ',';
			}

		}
	}
	return $msg;
}
?>
