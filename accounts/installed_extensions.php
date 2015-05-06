<?php

/* List of installed additional extensions. If extensions are added to the list manually
	make sure they have unique and so far never used extension_ids as a keys,
	and $next_extension_id is also updated. More about format of this file yo will find in 
	FA extension system documentation.
*/

$next_extension_id = 9; // unique id for next installed extension

$installed_extensions = array (
  1 => 
  array (
    'name' => 'Theme Dynamic',
    'package' => 'dynamic',
    'version' => '2.3.10-5',
    'type' => 'theme',
    'active' => false,
    'path' => 'themes/dynamic',
  ),
  2 => 
  array (
    'package' => 'Slim',
    'name' => 'Slim',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/Slim',
    'active' => false,
  ),
  3 => 
  array (
    'package' => 'kvcodes',
    'name' => 'kvcodes',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/kvcodes',
    'active' => false,
  ),
  4 => 
  array (
    'package' => 'api',
    'name' => 'api',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/api',
    'active' => false,
  ),
  5 => 
  array (
    'name' => 'Sales Summary Report',
    'package' => 'rep_sales_summary',
    'version' => '2.3.3-3',
    'type' => 'extension',
    'active' => false,
    'path' => 'modules/rep_sales_summary',
  ),
  6 => 
  array (
    'name' => 'Report Generator',
    'package' => 'repgen',
    'version' => '2.3.9-4',
    'type' => 'extension',
    'active' => false,
    'path' => 'modules/repgen',
  ),
  7 => 
  array (
    'name' => 'Theme Modern',
    'package' => 'modern',
    'version' => '2.3.0-6',
    'type' => 'theme',
    'active' => false,
    'path' => 'themes/modern',
  ),
  8 => 
  array (
    'name' => 'Theme Anterp',
    'package' => 'anterp',
    'version' => '2.3.0-5',
    'type' => 'theme',
    'active' => false,
    'path' => 'themes/anterp',
  ),
);
?>