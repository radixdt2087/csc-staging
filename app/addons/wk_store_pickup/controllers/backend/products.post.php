<?php
/**
 * Store Pickup - CS-Cart Store Pickup Shipment Method for CS-Cart and Multivendor
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

if ($mode == 'update') {
    $tabs = Registry::get('navigation.tabs');
    $tabs['wk_store_pickup'] = array(
        'title'=> __("wk_store_pickup"),
        'js'=>true
    );
    Registry::set('navigation.tabs', $tabs);
    $product = Tygh::$app['view']->getTemplateVars('product_data');
    $company_stores = Fn_Get_Company_Store_Pickup_points($product['company_id']);
    Registry::get('view')->assign('company_stores', $company_stores);
}