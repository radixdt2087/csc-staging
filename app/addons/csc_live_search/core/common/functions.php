<?php
use Tygh\Registry;
if (!defined('BOOTSTRAP')) { die('Access denied'); }
function fn_cls_get_current_company_id($params){
	if (!defined('PRODUCT_EDITION')){
		$config = fn_get_config_data();
	}
	if (PRODUCT_EDITION=="MULTIVENDOR"){
		$company_id = 0;
	}else{
		$company_id = @$params['runtime_company_id'];		
	}
	return $company_id ? $company_id : 0;		
}

function fn_cls_speedup_run_scan_info(){
	return "<p>".__('cls.speedup_scaner_instruction')."</p>";	
}
function fn_cls_get_active_addons(){
	static $addons;
	if (!$addons){
		return db_get_fields("SELECT addon FROM ?:addons WHERE status=?s", 'A');	
	}
	return $addons;
}

function fn_cls_hook_function($hook_name = null, &...$args){
	if ($hook_name){
		$ls_settings = $args[0];
		if (!empty($ls_settings[$hook_name])){						
			foreach	($ls_settings[$hook_name] as $dir){				
				if (!empty($dir) && file_exists(DIR_ROOT.$dir)){					
					$file = pathinfo($dir); 
					require_once(DIR_ROOT.$dir);
					if (function_exists($file['filename'])){
						call_user_func_array($file['filename'], $args);		
					}
				}	
			}			
		}		
	}
}
function fn_cls_get_storefront_company_ids($storefront_id){
	static $companies;
	if (!$companies){
		$companies = db_get_fields("SELECT ?:storefronts_companies.company_id FROM ?:storefronts_companies INNER JOIN ?:companies ON ?:companies.company_id=?:storefronts_companies.company_id WHERE ?:storefronts_companies.storefront_id=?i", $storefront_id);
	}
	return $companies;
}
function fn_cls_get_store_settings(){
	static $settings;
	if (!$settings){		
		if (AREA=="CLS"){
			$join_condition='';
			if (CLS_RUNTIME_STOREFRONT_ID){
				$join_condition .=db_quote(" AND ?:settings_vendor_values.storefront_id=?i", CLS_RUNTIME_STOREFRONT_ID);
			}
			if (CLS_RUNTIME_COMPANY_ID){
				$join_condition .=db_quote(" AND ?:settings_vendor_values.company_id=?i", CLS_RUNTIME_COMPANY_ID);
			}
			$settings = db_get_hash_single_array("SELECT IF(?:settings_vendor_values.value IS NULL, ?:settings_objects.value, ?:settings_vendor_values.value) as value, name 
				FROM ?:settings_objects 
				LEFT JOIN ?:settings_vendor_values 
					ON ?:settings_objects.object_id=?:settings_vendor_values.object_id $join_condition
				WHERE name IN (?a)", ['name', 'value'], 
				['show_out_of_stock_products', 'allow_negative_amount', 'inventory_tracking', 'global_tracking', 'default_tracking']);					
		}else{
			$settings = Registry::get('settings.General');			
		}
	}
	return $settings;	
}
if (!function_exists('__')){
    function __($str){
		$val =  db_get_field("SELECT value FROM ?:language_values WHERE name=?s AND lang_code=?s", $str, DESCR_SL);
		if (!$val){
			return 	'_'.$str;
		}
    }
}

if (!function_exists('mb_strtolower')){
    function mb_strtolower(){
		list($arg1) = func_get_args();
		return strtolower($arg1);
    }
}


if (!function_exists('mb_strlen')){
    function mb_strlen(){
		list($arg1) = func_get_args();
		return strlen($arg1);		
    }
}

if (!function_exists('mb_substr')){
    function mb_substr(){
		list($arg1, $arg2, $arg3) = func_get_args();
		return substr($arg1, $arg2, $arg3);		
    }
}
