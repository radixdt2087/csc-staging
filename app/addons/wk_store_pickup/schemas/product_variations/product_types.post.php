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
if (!defined('BOOTSTRAP')) {
    die('Access Denied');
}
use Tygh\Addons\ProductVariations\Product\Type\Type;
if (version_compare('4.9.3', PRODUCT_VERSION) < 0) {
    $schema[Type::PRODUCT_TYPE_VARIATION]['tabs'][] = 'wk_store_pickup';
    $schema[Type::PRODUCT_TYPE_VARIATION]['field_aliases']['wk_store_pickup'] = 'wk_store_pickup';
    $schema[Type::PRODUCT_TYPE_VARIATION]['fields'][] = 'wk_store_pickup';
    $schema[Type::PRODUCT_TYPE_VARIATION]['fields'][] = 'enable_store_pickup';
    $schema[Type::PRODUCT_TYPE_VARIATION]['fields'][] = 'store_pickup_only';
}
return $schema;