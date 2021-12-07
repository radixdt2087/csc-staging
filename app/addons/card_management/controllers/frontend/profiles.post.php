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

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * @var string $mode
 * @var string $action
 */
$merchantId = Registry::get('addons.digitzs_connect.admin_merchant_id');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    if ($mode == 'update') {
        $user_id = $auth['user_id'];
        $token = generateTokenCard();
        $customer_data = getCustomerDetails($_POST['user_data'],$user_id,$merchantId);
        $token=$token->data->attributes->appToken;
        $merchant = createCustomers($customer_data,$token);
        $error_status=false;
        if(isset($merchant->errors)) {
            foreach($merchant->errors as $mer) {
                $error = displayError($mer);
                fn_set_notification('E', __('error'), $error);
                $error_status=true;
            }
        }
        if(isset($merchant->data->id)) {
        $merchant_id = $merchant->data->id;
        $_data = array();
        $_data['user_id'] = $user_id;
        $_data['customer_id'] = $merchant_id;
        $id = db_get_field("SELECT user_id FROM ?:card_details WHERE user_id = ?i", $user_id);
            if(empty($id)) {
                db_query("INSERT INTO ?:card_details ?e", $_data);
            } else {
                db_query("UPDATE ?:card_details SET ?u WHERE user_id = ?i", $_data, $user_id);
            }
        }
    }
}

function generateTokenCard() {
    $appKey = 'V5Y10mcDeotpPUJ5xVd4L4Isi8ra0xfeLo3Z0uqaZREHYUIatuinXaJ4Q5Bngh6g';
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    $tokenApi = 'auth/token';
    $post = array(
      'data' => array('type' =>'auth','attributes' => array('appKey'=>$appKey))
    );
    $post = json_encode($post);
    $header = array(
      "x-api-key:".$apiKey,
      "Content-Type: application/json"
    );
    return callDigitzApiCustomer($tokenApi,$post,$header);
}

function createCustomers($post,$token) {
    $api = 'customers';
    $post = json_encode($post);
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    $header=array(
      "Authorization:Bearer ".$token,
      "x-api-key:".$apiKey,
      "Content-Type:application/json"
    );
    return callDigitzApiCustomer($api,$post,$header);
}

function getCustomerDetails($customer_details,$user_id, $merchantId) {
    extract($customer_details);
    $name =  $customer_details['firstname'].' '.$customer_details['lastname'];
    $merchantData = array( "data"=> array(
        "type" => "customers",
        "attributes" => array(
            "merchantId" => $merchantId ,
            "name" => $name,
           // "externalId" => $user_id
           "externalId" => '659898'
        )
    ));
    return $merchantData;
}
function callDigitzApiCustomer($api,$post,$header) {
    $curl = curl_init();
    $modes = Registry::get('addons.digitzs_connect.modes');
    $endPoint = '';
    if($modes =="Test") {
    $endPoint = "https://test.digitzsapi.com/test/";
    } else {
    $endPoint = "https://test.digitzsapi.com/test/";
    }

    curl_setopt_array($curl, array(
    CURLOPT_URL => $endPoint.$api,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $post,
    CURLOPT_HTTPHEADER => $header,
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);
}
function displayError($mer) {
    $error='';
    if($mer->source->pointer == '/data/attributes/businessInfo/ein') {
        $error = '"ein" must be 9 digits long and it\'s should be numeric from 0-9';
    } else if($mer->source->pointer == '/data/attributes/bankInfo/routingNumber') {
        $error = '"routingNumber" must be 9 digits long and it\'s should be numeric from 0-9';
    } else if($mer->source->pointer =='/data/attributes/personalInfo/socialSecurity') {
        $error = '"socialSecurity" must be 9 digits long and it\'s should be numeric from 0-9';
    } else if($mer->detail == 'Invalid bank routing number') {
        $error = '"routingNumber" is not valid. Please ask your bank or you can find routing number that is located on the bottom of your Cheque. It\'s the first set of nine numbers, on the left, and starts with a "0", "1", "2", or "3".';
    } else if($mer->detail == 'Invalid date of birth') {
        $error = '"birthdate" Your age must be 18+ to create merchant account';
    } else if($mer->source->pointer == '/data/attributes/merchantId') {
        $error = 'Please register merchant with digitzs';
    } else {
        $error = $mer->detail;
    }
    return $error;
}