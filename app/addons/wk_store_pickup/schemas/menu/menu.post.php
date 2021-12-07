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
$schema['central']['website']['items']['wk_store_pickup_menu'] = array(
    'attrs' => array(
        'class'=>'is-addon'
    ),
    'href'=>'wk_store_pickup.manage',
    'alt' => 'wk_store_pickup.manage, wk_store_pickup.orders, wk_store_pickup.products, wk_store_pickup.categories',
    'subitems'=> array(
        'wk_store_pickup_points'=>array(
            'href'=> 'wk_store_pickup.manage',
            'position'=> 100,
        ),
        'wk_store_pickup_products'=>array(
            'href'=> 'wk_store_pickup.products',
            'position'=> 200,
        ),
        'wk_store_pickup_orders'=>array(
            'href'=> 'wk_store_pickup.orders',
            'position'=> 300,
        ),
    ),
    'position'=>400
);

return $schema;