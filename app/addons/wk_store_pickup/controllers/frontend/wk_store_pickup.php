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
// Cart is empty, create it
if (empty(Tygh::$app['session']['cart'])) {
    fn_clear_cart(Tygh::$app['session']['cart']);
}
$cart = &Tygh::$app['session']['cart'];
$product_id = isset($_REQUEST['product_id'])?$_REQUEST['product_id']:0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $suffix = 'view';
    if ($mode == 'select_store') {
        $store_id = $_REQUEST['store_id'];
        if (fn_check_is_product_assigned_to_store($product_id, $store_id)) {
            $cart_id = isset($_REQUEST['cart_id'])?$_REQUEST['cart_id']:'';
            if (isset($_REQUEST['checkout']) && $cart_id) {
                $cart['products'][$cart_id]['wk_store_id']= $cart['products'][$cart_id]['extra']['wk_store_id'] = $store_id;
                $cart['calculate_shipping'] = true;
                return $_REQUEST['checkout'] == 'N' ?array(CONTROLLER_STATUS_REDIRECT, 'checkout.cart'): array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout');
            }
            $_SESSION['wk_store_pickup'][$product_id] = $store_id;
        }
        fn_wk_store_pickup_clear_product_page_cache(); // Clear Product Details page cache
        $suffix = 'view&product_id='.$product_id;
    }
    if ($mode == 'save_pincode') {
        if (isset($_REQUEST['wk_pincode'])) {
            $_SESSION['wk_pincode'] = $_REQUEST['wk_pincode'];
        }
        return;
    }
    if ($mode == 'remove_store') {
        if (isset($_REQUEST['cart_id'])) {
            $cart_id = $_REQUEST['cart_id'];
            unset($cart['products'][$cart_id]['wk_store_id'], $cart['products'][$cart_id]['extra']['wk_store_id']);
            $cart['calculate_shipping'] = true;
            if(isset($_REQUEST['is_cart']))
                return array(CONTROLLER_STATUS_REDIRECT, 'checkout.cart');
            return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout');
        } elseif ($product_id) {
            fn_wk_store_pickup_clear_product_page_cache();
            unset($_SESSION['wk_store_pickup'][$product_id]);
        }
        $suffix = 'view&product_id='.$product_id;
    }
    if ($mode == 'set_location') {
        if (!empty($_REQUEST['wkCustomerLat'])) {
            $_SESSION['wk_customer_lat'] = $_REQUEST['wkCustomerLat'];
            $_SESSION['wk_customer_lng'] = $_REQUEST['wkCustomerLng'];
        }
        exit;
    }
    return array(CONTROLLER_STATUS_REDIRECT, 'products.'.$suffix);
}

if ($mode == 'search') {
    $return_url = 'products.view&product_id='.$product_id;
    $Wk_return_text = fn_get_lang_var("wk_return_to_product_page");
    $selected_store_id = 0;
    fn_add_breadcrumb(fn_get_product_name($product_id), $return_url);
    if (isset($_REQUEST['checkout']) && !empty($_REQUEST['checkout'])) {
        $cart_id = isset($_REQUEST['cart_id'])?$_REQUEST['cart_id']:0;
        if ($_REQUEST['checkout'] == 'Y') {
            $Wk_return_text = fn_get_lang_var('wk_return_to_checkout_page');
            $return_url = 'checkout.checkout';
            fn_add_breadcrumb(__("checkout"), 'checkout.checkout');
        } else {
            $Wk_return_text = fn_get_lang_var("wk_return_to_cart_page");
            $return_url = 'checkout.cart';
            fn_add_breadcrumb(__("cart"), 'checkout.cart');
        }
        if ($cart_id) {
            $selected_store_id = isset($cart['products'][$cart_id]['wk_store_id'])?$cart['products'][$cart_id]['wk_store_id']:(isset($cart['products'][$cart_id]['extra']['wk_store_id'])? $cart['products'][$cart_id]['extra']['wk_store_id']:0);
        }
    } else {
        $selected_store_id = isset($_SESSION['wk_store_pickup'][$product_id])?$_SESSION['wk_store_pickup'][$product_id]:0;
    }
    fn_add_breadcrumb(__("wk_store_pickup_points"));
    if ($product_id) {
        list($store_pickup_points, $search) = Fn_Get_Store_Pickup_points($_REQUEST, 0, DESCR_SL);
        Fn_Get_Store_Pickup_Point_Distance_From_Customer_location($store_pickup_points);
        Registry::get('view')->assign('store_pickup_points', $store_pickup_points);
        Registry::get('view')->assign('search', $search);
        Registry::get('view')->assign('product_id', $product_id);
        Registry::get('view')->assign('selected_store_id', $selected_store_id);
        Registry::get('view')->assign('store_pickup_point_search', $search);
        Registry::get('view')->assign('wk_return_url', $return_url);
        Registry::get('view')->assign('wk_return_text', $Wk_return_text);
    } else {
        return array(CONTROLLER_STATUS_NO_PAGE_FOUND);
    }
    
}