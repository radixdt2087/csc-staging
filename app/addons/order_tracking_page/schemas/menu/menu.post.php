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

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

$schema['central']['website']['items']['order_tracking_page'] = array(
    'attrs'     => array(
        'class'=>'is-addon'
    ),
    'href'      => 'wk_order_tracking.manage',
    'position'  => 605
);

return $schema;
