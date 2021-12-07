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

/**
 * Hook.
 * This hook is needed for digitzs calculator on the checkout page.
 * Changes params to get payment processors.
 *
 * @param array $params    Array of flags/data which determines which data should be gathered
 * @param array $fields    List of fields for retrieving
 * @param array $join      Array with the complete JOIN information (JOIN type, tables and fields) for an SQL-query
 * @param array $order     Array containing SQL-query with sorting fields
 * @param array $condition Array containing SQL-query condition possibly prepended with a logical operator AND
 * @param array $having    Array containing SQL-query condition to HAVING group
 */
function fn_card_management_get_payments($params, $fields, $join, $order, &$condition, $having)
{
    $mode = Registry::get('runtime.mode');
    if ($mode == 'checkout') {
        $dauth = Tygh::$app['session']['auth']; 
        $pdata = db_get_field(
            'SELECT card_token'
            .' FROM ?:card_details WHERE user_id = ?i AND isDefault = 1 AND isDeleted = 0', $dauth['user_id']
        );
        $token = generateTokenCardManagement();
        $token=$token->data->attributes->appToken;
        if(!empty($pdata)){
            $defaultCard = getCardDetailsManagement($pdata,$token);
            Tygh::$app['view']->assign('defaultCard',$defaultCard);
            $savedCards = db_get_array('SELECT * FROM ?:card_details where user_id = ?i AND isDeleted = 0', $dauth['user_id']);
            // $savedCards = db_get_array('SELECT * FROM ?:card_details where user_id = ?i AND isDefault != 1', $dauth['user_id']);
            $cardDetails = [];
            foreach($savedCards as $key => $value){
                $token_id = $value['card_token'];
                $cardInfo = getCardDetailsManagement($token_id,$token);
                $cardDetails[] = $cardInfo;
            }
          //  echo '<pre>'; print_r($savedCards); die();
            Tygh::$app['view']->assign('card_details',$cardDetails);
        } 
    }
}
function generateTokenCardManagement() {
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
    return callDigitzApiCustomerManagement($tokenApi,$post,$header);
}

function callDigitzApiCustomerManagement($api,$post,$header) {
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

function getCardDetailsManagement($post,$token) {
    $api = 'tokens/'.$post;
    $post = json_encode($post);
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    $header=array(
      "Authorization:Bearer ".$token,
      "x-api-key:".$apiKey,
      "Content-Type:application/json"
    );
    return callDigitzApiCustomer1($api,$header);
}

function callDigitzApiCustomer1($api,$header) {
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
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => $header,
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);
}