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
if (!defined('BOOTSTRAP')) { die('Access denied'); }
if (defined('PAYMENT_NOTIFICATION')) {
    if (isset($_REQUEST['order_id'])) {
        if ($mode == 'response') {
            $order_id = $_REQUEST['order_id'];
            fn_change_order_status($order_id, $pp_response['order_status'], '', false);
            fn_finish_payment($order_id, $pp_response);
            fn_order_placement_routines('route', $order_id, false);
        }
    }
    exit;
    } else {
   // echo '<pre> dfdgdf'; print_r($order_info); die();   
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    if (!empty($apiKey)    
        && (!empty($order_info['payment_info']['card_number']) || !empty($order_info['payment_info']['card_token']))
        || !empty($order_info['payment_info']['cvv2'])
        
    ) {
        try {
        // echo '<pre> hi'; print_r($order_info); die();
            $ccount=0;
            $token=generateToken($apiKey);
            $token=$token->data->attributes->appToken;
            if(count($order_info['product_groups']) > 1) {
                $ccount=1;
            }
            foreach ($order_info['product_groups'] as $key => $value) { 
                $countryFee = $value['package_info']['location']['country'];
                $error_status=false;
                $current_id = $value['company_id'];
                $user_id=$order_info['user_id'];
                $cdata = fn_get_company_data($current_id);
                $vendor_merchant_id = '';
                
                if(!empty($cdata['digitzs_connect_account_id'])) {
                    $vendor_merchant_id = $cdata['digitzs_connect_account_id'];
                }
                $payment='';
                $totalcommission = 0;
                if($vendor_merchant_id!='') {
                    foreach($value['products'] as $key => $provalue){
                        $is_ovverride = checkOverride($provalue['product_id']);
                        $subtotal = $provalue['price']*$provalue['amount'];
                        $commission = calculateCommision($current_id,$is_ovverride, $provalue['product_id'],$subtotal);
                        $totalcommission +=$commission; 
                    }
                    //echo '<pre>'; print_r($totalcommission); die();
                    $payment_data=array('company_id'=>$current_id,'vendor_merchant_id'=>$vendor_merchant_id,'order_id'=>$order_info['order_id'],'payment_info'=>$order_info['payment_info'],'total'=>$totalcommission,'currency'=>$order_info['secondary_currency'],'invoice_id'=>strval(mt_rand()),'totalwithshipping' => $order_info['total']);
                    $order_data = '';
                    
                    if(!empty($order_info['payment_info']['card_token'])){
                        $cardTokenPayment = getTokenPaymentDetails($order_info['payment_info']['card_token'],$token);
                       // echo '<pre>'; print_r($cardTokenPayment); die();
                        $order_data = getCardTokenPaymentDetails($cardTokenPayment,$payment_data);
                    }elseif(!empty($order_info['payment_info']['card_number'])){
                        $order_data = getPaymentDetails($payment_data);
                    }
                    //echo '<pre>'; print_r($order_data); die();
                    $payment=createSplitPayment($order_data,$token);
                    //echo '<pre>'; print_r($payment); die();
                    if(isset($payment->errors)) {
                        foreach($payment->errors as $mer) {
                            fn_set_notification('E', __('error'), $mer->detail);
                            $error_status=true;
                        }
                    }
                } else {
                    fn_set_notification('E', __('error'), $cdata['company'].' Not register with Digitzs Payment');
                    $error_status=true;
                }
                if($error_status) {
                    fn_redirect('checkout.checkout');
                }
                //if($payment->data->attributes->transaction->message == 'Success') {
                if(!empty($payment->data->attributes->transaction->invoice)) {
                    //echo '<pre>'; print_r($order_info); die();
                    $actualCommission = withoutRounding($totalcommission,2);
                    $digitzsPaymentId = $payment->data->id;
                    $pp_response['order_status'] = 'P';
                    $_user_id=$order_info['user_id'];
                    $isCustomerId = checkCustomerExist($_user_id);
                    $customer_id = '';
                    if($isCustomerId == 'true' ){
                        $customer_id = db_get_field('SELECT customer_id FROM ?:card_details where user_id = ?i', $_user_id);
                    }else{
                        $merchantId = Registry::get('addons.digitzs_connect.admin_merchant_id');
                        $customer_data = getCustomerDetails($order_info,$_user_id,$merchantId);
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
                        $_data['user_id'] = $_user_id;
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
                    if($order_info['payment_info']['card_info_save'] == 'true'){
                        $iscardTokenExist = checkCardTokenExist($_user_id);
                        // echo '<pre>'; print_r($iscardTokenExist); die();
                        $card_number = '';
                        if(!empty($order_info['payment_info']['card_token'])){
                            $card_number = db_get_field('SELECT card_number FROM ?:card_details where card_token = ?i', $payment_data['payment_info']['card_token']);
                        }elseif(!empty($order_info['payment_info']['card_number'])){
                            $card_number=$order_info['payment_info']['card_number'];
                            if($order_info['payment_info']['card_numbers']!='') {
                                $card_number = strpos($order_info['payment_info']['card_number'],"X") >= 0 ? $order_info['payment_info']['card_numbers']:$order_info['payment_info']['card_number'];
                            }
                        }
                        $shippingAddress = array(
                            "line1" => $order_info['b_address'],
                            "line2" => $order_info['b_address_2'],
                            "city" => $order_info['b_city'],
                            "state" => $order_info['b_state'],
                            "zip" => $order_info['b_zipcode'],
                            "country" => $order_info['b_country']
                        );

                        $_user_data = array('card_holder_name'=>$order_info['payment_info']['cardholder_name'],
                        'card_type' => $order_info['payment_info']['card_type'],
                        'card_number' => $card_number,
                        'valid_thru' => $order_info['payment_info']['expiry_month'].$order_info['payment_info']['expiry_year'],
                        'name' => $order_info['b_firstname'].' '.$order_info['b_lastname']
                        );
                        $card_token = getCardToken($customer_id, $shippingAddress,$_user_data,$token);
                        if($iscardTokenExist == 'empty' ){
                            $cardData = array(
                                'card_token' => $card_token->data->id,
                                // 'card_number' => $card_number,
                                'isDefault' => '1'
                            );
                            db_query("UPDATE ?:card_details SET ?u WHERE user_id = ?i", $cardData, $order_info['user_id']);
                        }
                        else {
                            $isCardExist = checkCardExist($_user_id,$card_number);
                            //echo '<pre>'; print_r($isCardExist); die();
                            if($isCardExist == 'false'){
                                $cardData = array(
                                    'user_id' => $order_info['user_id'],
                                    'card_token' => $card_token->data->id,
                                    // 'card_number' => $card_number,
                                    'customer_id' => $customer_id,
                                );
                                db_query("INSERT INTO ?:card_details ?e ", $cardData);           
                            }
                        }
                    }
                    
                    $digitzsPaymentIds = array(
                        'digitzs_payment_id' => $digitzsPaymentId
                    );       
                    db_query("UPDATE ?:orders SET ?u WHERE order_id = ?i", $digitzsPaymentIds, $order_info['order_id']);
                    //$_data = array('commission_amount'=>$order_data['data']['attributes']['split']['amount']/100,'marketplace_profit'=>$order_data['data']['attributes']['split']['amount']/100);  
                    $_data = array('commission_amount'=>$actualCommission,'marketplace_profit'=>$actualCommission);                     
                    db_query("UPDATE ?:vendor_payouts SET ?u WHERE order_id = ?i", $_data,$payment_data['order_id'] + $ccount);
                    foreach ($order_info['product_groups'] as $key => $value) { 
                        foreach($value['products'] as $key => $provalue){
                            $is_ovverride = checkOverride($provalue['product_id']);
                            $subtotal = $provalue['price']*$provalue['amount'];
                            $payment_data1=array('company_id'=>$current_id,'order_id'=>$order_info['order_id'],'total'=>$subtotal,'totalwithshipping' => $order_info['total']);
                            $commissionInfo = getCommissionData($payment_data1,$provalue['product_id'],$countryFee,$is_ovverride);
                            //echo '<pre>'; print_r($commissionInfo); die();
                            $product_id = $provalue['product_id'];
                            $data = array(
                                'wesave_commission' => $commissionInfo['wesave_commission'] ,
                                'wesave_commission_amt' => $commissionInfo['wesave_commission_amt'],
                                'transaction_fee' => $commissionInfo['transaction_fee'],
                                'vendor_profit' => $commissionInfo['vendor_profit']
                            );
                            db_query('UPDATE ?:order_details SET ?u WHERE product_id = ?i AND order_id = ?s', $data, $product_id,$payment_data['order_id'] + $ccount);
                        }
                    }
                    $ccount++;
                }
            }
        } catch (Exception $e) {
            fn_set_notification('E', __('error'), $e->getMessage());
            $pp_response['reason_text'] = $e->getMessage();
            $pp_response['order_status'] = 'F';
        }
    } else {
        $pp_response['order_status'] = 'F';
    }

}

function generateToken($apiKey) {
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
    return callDigitzApi($tokenApi,$post,$header);
}

function getCommissionData($order_info, $product_id, $countryFee,$is_ovverride) {
    $order_id=$order_info['order_id'];
    $subtotal = $order_info['total'];
    $total = $order_info['totalwithshipping'];
    $vendor_commission = calculateCommision1($order_info['company_id'],$is_ovverride, $product_id,$subtotal);
    //echo '<pre>'; print_r($vendor_commission); die();
    $price = ($subtotal * $vendor_commission['commission'])/100;
    $price += $vendor_commission['fixed_commission'];
    $profit = $subtotal-$price;
    $commissionData = array(
        'wesave_commission' => $vendor_commission['commission'] ,
        'wesave_commission_amt' => $vendor_commission['fixed_commission'],
        'transaction_fee' => strval(withoutRounding($price,2)),
        'vendor_profit' => strval(withoutRounding($profit,2))
    );
    return $commissionData;
}

function getPaymentDetails($order_info) { 
    $merchant_id = Registry::get('addons.digitzs_connect.admin_merchant_id');
    $order_id=$order_info['order_id'];
    $payment_info=$order_info['payment_info'];
    $subtotal = $order_info['total'];
    $total = $order_info['totalwithshipping'];
    $card_number=$order_info['payment_info']['card_number'];
    if($order_info['payment_info']['card_numbers']!='') {
        $card_number = strpos($order_info['payment_info']['card_number'],"X") >= 0 ? $order_info['payment_info']['card_numbers']:$order_info['payment_info']['card_number'];
    }
    $paymentData = array("data" => array(
      "type" => "payments",
      "attributes" => array(
          "paymentType" => "cardSplit",
          "merchantId" => $order_info['vendor_merchant_id'],
          "card" => array(
              "holder" => $payment_info['cardholder_name'],
              "number" => $card_number,
              "expiry" => $payment_info['expiry_month'].$payment_info['expiry_year'],
              "code" => $payment_info['cvv2']
          ),
          "split"=>array(
              "merchantId" =>$merchant_id,
              "amount" => strval(round($subtotal*100))
          ),
           "transaction" => array(
              "amount" => strval(round($total*100)),
              "currency" =>$order_info['currency'],
              "invoice" => $order_info['invoice_id']
          )
        )
      )
    );
    return $paymentData;
}

function calculateCommision($company_id, $override,$product_id,$subtotal){
    $commission = '';
    if($override == 'yes'){
        $commission = db_get_row(
        'SELECT product_commission as commission, product_commission_fee as fixed_commission' 
        . ' FROM ?:products'
        . ' WHERE product_id = ?i', $product_id
        . ' WHERE company_id = ?i', $company_id
        );
    }else{
        $commission = db_get_row(
        'SELECT commission as commission, fixed_commission as fixed_commission'
        . ' FROM ?:vendor_plans AS p'
        . ' INNER JOIN ?:companies AS c ON c.plan_id = p.plan_id'
        . ' WHERE company_id = ?i', $company_id
        );
    }
    $price = ($subtotal * $commission['commission'])/100;
    $price += $commission['fixed_commission'];
    return $price;
}
function calculateCommision1($company_id, $override,$product_id,$subtotal){
    $commission = '';
    if($override == 'yes'){
        $commission = db_get_row(
        'SELECT product_commission as commission, product_commission_fee as fixed_commission' 
        . ' FROM ?:products'
        . ' WHERE product_id = ?i', $product_id
        . ' WHERE company_id = ?i', $company_id
        );
    }else{
        $commission = db_get_row(
        'SELECT commission as commission, fixed_commission as fixed_commission'
        . ' FROM ?:vendor_plans AS p'
        . ' INNER JOIN ?:companies AS c ON c.plan_id = p.plan_id'
        . ' WHERE company_id = ?i', $company_id
        );
    }
    return $commission;
    // $price = ($subtotal * $commission['commission'])/100;
    // $price += $commission['fixed_commission'];
    // return $price;
}
function calculateSplit($total,$countryFee){
    $commissionfee = '';
    if($countryFee == "US"){
        $commissionfee = (2.9/100)*$total;
    }
    else{
        $commissionfee = (3.9/100)*$total;
    }
    $cardfee = $commissionfee + 0.30;
    return $cardfee;
}

function createSplitPayment($post,$token) {
    $api = 'payments';
    $post = json_encode($post);
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    $header=array(
      "Authorization:Bearer ".$token,
      "x-api-key:".$apiKey,
      "Content-Type:application/json"
    );
    return callDigitzApi($api,$post,$header);
}

function callDigitzApi($api,$post,$header) {
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

/****  Product Split Code Starts  ***/
function checkOverride($product_id){
    $isOverride = db_get_field('SELECT override_commission'
    . ' FROM ?:products'
    . ' WHERE product_id = ?i', $product_id
    );
    return $isOverride;
}

function checkCustomerExist($user_id){
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

function checkCardTokenExist($user_id){
    $isExist = db_get_field('SELECT ifnull(nullif(card_token,""),"empty") '
    . ' FROM ?:card_details'
    . ' WHERE user_id = ?i', $user_id
    );
    if($isExist == 'empty'){ 
        $isExist = 'empty';
    }else{
        $isExist = 'notEmpty';
    }
    return $isExist;
}

function checkCardExist($user_id,$card_number){
    $isCardExist =  db_get_field('SELECT card_number FROM ?:card_details WHERE user_id = ?i AND card_number = ?i',$user_id, $card_number);
    // $isCardExist =  db_get_field('SELECT card_number'
    // . ' FROM ?:card_details'
    // . ' WHERE user_id = ?i ', $user_id
    // . ' WHERE card_number = ?i ', $card_number
    // );
    //echo '<pre>'; print_r($isCardExist); die();
    if($isCardExist == $card_number){ 
        $isExist = 'true';
    }else{
        $isExist = 'false';
    }
    //echo '<pre>'; print_r($isExist); die();
    return $isExist;
}

function getCustomerDetails($customer_details,$user_id, $merchantId) {
    extract($customer_details);
    $name =  $customer_details['firstname'].' '.$customer_details['lastname'];
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

function createCustomers($post,$token) {
    $api = 'customers';
    $post = json_encode($post);
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    $header=array(
      "Authorization:Bearer ".$token,
      "x-api-key:".$apiKey,
      "Content-Type:application/json"
    );
    return callDigitzApi($api,$post,$header);
}

function getCardToken($customer_id, $shippingAddress,$_user_data,$token)
{
    $api = 'tokens';
    $data = array("data" => array(
        "type" => "tokens",
        "attributes" => array(
            "tokenType" => "card",
            "customerId" => $customer_id,
            "label" => $_user_data['card_type'],
            "protected" => true,
            "card" => array(
                "type" => $_user_data['card_type'],
                "holder" => $_user_data['card_holder_name'],
                "number" => $_user_data['card_number'],
                "expiry" => $_user_data['valid_thru'],
                "protected" => true
            ),
            "billingAddress"=>array(
                "line1" => $shippingAddress['line1'],
                "line2" => $shippingAddress['line2'] == '' ? $shippingAddress['line1']: $shippingAddress['line2'],
                "city" => $shippingAddress['city'],
                "state" => $shippingAddress['state'],
                "zip" => $shippingAddress['zip'],
                "country" => $shippingAddress['country'] == 'US' ? 'USA': 'CAN'
            )
          )
        )
      );
    $post = json_encode($data);
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    $header=array(
      "Authorization:Bearer ".$token,
      "x-api-key:".$apiKey,
      "Content-Type:application/json"
    );
    return callDigitzApi($api,$post,$header);
}

function withoutRounding($number, $total_decimals) {
    $number = (string)$number;
    if($number === '') {
        $number = '0';
    }
    if(strpos($number, '.') === false) {
        $number .= '.';
    }
    $number_arr = explode('.', $number);

    $decimals = substr($number_arr[1], 0, $total_decimals);
    if($decimals === false) {
        $decimals = '0';
    }

    $return = '';
    if($total_decimals == 0) {
        $return = $number_arr[0];
    } else {
        if(strlen($decimals) < $total_decimals) {
            $decimals = str_pad($decimals, $total_decimals, '0', STR_PAD_RIGHT);
        }
        $return = $number_arr[0] . '.' . $decimals;
    }
    return $return;
}

function getTokenPaymentDetails($card_token,$token){
    $api = 'tokens/'.$card_token;
    $post = json_encode($card_token);
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    $header=array(
      "Authorization:Bearer ".$token,
      "x-api-key:".$apiKey,
      "Content-Type:application/json"
    );
    return callCardTokenApi($api,$header);
}

function callCardTokenApi($api,$header) {
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

function getCardTokenPaymentDetails($cardTokenPayment,$payment_data)
{
    $merchant_id = Registry::get('addons.digitzs_connect.admin_merchant_id');
    $customer_id = $cardTokenPayment->data->attributes->customerId;
    $card_token = $cardTokenPayment->data->id ;
    $card_number =  db_get_field('SELECT card_number FROM ?:card_details where card_token = ?i', $payment_data['payment_info']['card_token']);
    $subtotal = $payment_data['total'];
    $total = $payment_data['totalwithshipping'];   
    // $paymentData = array("data" => array(
    //   "type" => "payments",
    //   "attributes" => array(
    //       "paymentType" => "cardSplit",
    //       "merchantId" => $payment_data['vendor_merchant_id'],
    //       "card" => array(
    //           "holder" => $cardTokenPayment->data->attributes->card->holder,
    //           "number" => $card_number,
    //           "expiry" => $cardTokenPayment->data->attributes->card->expiry,
    //           "code" => $payment_data['payment_info']['cvv2']
    //       ),
    //       "split"=>array(
    //           "merchantId" =>$merchant_id,
    //           "amount" => strval(round($subtotal*100))
    //       ),
    //        "transaction" => array(
    //           "amount" => strval(round($total*100)),
    //           "currency" =>$payment_data['currency'],
    //           "invoice" => $payment_data['invoice_id']
    //       )
    //     )
    //   )
    // );
        $paymentData = array("data" => array(
          "type" => "payments",
          "attributes" => array(
              "paymentType" => "tokenSplit",
              "merchantId" => $payment_data['vendor_merchant_id'],
              "token" => array(
                  "customerId" => $customer_id,
                  "tokenId" => $payment_data['payment_info']['card_token']
                ),
              "split"=>array(
                  "merchantId" =>$merchant_id,
                  "amount" => strval(round($subtotal*100))
              ),
               "transaction" => array(
                  "amount" => strval(round($total*100)),
                  "currency" =>$payment_data['currency'],
                  "invoice" => $payment_data['invoice_id']
              )
            )
          )
        );
    return $paymentData;
}