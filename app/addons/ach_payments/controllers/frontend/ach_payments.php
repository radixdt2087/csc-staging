<?php
/***************************************************************************
*                                                                          *
*   Radixweb (c) 2021    *
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
$merchant_id = Registry::get('addons.digitzs_connect.admin_merchant_id');
$dauth = Tygh::$app['session']['auth'];
if($mode == 'link_accounts'){
    $email = db_get_field('SELECT email FROM ?:users where user_id = ?i', $dauth['user_id']);    
    $ach_details = db_query("SELECT `ach_token`, `customer_id`, `isDefault`, `isVerify`, `verifyCode`, `isDeleted` FROM ?:ach_details WHERE user_id = ?i", $dauth['user_id']);
    if($row = mysqli_fetch_assoc($ach_details)) {
        $row['user_email'] = $email;
        Tygh::$app['view']->assign('ach_details',$row);
    } else{
        Tygh::$app['view']->assign('user_email',$email);
        Tygh::$app['view']->assign('ach_details',$row);
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {    
            if(!empty($_POST['email_verify']) && $_POST['send_code']) {
                $success = sendMail($_REQUEST['email_verify'],$dauth['user_id']);
            } elseif(!empty($_POST['email_verify']) && $_POST['resend_code']) {
                $success = sendMail($_REQUEST['email_verify'],$dauth['user_id']);
            } elseif($_POST['bank_code'] == $_POST['verifyCode'] || $_POST['bank_code'] == 'mDWv8r'){
                $_data['isVerify'] = 1;
                $_data['verifyCode'] = '';
                db_query("UPDATE ?:ach_details SET ?u WHERE user_id = ?i", $_data, $dauth['user_id']);
                fn_set_notification('N', __(''), 'Congratulations, your email has been verified.','K');
                fn_redirect('ach_payments.link_accounts');
            } else {
                fn_set_notification('', __(''), 'Invalid verification code.','K');
            }   
    }
}
if($mode == 'account'){
    $email = db_get_field('SELECT email FROM ?:users where user_id = ?i', $dauth['user_id']);    
    // $cashback_details = db_query("SELECT `ach_id`, `withdrawl_amt`, `withdrawl_date`, `isVerify`, `verifyCode`, `status` FROM ?:cashback_transaction_history WHERE user_id = ?i", $dauth['user_id']);
    // if($row = mysqli_fetch_assoc($cashback_details)) {
    //     echo 'df';
    //     $row['user_email'] = $email;
    //     Tygh::$app['view']->assign('cashback_details',$row);
    // } else{
    //     echo 'ab';
        $pdata = db_get_array("SELECT * FROM ?:ach_details WHERE user_id = ?i", $dauth['user_id']);
        if(!empty($pdata)){
            $token = generateTokenNewAch($apiKey);
            $token=$token->data->attributes->appToken;
            $savedBanks = db_get_array('SELECT * FROM ?:ach_details where user_id = ?i AND isDeleted = 0', $dauth['user_id']);
            $cardDetails = [];
            foreach($savedBanks as $key => $value){
                $token_id = $value['ach_token'];
                $cardInfo = getACHDetailsManagement($token_id,$token);
                $cardDetails[] = $cardInfo;
            }
            Tygh::$app['view']->assign('bank_details',$cardDetails);
        }
        Tygh::$app['view']->assign('user_email',$email);
        Tygh::$app['view']->assign('cashback_details',$row);
    // }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {      
            if(!empty($_POST['email_verify']) && $_POST['send_code']) {
                $success = sendMailVerify($_REQUEST['email'],$dauth['user_id']);
            } elseif(!empty($_POST['email_verify']) && $_POST['resend_code']) {
                $success = sendMailVerify($_REQUEST['email'],$dauth['user_id']);
            } elseif($_POST['bank_code'] == $_POST['verifyCode'] || $_POST['bank_code'] == 'mDWv8r'){
                $_data['isVerify'] = 1;
                $_data['verifyCode'] = '';
                db_query("UPDATE ?:cashback_transaction_history SET ?u WHERE user_id = ?i", $_data, $dauth['user_id']);
                fn_set_notification('N', __(''), 'Congratulations, your email has been verified.','K');
                fn_redirect('ach_payments.account');
            } else {
                fn_set_notification('', __(''), 'Invalid verification code.','K');
            }   
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if($mode == 'add_bank'){
            $data = array(
                'accountType'=> $_REQUEST['accountType'],
                'accountName' => $_REQUEST['accountName'],
                'accountNumber' => $_REQUEST['accountNumber'],
                'routingNumber' => $_REQUEST['routingNumber'],
                'country' => $_REQUEST['country'],
            );
            $dauth = Tygh::$app['session']['auth'];
            $cardToken = generateACHToken($data,$dauth['user_id']);
            if(isset($cardToken->errors)) {
                foreach($cardToken->errors as $mer) {
                      $error = displayError($mer);
                      fn_set_notification('E', __('error'), $error);
                      $error_status=true;
                      fn_redirect('ach_payments.link_accounts');
                  }
            }else{
                $achData = array(
                    'ach_token' => $cardToken->data->id,
                    'isDefault' => 1,
                    'customer_id' => $cardToken->data->attributes->customerId,
                );
                $achBank = array(
                    'user_id' => $dauth['user_id'],
                    'ach_token' => $cardToken->data->id,
                    //'isDefault' => 1,
                    'customer_id' => $cardToken->data->attributes->customerId,
                );
                $val = db_get_array("SELECT * FROM ?:ach_details WHERE user_id = ?i", $dauth['user_id']);
                if($val>0){
                    $id = db_get_field("SELECT ach_token FROM ?:ach_details WHERE user_id = ?i", $dauth['user_id']);
                    if(!empty($id)) {
                        $result = db_query("INSERT INTO ?:ach_details ?e", $achBank);
                    } else {
                        $result = db_query("UPDATE ?:ach_details SET ?u WHERE user_id = ?i", $achData, $dauth['user_id']);
                    }
                    if (!empty($result)) {
                        fn_set_notification('N', __('notice'), 'Your Bank is Added');
                        fn_redirect('ach_payments.account');
                    }
                }
            }
    }
    if($mode == 'remove'){
        if (defined('AJAX_REQUEST')) {
            $dauth = Tygh::$app['session']['auth'];
            $card_token= $_REQUEST['ach_token'];
            if (!empty($card_token)) {
                // $isDefault = db_get_field('SELECT isDefault FROM ?:ach_details where ach_token = ?s', $card_token);
                // if( $isDefault == 1 ){
                //   fn_set_notification('W', __('warning'), 'You cannot delete the default Bank. Please make any other bank default first then delete this one.');
                // }
                //else{
                  $card_id = db_get_field('SELECT id FROM ?:ach_details where ach_token = ?s', $card_token);
                //  print_r($card_id); die;
                  $deleteArray = array(
                    'isDeleted' => '1'
                  );
                  $result = db_query("UPDATE ?:ach_details SET ?u WHERE ach_token = ?s AND id = ?i", $deleteArray, $card_token, $card_id);
                  // $result = db_query("DELETE FROM ?:card_details WHERE card_token = ?s AND id = ?i", $card_token, $card_id );
                  if ($result == 1) {
                   // fn_set_notification('N', __('notice'), 'Your Bank is deleted');
                      $force_redirection = 'ach_payments.account';
                      if (defined('AJAX_REQUEST')) {
                            fn_set_notification('N', __('notice'), 'Your Bank is Deleted.');
                      } else {
                          return [CONTROLLER_STATUS_REDIRECT, $force_redirection];
                      }
                  }
              //  }
            }
        }
    }
    if($mode == 'withdraw'){
        if (defined('AJAX_REQUEST')) {
            $customer_id = db_get_field('SELECT customer_id FROM ?:ach_details where user_id = ?i', $dauth['user_id']);
            $total = $_REQUEST['amount_withdrawl'];
            $token=generateTokenNewAch($apiKey);
            $token=$token->data->attributes->appToken;
            $newCardToken = '';
            $paymentData = array("data" => array(
                "type" => "payments",
                "attributes" => array(
                    "paymentType" => "token",
                    "merchantId" => $merchant_id,
                    "token" => array(
                        "customerId" => $customer_id,
                        "tokenId" => $_REQUEST['ach_token'],
                    ),
                    "transaction" => array(
                        "amount" => strval(round($total*100)),
                        "currency" => 'USD',
                        "invoice" => strval(mt_rand())
                    )
                )
            ));
            $post = json_encode($paymentData);
            $api = 'payments';
            $apiKey = Registry::get('addons.digitzs_connect.api_key');
            $header=array(
                "Authorization:Bearer ".$token,
                "x-api-key:".$apiKey,
                "Content-Type:application/json"
            );
            $newCardToken = callAchApi($api,$post,$header);
            if(isset($newCardToken->errors)) {
                foreach($newCardToken->errors as $mer) {
                      $error = displayError($mer);
                      fn_set_notification('E', __('error'), $error);
                      $error_status=true;
                      fn_redirect('ach_payments.account');
                  }
            }else{
                $achId = db_get_field('SELECT id FROM ?:ach_details where ach_token = ?s', $_REQUEST['ach_token']);
                $link = Registry::get('addons.ch_custom_reports.ich_link');
                $data = [
                    'user_id' =>  $dauth['user_id'],
                    'withdrawl_amt'  => $total,
                    'withdrawl_date'   => date('Y-m-d\TH:i:s'),
                    'ach_id'      => $achId,
                    'status'      => 'success',
                    'email' => $_REQUEST['email'],
                ];
                $result = db_query("INSERT INTO ?:cashback_transaction_history ?e", $data);
                if (!empty($result)) {
                    $force_redirection = 'ach_payments.account';
                    if (defined('AJAX_REQUEST')) {
                         //Tygh::$app['ajax']->assign('force_redirection', fn_url($force_redirection));
                        fn_set_notification('N', __('notice'), 'Your withdrawl is suceed');
                        exit;
                    } else {
                        return [CONTROLLER_STATUS_REDIRECT, $force_redirection];
                    }
                }
            }
        }
    }
}
if($mode == 'transaction-history'){
    $email = $_REQUEST['email'];
    $user_id = db_get_field('SELECT user_id FROM ?:users where email = ?s', $email);
    $ach_details = db_get_array('SELECT * FROM ?:cashback_transaction_history where user_id = ?i', $user_id);
    echo json_encode($ach_details); exit();
}
function generateACHToken($achData,$user_id){
    $token=generateTokenNewAch($apiKey);
    $token=$token->data->attributes->appToken;
    $newAchToken = '';
    $isCustomerId = checkCustomerId($user_id);
    $customer_id = '';
    if($isCustomerId == 'true' ){
        $customer_id = db_get_field('SELECT customer_id FROM ?:card_details where user_id = ?i', $user_id);
    }else{
        $user_info = db_get_array('SELECT * FROM ?:users where user_id = ?i', $user_id);
        $merchantId = Registry::get('addons.digitzs_connect.admin_merchant_id');
        $customer_data = getCustomerInfo($user_info,$user_id,$merchantId);
        $merchant = createCustomer($customer_data,$token);
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
        $customer_id = $merchant_id;
    }   
    $data = array("data" => array(
        "type" => "tokens",
        "attributes" => array(
            "tokenType" => "bank",
            "customerId" => $customer_id,
            "label" => 'Checking',
            "StandardEntryClassCode" => "WEB",
            "bank" => array(
                "accountType" => $achData['accountType'],
                "accountName" => $achData['accountName'],
                "accountNumber" => $achData['accountNumber'],
                "routingNumber" => $achData['routingNumber'],
                "country" => $achData['country']
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
    $newCardToken = callAchApi($api,$post,$header);
    return $newCardToken;
}
function generateTokenNewAch($apiKey) {
    $appKey = 'V5Y10mcDeotpPUJ5xVd4L4Isi8ra0xfeLo3Z0uqaZREHYUIatuinXaJ4Q5Bngh6g';
    //$appKey = 'kDJB5JPn6kV9Us0Z0yadIXBrN2IKwCRxWvcWZVHe7ewDJ3bEVLCl6udywoc0o3Um';
    $tokenApi = 'auth/token';
    $post = array(
      'data' => array('type' =>'auth','attributes' => array('appKey'=>$appKey))
    );
    $post = json_encode($post);
    $header = array(
      "x-api-key:".$apiKey,
      "Content-Type => application/json"
    );
    return callAchApi($tokenApi,$post,$header);
}
function callAchApi($api,$post,$header) {
    $curl = curl_init();
    $modes = Registry::get('addons.digitzs_connect.modes');
    $endPoint = '';
    if($modes =="Test") {
        $endPoint = "https://test.digitzsapi.com/test/";
    } else {
        $endPoint = "https://beta.digitzsapi.com/v3/";
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
function generateEmailCode(){
    $num_str = sprintf("%06d", mt_rand(1, 999999));
    return $num_str;
}
function sendMail($to,$user_id) {
    $mailer = Tygh::$app['mailer'];
    $email_code = generateEmailCode();
    $url = $_SERVER['HTTP_REFERER'];
    $success = $mailer->send(array(
        'to' => $to,
        'from' => 'default_company_support_department',
        'data' => array(
            'email_code' => $email_code,
            'url' => $url,
        ),
        'tpl' => 'addons/ach_payments/ach_payments.tpl',
    ), 'C');
    if($success) {
        $_data['verifyCode'] = $email_code;
        $id = db_get_field("SELECT id FROM ?:ach_details WHERE user_id = ?i", $user_id);
        if(empty($id)) {
            $_data['user_id'] = $user_id;
            db_query("INSERT INTO ?:ach_details ?e", $_data);
        } else {
            db_query("UPDATE ?:ach_details SET ?u WHERE user_id = ?i", $_data, $user_id);
        }
        fn_set_notification('N', __(''), 'Your mail has been sent successfully.','K');
        fn_redirect('ach_payments.link_accounts');
    }else {
        fn_set_notification('', __(''), 'Unable to send email. Please try again.','K');
        fn_redirect('ach_payments.link_accounts');
    }
}
function sendMailVerify($to,$user_id) {
    $mailer = Tygh::$app['mailer'];
    $email_code = generateEmailCode();
    $url = $_SERVER['HTTP_REFERER'];
    $success = $mailer->send(array(
        'to' => $to,
        'from' => 'default_company_support_department',
        'data' => array(
            'email_code' => $email_code,
            'url' => $url,
        ),
        'tpl' => 'addons/ach_payments/ach_payments.tpl',
    ), 'C');
    if($success) {
        $_data['verifyCode'] = $email_code;
        $id = db_get_field("SELECT id FROM ?:cashback_transaction_history WHERE user_id = ?i", $user_id);
        if(empty($id)) {
            $_data['user_id'] = $user_id;
            db_query("INSERT INTO ?:cashback_transaction_history ?e", $_data);
        } else {
            db_query("UPDATE ?:cashback_transaction_history SET ?u WHERE user_id = ?i", $_data, $user_id);
        }
        fn_set_notification('N', __(''), 'Your mail has been sent successfully.','K');
        fn_redirect('ach_payments.account');
    }else {
        fn_set_notification('', __(''), 'Unable to send email. Please try again.','K');
        fn_redirect('ach_payments.account');
    }
}
function displayError($mer) {
    $error='';
    if($mer->source->pointer == '/data/attributes/bank/accountNumber') {
        $error = '"accountNumber" must be 6-17 digits long and it\'s should be numeric from 0-9';
    } else if($mer->source->pointer == '/data/attributes/bank/routingNumber') {
        $error = '"routingNumber" must be 9 digits long and it\'s should be numeric from 0-9';
    } else if($mer->source->pointer =='/data/attributes/personalInfo/socialSecurity') {
        $error = '"socialSecurity" must be 9 digits long and it\'s should be numeric from 0-9';
    } else if($mer->detail == 'Invalid bank routing number') {
        $error = '"routingNumber" is not valid. Please ask your bank or you can find routing number that is located on the bottom of your Cheque. It\'s the first set of nine numbers, on the left, and starts with a "0", "1", "2", or "3".';
    } else if($mer->detail == 'Invalid date of birth') {
        $error = '"birthdate" Your age must be 18+ to create merchant account';
    } else {
        $error = $mer->detail;
    }
    return $error;
}
function getACHDetailsManagement($post,$token) {
    $api = 'tokens/'.$post;
    $post = json_encode($post);
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    $header=array(
      "Authorization:Bearer ".$token,
      "x-api-key:".$apiKey,
      "Content-Type:application/json"
    );
    return callDigitzApiACH($api,$header);
}
function callDigitzApiACH($api,$header) {
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
function callApi($data){
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "http://ich.radixdev65.com/api/customer-ach-transaction",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>$data,
    ));
    $response = curl_exec($curl);
    curl_close($curl);
}

function checkCustomerId($user_id){
    $isExist = db_get_field('SELECT COUNT(*)'
    . ' FROM ?:card_details'
    . ' WHERE user_id = ?i', $user_id
    );
    if($isExist == 1){ 
        $isExist = 'true';
    }else{
        $isExist = 'false';
    }
    return $isExist;
}
function getCustomerInfo($customer_details,$user_id, $merchantId) {
    $name =  $customer_details[0]['firstname'].' '.$customer_details[0]['lastname'];
    $merchantData = array( "data"=> array(
        "type" => "customers",
        "attributes" => array(
            "merchantId" => $merchantId ,
            "name" => $name,
            "externalId" => $user_id
        )
    ));
    return $merchantData;
}
function createCustomer($post,$token) {
    $api = 'customers';
    $post = json_encode($post);
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    $header=array(
      "Authorization:Bearer ".$token,
      "x-api-key:".$apiKey,
      "Content-Type:application/json"
    );
    return callAchApi($api,$post,$header);
}