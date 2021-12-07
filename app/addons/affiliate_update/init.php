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

if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks(
    'send_order_to_q6_post'
);
//die($_COOKIE['comAffilateId']);
//if(empty($_COOKIE['affilateId']))
//{

    /* Set Cookie */
    $cookie_name = "affilateId";

    $seo = $_SERVER['REQUEST_URI'];
    $seoName = explode("/",$seo);
    $vendorSeo = $seoName[1];
    if($vendorSeo) {
        $getSeo = db_get_array("SELECT * FROM ?:seo_names WHERE `name` = '$vendorSeo' and `type` = 'm'");
        if($getSeo){
            $vcompanyId = $getSeo[0]['object_id'];
            $vcheckUser = db_get_array("SELECT * FROM ?:users WHERE `company_id` = '$vcompanyId'");
            $affilate = $vcheckUser[0]['user_id'];
        }
    }

    if(isset($_GET['aff_id'])){
        $affilate = $_GET['aff_id'];
    }

    $str = $_SERVER['QUERY_STRING'];

    $companyId = strstr($str, 'company_id=');
    preg_match_all('!\d+!', $companyId, $matches);

    if(isset($matches[0][0])){
        $companyId = $matches[0][0];
        $checkUser = db_get_array("SELECT * FROM ?:users WHERE `company_id` = '$companyId'");
        $affilate = $checkUser[0]['user_id'];
    }

    if ((empty($_COOKIE[$cookie_name]) && $_REQUEST['dispatch'] != "products.search" && empty($_REQUEST['id']) && empty($_REQUEST['cid']) && !strpos($_SERVER['HTTP_REFERER'], 'products.search') !== false) || (!empty($_REQUEST['aff_id']) || !empty($_REQUEST['company_id']))) {
      if(!empty($affilate)) {
         setcookie($cookie_name, $affilate, time() + (86400 * 30), "/");
      }
    }
    
    //unset($_COOKIE['comAffilateId']);
    //setcookie('comAffilateId', null, -1, '/');
    /* End Set Cookie */
//}

?>
