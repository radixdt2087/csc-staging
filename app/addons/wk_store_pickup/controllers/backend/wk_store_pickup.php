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

$store_id = isset($_REQUEST['store_id'])?$_REQUEST['store_id']:0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $suffix = 'manage';
    fn_trusted_vars('store_pickup_point');

    if ($mode == 'wk_change_store') {
       
        $item_id = (int)$action;
        $order_id = $_REQUEST['order_id'];
        if (isset($_REQUEST['wk_store_point'][$item_id]) && !empty($_REQUEST['wk_store_point'][$item_id])) {
            // $lang_code = CART_LANGUAGE;
            $store_id = $_REQUEST['wk_store_point'][$item_id];
            $extra = db_get_field("SELECT extra FROM ?:order_details WHERE order_id = ?i AND item_id = ?i", $order_id, $item_id);
            if ($extra) {
                $extra =  @unserialize($extra);
                $extra['wk_store_id'] = $store_id;
                $extra['wk_store_pickup_info'] = Fn_Get_Store_Pickup_point($extra['wk_store_id']);
                $extra['wk_store_pickup'] = 'Y';
                $extra = serialize($extra);
                db_query("UPDATE ?:order_details SET extra = ?s WHERE item_id =?i AND order_id = ?i", $extra, $item_id, $order_id);
                db_query("UPDATE ?:wk_store_pickup_orders SET store_id = ?s WHERE item_id =?i AND order_id = ?i", $store_id, $item_id, $order_id);
            }
            return array(CONTROLLER_STATUS_REDIRECT, fn_url('orders.details&order_id='.$order_id));
        }
    }

    if ($mode == 'bulk_upload') {
        $uploaded_file_data = fn_filter_uploaded_data('wk_store_products', array('csv'));
        if ($uploaded_file_data) {
            $uploaded_data = reset($uploaded_file_data);
            $handle=@fopen($uploaded_data['path'], "r");
            $array = $fields = array(); $i = 0;
            $delimiter = isset($_REQUEST['wk_store_products']['delimiter'])?$_REQUEST['wk_store_products']['delimiter']:'S';
            $delimiter = $delimiter == 'C'?',':($delimiter == 'T'?"\t":';');
            if ($handle) {
                while (($row = fgetcsv($handle, 4096, $delimiter)) !== false) {
                    if (empty($fields)) {
                        $fields = $row;
                        continue;
                    }
                    foreach ($row as $k=>$value) {
                        if (!empty($fields[$k]))
                            $array[$i][$fields[$k]] = $value;
                    }
                    $i++;
                }
                if (!feof($handle)) {
                    fn_set_notification('E', __("error"), "Error: unexpected fgets() fail\n");
                    return array(CONTROLLER_STATUS_REDIRECT, 'wk_store_pickup.store_product&reset=false&store_id='.$store_id);
                }
                fclose($handle);
            }
            if ($array) {
                if (!in_array('product_id', $fields) || !in_array('stock', $fields)) {
                    fn_set_notification('E', __("error"), __("uploaded_csv_file_two_columns_named_stock"));
                    return array(CONTROLLER_STATUS_REDIRECT, 'wk_store_pickup.store_product&reset=false&store_id='.$store_id);
                }
                $_data = array();
                foreach ($array as $key=>$value) {
                    $avail_stock = Fn_Products_Available_Stock_For_Store_Pickup_points($value['product_id'], $store_id);
                    if ($avail_stock>=0) {
                        $_data[] = array(
                            'product_id'=> $value['product_id'],
                            'store_id' => $store_id,
                            'stock'  => $avail_stock >= $value['stock']?$value['stock']:$avail_stock,
                            'max_stock'=>$avail_stock
                        );
                    }
                }
                if ($_data) {
                    $_request_data = array('store_id'=>$store_id, 'pickup_stores'=>$_data);
                    Fn_Add_Product_To_Store_Pickup_points($_request_data);
                }
            } else {
                fn_set_notification('E', __("error"), __("uploaded_csv_file_two_columns_named_stock"));
            }
        }
        return array(CONTROLLER_STATUS_REDIRECT, 'wk_store_pickup.store_product&reset=false&store_id='.$store_id);
    }
    if ($mode == 'update') {
        
        $store_id = fn_update_store_pickup_point($_REQUEST['store_pickup_point'], $store_id);
        $suffix = 'update&store_id='.$store_id;
    }
    if ($mode == 'store_session_product') {
        if (defined('AJAX_REQUEST')) {
            $product_id = $_REQUEST['product_id'];
            $stock = fn_get_product_amount($product_id);
            $store_data =  array(
                'store_id' => $store_id,
                'product_id'=> $product_id,
                'quantity'  => $stock,
                'max_stock' => Fn_Products_Available_Stock_For_Store_Pickup_points($product_id, $store_id)
            );
            $_SESSION['wk_store_product'][$store_id] = isset($_REQUEST['pickup_stores'])?$_REQUEST['pickup_stores']:array();
            $_SESSION['wk_store_product'][$store_id][$product_id] = $store_data;
        }
        $suffix = 'store_product&reset=false&store_id='.$store_id;
    }

    if ($mode == 'remove_session_product') {
        $product_id = $_REQUEST['product_id'];
        if (defined('AJAX_REQUEST')) {
            unset($_SESSION['wk_store_product'][$store_id][$product_id]);
        }
        $suffix = 'store_product&reset=false&store_id='.$store_id;
    }
    if ($mode == 'store_product') {
        Fn_Add_Product_To_Store_Pickup_points($_REQUEST);
        $suffix = 'store_product&store_id='.$store_id;
    }
    if ($mode == 'm_update_products') {
        $store_pickup_data = isset($_REQUEST['store_pickup_data'])?$_REQUEST['store_pickup_data']:array();
        if (!empty($store_pickup_data)) {
            foreach ($store_pickup_data as $id=>$data) {
                $avail_stock = Fn_Products_Available_Stock_For_Store_Pickup_points($data['product_id'], $data['store_id']);
                if ($avail_stock < $data['stock']) {
                    $data['stock'] = $avail_stock;
                    unset($data['product_id']);
                }
                try {
                    db_query("UPDATE ?:wk_store_pickup_products SET ?u WHERE id = ?i", $data, $id);
                }catch(Exception $e) {
                    fn_set_notification('E', __("error"), $e->getMessage());
                }
            } 
        }
        $suffix = 'products';
    }
    if ($mode == 'delete') {
        fn_delete_store_pickup_point(array($store_id));
    }
    if ($mode == 'm_delete') {
        fn_delete_store_pickup_point($_REQUEST['store_ids']);
    }

    if ($mode == 'delete_product') {
        Fn_Delete_Store_Pickup_product(array($_REQUEST['id']), $store_id);
        $suffix = 'products';
    }

    if ($mode == 'm_delete_product') {
        try {
            Fn_Delete_Store_Pickup_product($_REQUEST['ids'], $store_id);
        }catch(Exception $e) {
            fn_set_notification('E', __("error"), $e->getMessage());
        }
        $suffix = 'products';
    }
    
    if ($mode == 'm_update_orders') {
        $suffix = 'orders';
        if (isset($_REQUEST['wk_orders_data'])) {
            foreach ($_REQUEST['wk_orders_data'] as $id=>$order_data) {
                try {
                    db_query("UPDATE ?:wk_store_pickup_orders SET update_timestamp = ?i, status = ?s WHERE id = ?i", TIME, $order_data['status'], $id);
                }catch(Exception $e) {
                    fn_set_notification('E', __("error"), $e->getMessage());
                }
            }
        }
    }
    $redirect_url = isset($_REQUEST['redirect_url'])?$_REQUEST['redirect_url']:'';
    if ($redirect_url) {
        return array(CONTROLLER_STATUS_REDIRECT, $redirect_url);
    }
    return array(CONTROLLER_STATUS_REDIRECT, 'wk_store_pickup.'.$suffix);
}

if ($mode == 'manage') {
    list($wk_store_pickup_points, $search) = Fn_Get_Store_Pickup_points($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'));
    Registry::get('view')->assign('wk_store_pickup_points', $wk_store_pickup_points);
    Registry::get('view')->assign('search', $search);
}

if ($mode == 'update' || $mode == 'add') {
    Tygh::$app['view']->assign('countries', fn_get_simple_countries(true, CART_LANGUAGE));
    Tygh::$app['view']->assign('states', fn_get_all_states());
    if ($mode == 'update') {
        $store_data = Fn_Get_Store_Pickup_point($store_id);
        if ($store_id && $store_data) {
            Registry::get('view')->assign('store_pickup_point', $store_data);
        }
    }
}

if ($mode == 'products') {
    list($wk_store_pickup_products, $search) = Fn_Get_Store_Pickup_products($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'));
    Registry::get('view')->assign('wk_store_pickup_products', $wk_store_pickup_products);
    Registry::get('view')->assign('search', $search);
}
if ($mode == 'orders') {
    list($wk_store_pickup_orders, $search) = Fn_Get_Store_Pickup_orders($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'));
    Registry::get('view')->assign('wk_store_pickup_orders', $wk_store_pickup_orders);
    Registry::get('view')->assign('search', $search);
}

if ($mode == 'store_product') {
    if ($store_id) {
        $store_data = Fn_Get_Store_Pickup_point($store_id);
        Registry::get('view')->assign('store_data', $store_data);
    } else {
        unset($_SESSION['wk_store_product']);
        fn_set_notification("W", __("warning"), __("please_select_an_store_pickup_points_first"));
        return array(CONTROLLER_STATUS_REDIRECT, 'wk_store_pickup.manage');
    }
    if(isset($_REQUEST['reset'])&& isset($_SESSION['wk_store_product'][$store_id]))
        Tygh::$app['view']->assign('selected_products', $_SESSION['wk_store_product'][$store_id]);
    else    
        unset($_SESSION['wk_store_product'][$store_id]);
}
