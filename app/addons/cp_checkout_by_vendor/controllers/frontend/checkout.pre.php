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

if (!defined('BOOTSTRAP')) { die('Access denied'); }

$cart = &Tygh::$app['session']['cart'];
$actual_cart_products = &Tygh::$app['session']['actual_cart_products'];


if ($mode == 'place_order') {
    $current_vendor = Tygh::$app['session']['one_vendor'];
    if (!empty($_REQUEST['cp_checkout_vendor_id'])
        && $current_vendor != $_REQUEST['cp_checkout_vendor_id']
    ) {
        fn_set_notification('W', __('warning'), __('cp_cbv_dont_go_to_cart'));
        return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout&process=Y');
    }
} elseif ($mode == 'vendor_checkout') {
    $company_id = !empty($_REQUEST['company_id']) ? $_REQUEST['company_id'] : 0;
	if (!empty($company_id) && !empty($cart['products'])) {
		$products_to_checkout = array();
		$cart_products = $cart['products'];
		$actual_cart_products = !empty($actual_cart_products) ? $actual_cart_products : array();
		foreach($cart_products as $key => $product) {
			if ($product['company_id'] == $company_id) {
				$products_to_checkout[$key] = $product;
			} else {
				$actual_cart_products[$key] = $product;
			}
		}
		if (!empty($products_to_checkout)) {
			fn_clear_cart($cart);
			fn_cp_cv_attach_products_to_cart($products_to_checkout, $cart, $auth);
			if (!empty($actual_cart_products)) {
				Tygh::$app['session']['back_to_actual'] = 'Y';
			}
		}
	}
	return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout&process=Y');
	
} elseif ($mode == 'cart' || $mode == 'complete') {
	if ($_SERVER['REQUEST_METHOD'] == 'GET' && !defined('AJAX_REQUEST')) {
		if (!empty(Tygh::$app['session']['back_to_actual'])) {
			if (!empty($actual_cart_products)) {
			    if ($mode == 'complete') {
			        fn_clear_cart($cart);
			    }
				fn_cp_cv_attach_products_to_cart($actual_cart_products, $cart, $auth);
				$actual_cart_products = array();
				fn_set_notification('N', __('notice'), __('products_from_all_vendors_back_in_cart'));
			}
			Tygh::$app['session']['back_to_actual'] = null;
		}
	}
} elseif ($mode == 'checkout') {
    if (!empty($cart['products'])) {
        $one_vendor = 0;
        $company_ids = array();
        foreach ($cart['products'] as $v) {
            $company_id = $v['company_id'];
            $company_ids[$company_id] = 1;
        }
        if (count($company_ids) == 1) {
            Tygh::$app['session']['one_vendor'] = $company_id;
        } else {
            Tygh::$app['session']['one_vendor'] = null;
            if (Registry::get('addons.cp_checkout_by_vendor.allow_all_vendor') != 'Y') {
                fn_set_notification('W', __('warning'), __('cp_should_checkout_by_vendor'));
                fn_redirect('checkout.cart');
            }
        }
    } else {
        Tygh::$app['session']['one_vendor'] = null;
    }
}
