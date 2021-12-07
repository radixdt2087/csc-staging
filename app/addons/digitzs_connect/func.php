<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2021 Radixweb*
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

defined('BOOTSTRAP') or die('Access denied');
use Tygh\Registry;

function fn_digitzs_connect_sucess_user_login() {    
    $dauth = Tygh::$app['session']['auth'];     
    if(isset($dauth['user_type']) && $dauth['user_type'] == 'V' && AREA == 'A') {
        $company_id = $dauth['company_id'];
        dispVendorMsg($company_id,false);
    }   
}

function fn_digitzs_connect_init_templater() {   
    $url = $_SERVER['REQUEST_URI'];
    if($url == '/vendor.php?dispatch=products.add') {
        $dauth = Tygh::$app['session']['auth'];         
        $company_id = $dauth['company_id'];
        dispVendorMsg($company_id,true);      
    } 
}

function dispVendorMsg($company_id,$redirect=false) {
    $msg = '<p><b style="color:#258D78">Welcome to your Vendor Dashboard.</b> <span style="color:#000;">To add products and accept payments, you must first enable your merchant processing account by applying with the Digitzs Payment Gateway. Go to Vendor -><a href="/vendor.php?dispatch=companies.update&m=1&company_id='.$company_id.'"> <b>Digitzs Connect</b></a> to be approved with your Digitzs Account.</span></p>';
    $cdata = fn_get_company_data($company_id);  
    if(empty($cdata['digitzs_connect_account_id'])) {
        fn_set_notification('', __(''), $msg,'K');   
        if($redirect) {
            fn_redirect('products.manage');
        }
    }
}

function fn_digitzs_connect_add_payment_processor() {
    fn_digitzs_connect_remove_payment_processor();

    db_query("INSERT INTO ?:payment_processors ?e", array (
        'processor' => 'Digitzs',
        'processor_script' => 'digitzs.php',
        'processor_template' => 'addons/digitzs_connect/views/orders/components/payments/cc.tpl',
        'admin_template' => 'digitzs.tpl',
        'callback' => 'Y',
        'type' => 'P'
    ));
}

function fn_digitzs_connect_remove_payment_processor() {

    db_query("DELETE FROM ?:payment_processors WHERE processor_script = ?s", 'digitzs.php');
}
/**
 * Hook.
 * This hook is needed for digitzs calculator on the checkout page.
 * Changes params to get payment processors.
 *
 * @param array $params    Array of flags/data which determines which data should be gathered
 * @param array $fields    List of fields for retrieving
 * @param array $join      Array with the complete JOIN information (JOIN type, tables and fields) for an SQL-query
 * @param array $order     Array containing SQL-query with sorting fields
 * @param array $condition Array containing SQL-query condition possibly prepended with a logical operator AND
 * @param array $having    Array containing SQL-query condition to HAVING group
 */
function fn_digitzs_connect_get_payments($params, $fields, $join, $order, &$condition, $having)
{
    $mode = Registry::get('runtime.mode');
    if ($mode == 'checkout') {
        $dauth = Tygh::$app['session']['auth']; 
        $pdata = db_get_row(
            'SELECT card_number,valid_thru,card_holder_name'
            . ' FROM ?:users WHERE user_id = ?i', $dauth['user_id']
            );
            Tygh::$app['view']->assign('pdata',$pdata);
    }
}