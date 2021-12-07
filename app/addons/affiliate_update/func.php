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

use Tygh\Registry;
use Tygh\Tools\Url;

if ( !defined('AREA') ) { die('Access denied'); }

//die();
function fn_replink_order_send_order_to_q6_post($category_id, $field_list, $get_main_pair, $skip_company_condition, $lang_code)
{
	
}

/** Vendor Registration */
$userData = $_REQUEST['company_data'];
// if($userData){
//      print_r($userData['fields']);
//     die();
// }  
// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $userData) 
{

    $domain = $_SERVER['HTTP_REFERER'];
    $url = parse_url($domain);
    $domain_name = $url['host'];
    
    $userData = $_REQUEST['company_data'];
    $phoneNumber = preg_replace( '/[^0-9]/', '', $userData['phone']);

    //$parts = explode("@", $userData['email']);
    //$username = $parts[0];

    $email         = $userData['email'];
    $password      = "Chetu123";
    $firstname     = $userData['fields'][37];
    $lastname      = $userData['fields'][38];
    $companyName   = $userData['company'];
    $phone         = $phoneNumber;
    $domain        = $domain_name;
    $affId         = (!empty($_COOKIE['affilateId']) && $_COOKIE['affilateId'] != 1) ? $_COOKIE['affilateId'] : 0;
    $profileStatus = $_REQUEST['dispatch'];

    $getPlanData = "";
    if($userData['plan_id'] != "")
    {
        $planId = $userData['plan_id'];
        $getPlanData = db_get_array("SELECT * FROM ?:vendor_plans WHERE `plan_id` = $planId");
    }

    $data = [
        'firstName'  => $firstname,
        'lastName'   => $lastname,
        'email'      => $email,
        'companyName' => $companyName,
        'phone'      => ($phone != "") ? $phone : mt_rand(),
        'password'   => $password,
        'role_id'    => 4,
        'createdBy'  => $affId,
        'domainName' => $domain,
        'profileStatus' => $profileStatus,
        'planData' => json_encode($getPlanData),
    ];

    $data_json = json_encode($data);
    $Link = Registry::get('addons.merchant_affiliation.ich_link');
    // if($mode == 'apply_for_vendor')
    // {
        $url = $Link.'/api/userSingup';
        // curl initiate
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response  = curl_exec($ch);
        curl_close($ch);
        
        unset($_COOKIE['affilateId']);
        setcookie('affilateId', null, -1, '/');
    //}
}
/** End Vendor Registration */

