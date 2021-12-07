<?php
/**
 * Store Pickup - CS-Cart Store Pickup Shipping Method for CS-Cart and Multivendor
 *
 * PHP version 7.1
 *
 * @category  Addon
 * @package   CSCart_Multivendor
 * @author    Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2010 Webkul Software Pvt. Ltd. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version   GIT:1.0
 * @link      Technical Support:  http://webkul.uvdesk.com
 */

if (!defined('BOOTSTRAP')) { 
    die('Access denied'); 
}

$schema['controllers']['wk_store_pickup']['permissions'] = true;
$schema['controllers']['tools']['modes']['update_status']['param_permissions']['table']['wk_store_pickup_products'] = true;
$schema['controllers']['tools']['modes']['update_status']['param_permissions']['table']['wk_store_pickup_points'] = true;

return $schema;