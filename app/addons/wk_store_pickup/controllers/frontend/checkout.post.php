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

use Tygh\Settings;
use Tygh\Http;
use Tygh\Registry;
// Cart is empty, create it
if (empty(Tygh::$app['session']['cart'])) {
    fn_clear_cart(Tygh::$app['session']['cart']);
}
$cart = &Tygh::$app['session']['cart'];
if ($mode == 'checkout') {
    if ((isset($_REQUEST['edit_step']) && $_REQUEST['edit_step'] == 'step_four') || ((version_compare('4.9.3', PRODUCT_VERSION) == -1) && Registry::get('addons.step_by_step_checkout.status') != 'A')) {
        $cart_products = $cart['products'];
        $force_redirect_for_update = false;
        foreach ($cart_products as $cart_id => $product_data) {
            if (isset($product_data['extra']['enabled_store_pickup']) && $product_data['extra']['enabled_store_pickup'] && isset($product_data['extra']['wk_store_pickup_only']) && $product_data['extra']['wk_store_pickup_only']) {
                $store_id = isset($product_data['extra']['wk_store_id'])?$product_data['extra']['wk_store_id']:0;
                $store_pickup_points = Fn_Get_Product_Store_Pickup_Points_Short_info($product_data['product_id'], 0);
                if ($store_pickup_points) {
                    if (empty($store_id)) {
                        fn_set_notification("E", __("error"), __("wk_select_pickup_point_for_product_to_place_an_order", array('[product]'=>$product_data['product'])));
                        $force_redirect_for_update = true;
                    } else {
                        if (isset($store_pickup_points[$store_id])) {
                            if ($store_pickup_points[$store_id]['stock']) {
                                if (!($store_pickup_points[$store_id]['stock'] >=  $product_data['amount'])) {
                                    fn_set_notification("E", __("error"), __("wk_selected_do_not_have_enough_stock_to_place_order", array('[store]'=>$store_pickup_points[$store_id]['title'],'[product]'=>$product_data['product'])));
                                }
                            } else {
                                $force_redirect_for_update = true;
                                fn_delete_cart_product($cart, $cart_id);
                                fn_set_notification("W", __("warning"), __("wk_product_remove_from_cart_becuase_no_store_assigned_to_this_product", array('[product]'=> $product_data['product'])));
                            }
                        }
                    }
                } else {
                    $force_redirect_for_update = true;
                    fn_delete_cart_product($cart, $cart_id);
                    fn_set_notification("W", __("warning"), __("wk_product_remove_from_cart_becuase_no_store_assigned_to_this_product", array('[product]'=> $product_data['product'])));
                }
            }
        }
        if ($force_redirect_for_update) {
            if (defined('AJAX_REQUEST')) {
                $cart['recalculate'] = true;
                if (!empty($_REQUEST['edit_step']) && $_REQUEST['edit_step'] == 'step_four'){
                    $cart['edit_step'] = 'step_three';
                    Tygh::$app['ajax']->assign('non_ajax_notifications', true);
                } 
                Tygh::$app['ajax']->assign('force_redirection', fn_url('checkout.checkout'));
            } else {
                return array(CONTROLLER_STATUS_REDIRECT, 'checkout.cart');
            }
        }
    }
}