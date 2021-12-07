<?php

// if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;
use Tygh\Tools\Url;

$user_id = Tygh::$app['session']['auth']['user_type'];
//print_r($user_id);
//$this->context->smarty->assign('user_type', $user_id);
// //echo date_default_timezone_get();

//print_r($user_type);

// /* Get Vendor payout details */
// $curlOrder = curl_init();

// curl_setopt_array($curlOrder, array(
//   CURLOPT_URL => "https://staging.wesave.com/index.php/api/OrderIDApi",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_SSL_VERIFYHOST => 0,
//     CURLOPT_SSL_VERIFYPEER => 0,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "GET",
//   CURLOPT_HTTPHEADER => array(
//     "cache-control: no-cache",
//     "content-type: application/json",
//     "postman-token: 53ea7e56-4f66-34da-798d-e3ec3e9d593e"
//   ),
// ));

// $responseOrder = curl_exec($curlOrder);
// $errOrder = curl_error($curlOrder);

// $responseOrder = json_decode($responseOrder);

// curl_close($curlOrder);

// if(!empty($responseOrder))
// {  
//     $vendor_payouts = db_get_array("SELECT * FROM ?:vendor_payouts WHERE order_id = $responseOrder"); 

//     $vendor_payouts = json_encode($vendor_payouts);
    
//     $curlCompleteOrder = curl_init();
    
//     curl_setopt_array($curlCompleteOrder, array(
//     CURLOPT_URL => "https://staging.wesave.com/index.php/api/getOrderWithSellerFee/".$vendor_payouts,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => "",
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 30,
//     CURLOPT_SSL_VERIFYHOST => 0,
//         CURLOPT_SSL_VERIFYPEER => 0,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => "GET",
//     CURLOPT_HTTPHEADER => array(
//         "cache-control: no-cache",
//         "content-type: application/json",
//         "postman-token: 53ea7e56-4f66-34da-798d-e3ec3e9d593e"
//     ),
//     ));

//     $responseCompleteOrder = curl_exec($curlCompleteOrder);
//     $errCompleteOrder = curl_error($curlCompleteOrder);
// }
// /* End Get vendor payouts details */
// // $date = date("Y-m-d H:i:s A",$responseTime);



function fn_ch_custom_reports_init_templater($company_id){
	if($company_id != '' && $company_id > 0){
        $companyUrl = db_get_field("SELECT url FROM ?:companies WHERE company_id = $company_id"); 
	    return $companyUrl;
	}
}

function fn_ch_custom_reports_get_username($user_id){
	if($user_id != '' && $user_id > 0){
        $userName = db_get_field("SELECT firstname FROM ?:users WHERE user_id = $user_id"); 
	    return $userName;
	}
}


?>