<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_wk_vendor_subdomain_install(){
    $addon_name = fn_get_lang_var('wk_vendor_subdomain');
    Tygh::$app['view']->assign('mode','notification');
    fn_set_notification('S', __('well_done'), __('wk_webkul_user_guide_content', array('[support_link]' => 'https://webkul.uvdesk.com/en/customer/create-ticket/', '[user_guide]' => 'https://webkul.com/blog/cs-cart-vendor-subdomain/', '[addon_name]' => $addon_name)));
}

function fn_is_vendor_subdomain_allowed($company_id){
    $is_allowed = db_get_field("SELECT ?:vendor_plans.allowed_vendor_subdomain FROM ?:companies INNER JOIN ?:vendor_plans ON ?:vendor_plans.plan_id = ?:companies.plan_id WHERE ?:companies.company_id = ?i",$company_id);
    return $is_allowed;
}
/**
* changing the status of subdomian for any particular company
* @param int $company_id company_id
* @param String $status status to updated 
* @return boolean value of update params
* 
*/
function fn_vendor_subdomain_change_status($company_id,$status){
    return db_query("UPDATE ?:wk_vendor_subdomain SET status = ?s WHERE company_id = ?i",$status,$company_id);
}

/**
* Getting list of all vendor subdomain
* @param array $params request parameters
* @return array $subdomainList list of subdomain and $params request params
* 
*/
function fn_get_vendor_subdomain_list($params){
    $condition = '';
    if((isset($params['company_id']) && !empty($params['company_id'])) || Registry::get('runtime.company_id')){
        $company_id = isset($params['company_id']) && !empty($params['company_id'])?$params['company_id'] : Registry::get('runtime.company_id');
        $condition .= db_quote(" AND company_id = ?i",$company_id);
    }

    if(isset($params['status']) && !empty($params['status'])){
        $condition .= db_quote(" AND status = ?s",$params['status']);
    }

    if(isset($params['subdomain']) && !empty($params['subdomain'])){
        $piece = '%'.$params['subdomain'].'%';
        $condition .= db_quote(" AND subdomain LIKE ?l", $piece);
    }
    fn_get_vendor_profile_page_seo(0);
    $subdomainList = db_get_array("SELECT  * FROM ?:wk_vendor_subdomain WHERE 1 $condition");
    return array($subdomainList,$params);
}


/**
* Deleting any vendor subdomain
* @param int $company_id company_id 
* 
*/
function fn_delete_vendor_subdomain_by_id($company_id){
    db_query("DELETE From ?:wk_vendor_subdomain WHERE company_id = ?i",$company_id);
}

/**
*
* Getting status and subdomain of any vendor
* @param int $company_id company_id
* @return array $values subdomain and status value
* 
*/
function fn_check_subdomain_prefix_for_company($company_id = ''){
    if(!empty($company_id)){
        $values = db_get_row("SELECT subdomain,status FROM ?:wk_vendor_subdomain WHERE company_id = ?i", $company_id);
        if(!empty($values)){
            return array($values['subdomain'],$values['status']); 
        }  
    }
    return false;
}

/*
* Hook for settings the url location
*  
*/ 

function fn_wk_vendor_subdomain_url_set_locations(&$url, &$area, &$protocol, &$lang_code, &$locations){
    if($area != 'C'){
        return;
    }
    
    $company_id = fn_has_vendor_subdomain_from_serverhost($_SERVER['HTTP_HOST']);
    $check = isset($_REQUEST['dispatch'])? ($_REQUEST['dispatch'] == 'checkout.checkout' || $_REQUEST['dispatch'] == 'checkout.cart' || $_REQUEST['dispatch'] == 'wishlist.view')?true:false:false;
    $product_id = isset($_REQUEST['product_id'])? $_REQUEST['product_id'] : 0;
    $new_company_id = isset($_REQUEST['company_id'])? $_REQUEST['company_id'] : 0;
    if($product_id){
        $company = fn_get_company_by_product_id($product_id);
        $new_company_id = $company['company_id'];
    }
    $company_check = $new_company_id ? $new_company_id == $company_id : true;
    if(!empty($company_id) && !$check && $company_check){
        fn_get_vendor_profile_page_seo(0);
        $subdomain = db_get_field("SELECT subdomain FROM ?:wk_vendor_subdomain WHERE company_id = ?i",$company_id);
        $_REQUEST['company_id'] = $company_id;
        $config = Registry::get("config");
        $config['http_host'] = $subdomain.'.'.$config['current_host'];
        $config['https_host'] = $subdomain.'.'.$config['current_host'];
        $config['http_location'] = 'http://' . $config['http_host'] . $config['http_path'];
        $config['https_location'] = 'https://' . $config['https_host'] . $config['https_path'];
        $config['current_location'] = (defined('HTTPS')) ? $config['https_location'] : $config['http_location'];
        $config['current_host'] = (defined('HTTPS')) ? $config['https_host'] : $config['http_host'];
        Registry::set("config",$config);
        $locations['C'] = array(
            'http'    => $config['http_location'],
            'https'   => $config['https_location'],
            'current' => $config['current_location'],
            'rel'     => $config['current_location'],
        );   
    } 
}

/**
 * Get Post url
 * @param string $url url
 * @param string $area area for area
 * @param string $original_url original url from fn_url
 * @param string $prefix prefix
 * @param int $company_id_in_url Company identifier
 * @param string $lang_code language code
 * @return string $url after removing subdomain as per requirement
*/
function fn_wk_vendor_subdomain_url_post(&$url, &$area, &$original_url, &$prefix, &$company_id_in_url, &$lang_code)
{
    if ($area != 'C') {
        return $url;
    }
    if(filter_var($url, FILTER_VALIDATE_URL)){
        if(empty(fn_has_vendor_subdomain($url)) && strpos($original_url,'companies.products') !== false){
            $queryString = explode('?',$original_url);
            foreach($queryString as $v){
                if(strpos($v,'company_id')===0){
                    $company_id = explode('=',$v)['1'];
                    if($company_id && fn_is_vendor_subdomain_allowed($company_id)){
                        $listdata = fn_check_subdomain_prefix_for_company($company_id);
                        if($listdata && $listdata[1] == 'A'){
                            $url = fn_add_subdomain_prefix_in_url($url,$listdata[0]);
                            fn_convert_url_to_seo($url, $area, $original_url, $prefix, $company_id_in_url,$lang_code,true);
                            return $url;
                        }
                    }
                }
            }
           
        }else if(fn_has_vendor_subdomain($url)){
            if(strpos($original_url,'companies.products') !== false){
                if($_REQUEST['company_id'] == $company_id_in_url)
                    fn_convert_url_to_seo($url, $area, $original_url, $prefix, $company_id_in_url,$lang_code,true);
            }elseif(defined('AJAX_REQUEST')){
                if(!empty(parse_url($url)['query']))
                    $url = fn_remove_vendor_subdomain($url);
                fn_convert_url_to_seo($url, $area, $original_url, $prefix, $company_id_in_url,$lang_code);
            } 
        }
    }
}

/*
* seo_get_name_post Hook
*/
function fn_wk_vendor_subdomain_seo_get_name_post(&$name, &$object_type, &$object_id, &$dispatch, &$company_id, &$lang_code){
    fn_get_vendor_profile_page_seo(0);
    if($company_id && !fn_is_vendor_subdomain_allowed($company_id) && $dispatch == 'companies.view'){
        $name = '';
    }
}

/*
* Get Route Hook
*/
function fn_wk_vendor_subdomain_get_route(&$req, &$result, &$area, &$is_allowed_url){
    $company_id = fn_has_vendor_subdomain_from_serverhost($_SERVER['HTTP_HOST']); 
    $lang_code = Registry::get('settings.frontend_default_language');
    $seo_settings = fn_get_seo_settings($company_id);
    $seo_vars = fn_get_seo_vars();
    $extension = '/';
    if(fn_check_seo_schema_option($seo_vars['m'], 'html_options', $seo_settings)) {
        $extension = SEO_FILENAME_EXTENSION;
    }
    if($is_allowed_url && $company_id && !in_array($_SERVER['HTTP_HOST'],array(Registry::get('config.http_host'),
    Registry::get('config.https_host'))) && empty($req)){
        $uri = fn_get_request_uri($_SERVER['REQUEST_URI']);
        if(empty($uri))
            $_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'].fn_seo_get_name('m', $company_id, '', null,$lang_code).$extension;
        $is_allowed_url = 0;
    }
    if($is_allowed_url & !empty($req) && !isset($_REQUEST['dispatch']) && $company_id){
        $uri = fn_get_request_uri($_SERVER['REQUEST_URI']);
        $data = parse_url($_SERVER['REQUEST_URI']);
        $new_uri = fn_get_request_uri($_SERVER['HTTP_REFERER']);
        if(empty($uri) && empty($new_uri))
           $_SERVER['REQUEST_URI'] = $data['path'].fn_seo_get_name('m', $company_id, '', null,$lang_code).$extension.'?'.$data['query'];
        else if(!empty($new_uri)){
            $path = substr_replace($_SERVER['REQUEST_URI'],'/',-1);
            $_SERVER['REQUEST_URI'] = $path.$new_uri;
        }
        $is_allowed_url = 0;
    }
}

/*
* Get Cart Products Hook
*/
function fn_wk_vendor_subdomain_get_cart_products(&$user_id, &$params, &$fields, &$conditions){
    $company_id = fn_has_vendor_subdomain_from_serverhost($_SERVER['HTTP_HOST']); 
    if($company_id){
         $condition .= db_quote(" AND ?:user_session_products.company_id = ?i", $company_id);
    }
}

/*
* Get Any Products list Hook
*/
function fn_wk_vendor_subdomain_get_products(&$params, &$fields, &$sortings, &$condition, &$join, &$sorting, &$group_by, &$lang_code, &$having){
    $company_id = fn_has_vendor_subdomain_from_serverhost($_SERVER['HTTP_HOST']); 
    if($company_id){
        $condition .= db_quote(' AND products.company_id = ?i ',$company_id);
    }
}

//function for checking whether any url having subdomain or not
function fn_has_vendor_subdomain($url) {
    $x = parse_url($url);
    if(isset($x['host'])){
        $host = explode('.', $x['host']);
        if(is_array($host)){
            $subdomain = $host[0] != 'www'? $host[0]:$host[1];
            $company_id = db_get_field("SELECT company_id FROM ?:wk_vendor_subdomain WHERE subdomain = ?s AND status = ?s",$subdomain,'A');
            if($company_id){
                return $company_id;
            }
        }
    }
    return 0;
}

//function for checking whether server host param having subdomain or not
function fn_has_vendor_subdomain_from_serverhost($url) {
    $host = explode('.', $url);
    if(is_array($host)){
        $subdomain = $host[0] != 'www'? $host[0]:$host[1];
        $company_id = db_get_field("SELECT company_id FROM ?:wk_vendor_subdomain WHERE subdomain = ?s AND status = ?s",$subdomain,'A');
        if($company_id && fn_is_vendor_subdomain_allowed($company_id)){
            return $company_id;
        }
    }
    return 0;
}

//function for getting subdomain value from any url
function fn_remove_vendor_subdomain($url){
    $parsed = parse_url($url);
    if(isset($parsed['host'])){
        $host = explode('.', $parsed['host']);
        $subdomain = $host[0] != 'www' ? $host[0]:$host[1];
        //$subdomain = '';
        $company_id = db_get_field("SELECT company_id FROM ?:wk_vendor_subdomain WHERE subdomain = ?s AND status = ?s",$subdomain,'A');
        if($subdomain && $company_id){
            return str_replace($subdomain.'.','',$url);
        }else{
            return $url;
        }
    }
    return $url;
}

//function for adding subdomain into any url
function fn_add_subdomain_prefix_in_url($url,$subdomain){
    $parsed = parse_url($url);
    $parsed['host'] = $subdomain.'.'.$parsed['host'];
    $url = '';
    foreach($parsed as $key=>$value){
        if($key == 'scheme'){
             $value = $value.'://';
        }
        if($key == 'query'){
             $value = '?'.$value;
        }
        $url .= $value;
    }
    return $url;
}

function fn_convert_url_to_seo(&$url, &$area, &$original_url, &$prefix, &$company_id_in_url, &$lang_code,$is_subdomain = false){
    $d = SEO_DELIMITER;
    $parsed_query = array();
    $parsed_url = parse_url($url);

    $index_script = Registry::get('config.customer_index');
    
    $settings_company_id = empty($company_id_in_url) ? 0 : $company_id_in_url;

    $http_path = Registry::get('config.http_path');
    $https_path = Registry::get('config.https_path');

    $seo_settings = fn_get_seo_settings($settings_company_id);
    $current_path = '';
    
    if (empty($parsed_url['scheme'])) {
        $current_path = (defined('HTTPS')) ? $https_path . '/' : $http_path . '/';
    } else {
        if (rtrim($url, '/') == Registry::get('config.http_location') || rtrim($url, '/') == Registry::get('config.https_location')) {
            $url = rtrim($url, '/') . "/" . $index_script;
            $parsed_url['path'] = rtrim($parsed_url['path'], '/') . "/" . $index_script;
        }
    }

    if (!empty($parsed_url['query'])) {
        parse_str($parsed_url['query'], $parsed_query);
    }


    if (!empty($parsed_url['path']) && empty($parsed_url['query']) && $parsed_url['path'] == $index_script) {
        $url = $current_path . (($seo_settings['seo_language'] == 'Y') ? $lang_code . '/' : '');
        return $url;
    }

    if (!empty($parsed_url['path']) && empty($parsed_url['query']) && $parsed_url['path'] == $index_script) {
        $url = $current_path . (($seo_settings['seo_language'] == 'Y') ? $lang_code . '/' : '');
        return $url;
    }

    $path = str_replace($index_script, '', $parsed_url['path'], $count);

    if ($count == 0) {
        return $url; // This is currently seo link
    }
    $fragment = !empty($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
    $link_parts = array(
        'scheme' => !empty($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '',
        'host' => !empty($parsed_url['host']) ? $parsed_url['host'] : '',
        'path' => $current_path . $path,
        'lang_code' => ($seo_settings['seo_language'] == 'Y') ? $lang_code . '/' : '',
        'parent_items_names' => '',
        'name' => '',
        'page' => '',
        'extension' => '',
    );
 
    if (!empty($parsed_query)) {
        if (!empty($parsed_query['sl'])) {
            $lang_code = $parsed_query['sl'];

            if ($seo_settings['single_url'] != 'Y') {
                $unset_lang_code = $parsed_query['sl'];
                unset($parsed_query['sl']);
            }

            if ($seo_settings['seo_language'] == 'Y') {
                $link_parts['lang_code'] = $lang_code . '/';
                $unset_lang_code = isset($parsed_query['sl']) ? $parsed_query['sl'] : $unset_lang_code;
                unset($parsed_query['sl']);
            }
        }

        $lang_code = fn_get_corrected_seo_lang_code($lang_code, $seo_settings);
    
        if (!empty($parsed_query['dispatch']) && is_string($parsed_query['dispatch'])) {
            if (!empty($original_url) && (stripos($parsed_query['dispatch'], '/') !== false || substr($parsed_query['dispatch'], -1 * strlen(SEO_FILENAME_EXTENSION)) == SEO_FILENAME_EXTENSION)) {
                $url = $original_url;
                return $url; // This is currently seo link
            }

            $seo_vars = fn_get_seo_vars();
            $rewritten = false;
            foreach ($seo_vars as $type => $seo_var) {
                if (empty($seo_var['dispatch']) || ($seo_var['dispatch'] == $parsed_query['dispatch'] && !empty($parsed_query[$seo_var['item']]))) {
                    if (!empty($seo_var['dispatch'])) {
                        $link_parts['name'] = fn_seo_get_name($type, $parsed_query[$seo_var['item']], '', $company_id_in_url, $lang_code);
                        //fn_print_r($type);
                    } else {
                        $link_parts['name'] = fn_seo_get_name($type, 0, $parsed_query['dispatch'], $company_id_in_url, $lang_code);
                    }

                    if (empty($link_parts['name'])) {
                        continue;
                    }

                    if (fn_check_seo_schema_option($seo_var, 'tree_options', $seo_settings)) {
                        $parent_item_names = fn_seo_get_parent_items_path($seo_var, $type, $parsed_query[$seo_var['item']], $company_id_in_url, $lang_code);
                        $link_parts['parent_items_names'] = !empty($parent_item_names) ? join('/', $parent_item_names) . '/' : '';
                    }

                    if (fn_check_seo_schema_option($seo_var, 'html_options', $seo_settings)) {
                        $link_parts['extension'] = SEO_FILENAME_EXTENSION;
                    } else {
                        $link_parts['name'] .= '/';
                    }

                    if (!empty($seo_var['pager'])) {

                        $page = isset($parsed_query['page']) ? intval($parsed_query['page']) : 0;

                        if (!empty($page) && $page != 1) {
                            if (fn_check_seo_schema_option($seo_var, 'html_options', $seo_settings)) {
                                $link_parts['name'] .= $d . 'page' . $d . $page;
                            } else {
                                $link_parts['name'] .= 'page' . $d . $page . '/';
                            }
                        }
                        unset($parsed_query['page']);
                    }

                    fn_seo_parsed_query_unset($parsed_query, $seo_var['item']);

                    $rewritten = true;
                    break;
                }
            }

            if (!$rewritten) {
                // deprecated
                fn_set_hook('seo_url', $seo_settings, $url, $parsed_url, $link_parts, $parsed_query, $company_id_in_url, $lang_code);

                if (empty($link_parts['name'])) {
                    // for non-rewritten links
                    $link_parts['path'] .= $index_script;
                    $link_parts['lang_code'] = '';
                    if (!empty($unset_lang_code)) {
                        $parsed_query['sl'] = $unset_lang_code;
                    }
                }
            } else {
                unset($parsed_query['company_id']); // we do not need this parameter if url is rewritten
            }

        } elseif ($seo_settings['seo_language'] != 'Y' && !empty($unset_lang_code)) {
            $parsed_query['sl'] = $unset_lang_code;
        }
    }


    if($is_subdomain ){
        $link_parts['name'] = '';
        $original_url = '';
        $link_parts['extension'] = '';
    }

    $url = join('', $link_parts);
    if (!empty($parsed_query)) {
        $url .= '?' . http_build_query($parsed_query). $fragment ;
    }
    return $url;
}

function fn_get_vendor_profile_page_seo($company_id){
    if(Registry::get('addons.wk_vendor_subdomain.profile_page_seo')){
        foreach (fn_get_translation_languages() as $lc => $_v) {
            fn_create_seo_name(0, 's', Registry::get('addons.wk_vendor_subdomain.profile_page_seo'), 0,'companies.view', '', $lc);
        }
    }else{
        fn_delete_seo_name(0, 's', 'companies.view');
    }
}
?>