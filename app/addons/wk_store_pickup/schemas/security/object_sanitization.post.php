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
use Tygh\Tools\SecurityHelper;

$schema['wk_store_pickup_point'] = array(
    SecurityHelper::SCHEMA_SECTION_FIELD_RULES => array(
        'title' => SecurityHelper::ACTION_REMOVE_HTML,
        'phone' => SecurityHelper::ACTION_REMOVE_HTML,
        'latitude' => SecurityHelper::ACTION_REMOVE_HTML,
        'longitude' => SecurityHelper::ACTION_REMOVE_HTML,
        'address' => SecurityHelper::ACTION_REMOVE_HTML,
        'pincode' => SecurityHelper::ACTION_REMOVE_HTML,
        'city' => SecurityHelper::ACTION_REMOVE_HTML,
        'state' => SecurityHelper::ACTION_REMOVE_HTML,
        'description' => SecurityHelper::ACTION_SANITIZE_HTML,
    )
);
return $schema;
