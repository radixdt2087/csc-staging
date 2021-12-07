<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2021 Radixweb*
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

function fn_shopify_password_update_redirect_all_link(){
   
    if(Tygh::$app['session']['is_shopify'] == 1){
        $redirect_url = 'profiles.update';
        $msg = '<p>You must first change your password in order to continue..</p>';
        fn_set_notification('', __(''), $msg,'K');   
        fn_redirect($redirect_url);
        return;
     }
}

function fn_shopify_password_update_init_templater() {

    $authUserId = Tygh::$app['session']['auth']['user_id']; 
    $res = db_query("SELECT * FROM ?:users WHERE user_id = '$authUserId'");
    $shopifyFlg = $res->fetch_assoc();
    if(isset($_REQUEST['dispatch']) && $_REQUEST['dispatch'] == 'profiles.update'){
        Tygh::$app['session']['is_shopify'] = 0;
    }else{
        if($shopifyFlg['is_shopify'] != 0){
            Tygh::$app['session']['is_shopify'] = 1;
        }
    }
    if(isset($_REQUEST['dispatch']) && ($_REQUEST['dispatch'] != 'auth.login_form' && $_REQUEST['dispatch'] != 'auth.login' && $_REQUEST['dispatch'] != 'auth.logout')) {

        fn_shopify_password_update_redirect_all_link();
    }
}
