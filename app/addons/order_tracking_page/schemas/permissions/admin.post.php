<?php
/**
 * CS-Cart Order Status Tracker - order_tracking_page
 * 
 * PHP version 7.0
 * 
 * @category  Add-on
 * @package   CS_Cart
 * @author    WebKul Software Private Limited <support@webkul.com>
 * @copyright 2010 WebKul Software Private Limited
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version   GIT: 1.0
 * @link      Technical Support: Forum - http://webkul.com/ticket
 */

$schema['wk_order_tracking'] = array (
    'modes' => array (
        'manage' => array (
            'permissions' => 'view_wk_order_tracking'
        ),
        'update' => array (
            'permissions' => 'manage_wk_order_tracking'
        ),
        'm_delete' => array (
            'permissions' => 'manage_wk_order_tracking'
        ),
        'delete' => array (
            'permissions' => 'manage_wk_order_tracking'
        )
    ),
    'permissions' => array (
        'GET' => 'view_wk_order_tracking',
        'POST' => 'manage_wk_order_tracking'
    )
);

return $schema;