<?php
if( !defined('BOOTSTRAP') ) die('Access denied');
use Tygh\Registry;

// addons/<addon_name>/upgrade/FILE
$v = explode(DIRECTORY_SEPARATOR, __FILE__);
$addon_name = $v[count($v)-3];
if( function_exists('addon_install_lang_vars') ) {
	addon_install_lang_vars($addon_name, addon_silent_install($addon_name) );
}
_check_seo_names();

db_query("ALTER table ?:ez_charities_tracking MODIFY commission decimal(12,2)");
db_query("UPDATE ?:settings_objects SET name='commission_from' WHERE name='commision_from'");

function _check_seo_names() {
	$count = 0;
	foreach( db_get_array("SELECT * FROM ?:seo_names WHERE type='h'") as $s_data) {
		$regex = ';-'.strtolower(__("ch_charity", array(), $s_data['lang_code'])).'$;';
		if( preg_match(';-'.strtolower(__("ch_charity", array(), $s_data['lang_code'])).'$;', $s_data['name']) ) {
			// Change 'name-charity' to 'charity-name'
			$s_data['name'] = preg_replace(';^(.*)-'.strtolower(__("ch_charity", array(), $s_data['lang_code'])).'$;',
										strtolower(__("ch_charity", array(), $s_data['lang_code'])).'-$1',
										$s_data['name']);
			db_query("UPDATE ?:seo_names SET name=?s WHERE object_id=?i AND type='h' AND lang_code=?s",
					 	$s_data['name'], $s_data['object_id'], $s_data['lang_code']);
			$count++;
		}
	}
	if( $count ) 
		fn_set_notification('N', __("notice"), "Upgraded $count SEO names to use charity-[name] prefix.", 'K');
}

// 12/12/18: 4.9.9 Install themes and langvars
fn_install_addon_templates('charities');
if( function_exists('addon_install_setup') ) {
	addon_install_setup('charities');
}
// Clear all the caches
fn_clear_cache();
fn_clear_template_cache();

?>