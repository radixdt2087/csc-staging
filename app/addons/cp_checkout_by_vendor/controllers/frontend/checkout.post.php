<?php
/*****************************************************************************
*                                                        © 2013 Cart-Power   *
*           __   ______           __        ____                             *
*          / /  / ____/___ ______/ /_      / __ \____ _      _____  _____    *
*      __ / /  / /   / __ `/ ___/ __/_____/ /_/ / __ \ | /| / / _ \/ ___/    *
*     / // /  / /___/ /_/ / /  / /_/_____/ ____/ /_/ / |/ |/ /  __/ /        *
*    /_//_/   \____/\__,_/_/   \__/     /_/    \____/|__/|__/\___/_/         *
*                                                                            *
*                                                                            *
* -------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license *
* and  accept to the terms of the License Agreement can install and use this *
* program.                                                                   *
* -------------------------------------------------------------------------- *
* website: https://store.cart-power.com                                      *
* email:   sales@cart-power.com                                              *
******************************************************************************/

use Tygh\Registry;

if(!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    return;
}

if ($mode == 'complete') {
    $cart = Tygh::$app['session']['cart'];
    if (!empty($cart['products']) && Registry::get('addons.cp_checkout_by_vendor.show_cart_on_complete') == 'Y') {
        $estimate = Registry::get('settings.General.estimate_shipping_cost') == 'Y' ? 'A' : 'S';
        list($cart_products, $product_groups) = fn_calculate_cart_content($cart, $auth, $estimate, true, 'F', true);
        fn_gather_additional_products_data($cart_products, array(
            'get_icon' => true,
            'get_detailed' => true,
            'get_options' => true,
            'get_discounts' => false
        ));
        
        Tygh::$app['view']->assign('cart', $cart);
        Tygh::$app['view']->assign('cart_products', $cart_products);
    }
}
