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
use Tygh\Navigation\LastView;
use Tygh\VendorPayouts;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

//
// Hooks
//

function fn_cp_checkout_by_vendor_get_payments($params, $fields, $join, $order, &$condition, $having)
{
    if (AREA == 'A') {
        $company_id = Registry::get('runtime.company_id');
        if (!empty($company_id)) {
            $condition[] = db_quote('?:payments.company_id = ?i', $company_id);
        }
    } else {
        $one_vendor_id = Tygh::$app['session']['one_vendor'];
        if (!empty($one_vendor_id)) {
            $allow_payments = db_get_field('SELECT allow_create_payment FROM ?:companies WHERE company_id = ?i',  $one_vendor_id);
            $check = db_get_field('SELECT COUNT(payment_id) FROM ?:payments WHERE company_id = ?i AND status = ?s', $one_vendor_id, 'A');
            if (!empty($check) && $allow_payments == 'Y') {
                if (Registry::get('addons.cp_checkout_by_vendor.add_standart_payment') == "Y") {
                    if (Tygh::$app['session']['auth']['user_type'] == 'V') {
                        $condition[] = db_quote('(?:payments.company_id = ?i)', 0);
                    } else {
                        $condition[] = db_quote('(?:payments.company_id = ?i OR ?:payments.company_id = ?i)', $one_vendor_id, 0);
                    }
                } else {
                    $condition[] = db_quote('?:payments.company_id = ?i', $one_vendor_id);
                }
            } else {
                $condition[] = db_quote('?:payments.company_id = ?i', 0);
            }
        } else {
            $mode = Registry::get('runtime.mode');
            $controller = Registry::get('runtime.controller');
            if (($controller == 'checkout' && $mode == 'cart') || (Tygh::$app['session']['back_to_actual'] == 'Y')) {
                $company_ids = array(0);
                foreach(Tygh::$app['session']['cart']['products'] as $key => $product) {
                    $company_ids = array_merge($company_ids, array($product['company_id']));
                }
                
                $condition[] = db_quote('?:payments.company_id IN (?n)', $company_ids);

            } elseif (Registry::get('addons.cp_shipping_values.status') == 'A' && !empty($params['cp_add_this_vendor'])) {
                $company_ids = array(0, $params['cp_add_this_vendor']);
                $condition[] = db_quote('?:payments.company_id IN (?n)', $company_ids);
            } else {
                $condition[] = db_quote('?:payments.company_id = ?i', 0);
            }
        }
    }
}
function fn_cp_checkout_by_vendor_update_payment_pre(&$payment_data, $payment_id, $lang_code, $certificate_file, $certificates_dir)
{
    if (AREA == 'A') {
        $company_id = Registry::get('runtime.company_id');
        if (!empty($company_id)) {
            $payment_data['company_id'] = $company_id;
        }
    } 
}

function fn_cp_checkout_by_vendor_change_order_status($status_to, $status_from, $order_info, $force_notification, $order_statuses) 
{
    if (!function_exists('___cp')) {
        return;
    }
    $payment_company = db_get_field('SELECT company_id FROM ?:payments WHERE payment_id = ?i', $order_info['payment_id']);
    if (!empty($payment_company) && $payment_company == $order_info['company_id']) {
        $statuses = Registry::get('addons.cp_checkout_by_vendor.complete_status');
        $statuses = array_keys($statuses);
        $payout = db_get_row(
            'SELECT * FROM ?:vendor_payouts WHERE order_id = ?i AND payout_type = ?s',
            $order_info['order_id'], 'order_placed'
        );

        if (!empty($payout)) {
            $vendor_payouts = VendorPayouts::instance();
            $default_complete_statuses = $vendor_payouts->getPayoutOrderStatuses();

            $withdrawal = array(
                'company_id' => $order_info['company_id'], 
                'order_id' => 0,
                'payout_date' => TIME,
                'start_date' => TIME,
                'end_date' => TIME,
                'order_amount' => 0,
                'payment_method' => '',
                'comments' => '',
                'payout_type' => 'withdrawal',
                'approval_status' => 'C',
                'commission_amount' => 0,
                'commission' => 0,
                'commission_type' => 'A', 
                'vendor_payment' => 'N',
                'paid' => 'N',
                'exclude' => 'N'
            );
            
            $commission_coef = $payout['commission'] / 100;

            $default_payout_amount = 0;
            if (!in_array($status_to, $default_complete_statuses) && in_array($status_from, $default_complete_statuses)) {
                $default_payout_amount = ($commission_coef - 1) * $payout['order_amount'];
            } elseif(in_array($status_to, $default_complete_statuses) && !in_array($status_from, $default_complete_statuses)) {
                $default_payout_amount = (1 - $commission_coef) * $payout['order_amount'];
            }
            if (!empty($default_payout_amount)) {
                $withdrawal['payout_amount'] = $default_payout_amount;
                db_query('INSERT INTO ?:vendor_payouts ?e', $withdrawal);
            }

            $payout_amount = 0; 
            if (in_array($status_to, $statuses) && !in_array($status_from, $statuses)) {
                $payout_amount = $payout['order_amount'] * $commission_coef;
            } elseif (in_array($status_from, $statuses) && !in_array($status_to, $statuses)) {
                $payout_amount = -1 * $payout['order_amount'] * $commission_coef;
            }
            if (!empty($payout_amount)) {
                $withdrawal['payout_amount'] = $payout_amount;
                db_query('INSERT INTO ?:vendor_payouts ?e', $withdrawal);
            }
        }
        $payouts = db_get_array(
            'SELECT * FROM ?:vendor_payouts WHERE order_id = ?i AND payout_type = ?s',
            $order_info['order_id'], 'order_placed'
        );

        foreach ($payouts as $payout) {
            if (isset($payout['commission_amount']) && $payout['commission_amount'] > 0) {
                db_query(
                    'UPDATE ?:vendor_payouts SET commission_amount = ?d WHERE payout_id = ?i',
                    $payout['order_amount'] * $commission_coef, $payout['payout_id']
                );
            }
        }
    }
}

function fn_cp_checkout_by_vendor_mve_place_order($order_info, $company_data, $action, $__order_status, $cart, &$_data, &$payout_id, $auth)
{
    if (!empty($order_info['payout_id'])) {
        $_data['exclude'] = 'Y';
    } else {
        // fix for demo-data payouts
        VendorPayouts::instance()->delete($payout_id);
        $payout_id = 0;
    }
}

function fn_cp_checkout_by_vendor_calculate_cart_taxes_pre(&$cart, $cart_products, $product_groups, $calculate_taxes, $auth)
{
    if (!empty($cart['skip_shipping'])) {
        $cart['shipping_required'] = false;
        $cart['company_shipping_failed'] = false;
        $cart['shipping_failed'] = false;
    }
}

function fn_cp_checkout_by_vendor_checkout_select_default_payment_method(&$cart, $payment_methods, $completed_steps)
{
    // Check current payment_id 
    if (!empty($cart['payment_id'])) {
        $first_method = array();
        $cur_payment_exists = false;
        foreach ($payment_methods as $p_method) {
            if (empty($first_method)) {
                $first_method = reset($p_method);
            }
            if (array_key_exists($cart['payment_id'], $p_method)) {
                $cur_payment_exists = true;
                break;
            }
        }
        if (empty($cur_payment_exists) && !empty($first_method)) {
            $cart['payment_id'] = $first_method['payment_id'];
        }
    }
}

//
// Common functions
//

function fn_cp_cv_attach_products_to_cart($cart_products, &$cart, &$auth)
{
    fn_add_product_to_cart($cart_products, $cart, $auth, true);
    fn_calculate_cart_content($cart, $auth);
    if (!empty($auth['user_id']) && version_compare(PRODUCT_VERSION, '4.12.1', '>')) {
        fn_save_cart_content($cart, $auth['user_id']);
    }
}

function fn_cp_cv_check_vendor_checkout($company_id, $auth)
{
    static $force_allowed = false;
    static $avail_vendors = null;
    if (!$force_allowed && !isset($avail_vendors)) {
        $settings = Registry::get('addons.cp_checkout_by_vendor');
        if (!empty($settings['allow_all_vendor']) && $settings['allow_all_vendor'] == 'Y'
            && !empty($settings['hide_without_payment']) && $settings['hide_without_payment'] == 'Y'
        ) {
            $condition = db_quote(' status = ?s', 'A');
            if (!empty($auth['usergroup_ids'])) {
                $condition .= ' AND (' . fn_find_array_in_set($auth['usergroup_ids'], 'usergroup_ids', true) . ')';
            }
            $avail_vendors = db_get_fields(
                'SELECT DISTINCT company_id FROM ?:payments WHERE ?p', $condition
            );
        } else {
            $force_allowed = true;
        }
    }
    return $force_allowed || in_array($company_id, $avail_vendors) ? true : false;
}

function fn_cp_split_products_by_vendor($cart_products)
{
    $companies = array();
    foreach($cart_products as $key => $product) {
        $companies[$product['company_id']][] = $key;
    }
    return $companies;
}

function fn_exists_payout_order($payout_id)
{
    return db_get_field('SELECT order_id FROM ?:orders WHERE payout_id = ?i', $payout_id);
}
