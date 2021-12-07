<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
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

/** @var string $mode */

if ($mode == 'login') {

    $dauth = Tygh::$app['session']['auth']['user_id'];  

    if($dauth){
        $res = db_query("SELECT * FROM ?:users WHERE user_id = '$dauth' AND is_shopify = 1");
        if($res){
            $shopifyUser = $res->fetch_assoc();
            Tygh::$app['session']['is_shopify'] = $shopifyUser['is_shopify'];
        }else{
            Tygh::$app['session']['is_shopify'] = 0;
        }
    }
}
