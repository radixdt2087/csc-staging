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
    $apiKey = Registry::get('addons.digitzs_connect.api_key');
    if (!empty($apiKey)
        && !empty($order_info['payment_info']['card_number'])
        && !empty($order_info['payment_info']['cvv2'])
    ) {
        try {
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
                    $payment_data=array('company_id'=>$current_id,'vendor_merchant_id'=>$vendor_merchant_id,'order_id'=>$order_info['order_id'],'payment_info'=>$order_info['payment_info'],'total'=>$totalcommission,'currency'=>$order_info['secondary_currency'],'invoice_id'=>strval(mt_rand()),'totalwithshipping' => $order_info['total']);
                    $order_data=getPaymentDetails($payment_data);
                    $payment=createSplitPayment($order_data,$token);
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
                if($payment->data->attributes->transaction->message == 'Success') {
                    $actualCommission = withoutRounding($totalcommission,2);
                    $pp_response['order_status'] = 'P';
                    $_user_id=$order_info['user_id'];
                    $card_number=$order_info['payment_info']['card_number'];
                    if($order_info['payment_info']['card_numbers']!='') {
                        $card_number = strpos($order_info['payment_info']['card_number'],"X") >= 0 ? $order_info['payment_info']['card_numbers']:$order_info['payment_info']['card_number'];
                    }
                    $_user_data = array('card_holder_name'=>$order_info['payment_info']['cardholder_name'],
                    'card_number'=>$card_number
                    /*'valid_thru'=>$order_info['payment_info']['expiry_month']."/".$order_info['payment_info']['expiry_year']*/
                    );
                    db_query("UPDATE ?:users SET ?u WHERE user_id = ?i", $_user_data, $_user_id);

                    //$_data = array('commission_amount'=>$order_data['data']['attributes']['split']['amount']/100,'marketplace_profit'=>$order_data['data']['attributes']['split']['amount']/100);  
                    $_data = array('commission_amount'=>$actualCommission,'marketplace_profit'=>$actualCommission); 

                    db_query("UPDATE ?:vendor_payouts SET ?u WHERE order_id = ?i", $_data,$payment_data['order_id'] + $ccount);
                    
                    foreach ($order_info['product_groups'] as $key => $value) { 
                        foreach($value['products'] as $key => $provalue){
                            $is_ovverride = checkOverride($provalue['product_id']);
                            $subtotal = $provalue['price']*$provalue['amount'];
                            $payment_data1=array('company_id'=>$current_id,'order_id'=>order_id,'total'=>$subtotal,'totalwithshipping' => $order_info['total']);
                            $commissionInfo = getCommissionData($payment_data1,$provalue['product_id'],$countryFee,$is_ovverride);
                            $product_id = $provalue['product_id'];
                            $data = array(
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
      "Content-Type: application/json"
    );
    return callDigitzApi($tokenApi,$post,$header);
}

function getCommissionData($order_info, $product_id, $countryFee,$is_ovverride) {
    $order_id=$order_info['order_id'];
    $subtotal = $order_info['total'];
    $total = $order_info['totalwithshipping'];
    $vendor_commission = calculateCommision($order_info['company_id'],$is_ovverride, $product_id,$subtotal);
    $profit = $subtotal-$vendor_commission;
    $commissionData = array(
        'transaction_fee' => strval(round($vendor_commission,2)),
        'vendor_profit' => strval(round($profit,2))
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
