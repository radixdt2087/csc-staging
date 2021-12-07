<?php
/**
 * Store Pickup - CS-Cart Store Pickup Shipping Method for CS-Cart and Multivendor
 *
 * PHP version 7.1
 *
 * @category  Addon
 * @package   Multi-vendor/CS-Cart
 * @author    Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2010 Webkul Software Pvt. Ltd. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version   GIT:1.0
 * @link      Technical Support:  http://webkul.uvdesk.com
 */
if (!defined('BOOTSTRAP')) {
    die('Access Denied');
}
use Tygh\Registry;
use Tygh\Tools\SecurityHelper;
use Tygh\Enum\OutOfStockActions;
use Tygh\Enum\ProductTracking;
/**
 * Fn_Wk_Store_Pickup_install function For showing successfull installation of Addon
 *
 * @return void
 */
function Fn_Wk_Store_Pickup_install() 
{
    fn_set_notification(
        'S', __('well_done'), __(
            'wk_webkul_user_guide_content', array(
                '[support_link]'    => 'https://webkul.uvdesk.com/en/customer/create-ticket/',
                '[user_guide]'      => 'https://webkul.com/blog/cs-cart-store-pickup/',
                '[addon_name]'      => fn_get_lang_var('wk_store_pickup')
            )
        )
    );
}
/**
 * Fn_Wk_Store_Pickup_uninstall function
 *
 * @return void
 */
function Fn_Wk_Store_Pickup_uninstall() 
{
    
}
/**
 * Fn_Update_Store_Pickup_point function
 *
 * @param array   $data 
 * @param integer $store_id 
 * @param string  $lang_code  
 * 
 * @return void
 */
function Fn_Update_Store_Pickup_point($data = array(), $store_id = 0, $lang_code = CART_LANGUAGE)  
{
    SecurityHelper::sanitizeObjectData('wk_store_pickup_point', $data);    
    if ($store_id) {
        $data['lang_code'] = $lang_code;
        if (Registry::get('runtime.company_id') && isset($data['company_id'])) {
            unset($data['company_id']);
        }
        db_query("UPDATE ?:wk_store_pickup_points SET ?u WHERE store_id = ?i", $data, $store_id);
        db_query("UPDATE ?:wk_store_pickup_point_descriptions SET ?u WHERE store_id = ?i AND lang_code = ?s", $data, $store_id, $lang_code);
    } else {
        $data['company_id'] = Registry::get('runtime.company_id')?Registry::get('runtime.company_id'):$data['company_id'];
        $store_id = db_query("INSERT INTO ?:wk_store_pickup_points ?e", $data);
        $data['store_id'] = $store_id;
        foreach (fn_get_translation_languages() as $data['lang_code'] => $v) {
            db_query("INSERT INTO ?:wk_store_pickup_point_descriptions ?e", $data);
        }
    }
    return $store_id;
}
/**
 * Fn_Get_Store_Pickup_point function
 *
 * @param integer $store_id 
 * @param string  $lang_code  
 * 
 * @return void
 */
function Fn_Get_Store_Pickup_point($store_id = 0, $lang_code = CART_LANGUAGE) 
{
    $condition = $join = '';
    if (Registry::get('runtime.company_id') && AREA == 'A') {
        $condition .= db_quote(" AND ?:wk_store_pickup_points.company_id = ?i", Registry::get('runtime.company_id'));
    }
    if (AREA == 'C') {
        $condition .= db_quote(" AND ?:wk_store_pickup_points.status = ?s", 'A');
    }
    $condition .= db_quote(" AND ?:wk_store_pickup_points.store_id = ?i", $store_id);
    
    $join .= db_quote(" LEFT JOIN ?:wk_store_pickup_point_descriptions ON ?:wk_store_pickup_points.store_id = ?:wk_store_pickup_point_descriptions.store_id ");

    $condition .= db_quote(" AND ?:wk_store_pickup_point_descriptions.lang_code = ?s", $lang_code);

    return db_get_row("SELECT * FROM ?:wk_store_pickup_points $join WHERE 1 $condition");
}
/**
 * Fn_Get_Store_Pickup_points function
 *
 * @param array   $params 
 * @param integer $items_per_page 
 * @param string  $lang_code  
 * 
 * @return void
 */
function Fn_Get_Store_Pickup_points($params = array(), $items_per_page = 0, $lang_code = CART_LANGUAGE) 
{
    $params = array_merge(
        array(
            'items_per_page' => $items_per_page,
            'page' => 1
        ), $params
    );
    $fields = '';
    $sortings = array (
        'store_id' => '?:wk_store_pickup_points.store_id',
        'title' => '?:wk_store_pickup_point_descriptions.title',
        'status' => '?:wk_store_pickup_points.status',
        'city'  => '?:wk_store_pickup_point_descriptions.city',
        'country'=> '?:wk_store_pickup_points.country',
        'pincode'=> '?:wk_store_pickup_points.pincode',
        'address'=> '?:wk_store_pickup_point_descriptions.address'
    );

    $condition = $limit = $join = '';
   
    if (!empty($params['title'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_point_descriptions.title LIKE ?l", '%'.$params['title'] . '%');
    }
    if (!empty($params['address'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_point_descriptions.address LIKE ?l", '%'.$params['address'] . '%');
    }
    if (!empty($params['company_id'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_points.company_id = ?i", $params['company_id']);
    }
    if (!empty($params['status'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_points.status = ?s", $params['status']);
    }
    if (!empty($params['phone'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_points.phone = ?s", $params['phone']);
    }
    if (!empty($params['city'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_point_descriptions.city = ?i", $params['city']);
    }
    if (!empty($params['store_id'])) {
        $condition = db_quote(" AND  ?:wk_store_pickup_points.store_id = ?i", $params['store_id']);
    }
    if (!empty($params['pincode'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_points.pincode = ?i", $params['pincode']);
    }
    if (Registry::get('runtime.company_id') && AREA == 'A') {
        $condition .= db_quote(" AND ?:wk_store_pickup_points.company_id = ?i", Registry::get('runtime.company_id'));
    }

    if (!empty($params['q'])) {
        $search_words = $params['q'];
        $condition .= db_quote(" AND (?:wk_store_pickup_point_descriptions.title LIKE ?l OR ?:wk_store_pickup_point_descriptions.city LIKE ?l OR ?:wk_store_pickup_point_descriptions.address LIKE ?l OR ?:wk_store_pickup_points.pincode LIKE ?l OR ?:country_descriptions.country LIKE ?l OR ?:wk_store_pickup_point_descriptions.state LIKE ?l OR ?:state_descriptions.state LIKE ?l)", '%'.$search_words . '%', '%'.$search_words . '%', '%'.$search_words . '%', '%'.$search_words . '%', '%'.$search_words . '%', '%'.$search_words . '%', '%'.$search_words . '%');
    }
    $sorting = db_sort($params, $sortings, 'store_id', 'asc');

    $join .= db_quote(" LEFT JOIN ?:wk_store_pickup_point_descriptions ON ?:wk_store_pickup_points.store_id = ?:wk_store_pickup_point_descriptions.store_id");

    $join .= db_quote(" LEFT JOIN ?:country_descriptions ON ?:wk_store_pickup_points.country = ?:country_descriptions.code AND ?:country_descriptions.lang_code = ?s", $lang_code);
    $join .= db_quote(" LEFT JOIN ?:states ON ?:wk_store_pickup_point_descriptions.state = ?:states.code AND ?:country_descriptions.code = ?:states.country_code");
    $join .= db_quote(" LEFT JOIN ?:state_descriptions ON ?:states.state_id = ?:state_descriptions.state_id AND ?:state_descriptions.lang_code = ?s", $lang_code);
    
    $condition .= db_quote(" AND ?:wk_store_pickup_point_descriptions.lang_code = ?s", $lang_code);

    if (isset($params['product_id']) && AREA == 'C') {
        $join .= db_quote(" LEFT JOIN ?:wk_store_pickup_products ON ?:wk_store_pickup_points.store_id = ?:wk_store_pickup_products.store_id ");
        $condition .= db_quote(" AND ?:wk_store_pickup_products.product_id = ?i AND ?:wk_store_pickup_products.status = ?s", $params['product_id'], 'A');
        $condition .= db_quote(" AND ?:wk_store_pickup_products.stock > ?i", 0);
        $fields .= ', ?:wk_store_pickup_products.stock';
    }
    if (AREA == 'C') {
        $condition .= db_quote(" AND ?:wk_store_pickup_points.status = ?s", 'A');
    }
    try{
        if (!empty($params['items_per_page'])) {
            $params['total_items'] = db_get_field("SELECT COUNT(*) FROM ?:wk_store_pickup_points $join WHERE 1 $condition");
            $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
        }
        $wk_store_pickup_points = db_get_hash_array("SELECT ?:wk_store_pickup_points.*, ?:wk_store_pickup_point_descriptions.* , ?:country_descriptions.country as country_title $fields FROM ?:wk_store_pickup_points $join WHERE 1  $condition $sorting $limit", 'store_id');
        if (AREA == 'A') {
            foreach ($wk_store_pickup_points as $store_id=>&$store_pickup_point) {
                $condition = '';
                $join = db_quote(" INNER JOIN ?:orders ON ?:wk_store_pickup_orders.order_id = ?:orders.order_id");
                if (Registry::get('runtime.company_id')) { 
                    $condition = db_quote(" AND ?:orders.company_id = ?i", Registry::get('runtime.company_id'));
                }
                $store_pickup_point['orders'] = db_get_field("SELECT count(id) FROM ?:wk_store_pickup_orders $join WHERE store_id = ?i $condition", $store_id);
                $store_pickup_point['products'] = db_get_field("SELECT count(product_id) FROM ?:wk_store_pickup_products WHERE store_id = ?i", $store_id);
            }
        }
    }catch(Exception $e) {
        fn_set_notification('E', __("error"), $e->getMessage());
    }

    return array($wk_store_pickup_points, $params);
}
/**
 * Undocumented function
 *
 * @param integer $store_id 
 * @param [type]  $lang_code 
 * 
 * @return void
 */
function Fn_Get_Store_Pickup_name($store_id = 0, $lang_code = CART_LANGUAGE) 
{
    if (!empty($store_id)) {
        return db_get_field('SELECT `title` FROM ?:wk_store_pickup_point_descriptions WHERE store_id = ?i AND lang_code = ?s', $store_id, $lang_code);
    }
    return false;
}
/**
 * Undocumented function
 *
 * @param [type] $params 
 * 
 * @return void
 */
function Fn_Add_Product_To_Store_Pickup_points($params) 
{
    $store_id = isset($params['store_id'])?$params['store_id']:0;
    $store_product_data = isset($params['pickup_stores'])?$params['pickup_stores']:array();
    $updated = $skipped = 0;
    try {
        $store_data = Fn_Get_Store_Pickup_point($store_id);
        if ($store_product_data && $store_id && $store_data) {
            foreach ($store_product_data as &$_data) {
                if (!db_get_field("SELECT product_id FROM ?:products WHERE product_id = ?i AND company_id = ?i", $_data['product_id'], $store_data['company_id'])) {
                    $skipped++;
                    continue;
                }
                $updated++;
                if (isset($_data['max_stock'])) {
                    unset($_data['max_stock']);
                }
                $parent_product_id = 0;
                $parent_product_id = $product_id = $_data['product_id'];
                $is_configurable = false;
                $condition = db_quote(" AND store_id =?i", $store_id);
                $_data['store_id'] = $store_id;

                db_query("REPLACE INTO ?:wk_store_pickup_products ?e", $_data);
                db_query("UPDATE ?:products SET enable_store_pickup = ?s WHERE product_id = ?i", 'Y', $parent_product_id);
            }
            if(isset($_SESSION['wk_store_product']))
                unset($_SESSION['wk_store_product'][$store_id]);
          
            fn_set_notification("N", __("notice"), __("products_added_store_pickup_points_successfully", array('[count]'=>$updated, '[store]'=>$store_data['title'])));
        }
    }catch(Exception $e) {
        fn_set_notification('E', __("error"), $e->getMessage());
    }

}
/**
 * Fn_Get_Store_Pickup_products function
 *
 * @param array   $params 
 * @param integer $items_per_page 
 * @param string  $lang_code  
 * 
 * @return void
 */
function Fn_Get_Store_Pickup_products($params = array(), $items_per_page = 0, $lang_code = CART_LANGUAGE) 
{
    $params = array_merge(
        array(
            'items_per_page' => $items_per_page,
            'page' => 1
        ), $params
    );

    $sortings = array (
        'store_id' => '?:wk_store_pickup_products.store_id',
        'title' => '?:wk_store_pickup_point_descriptions.title',
        'status' => '?:wk_store_pickup_products.status',
        'stock'  => '?:wk_store_pickup_products.stock',
        'product_id'=> '?:wk_store_pickup_products.product_id',
        'product'=>'?:product_descriptions.product'
    );

    $condition = $limit = $join = '';
   
    if (!empty($params['product'])) {
        $condition .= db_quote(" AND ?:product_descriptions.product LIKE ?l", '%'.$params['product'] . '%');
    }
    if (!empty($params['title'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_point_descriptions.title LIKE ?l", '%'.$params['title'] . '%');
    }
    if (!empty($params['company_id'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_points.company_id = ?i", $params['company_id']);
    }
    if (!empty($params['status'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_products.status = ?s", $params['status']);
    }
    if (!empty($params['product_id'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_products.product_id = ?i", $params['product_id']);
    }
    if (!empty($params['store_id'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_products.store_id = ?i", $params['store_id']);
    }
    if (!empty($params['stock'])) {
        $condition = db_quote(" AND  ?:wk_store_pickup_products.stock = ?i", $params['stock']);
    }
    if (Registry::get('runtime.company_id')) {
        $condition .= db_quote(" AND ?:wk_store_pickup_points.company_id = ?i", Registry::get('runtime.company_id'));
    }
    if (!empty($params['q'])) {
        $search_words = $params['q'];
        $condition .= db_quote(" AND (?:wk_store_pickup_point_descriptions.title LIKE ?l OR ?:product_descriptions.product LIKE ?l OR ?:wk_store_pickup_point_descriptions.city LIKE ?l OR ?:wk_store_pickup_point_descriptions.address LIKE ?l OR ?:wk_store_pickup_points.pincode LIKE ?l OR ?:wk_store_pickup_products.stock LIKE ?l)", '%'.$search_words . '%', '%'.$search_words . '%', '%'.$search_words . '%', '%'.$search_words . '%', '%'.$search_words . '%', '%'.$search_words . '%');
    }
    if (AREA == 'C') {
        $condition .= db_quote(" AND ?:wk_store_pickup_products.status = ?s", 'A');
        $condition .= db_quote(" AND ?:wk_store_pickup_points.status = ?s", 'A');
    }
    $condition .= db_quote(" AND ?:product_descriptions.lang_code = ?s", $lang_code);

    $condition .= db_quote(" AND ?:wk_store_pickup_point_descriptions.lang_code = ?s", $lang_code);

    $sorting = db_sort($params, $sortings, 'store_id', 'asc');
    $join .= db_quote(" LEFT JOIN ?:wk_store_pickup_points ON ?:wk_store_pickup_products.store_id = ?:wk_store_pickup_points.store_id");

    $join .= db_quote(" LEFT JOIN ?:wk_store_pickup_point_descriptions ON ?:wk_store_pickup_points.store_id = ?:wk_store_pickup_point_descriptions.store_id ");

    $join .= db_quote(" LEFT JOIN ?:product_descriptions ON ?:wk_store_pickup_products.product_id = ?:product_descriptions.product_id");
    
    $join .= db_quote(" LEFT JOIN ?:products ON ?:wk_store_pickup_products.product_id = ?:products.product_id AND ?:products.enable_store_pickup = ?s", 'Y');

    if (!empty($params['items_per_page'])) {
        $params['total_items'] = db_get_field("SELECT COUNT(*) FROM ?:wk_store_pickup_products $join WHERE 1 $condition");
        $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
    }

    $wk_store_pickup_products = db_get_array("SELECT ?:wk_store_pickup_products.*,?:product_descriptions.product, ?:wk_store_pickup_point_descriptions.title,?:wk_store_pickup_points.store_id as store_id FROM ?:wk_store_pickup_products $join WHERE 1  $condition $sorting $limit");

    return array($wk_store_pickup_products, $params);
}

/**
 * Fn_Get_Store_Pickup_order function
 *
 * @param array   $params 
 * @param integer $items_per_page 
 * @param string  $lang_code  
 * 
 * @return void
 */
function Fn_Get_Store_Pickup_orders($params = array(), $items_per_page = 0, $lang_code = CART_LANGUAGE) 
{
    $params = array_merge(
        array(
            'items_per_page' => $items_per_page,
            'page' => 1
        ), $params
    );

    $sortings = array (
        'id' => '?:wk_store_pickup_orders.id',
        'store_id' => '?:wk_store_pickup_orders.store_id',
        'order_id' => '?:wk_store_pickup_orders.order_id',
        'title' => '?:wk_store_pickup_point_descriptions.title',
        'status' => '?:wk_store_pickup_orders.status',
        'timestamp' => '?:wk_store_pickup_orders.timestamp',
        'update_timestamp'=>'?:wk_store_pickup_orders.update_timestamp',
        'amount'  => '?:wk_store_pickup_orders.amount',
        'product_id'=> '?:wk_store_pickup_orders.product_id',
        'product'  => '?:product_descriptions.product',
        'customer' => array("?:orders.lastname", "?:orders.firstname"),
    );

    $condition = $limit = $join = '';
    if (!empty($params['product'])) {
        $condition .= db_quote(" AND ?:product_descriptions.product LIKE ?l", '%'.$params['product'] . '%');
    }
    if (!empty($params['title'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_point_descriptions.title LIKE ?l", '%'.$params['title'] . '%');
    }
    if (!empty($params['company_id'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_points.company_id = ?i", $params['company_id']);
    }
    if (!empty($params['status'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_orders.status = ?s", $params['status']);
    }
    if (!empty($params['order_id'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_orders.order_id = ?s", $params['order_id']);
    }
    if (!empty($params['product_id'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_orders.product_id = ?i", $params['product_id']);
    }
    if (!empty($params['store_id'])) {
        $condition .= db_quote(" AND ?:wk_store_pickup_orders.store_id = ?i", $params['store_id']);
    }
    if (!empty($params['amount'])) {
        $condition = db_quote(" AND  ?:wk_store_pickup_orders.amount = ?i", $params['amount']);
    }
    if (!empty($params['cname'])) {
        $union_condition = ' AND ';
        if (isset($params['cname']) && fn_string_not_empty($params['cname'])) {
            $customer_name = fn_explode(' ', $params['cname']);
            $customer_name = array_filter($customer_name, "fn_string_not_empty");
            if (sizeof($customer_name) == 2) {
                $condition .= db_quote(" $union_condition ?:orders.firstname LIKE ?l AND ?:orders.lastname LIKE ?l", "%" . array_shift($customer_name) . "%", "%" . array_shift($customer_name) . "%");
            } else {
                $condition .= db_quote(" $union_condition (?:orders.firstname LIKE ?l OR ?:orders.lastname LIKE ?l)", "%" . trim($params['cname']) . "%", "%" . trim($params['cname']) . "%");
            }
        }
    }
    if (Registry::get('runtime.company_id')) {
        $condition .= db_quote(" AND ?:orders.company_id = ?i", Registry::get('runtime.company_id'));
    }
    
    $sorting = db_sort($params, $sortings, 'id', 'desc');

    $join .= db_quote(" LEFT JOIN ?:wk_store_pickup_points ON ?:wk_store_pickup_orders.store_id = ?:wk_store_pickup_points.store_id");

    $join .= db_quote(" LEFT JOIN ?:product_descriptions ON ?:wk_store_pickup_orders.product_id = ?:product_descriptions.product_id AND ?:product_descriptions.lang_code = ?s", $lang_code);
    

    $join .= db_quote(" INNER JOIN ?:orders ON ?:wk_store_pickup_orders.order_id = ?:orders.order_id");

    $join .= db_quote(" LEFT JOIN ?:wk_store_pickup_point_descriptions ON ?:wk_store_pickup_points.store_id = ?:wk_store_pickup_point_descriptions.store_id");

    $condition .= db_quote(" AND ?:wk_store_pickup_point_descriptions.lang_code = ?s", $lang_code);

    if (!empty($params['items_per_page'])) {
        $params['total_items'] = db_get_field("SELECT COUNT(*) FROM ?:wk_store_pickup_orders $join WHERE 1 $condition");
        $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
    }

    $wk_store_pickup_orders = db_get_array("SELECT ?:wk_store_pickup_orders.*, ?:wk_store_pickup_point_descriptions.title, ?:orders.company_id as company_id,?:orders.firstname, ?:orders.lastname,?:orders.user_id, ?:orders.email,?:product_descriptions.product, ?:wk_store_pickup_points.company_id as s_company_id FROM ?:wk_store_pickup_orders $join WHERE 1  $condition $sorting $limit");
    return array($wk_store_pickup_orders, $params);
}

/**
 * Fn_Delete_Store_Pickup_point function
 *
 * @param array $store_ids 
 * 
 * @return void
 */
function Fn_Delete_Store_Pickup_point($store_ids = array()) 
{
    db_query("DELETE FROM ?:wk_store_pickup_points WHERE store_id IN (?n)", $store_ids);
    db_query("DELETE FROM ?:wk_store_pickup_point_descriptions WHERE store_id IN (?n)", $store_ids);
    db_query("DELETE FROM ?:wk_store_pickup_products WHERE store_id IN (?n)", $store_ids);
}
/**
 * Fn_Delete_Store_Pickup_product function
 *
 * @param array   $ids 
 * @param integer $store_id 
 * 
 * @return void
 */
function Fn_Delete_Store_Pickup_product($ids = array(), $store_id = 0) 
{
    if ($ids) {
        $condition = '';
        if ($store_id) {
            $condition = db_quote(" AND store_id = ?i", $store_id);
        }
        db_query("DELETE FROM ?:wk_store_pickup_products WHERE id IN (?n)", $ids);
        if($store_id)
            fn_set_notification("N", __("notice"), __("wk_products_deleted_from_store", array('[store]'=>Fn_Get_Store_Pickup_name($store_id), '[count]'=>count($ids))));
    }
}
/**
 * function to get all store pickup point for any companmy
 *
 * @param [type] $company_id 
 * @param string $lang_code  
 * 
 * @return void
 */
function Fn_Get_Company_Store_Pickup_points($company_id = 0, $lang_code = CART_LANGUAGE) 
{
    return db_get_hash_single_array("SELECT title, ?:wk_store_pickup_points.store_id FROM ?:wk_store_pickup_points LEFT JOIN ?:wk_store_pickup_point_descriptions ON ?:wk_store_pickup_points.store_id = ?:wk_store_pickup_point_descriptions.store_id WHERE company_id = ?i AND status = ?s AND lang_code = ?s", array('store_id', 'title'), $company_id, 'A', $lang_code);
}
/**
 * Function to get short info of any product store pickup point.
 *
 * @param integer $product_id 
 * @param integer $stock 
 * @param integer $store_id 
 * @param [type]  $lang_code 
 * 
 * @return void
 */
function Fn_Get_Product_Store_Pickup_Points_Short_info($product_id = 0, $stock=0, $store_id = 0, $lang_code = CART_LANGUAGE) 
{
    $condition = $join = '';
    
    $condition .= db_quote(" AND (?:wk_store_pickup_products.product_id = ?i OR ?:wk_store_pickup_products.variation_id = ?i)", $product_id, $product_id);

    $condition .= db_quote(" AND ?:wk_store_pickup_products.stock >= ?i", $stock);
    if (AREA == 'C') {
        $condition .= db_quote(" AND ?:wk_store_pickup_products.status = ?s", 'A');
        $condition .= db_quote(" AND ?:wk_store_pickup_points.status = ?s", 'A');
        $join .= db_quote("INNER JOIN ?:wk_store_pickup_points ON ?:wk_store_pickup_products.store_id = ?:wk_store_pickup_points.store_id ");
        $condition .= db_quote(" AND ?:wk_store_pickup_products.stock > ?i", 0);  
    }

    $condition .= db_quote(" AND ?:wk_store_pickup_point_descriptions.lang_code = ?s", $lang_code);

    $condition .= db_quote(" AND ?:products.enable_store_pickup = ?s", 'Y');

    if ($store_id) {
        $condition .= db_quote(" AND ?:wk_store_pickup_products.store_id = ?i", $store_id);
    }
    
    $join .= db_quote("INNER JOIN ?:wk_store_pickup_point_descriptions ON ?:wk_store_pickup_products.store_id = ?:wk_store_pickup_point_descriptions.store_id ");

    $join .= db_quote("LEFT JOIN ?:products ON ?:products.product_id = ?:wk_store_pickup_products.product_id");

    $product_stores = db_get_hash_array("SELECT ?:wk_store_pickup_point_descriptions.title, ?:wk_store_pickup_products.store_id as store_id, ?:wk_store_pickup_products.stock, ?:wk_store_pickup_products.status, ?:products.store_pickup_only FROM ?:wk_store_pickup_products  $join WHERE 1 $condition ", 'store_id');
    return $product_stores;
}
/**
 * Get Store pickup points short info for selected product
 *
 * @param [type] $product_id 
 * @param [type] $store_id 
 * @param mixed  $stock 
 * @param mixed  $lang_code 
 * 
 * @return void
 */
function Fn_Get_Selected_Product_Store_Pickup_Point_Short_info($product_id, $store_id = 0, $stock=0, $lang_code = CART_LANGUAGE) 
{
    $product_pickup_info = Fn_Get_Product_Store_Pickup_Points_Short_info($product_id, $stock, $store_id);
    return $product_pickup_info?reset($product_pickup_info):$product_pickup_info;
}
/**
 * Fn_Wk_Store_Pickup_Update_Product_post function Update_Product_post Hook Handler
 *
 * @param [type] $product_data 
 * @param [type] $product_id 
 * @param [type] $lang_code  
 * @param [type] $create 
 * 
 * @return void
 */
function Fn_Wk_Store_Pickup_Update_Product_post(&$product_data, $product_id, $lang_code, &$create) 
{
    $store_ids = array();
    if (isset($product_data['enable_store_pickup']) && $product_data['enable_store_pickup'] == 'Y') {
        if (isset($product_data['pickup_stores'])) {
            db_query("DELETE FROM ?:wk_store_pickup_products WHERE product_id = ?i ", $product_id);
            foreach ($product_data['pickup_stores'] as $key=>&$pickup_store) {
                if (in_array($pickup_store['store_id'], $store_ids)) {
                    unset($product_data['pickup_stores'][$key]);
                    continue;
                }
                if (!empty($pickup_store['store_id']) && $pickup_store['stock'] >= 0) {
                    $store_ids[] = $pickup_store['store_id'];
                } else {
                    unset($product_data['pickup_stores'][$key]);
                }
            }
        }
        if (isset($product_data['pickup_stores']) && !empty($product_data['pickup_stores']) && $store_ids) {
            foreach ($product_data['pickup_stores'] as $key => $pickup_data) {
                $avail_stock = Fn_Products_Available_Stock_For_Store_Pickup_points($pickup_data['product_id'], $pickup_data['store_id']);
                if ($avail_stock){
                    $pickup_data['stock']  = $avail_stock >= $pickup_data['stock']?$pickup_data['stock']:$avail_stock;
                    db_query("INSERT INTO ?:wk_store_pickup_products ?e", $pickup_data);
                }
            }
            // db_query("INSERT INTO ?:wk_store_pickup_products ?m", $product_data['pickup_stores']);
        } else{
            db_query("UPDATE ?:products SET enable_store_pickup = ?s WHERE product_id = ?i", 'N', $product_id);
        }
    }
}
/**
 * Hook handler: update product data
 *
 * @param [type] $product_data 
 * @param [type] $auth 
 * @param [type] $preview 
 * @param [type] $lang_code 
 * 
 * @return void
 */
function Fn_Wk_Store_Pickup_Get_Product_Data_post(&$product_data, $auth, $preview, $lang_code) 
{
    $parent_product_id = $product_id = $product_data['product_id'];
    if ($product_data && isset($product_data['enable_store_pickup']) && ($product_data['enable_store_pickup'] == 'Y' || AREA == 'A')) {
        $condition = '';
        if (AREA == 'C') {
            $condition .= db_quote(" AND ?:wk_store_pickup_products.status = ?s", 'A');
        }
        $product_data['pickup_stores'] = Fn_Get_Product_Store_Pickup_Points_Short_info($parent_product_id, 0);
        if (AREA == 'C' && empty($product_data['pickup_stores']) && isset($product_data['enable_store_pickup']) && $product_data['enable_store_pickup'] == 'Y' && $product_data['store_pickup_only'] == 'Y') {
            $product_data['amount'] = 0;
        }
    }
}
/**
 * Hook Handler: Process product delete (run after product is deleted)
 *
 * @param int  $product_id      Product identifier
 * @param bool $product_deleted True if product was deleted successfully, false otherwise
 * 
 * @return mixed
 */
function Fn_Wk_Store_Pickup_Delete_Product_post($product_id, $product_deleted) 
{
    if($product_deleted)
        db_query("DELETE FROM ?:wk_store_pickup_products WHERE product_id = ?i", $product_id);
}

/**
 * Function delete order
 *
 * @param int $order_id 
 * 
 * @return int
 */
function Fn_Wk_Store_Pickup_Delete_order($order_id)
{
    db_query("DELETE FROM ?:wk_store_pickup_orders WHERE order_id = ?i", $order_id);
}
/**
 * Adds product to cart.
 *
 * @param array $product_data List of products data
 * @param array $cart         Array of cart content and user information necessary for purchase
 * @param array $auth         Array of user authentication data (e.g. uid, usergroup_ids, etc.)
 * @param bool  $update       Flag, if true that is update mode. Usable for order management
 *
 * @return array|bool   Return list of added product IDs or false otherwise.
 */
function Fn_Wk_Store_Pickup_Pre_Add_To_cart(&$product_data, $cart, $auth, $update) 
{
    foreach ($product_data as $key => &$value) {
        if (isset($value['product_id'])) {
            if (empty($value['extra'])) {
                $value['extra'] = array();
            }
            $product_id = $value['product_id'];
            list($enabled_store_pickup, $is_store_pickup_only) = Fn_Product_Is_For_Store_Pickup_only($product_id);
            if ($enabled_store_pickup) {
                $store_id = !empty($value['wk_store_id'])?$value['wk_store_id']:(!empty($value['extra']['wk_store_id'])?$value['extra']['wk_store_id']:0);
                $product_stores = Fn_Get_Product_Store_Pickup_Points_Short_info($product_id, 0, 0);
                if (!$store_id && $is_store_pickup_only && $product_stores && !$update) {
                    unset($product_data[$key]);
                    fn_set_notification("E", __("error"), __("please_select_an_store_pickup_points_first_before_order"));
                    $update = false;
                    continue;
                } elseif ($store_id && fn_check_is_product_assigned_to_store($product_id, $store_id)) {
                    $value['extra']['wk_store_id'] = $store_id;
                }
                if (empty($product_stores) && $is_store_pickup_only) {
                    $product_name = isset($value['product'])?$value['product']:fn_get_product_name($value['product_id']);
                    if ($update) {
                        fn_set_notification("W", __("warning"), __("wk_product_remove_from_cart_becuase_no_store_assigned_to_this_product", array('[product]'=> $product_name)));
                    } else {
                        fn_set_notification("W", __("warning"), __("wk_product_can_not_be_add_to_cart_becuase_no_store_assigned_to_this_product", array('[product]'=> $product_name)));
                    }
                    unset($product_data[$key]);
                    if (isset($cart['products'][$key])) {
                        fn_delete($cart, $key);
                    }
                    continue;
                }
                $value['extra']['wk_store_pickup_only'] = $is_store_pickup_only;
                $value['extra']['enabled_store_pickup'] = $enabled_store_pickup;
                $value['extra']['wk_store_pickup_points'] = $product_stores;
            }
        }
    }
}

/**
 * Processes cart data after calculating all prices and other data (taxes, shippings etc) including products group
 *
 * @param array  $cart                  Cart data
 * @param array  $auth                  Auth data
 * @param string $calculate_shipping    // 1-letter flag
 * @param bool   $calculate_taxes       Flag determines if taxes should be calculated
 * @param string $options_style         1-letter flag
 * @param bool   $apply_cart_promotions Flag determines if promotions should be applied to the cart
 * @param array  $cart_products         Cart products
 * @param array  $product_groups        Products grouped by packages, suppliers, vendors
 * 
 * @return mixed
 */
function Fn_Wk_Store_Pickup_Calculate_Cart_post($cart, $auth, $calculate_shipping, $calculate_taxes, $options_style, $apply_cart_promotions, &$cart_products, &$product_groups) 
{
    foreach ($cart_products as $cart_id=>&$product) {
        if (!empty($product['extra']['wk_store_id']) && isset($product['wk_is_edp'])) {
            $product['is_edp'] = $product['wk_is_edp'];// for removing product from downloadable_product
        }
    }
    foreach ($product_groups as $group_key=>&$group) {
        foreach ($group['products'] as $key=>&$product ) {
            if (!empty($product['extra']['wk_store_id']) && isset($product['wk_is_edp'])) {
                $product['is_edp'] = $product['wk_is_edp']; // for removing product from downloadable_product
            }
        }
    }
}
/**
 * Hook Handler: Executes when calculating cart content after products data is collected.
 * Allows to modify cart content and affect further processes like promotions or shipping calculation.
 *
 * @param array $cart                  Array of the cart contents and user information necessary for purchase
 * @param array $cart_products         Array of products in cart
 * @param array $auth                  Array of user authentication data (e.g. uid, usergroup_ids, etc.)
 * @param bool  $apply_cart_promotions Whether promotions have to be applied to cart content
 * 
 * @return mixed
 */
function Fn_Wk_Store_Pickup_Calculate_Cart_items(&$cart, &$cart_products, $auth, $apply_cart_promotions)
{
    foreach ($cart['products'] as $cart_id=>&$product) {
        list($enabled_store_pickup, $is_store_pickup_only) = Fn_Product_Is_For_Store_Pickup_only($product['product_id']);
        if ($enabled_store_pickup){
            $pickup_point_info =  Fn_Get_Product_Store_Pickup_Points_Short_info($product['product_id']);
            $selected_store_id = !empty($product['extra']['wk_store_id'])?$product['extra']['wk_store_id']:0;
            if ($selected_store_id && $enabled_store_pickup && !empty($pickup_point_info[$selected_store_id])) {
                $product['extra']['wk_store_id'] = $selected_store_id;
                $cart_products[$cart_id]['extra']['wk_store_id'] =  $selected_store_id;
                if ($pickup_point_info[$selected_store_id]['stock'] >= $cart_products[$cart_id]['amount']){
                    $cart_products[$cart_id]['wk_is_edp'] = $cart_products[$cart_id]['is_edp'];
                    $cart_products[$cart_id]['wk_free_shipping'] = $cart_products[$cart_id]['free_shipping'];
                    $cart_products[$cart_id]['wk_edp_shipping'] = $cart_products[$cart_id]['edp_shipping'];
                    $cart_products[$cart_id]['free_shipping'] = 'Y';
                    $cart_products[$cart_id]['edp_shipping'] = 'N';
                    $cart_products[$cart_id]['is_edp'] = 'Y';
                }
            } else{
                unset($product['extra']['wk_store_id'], $cart_products[$cart_id]['extra']['wk_store_id']);
            }
            if ($enabled_store_pickup && $is_store_pickup_only && !$selected_store_id) {
                $cart_products[$cart_id]['wk_is_edp'] = $cart_products[$cart_id]['is_edp'];
                $cart_products[$cart_id]['wk_free_shipping'] = $cart_products[$cart_id]['free_shipping'];
                $cart_products[$cart_id]['wk_edp_shipping'] = $cart_products[$cart_id]['edp_shipping'];
                $cart_products[$cart_id]['free_shipping'] = 'Y';
                $cart_products[$cart_id]['edp_shipping'] = 'N';
                $cart_products[$cart_id]['is_edp'] = 'Y';
            }
            $product['extra']['wk_store_pickup_points'] = $cart_products[$cart_id]['extra']['wk_store_pickup_points'] = $pickup_point_info;
            $product['extra']['enabled_store_pickup'] = $cart_products[$cart_id]['extra']['enabled_store_pickup'] = $enabled_store_pickup;
            $product['extra']['wk_store_pickup_only'] = $cart_products[$cart_id]['extra']['wk_store_pickup_only'] = $is_store_pickup_only;
        }
    }
}
/**
 * Hook handler pre_place_order
 */
function Fn_Wk_Store_Pickup_Pre_Place_order(&$cart, &$allow, $product_groups)
{
    foreach ($cart['products'] as $key => &$value) {
        $product_id = $value['product_id'];
        list($enabled_store_pickup, $is_store_pickup_only) = Fn_Product_Is_For_Store_Pickup_only($product_id);
        if ($enabled_store_pickup) {
            $store_id = !empty($value['extra']['wk_store_id'])?$value['extra']['wk_store_id']:0;
            $product_stores = Fn_Get_Product_Store_Pickup_Points_Short_info($product_id, 0, 0);
            if (!$store_id && $is_store_pickup_only && $product_stores) {
                fn_set_notification("E", __("error"), __("please_select_an_store_pickup_points_first_before_order"));
                $allow = false;
                continue;
            }
            if ((empty($product_stores) || empty($product_stores[$store_id])) && $is_store_pickup_only ) {
                $product_name = isset($value['product'])?$value['product']:fn_get_product_name($value['product_id']);
                fn_set_notification("W", __("warning"), __("wk_product_remove_from_cart_becuase_no_store_assigned_to_this_product", array('[product]'=> $product_name)));
                // if (isset($cart['products'][$key])) {
                //     fn_delete($cart, $key);
                // }
                $allow = false;
                continue;
            }
            if (!empty($product_stores[$store_id]) && $product_stores[$store_id]['stock'] < $value['amount']) {
                unset($value['extra']['wk_store_id'], $value['extra']['wk_store_pickup_points'], $value['extra']['enabled_store_pickup'], $value['extra']['wk_store_pickup_only']);
            }
        }
    }
}
 /**
 * Hook Handler: Modifies product order details
 *
 * @param int   $order_id      Order identifier to create details for
 * @param array $cart          Cart contents
 * @param array $order_details Ordered product details
 * @param array $extra         Product extra parameters
 * 
 * @return mixed
 */
function Fn_Wk_Store_Pickup_Create_Order_details($order_id, $cart, &$order_details, &$extra) 
{
    foreach ($cart['products'] as $k=>$v) {
        if (!empty($v['extra']['wk_store_id']) && $order_details['item_id'] == $k) {
            if (fn_allowed_for("MULTIVENDOR")) {
                if (db_get_field("SELECT is_parent_order FROM ?:orders WHERE order_id = ?i", $order_details['order_id']) == 'Y') {
                    break;
                } 
            }
            $order_details['store_id'] = $v['extra']['wk_store_id'];
            $extra['wk_store_id'] = $v['extra']['wk_store_id'];
            $extra['wk_store_pickup'] = 'Y';
            $extra['wk_store_pickup_info'] = Fn_Get_Store_Pickup_point($v['extra']['wk_store_id']);
            Fn_Create_Store_Pickup_Point_Orders_mapping($order_details);
            $order_details['extra'] = serialize($extra);
            break;
        }
    }
}

/**
 * Function to check whether product is Store Pickup Product Only and store pickup enabled product
 *
 * @param int $product_id Store Product Identifier 
 * 
 * @return array Return array of bulean values cantaining store pickup product and store pickup only
 */
function Fn_Product_Is_For_Store_Pickup_only($product_id) 
{
    $is_store_pickup_only = db_get_field("SELECT store_pickup_only FROM ?:products WHERE product_id = ?i AND enable_store_pickup = ?s", $product_id, 'Y');
    if ($is_store_pickup_only == 'N') { 
        return array(true, false);
    } elseif ($is_store_pickup_only == 'Y') {
        return array(true, true); 
    }
    return array(false, false);
}

/**
 * Executes when changing order status before changing a product stock balance in the database.
 *
 * @param int     $order_id           Parent order identifier
 * @param string  $status_to          New parent order status (one char)
 * @param string  $status_from        Old parent order status (one char)
 * @param array   $force_notification Array with notification rules
 * @param boolean $place_order        True, if this function have been called inside of fn_place_order function.
 * @param array   $order_info         Child order identifier
 * @param string  $k                  Product cart ID
 * @param array   $v                  Cart product data
 * 
 * @return mixed
 */
function Fn_Wk_Store_Pickup_Change_Order_Status_Before_Update_Product_amount($order_id, $status_to, $status_from, $force_notification, $place_order, $order_info, $k, $v) 
{
    if (isset($v['extra']['wk_store_id']) && !empty($v['extra']['wk_store_id'])) {
        $order_statuses = fn_get_statuses(STATUSES_ORDER, array(), true, false, ($order_info['lang_code'] ? $order_info['lang_code'] : CART_LANGUAGE), $order_info['company_id']);
        if ($order_statuses[$status_to]['params']['inventory'] == 'D' && $order_statuses[$status_from]['params']['inventory'] == 'I') {
            // decrease amount
            $sign = '-';
            Fn_Update_Wk_Store_Product_amount($v, $sign);
        } elseif ($order_statuses[$status_to]['params']['inventory'] == 'I' && $order_statuses[$status_from]['params']['inventory'] == 'D') {
            // increase amount
            $sign = '+';
            Fn_Update_Wk_Store_Product_amount($v, $sign);
        }
        if ($status_from == $status_to) {
            // increase amount
            $sign = '+';
            Fn_Update_Wk_Store_Product_amount($v, $sign);
        }
    }
}
/**
 * Function to update store Pickup product
 *
 * @param array  $store_product Store Product Info
 * @param string $sign          Sign of action like + for inventory increase and - for inventory decrease
 * 
 * @return void
 */
function Fn_Update_Wk_Store_Product_amount($store_product, $sign = '-') 
{
    $product_id = $store_product['product_id'];
    $store_id = @$store_product['extra']['wk_store_id'];
    $amount = $store_product['amount'];
    if (Registry::get('settings.General.inventory_tracking') != 'Y') {
        return true;
    }
    $tracking_info = db_get_row("SELECT tracking, out_of_stock_actions FROM ?:products WHERE product_id = ?i", $product_id);
    // Return if product does not exist
    if (empty($tracking_info)) {
        return true;
    }
    $tracking = $tracking_info['tracking'];
    $allow_pre_orders = $tracking_info['out_of_stock_actions'] == OutOfStockActions::BUY_IN_ADVANCE;
    if ($tracking == ProductTracking::DO_NOT_TRACK) {
        return true;
    }
    $current_amount = db_get_field("SELECT stock FROM ?:wk_store_pickup_products WHERE product_id = ?i AND store_id = ?i", $product_id, $store_id);
    if ($sign == '-') {
        $new_amount = $current_amount - $amount;
    } else {
        $new_amount = $current_amount + $amount;
    }
    db_query("UPDATE ?:wk_store_pickup_products SET stock = ?i WHERE product_id = ?i AND store_id = ?i", $new_amount, $product_id, $store_id);
}
/**
 * Undocumented function
 *
 * @param [type] $order_details 
 * 
 * @return void
 */
function Fn_Create_Store_Pickup_Point_Orders_mapping($order_details) 
{
    $order_details['timestamp'] = TIME;
    db_query("REPLACE INTO ?:wk_store_pickup_orders ?e", $order_details);
}

/**
 * Fn_Store_Pickup_Google_langs function
 *
 * @param [type] $lang_code 
 * 
 * @return void
 */
function Fn_Store_Pickup_Google_langs($lang_code)
{
    $supported_langs = array ('en', 'eu', 'ca', 'da', 'nl', 'fi', 'fr', 'gl', 'de', 'el', 'it', 'ja', 'no', 'nn', 'ru' , 'es', 'sv', 'th');
    if (in_array($lang_code, $supported_langs)) {
        return $lang_code;
    }
    return '';
}

/**
 * Fn_Wk_Store_Pickup_Get_Products_pre function
 *
 * @param [type] $params 
 * @param [type] $items_per_page 
 * @param [type] $lang_code 
 * 
 * @return void
 */
function Fn_Wk_Store_Pickup_Get_Products_pre(&$params, $items_per_page, $lang_code) 
{
    if (defined('AJAX_REQUEST') && isset($_REQUEST['wk_store_id'])) {
        $store_id = $_REQUEST['wk_store_id'];
        $exclude_pids = db_get_fields("SELECT product_id FROM ?:wk_store_pickup_products WHERE store_id = ?i", $store_id);
        if (isset($_SESSION['wk_store_product'][$store_id]) && !empty($_SESSION['wk_store_product'][$store_id])) {
            foreach ($_SESSION['wk_store_product'][$store_id] as $store_product) {
                $exclude_pids[] = $store_product['product_id'];
            }
        }
        $params['exclude_pid'] = $exclude_pids;
        $params['company_id'] = $_REQUEST['company_id'];
    }
}

/**
 * Changes additional params for selecting products
 *
 * @param array  $params    Product search params
 * @param array  $fields    List of fields for retrieving
 * @param array  $sortings  Sorting fields
 * @param string $condition String containing SQL-query condition possibly prepended with a logical operator (AND or OR)
 * @param string $join      String with the complete JOIN information (JOIN type, tables and fields) for an SQL-query
 * @param string $sorting   String containing the SQL-query ORDER BY clause
 * @param string $group_by  String containing the SQL-query GROUP BY field
 * @param string $lang_code Two-letter language code (e.g. 'en', 'ru', etc.)
 * @param array  $having    HAVING condition
 * 
 * @return mixed
 */
function Fn_Wk_Store_Pickup_Get_products($params, &$fields, $sortings, $condition, $join, $sorting, $group_by, $lang_code, $having) 
{
    $fields['enable_store_pickup'] = 'products.enable_store_pickup';
    $fields['store_pickup_only'] = 'products.store_pickup_only';
}
/**
 * function to calculate disatance of pickup points from given location
 *
 * @param [type] $stores List of stores
 * 
 * @return void
 */
function Fn_Get_Store_Pickup_Point_Distance_From_Customer_location(&$stores) 
{
    $mode = 'driving';
    $api_key = trim(Registry::get('addons.wk_store_pickup.google_api_key'));
    $distance_unit = !empty($_REQUEST['wk_range_unit'])?$_REQUEST['wk_range_unit']:(!empty($_SESSION['wk_range_unit'])?$_SESSION['wk_range_unit']:Registry::get('addons.wk_store_pickup.wk_search_range_unit'));
    $radius = isset($_REQUEST['wk_radius'])?$_REQUEST['wk_radius']:(isset($_SESSION['wk_radius'])?$_SESSION['wk_radius']:Registry::get('addons.wk_store_pickup.wk_search_range'));
    $_SESSION['wk_range_unit'] = $distance_unit;
    if ($radius > Registry::get('addons.wk_store_pickup.wk_search_range_max')) {
        $radius = Registry::get('addons.wk_store_pickup.wk_search_range_max');
    }
    $_SESSION['wk_radius'] = $radius;
    $distance_units = $distance_unit == 'km'?'metric':'imperial';
    if ($stores) {
        $i = 1;
        $destinations = '';
        foreach ($stores as $store_data) {
            $destinations .= (!empty($store_data['latitude']) && !empty($store_data['longitude']))?($store_data['latitude'].','.$store_data['longitude']):$store_data['address'];
            if ($i != count($stores)) {
                $destinations .= "|";
            }
            $i++;
        }
       
        $customer_address = '';
        if (!empty($_REQUEST['wk_customer_lat'])) {
            $_SESSION['wk_customer_lat'] = $_REQUEST['wk_customer_lat'];
            $_SESSION['wk_customer_lng'] = $_REQUEST['wk_customer_lng'];
            $customer_address = $_REQUEST['wk_customer_lat'].','.$_REQUEST['wk_customer_lng'];
        }elseif(!empty($_SESSION['wk_customer_lat'])){
            $customer_address = $_SESSION['wk_customer_lat'].','.$_SESSION['wk_customer_lng'];
        }
        $origins = $customer_address;
        $distance_data = json_decode(@file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=$distance_units&origins=$origins&destinations=$destinations&key=$api_key&mode=$mode"), true);
        $distance_element = isset($distance_data['rows'][0]['elements']) ?$distance_data['rows'][0]['elements']:array();
        $i = 0;
        foreach ($stores as $key=>&$store_data) {
            $distance = isset($distance_element[$i])?$distance_element[$i]:array('status'=>'NO');
            if ($distance['status'] == 'OK') {
                $store_data['distance_found']=true;
                if(floatval($distance['distance']['text']) <= floatval($radius)){
                    $store_data['distance'] = $distance['distance']['text'];
                    $store_data['distance_value'] = $distance['distance']['value'];
                    $store_data['duration'] = $distance['duration']['text'];
                }else{
                    unset($stores[$key]);
                }
            } else {
                $store_data['distance_found']=false;
                unset($stores[$key]);
            }
            $i++;
        }
        $stores = fn_sort_array_by_key($stores, 'distance_value');
        return true;
    }
}
/**
 * function to get selected store distance from customer location
 *
 * @param [type] $store_data 
 * 
 * @return void
 */
function Fn_Get_Single_Store_Distance_From_customer($store_data) 
{
    $mode = 'driving';
    $api_key = trim(Registry::get('addons.wk_store_pickup.google_api_key'));
    $distance_unit = !empty($_REQUEST['wk_range_unit'])?$_REQUEST['wk_range_unit']:(!empty($_SESSION['wk_range_unit'])?$_SESSION['wk_range_unit']:Registry::get('addons.wk_store_pickup.wk_search_range_unit'));
    $distance_units = $distance_unit == 'km'?'metric':'imperial';
    $origins = '';
    if(!empty($_SESSION['wk_customer_lat'])){
        $origins = $_SESSION['wk_customer_lat'].','.$_SESSION['wk_customer_lng'];
    }
    if (empty($origins)){
        return $store_data;
    }
    $destinations = (!empty($store_data['latitude']) && !empty($store_data['longitude']))?($store_data['latitude'].','.$store_data['longitude']):$store_data['address'];
    $distance_data = json_decode(@file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=$distance_units&origins=$origins&destinations=$destinations&key=$api_key&mode=$mode"), true);
    $distance_element = isset($distance_data['rows'][0]['elements']) ?$distance_data['rows'][0]['elements']:array();
    foreach ($distance_element as $key => $distance) {
        if ($distance['status'] == 'OK') {
            $store_data['distance_found']=true;
            $store_data['distance'] = $distance['distance']['text'];
            $store_data['duration'] = $distance['duration']['text'];
        } else {
            $store_data['distance_found']=false;
        }
        $store_data['customer_lat'] = $_SESSION['wk_customer_lat'];
        $store_data['customer_lng'] = $_SESSION['wk_customer_lng'];
    }
    return $store_data;
}
/**
 * function to get customer lat and lang coordinates from his profile or ip address
 *
 * @return void
 */
function Fn_Get_Customer_latlng()
{
    return array();
}
/**
 * Funmction to check whether product is assigned to given store
 *
 * @param [type] $product_id 
 * @param [type] $store_id 
 * 
 * @return void
 */
function Fn_Check_Is_Product_Assigned_To_store($product_id, $store_id) 
{
    return db_get_field("SELECT store_id FROM ?:wk_store_pickup_products WHERE product_id = ?i AND store_id = ?i AND status = ?s", $product_id, $store_id, 'A');
}
/**
 * Fn_Settings_Variants_Addons_Wk_Store_Pickup_Inventory_Update_status function
 *
 * @return array $result 
 */
function Fn_Settings_Variants_Addons_Wk_Store_Pickup_Inventory_Update_status() 
{
    $result = fn_get_simple_statuses(STATUSES_ORDER);
    return $result;
}

/**
 * Function get current available stock of product for store pickup w.r.t Cs-Cart stock
 * 
 * @param int $product_id Product Identifier  
 * @param int $store_id   Store Id of selected product
 * 
 * @return mixed
 */
function Fn_Products_Available_Stock_For_Store_Pickup_points($product_id, $store_id = 0) 
{
    $condition = '';
    if ($store_id) {
        $condition = db_quote(" AND store_id != ?i", $store_id);
    }
    $store_stock = db_get_field("SELECT SUM(stock) FROM ?:wk_store_pickup_products WHERE (product_id = ?i OR variation_id = ?i) $condition", $product_id, $product_id);
    $product_amount = fn_get_product_amount($product_id);
    if ($store_stock) {
        return $product_amount-$store_stock;
    }
    return $product_amount;
}
/**
 * Fn_Wk_Store_Pickup_Clear_Product_Page_cache function is used to delete block content cache of products.view page
 *
 * @return void
 */
function Fn_Wk_Store_Pickup_Clear_Product_Page_cache() 
{
    if (empty($_SESSION['auth']['user_id'])||$_SESSION['auth']['user_id']==0) {
        $wk_dir_path = Registry::get('config.dir.cache_registry');
        if (is_dir($wk_dir_path) || file_exists($wk_dir_path)) {
            $cached_directory = fn_get_dir_contents($wk_dir_path, true, false, '', '', true);
            foreach ($cached_directory as $key => $dir_name) {
                $dir_Name = pathinfo($dir_name, PATHINFO_BASENAME);
                if (strpos($dir_Name, 'products.view') !== false) {
                    fn_rm($wk_dir_path.$dir_name); /* this function is used remove cache of block content on product description page*/
                }
            }
        }
    }
}