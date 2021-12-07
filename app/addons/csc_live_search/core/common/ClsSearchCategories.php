<?php
use Tygh\CscLiveSearch;
if (!defined('BOOTSTRAP')) { die('Access denied'); }
class ClsSearchCategories{
	public static function _get_categories($params){		
		$categories =[];
		$company_id = fn_cls_get_current_company_id($params);	
		$ls_settings = CscLiveSearch::_get_option_values(true, $company_id);				
		$join = $condition = $limit = '';
		$fields = [
			"?:category_descriptions.category_id", 
			"?:category_descriptions.category as category"
		];
		if ($ls_settings['show_parent_category']=="Y"){
			$fields[] = "cd2.category as p_category";
			$join .=db_quote(" LEFT JOIN ?:category_descriptions as cd2 ON cd2.category_id=?:categories.parent_id AND cd2.lang_code=?s", $params['lang_code']);	
		}
		$cats_where=array();
		if ($ls_settings['search_on_category_name']=="Y"){
			$cats_where[]=db_quote(" ?:category_descriptions.category LIKE ?l", "%$params[q]%");	
		}
		if ($ls_settings['search_on_category_metakeywords']=="Y"){
			$cats_where[]=db_quote(" ?:category_descriptions.meta_keywords LIKE ?l", "%$params[q]%");	
		}
		if ($company_id) {
			$condition .=" AND company_id=$company_id";
		}		
		if ($ls_settings['cats_limit']){
			$limit="LIMIT {$ls_settings['cats_limit']}";
		}		
		if ($cats_where){
			fn_cls_hook_function('hooks_get_categories', $ls_settings, $company_id, $params, $fields, $join, $condition, $cats_where, $limit);
			
			$condition .=' AND ('.implode(' OR ', $cats_where).')';							
			$categories=db_get_array("SELECT " . implode(',', $fields) . " FROM ?:category_descriptions 
			LEFT JOIN ?:categories ON ?:categories.category_id=?:category_descriptions.category_id $join 
			WHERE ?:category_descriptions.lang_code=?s $condition  AND status='A' $limit", $params['lang_code']);
							
			if ($ls_settings['show_parent_category']=="Y"){
				foreach ($categories as &$cat){
					if (!empty($cat['p_category'])){
						$cat['category'] = 	$cat['p_category'].'/'.$cat['category'];
						unset($cat['p_category']);
					}	
				}
			}
		}			
		return $categories;
	}
	public static function _get_storefront_categories($params){		
		$categories =[];
		$company_id = fn_cls_get_current_company_id($params);			
		$ls_settings = CscLiveSearch::_get_option_values(true, $company_id);					
		$search_storefronts = $ls_settings['allow_storefronts'];
		foreach ([$company_id, ''] as $val){
			if (($key = array_search($val, $search_storefronts)) !== false) {
				unset($search_storefronts[$key]);
			}
		}			
		if ($search_storefronts){
			$condition = " AND ?:categories.company_id IN (".implode(',', $search_storefronts).")";
			$categories=db_get_array("SELECT ?:category_descriptions.category_id, 
			?:category_descriptions.category, 
			?:categories.company_id, 
			?:companies.storefront 
			FROM ?:category_descriptions 
			LEFT JOIN ?:categories ON ?:categories.category_id=?:category_descriptions.category_id
			LEFT JOIN ?:companies ON ?:companies.company_id=?:categories.company_id
			WHERE ?:category_descriptions.lang_code=?s $condition 
			AND (?:category_descriptions.category LIKE ?l OR ?:category_descriptions.category LIKE ?l) 
			AND ?:categories.status='A' LIMIT {$ls_settings['cats_limit']}", $params['lang_code'], "$params[q]%", "% $params[q]%");
		}
		foreach ($categories as &$category){
			$category['url'] = 'http://'.$category['storefront'].'/index.php?dispatch=categories.view&category_id='.$category['category_id'];
			unset($category['storefront']);	
		}
						
		return $categories;
	}
	
}
