<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2021 Radixweb  *
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

if ($mode == 'details') {

    $auth = Tygh::$app['session']['auth'];
    $order_id = $_REQUEST['order_id'];
    $order_info = fn_get_order_info($_REQUEST['order_id'], false, true, true, false);
    $commissionAmt = $id = db_query("SELECT commission_amount, order_amount FROM ?:vendor_payouts WHERE order_id = ?i", $order_id);
    if($row = mysqli_fetch_assoc($commissionAmt)){
        if(isset($auth['user_type']) && $auth['user_type'] == 'A') {
                $permission ='admin';
                Tygh::$app['view']->assign('permission',$permission);
                Tygh::$app['view']->assign('commission',$row);
        }
        if(isset($auth['user_type']) && $auth['user_type'] == 'V') {
            $permission ='access';
            $Vcommission = $row['order_amount'] - $row['commission_amount'];
            Tygh::$app['view']->assign('permission',$permission);
            Tygh::$app['view']->assign('vcommission',$Vcommission);
            Tygh::$app['view']->assign('commission',$row);
            
        }

    }
   // Tygh::$app['view']->assign('order_info', $order_info);   
}