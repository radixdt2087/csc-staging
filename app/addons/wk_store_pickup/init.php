<?php
/**
 * CS-Cart Store Pickup - CS-Cart Store Pickup Shipment Method for CS-Cart and Multivendor
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
    die('Access denied');
}

fn_register_hooks(
    'update_product_post',
    'get_product_data_post',
    'get_products_pre',
    'pre_add_to_cart',
    'calculate_cart_items',
    'calculate_cart_post',
    'create_order_details',
    'change_order_status_before_update_product_amount',
    'delete_order',
    'delete_product_post',
    'get_products',
    'pre_place_order'
);