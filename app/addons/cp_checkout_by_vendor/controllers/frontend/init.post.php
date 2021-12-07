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

if(!defined('BOOTSTRAP')) { die('Access denied'); }

$cart = &Tygh::$app['session']['cart'];

if (empty($cart['products'])) {
    if (!empty(Tygh::$app['session']['back_to_actual'])) {
        $actual_cart_products = &Tygh::$app['session']['actual_cart_products'];
        if(!empty($actual_cart_products)) {
            fn_cp_cv_attach_products_to_cart($actual_cart_products, $cart, $auth);
            $actual_cart_products = array();
            Tygh::$app['session']['back_to_actual'] = null;
            fn_set_notification('N', __('notice'), __('products_from_all_vendors_back_in_cart'));
        }
    }
}
