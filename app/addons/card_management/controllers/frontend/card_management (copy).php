<?php
/***************************************************************************
*                                                                          *
*   (c) 2021    *
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
use Tygh\Ajax;
use Tygh\Tools\Url;

if (!defined('BOOTSTRAP')) { die('Invalid Access'); }
$apiKey = Registry::get('addons.digitzs_connect.api_key');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 // For deleting the Card
  if ($mode == 'delete') {
    if (defined('AJAX_REQUEST')) {
      $dauth = Tygh::$app['session']['auth'];
      $card_token= $_REQUEST['card_token'];
      if (!empty($card_token)) {
          $isDefault = db_get_field('SELECT isDefault FROM ?:card_details where card_token = ?s', $card_token);
          if( $isDefault == 1 ){
            fn_set_notification('W', __('warning'), 'You cannot delete the default Card. Please make any other card default first then delete this one.');
          }
          else{
            $card_id = db_get_field('SELECT id FROM ?:card_details where card_token = ?s', $card_token);
            $result = db_query("DELETE FROM ?:card_details WHERE card_token = ?s AND id = ?i", $card_token, $card_id );
            if ($result == 1) {
                $force_redirection = 'checkout.checkout';
                if (defined('AJAX_REQUEST')) {
                    Tygh::$app['ajax']->assign('force_redirection', fn_url($force_redirection));
                    Tygh::$app['ajax']->assign('message', fn_set_notification('N', __('notice'), 'Your Card is Deleted'));
                    exit;
                } else {
                    return [CONTROLLER_STATUS_REDIRECT, $force_redirection];
                }
            }
          }
      }
    }
  }
  //For adding the card
  if ($mode == 'add') {
    if (defined('AJAX_REQUEST')) {
        $data = array(
          'card_holder_name'=> $_REQUEST['holder'],
          'card_number' => str_replace(' ','',$_REQUEST['card_number']),
          'valid_thru' => $_REQUEST['month'].$_REQUEST['year'],
        );
        $dauth = Tygh::$app['session']['auth'];
        $cardToken = generateCardToken($data,$dauth['user_id']);
        $cardData = array(
            'user_id' => $dauth['user_id'],
            'card_token' => $cardToken->data->id,
            'card_number' => str_replace(' ','',$_REQUEST['card_number']),
            'customer_id' => $cardToken->data->attributes->customerId,
        );
        $result = db_query("INSERT INTO ?:card_details ?e ", $cardData);  
        if (!empty($result)) {
          fn_set_notification('N', __('notice'), 'Your Card is Added');
          $force_redirection = 'checkout.checkout';
          if (defined('AJAX_REQUEST')) {
              Tygh::$app['ajax']->assign('force_redirection', fn_url($force_redirection));
              exit;
          } else {
              return [CONTROLLER_STATUS_REDIRECT, $force_redirection];
          }
      }
    }
  }
  //For updating the card
  if ($mode == 'update') {
    if (defined('AJAX_REQUEST')) {  
      $card_id = db_get_field('SELECT id FROM ?:card_details where card_token = ?s', $_REQUEST['card_token']);
      $data = array(
        'card_holder_name'=> $_REQUEST['holder'],
        'valid_thru' => $_REQUEST['month'].$_REQUEST['year'],
        'b_address' => $_REQUEST['b_address'],
        'b_address_2' => $_REQUEST['b_address_2'],
        'b_city' => $_REQUEST['b_city'],
        'b_state' => $_REQUEST['b_state'],
        'b_country' => $_REQUEST['b_country'],
        'b_zipcode' => $_REQUEST['b_zipcode'],
        'card_token' => $_REQUEST['card_token'],
       );  
      $dauth = Tygh::$app['session']['auth']; 
      $card_token = updateCardToken($data,$dauth['user_id']); 
      $cardData = array(
          'card_token' => $card_token->data->id,
      );
     // echo '<pre>'; print_r($card_id); die();
      
      $result = db_query("UPDATE ?:card_details SET ?u WHERE id = ?i", $cardData, $card_id);
      if ($result == 1) {
        fn_set_notification('N', __('notice'), 'Your Card is Updated');
        $force_redirection = 'checkout.checkout';
        if (defined('AJAX_REQUEST')) {
            Tygh::$app['ajax']->assign('force_redirection', fn_url($force_redirection));
            exit;
        } else {
            return [CONTROLLER_STATUS_REDIRECT, $force_redirection];
        }
    }
    }
  }
  //making the card default.
  if ($mode == 'setDefault') {
    if (defined('AJAX_REQUEST')) {
      $dauth = Tygh::$app['session']['auth'];
      $card_token= $_REQUEST['card_token'];
      if (!empty($card_token)){
        //$card_id = db_get_field('SELECT id FROM ?:card_details where card_token = ?s', $card_token); 
        $removeDefault = db_query("UPDATE ?:card_details SET isDefault = '0' WHERE user_id = ?i", $dauth['user_id']);
        if ($removeDefault == 1) {
            $setDefaultArray = array(
            'isDefault' => '1'
          );       
          $isDefault = db_query("UPDATE ?:card_details SET ?u WHERE card_token = ?s", $setDefaultArray, $card_token);
          echo $isDefault; die();
          if ($isDefault == 1) {
            fn_set_notification('N', __('notice'), 'This card is set as default Card.');
            $force_redirection = 'checkout.checkout';
                if (defined('AJAX_REQUEST')) {
                    Tygh::$app['ajax']->assign('force_redirection', fn_url($force_redirection));
                    exit;
                } else {
                    return [CONTROLLER_STATUS_REDIRECT, $force_redirection];
                }
          }
        }
      }
    }
  }
}
function generateCardToken($cardData, $user_id){
  $userDetails = db_get_array('SELECT * FROM ?:user_profiles where user_id = ?i', $user_id);
  $customer_id = db_get_field('SELECT customer_id FROM ?:card_details where user_id = ?i', $user_id);
  $token=generateTokenNew($apiKey);
  $token=$token->data->attributes->appToken;
  $newCardToken = '';
  foreach ($userDetails as $key => $value){
    $data = array("data" => array(
      "type" => "tokens",
      "attributes" => array(
          "tokenType" => "card",
          "customerId" => $customer_id,
          "label" => "MasterCard",
          "protected" => true,
          "card" => array(
              "type" => "visa",
              "holder" => $cardData['card_holder_name'],
              "number" => $cardData['card_number'],
              "expiry" => $cardData['valid_thru'],
              "protected" => true
          ),
          "billingAddress"=>array(
              "line1" => $value['b_address'],
              "line2" => $value['b_address_2'] == '' ? $value['b_address']: $shippingAddress['b_address_2'],
              "city" => $value['b_city'],
              "state" => $value['b_state'],
              "zip" => $value['b_zipcode'],
              "country" => $value['b_country'] == 'US' ? 'USA': 'CAN'
          )
        )
      )
    );
    $post = json_encode($data);
    $api = 'tokens';
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    $header=array(
      "Authorization:Bearer ".$token,
      "x-api-key:".$apiKey,
      "Content-Type:application/json"
    );
    $newCardToken = callCardApi($api,$post,$header);
    return $newCardToken;  
  }
}
function updateCardToken($cardData, $user_id){
  $card_num = db_get_field('SELECT card_number FROM ?:card_details where card_token = ?s', $cardData['card_token']);
  $customer_id = db_get_field('SELECT customer_id FROM ?:card_details where user_id = ?i', $user_id);
  $token=generateTokenNew($apiKey);
  $token=$token->data->attributes->appToken;
  $data = array("data" => array(
    "type" => "tokens",
    "attributes" => array(
        "tokenType" => "card",
        "customerId" => $customer_id,
        "label" => "MasterCard",
        "protected" => true,
        "card" => array(
            "type" => "visa",
            "holder" => $cardData['card_holder_name'],
            "number" => $card_num,
            "expiry" => $cardData['valid_thru'],
            "protected" => true
        ),
        "billingAddress"=>array(
            "line1" => $cardData['b_address'],
            "line2" => $cardData['b_address_2'] == '' ? $cardData['b_address']: $cardData['b_address_2'],
            "city" => $cardData['b_city'],
            "state" => $cardData['b_state'],
            "zip" => $cardData['b_zipcode'],
            "country" => $cardData['b_country'] == 'US' ? 'USA': 'CAN'
        )
      )
    )
  );
  $post = json_encode($data);
  $api = 'tokens';
  $apiKey = Registry::get('addons.digitzs_connect.api_key');
  $header=array(
    "Authorization:Bearer ".$token,
    "x-api-key:".$apiKey,
    "Content-Type:application/json"
  );
  $newCardToken = callCardApi($api,$post,$header);
  return $newCardToken;  
}

function generateTokenNew($apiKey) {
  $appKey = 'V5Y10mcDeotpPUJ5xVd4L4Isi8ra0xfeLo3Z0uqaZREHYUIatuinXaJ4Q5Bngh6g';
  $tokenApi = 'auth/token';
  $post = array(
    'data' => array('type' =>'auth','attributes' => array('appKey'=>$appKey))
  );
  $post = json_encode($post);
  $header = array(
    "x-api-key:".$apiKey,
    "Content-Type => application/json"
  );
  return callCardApi($tokenApi,$post,$header);
}

function callCardApi($api,$post,$header) {
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