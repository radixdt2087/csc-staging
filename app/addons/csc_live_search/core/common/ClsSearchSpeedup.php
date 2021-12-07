<?php
/*****************************************************************************
*                                                                            *
*          All rights reserved! CS-Commerce Software Solutions               *
* 			http://www.cs-commerce.com/license-agreement.html 				 *
*                                                                            *
*****************************************************************************/
use Tygh\CscLiveSearch;
if (!defined('BOOTSTRAP')) { die('Access denied'); }
class ClsSearchSpeedup{
	public static function _get_rest_of_products($step=500){
		$join='';
		$condition='';
		for ($i = 0; $i < 10; $i++) {
			$join .=db_quote(" LEFT JOIN ?:csc_search_speedup_products_clusters_{$i} ON ?:csc_search_speedup_products_clusters_{$i}.product_id=?:products.product_id");
			$condition .= db_quote(" AND ?:csc_search_speedup_products_clusters_{$i}.cluster_id IS NULL ");
		}			
		$products = db_get_array("SELECT SQL_CALC_FOUND_ROWS product, product_code, search_words, ?:products.product_id 
			FROM ?:products 
			INNER JOIN ?:product_descriptions ON ?:product_descriptions.product_id=?:products.product_id
			$join
			WHERE 1 $condition
			LIMIT $step");
		$found_rows = db_get_field("SELECT COUNT(*)	FROM ?:products 
			INNER JOIN ?:product_descriptions ON ?:product_descriptions.product_id=?:products.product_id
			$join
			WHERE 1 $condition");		
		return array($products, $found_rows);	
	}
	public static function _speedup_scan_products( $partial=false ){		
		$addon= CscLiveSearch::_get_option_values(true);	
		$clusters = db_get_hash_array("SELECT cluster_id, cluster FROM ?:csc_search_speedup_clusters", csc_live_search::_z('L2k1p3Eypt=='));
		if ($partial){
			$step=500;	
		}else{
			$step=800;		
		}		
		$last_product_id = fn_get_storage_data('csc_speedup_product_id');
		$store_data = true;
		if (!$last_product_id){
			$last_product_id=0;
		}		
		$join = db_quote(" INNER JOIN ?:product_descriptions ON ?:product_descriptions.product_id=?:products.product_id	");
		$condition = db_quote(" AND ?:products.product_id >?i", $last_product_id);	
		$products = db_get_array("SELECT product, product_code, search_words, ?:products.product_id 
			FROM ?:products 
			$join		
		WHERE  1 $condition ORDER BY ?:products.product_id ASC
		LIMIT $step");
		$found_rows = db_get_field("SELECT COUNT(*) FROM ?:products $join WHERE  1 $condition");
		//$found_rows = db_get_found_rows();	
		if (!$products){
			$store_data = false;	
			list($products, $found_rows) = self::_get_rest_of_products($step);	
		}
		$rest_products =  $found_rows - count($products);
		if (!$products){
			fn_set_notification('N', __('notice'), __('css.scaner_finish'));	
			return false;
		}		
		$data=array();		
		foreach ($products as $product){			
			$search_string = '';
			if ($addon['search_on_name']=="Y"){
				$search_string .=$product['product'];
			}
			if ($addon['search_on_pcode']=="Y"){
				$search_string .=" ".$product['product_code'];
			}
			if ($addon['search_on_keywords']=="Y"){
				$search_string .=" ".$product['search_words'];
			}
			if ($addon['search_on_product_id']=="Y"){
				$search_string .=" ".$product['product_id'];
			}			
			self::_prepare_additional_by_product($addon, $product['product_id'], $search_string);		
			
			$data = self::_prepare_product_clusters($addon, $product['product_id'], $search_string, $data, $clusters);			
		}				
		if ($data){
			foreach ($data as $k=>$d){
				db_query("REPLACE INTO ?:csc_search_speedup_products_clusters_{$k} ?m", $d);	
			}	
		}
		if ($store_data){
			fn_set_storage_data('csc_speedup_product_id', $product['product_id']);
		}
		if (!$partial){
			self::_speedup_scan_products();
		}
		return $rest_products;
	}
	
	public static function _prepare_additional_by_product($addon, $product_id, &$search_string){
		if ($addon['search_by_features']){
			$addon['search_by_features'] = array_filter($addon['search_by_features']);
		}
		if ($addon['search_on_features']=="Y"){	
			$cond = '';
			if (!empty($addon['search_by_features'])){
				$cond .=db_quote(" AND feature_id IN (?a)", $addon['search_by_features']);				
			}		
			$features= db_get_array("SELECT value, variant FROM ?:product_features_values LEFT JOIN ?:product_feature_variant_descriptions ON ?:product_feature_variant_descriptions.variant_id=?:product_features_values.variant_id WHERE product_id=?i $cond", $product_id);						
			$vals = array();
			foreach ($features as $feature){
				if ($feature['value']){
					$vals[$feature['value']]=$feature['value'];
				}
				if ($feature['variant']){
					$vals[$feature['variant']]=$feature['variant'];	
				}
			}
			$search_string .=" ".implode(" ", $vals);		
		}
		if ($addon['search_on_options']=="Y"){
			$options= db_get_array("SELECT variant_name FROM ?:product_option_variants_descriptions 
			LEFT JOIN ?:product_option_variants ON ?:product_option_variants_descriptions.variant_id=?:product_option_variants.variant_id 
			LEFT JOIN ?:product_options ON ?:product_options.option_id=?:product_option_variants.option_id 
			LEFT JOIN ?:product_global_option_links ON ?:product_global_option_links.option_id=?:product_options.option_id
			WHERE (?:product_global_option_links.product_id=?i OR ?:product_options.product_id=?i)				
			", $product_id, $product_id);
			foreach ($options as $option){
				if (trim($option['variant_name'])){
					$search_string .=" ".$option['variant_name'];
				}					
			}		
		}
	}
	
	public static function _prepare_product_clusters($addon, $product_id, $search_string, $data=array(), $clusters=array()){		
		$words = self::_speedup_prepare_words($search_string);	
		$have_cluster = false;			
		foreach ($words as $word){
			$word = self::_speedup_word_to_claster($word);				
			if (mb_strlen($word, 'utf-8') >= $addon['speedup_cluster_size']){					 
				$cluster=mb_substr($word, 0, $addon['speedup_cluster_size'], 'utf-8');
				$cluster=mb_strtolower($cluster, 'utf-8');					
				if (!isset($clusters[$cluster])){										
					$cluster_id = db_get_field("SELECT cluster_id FROM ?:csc_search_speedup_clusters WHERE cluster=?s", $cluster);
						if (!$cluster_id){					
							$cl_data = array(
								'cluster'=>$cluster									
							);
							$cluster_id = db_query("INSERT INTO ?:csc_search_speedup_clusters ?e", $cl_data);
						}
						$clusters[$cluster]['cluster_id']=$cluster_id;						
				}else{
					$cluster_id = $clusters[$cluster]['cluster_id'];
				}
				$data[self::_group_id($cluster_id)][$product_id.'-'.$cluster_id] = array(
					'cluster_id'=>$cluster_id,
					'product_id'=>	$product_id				
				);
				$have_cluster = true;				
			}		
		}
		if (!$have_cluster){
			$data[0][$product_id] = array(
				'cluster_id'=>0,
				'product_id'=>	$product_id				
			);	
		}
		return $data;					
	}
	
	public static function _speedup_prepare_words($string){
		$del = array(' ', ';', '-', '/', ',', '>', '_');
		$words = explode( $del[0], str_replace($del, $del[0], $string));
		return $words;
	}
	public static function _speedup_word_to_claster($word){
		$word = trim($word);		
		$word = str_replace(array('\'', '/', '-', '_', '?', '!', ',', '.', '"', '«', '»', '“', '`', '#', '$', '^', '*', '(', ')', '+', '-', '=', '<', '>', '%', '|', '~', ';', ':'), '', $word);
		return $word;
	}
	public static function _group_id($cluster_id){
		return substr($cluster_id, -1);
	}	
	
	public static function _scan_single_product($product_id){
		$addon= CscLiveSearch::_get_option_values(true);	
		$products = db_get_array("SELECT product, product_code, search_words, ?:products.product_id 
			FROM ?:products 
			LEFT JOIN ?:product_descriptions ON ?:product_descriptions.product_id=?:products.product_id		
		WHERE ?:products.product_id =?i", $product_id);
		$data=array();
		foreach($products as $product){
			$search_string = '';
			if ($addon['search_on_name']=="Y"){
				$search_string .=$product['product'];
			}
			if ($addon['search_on_pcode']=="Y"){
				$search_string .=" ".$product['product_code'];
			}
			if ($addon['search_on_keywords']=="Y"){
				$search_string .=" ".$product['search_words'];
			}
			self::_prepare_additional_by_product($addon, $product['product_id'], $search_string);
			$data = self::_prepare_product_clusters($addon, $product_id, $search_string, $data);		
		}	
		if ($data){
			foreach ($data as $k=>$d){
				db_query("REPLACE INTO ?:csc_search_speedup_products_clusters_{$k} ?m", $d);	
			}				
		}	
	}
	public static function _speedup_clear_speedup(){
		for ($i = 0; $i < 10; $i++) {
			db_query("TRUNCATE TABLE ?:csc_search_speedup_products_clusters_{$i}");
		}	
		db_query("TRUNCATE TABLE ?:csc_search_speedup_clusters");
		fn_set_storage_data('csc_speedup_product_id', '0');
		fn_set_notification('N', __('notice'), __('cls.tables_was_cleared'));
	}
		
	public static function _get_search_conditions($q, $cluster_size){		
		$words = self::_speedup_prepare_words($q);			
		$clusters = array();
		$condition = $join='';		
		foreach ($words as $word){
			$word = self::_speedup_word_to_claster($word);
			$cluster=mb_substr($word, 0, $cluster_size, 'utf-8');
			if (mb_strlen($cluster, 'utf-8') >= $cluster_size){
				$clusters[]=mb_strtolower($cluster, 'utf-8');
			}
		}
		if ($clusters){
			$cluster_ids = db_get_fields("SELECT cluster_id FROM ?:csc_search_speedup_clusters WHERE cluster IN (?a)", $clusters);
		}
		if (!empty($cluster_ids)){
			foreach ($cluster_ids as $k=> $clid){
				$suff = self::_group_id($clid);
				$join .=db_quote(" INNER JOIN ?:csc_search_speedup_products_clusters_{$suff} as spc_{$k} ON spc_{$k}.product_id=products.product_id AND spc_{$k}.cluster_id =?i", $clid);	
			}
		}else{
			$condition .=" AND 1=2";
		}		
		return array($join, $condition);
	}
	
}


