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
use Tygh\Registry;
if($mode === 'details') {
	$apiKey = Registry::get('addons.digitzs_connect.api_key');
	$token = generateAuthToken($apiKey);
	$txnStatus = false;
	$header = array(
		"x-api-key:".$apiKey,
		"Authorization:Bearer $token"
	);
	if($_REQUEST['order_id'] > 0){
		$digitzs_payment_id = db_get_field("SELECT digitzs_payment_id FROM ?:orders WHERE order_id = ?i", $_REQUEST['order_id']);
		if($digitzs_payment_id){
			$responseData =  callOrderRefundStatus($header,$digitzs_payment_id);
			if($responseData->data->txnStatus == 'CCCredit'){
				$txnStatus = true;
				$data = array (
						'status' => 'E'
					);
				db_query("UPDATE ?:orders SET ?u WHERE order_id = ?i", $data, $_REQUEST['order_id']);
			}
			Tygh::$app['view']->assign('txnStatus', $txnStatus);   
		}
	}
	
}
function callOrderRefundStatus($header,$digitzs_payment_id){
	$curl = curl_init();
	$endPoint = '';
	$modes = Registry::get('addons.digitzs_connect.modes');
	if($modes =="Test") {
		$endPoint = "https://test.digitzsapi.com/test/payments/status/$digitzs_payment_id";
	} else {
		$endPoint = "https://beta.digitzsapi.com/v3/payments/status/$digitzs_payment_id";
	}
	curl_setopt_array($curl, array(
		CURLOPT_URL => $endPoint,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => $header,
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $responseData = json_decode($response);
}
function generateAuthToken($apiKey) {
	$modes = Registry::get('addons.digitzs_connect.modes');
	$appKey = 'V5Y10mcDeotpPUJ5xVd4L4Isi8ra0xfeLo3Z0uqaZREHYUIatuinXaJ4Q5Bngh6g';
	if($modes =="Test") {
		$endPoint = "https://test.digitzsapi.com/test/auth/token";
	} else {
		$endPoint = "https://beta.digitzsapi.com/v3/auth/token";
	}
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $endPoint,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{
		"data": {
			"type": "auth",
			"attributes": {
				"appKey": "'.$appKey.'"
			}
		}
		}',
		CURLOPT_HTTPHEADER => array(
		'x-api-key: '.$apiKey.'',
		'Content-Type: application/json'
		),
	));
	$response = curl_exec($curl);
	$response = json_decode($response);
	return $response->data->attributes->appToken;
}
?>
