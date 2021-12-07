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
use Tygh\Tygh;
use Tygh\Mailer;
use Tygh\Models\Company;
use Tygh\Models\VendorPlan;

if (!defined('BOOTSTRAP')) { die('Access denied'); }
$auth = Tygh::$app['session']['auth'];
$apiKey = !empty(Registry::get('addons.digitzs_connect.api_key'))? Registry::get('addons.digitzs_connect.api_key'):'lO6grwpF7n5MZt9Qc0U2T6l0Cp9DOERm6XcMDwtY';
if (isset($auth['user_type']) && $auth['user_type'] == 'V') {
    //$suffix = 'manage';
    if ($mode == 'update') {
        $_pid = Tygh::$app['view']->getTemplateVars('company_data')['plan_id'];
        $company_id = Registry::get('runtime.company_id');

        $downgrade_id = db_get_field("SELECT downgrade_plan_id FROM ?:companies WHERE company_id=?i",$company_id);
        if($downgrade_id) {
            $current_plan_data = db_get_row("SELECT plan_date,vp.periodicity FROM ?:plan_payment_details pd INNER JOIN ?:vendor_plans vp on  pd.plan_id=vp.plan_id WHERE pd.plan_id=?i and `type`=?s and pd.`status`=?s and company_id=?i",$_pid,'plan','Success',$company_id);
            $period = '';
            if($current_plan_data) {
                if($current_plan_data['periodicity'] == "month") {
                    $period="+1 month";
                } else if($current_plan_data['periodicity'] == "year") {
                    $period="+1 year";
                }
                if($current_plan_data['plan_date'] && $period != '') {
                $plan_exp_date = strtotime($period, $current_plan_data['plan_date']);
                Tygh::$app['view']->assign(
                                'plan_exp_date',
                                $plan_exp_date
                            );
                }
            } //else {
                //fn_set_notification('E', __(''), 'Current plan payment details not found. Please make payment');
                // $timestamp = db_get_field("SELECT `timestamp` FROM ?:users WHERE `user_id`=?i",$auth['user_id']);
                // $periodicity = db_get_field("SELECT `periodicity` FROM ?:vendor_plans WHERE `plan_id`=?i",$_pid);
                // if($periodicity == "month") {
                //     $period="+1 month";
                // } else if($periodicity == "year") {
                //     $period="+1 year";
                // }
                // if($period != '') {
                //     $plan_exp_date = strtotime($period, $timestamp);
                //     Tygh::$app['view']->assign(
                //                     'plan_exp_date',
                //                     $plan_exp_date
                //                 );
                // }
           // }
        }
        
        // $payment_details = db_query("SELECT * FROM ?:plan_payment_details WHERE company_id = ?i and plan_id=?i and `status`='Success' order by id desc limit 0,1", $company_id,$_pid);
        //     if($row = mysqli_fetch_assoc($payment_details)) {
        //         Tygh::$app['view']->assign(
        //             'payment_details',
        //             $row
        //         );
        //     }
        $addon_data = db_get_array("SELECT `id`, `name`, `short_desc`, `long_desc`, `price`, `product_img`, `product_video`, `status`, `payment_frequency`, `allow_package`, `prorate_charge` FROM ?:plan_addons_details where `status`='A'");
        Tygh::$app['view']->assign('addon_data',$addon_data);
        $ids = db_get_array("SELECT id FROM ?:plan_payment_details WHERE company_id = ?i and `type`='addons' and `status`='Success'", $company_id);
        $addon_details=array();
        foreach($ids as $res) {
            $addons_resultset = db_query("SELECT `id`, `company_id`, `amount`, `status`, `plan_date`, `plan_id`, `addon_id`, `pid` FROM ?:addons_payment_details WHERE company_id = ?i and pid=?i", $company_id,$res['id']);
            while($arow = mysqli_fetch_assoc($addons_resultset)) {
                $addon_details[] = $arow;
            }
        }
        Tygh::$app['view']->assign(
            'addon_details',
            $addon_details
        );
        $current_plan_addons = array();
        foreach ($addon_data as $dkey => $adata) {
            foreach ($addon_details as $akey => $adetails) {
                if($adetails['addon_id'] == $adata['id']) {
                    $current_plan_addons[]=array('id'=>$adata['id'],'name'=>$adata['name'],'price'=>$adata['price'],'payment_frequency'=>$adata['payment_frequency']);
                    break;
                }
            }
        }
        Tygh::$app['view']->assign('current_plan_addons',$current_plan_addons);
        if($_pid) {
            $resultset = db_get_row("SELECT ppd.plan_date,periodicity FROM ?:plan_payment_details ppd inner join ?:vendor_plans vp on vp.plan_id=ppd.plan_id WHERE company_id = ?i and ppd.plan_id=?i and ppd.`type`='plan' and ppd.`status`='Success'", $company_id,$_pid);
            if($resultset) {
                $month=date("m",$resultset['plan_date']);
                $year=date("Y",$resultset['plan_date']);
                $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
                $resultset['days'] = $days;
                Tygh::$app['view']->assign(
                    'plan_details',
                    $resultset
                );
            } else {
                $curr_time = time();
                $month=date("m",$curr_time);
                $year=date("Y",$curr_time);
                $days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
                $periodicity = db_get_field("SELECT periodicity FROM ?:vendor_plans WHERE plan_id=?i ", $_pid);
                $resultset['plan_date'] = $curr_time;
                $resultset['periodicity'] = $periodicity;
                $resultset['days'] = $days;
                Tygh::$app['view']->assign(
                    'plan_details',
                    $resultset
                );
                //fn_set_notification('E', __(''), 'Current plan payment details not found. Please make payment');
            }
        }
        $card_details = db_get_array('SELECT id,`user_id`,card_token,customer_id,isDefault FROM ?:card_details  where `user_id` = ?i order by isDefault desc',$auth['user_id']);
        $token = generateEnrollmentToken();
        $token = $token->data->attributes->appToken;
        $header = array(
            "Authorization:Bearer ".$token,
            "x-api-key:".$apiKey,
            "Content-Type:application/json"
        );
        $_card_details = array();
        foreach($card_details as $card) {
            $token_id = $card['card_token'];
            $token_res = getCardDetails($header,$token_id);
            $address = $token_res->data->attributes->billingAddress->line1;
            $city = $token_res->data->attributes->billingAddress->city;
            $country = $token_res->data->attributes->billingAddress->country;
            $zip = $token_res->data->attributes->billingAddress->zip;
            $state = $token_res->data->attributes->billingAddress->state;
            $card_type = $token_res->data->attributes->card->type;
            if($card_type == "masterCard") {
                $card_type = "mastercard";
            } else if($card_type == "americanExpress") {
                $card_type = "amex";
            } else if($card_type == "dinersClub") {
                $card_type = "diners_club_international";
            }
            $_card_details[] = array('id'=>$card['id'],'card_number'=>$token_res->data->attributes->card->number,'holder_name'=>$token_res->data->attributes->card->holder,'customer_id'=>$card['customer_id'],'card_token'=>$card['card_token'],'isDefault'=>$card['isDefault'],'expiry'=>$token_res->data->attributes->card->expiry,'type'=>$card_type,'address'=>$address,'city'=>$city,'country'=>$country,'state'=>$state,'zip'=>$zip);
        }
        Tygh::$app['view']->assign('card_details', $_card_details);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($mode == 'update_status') {
            $_REQUEST['notify_user'] = false;
        }
        $company_id = $_REQUEST['company_id'];
        $action = $_POST['card_action'];
        if (isset($auth['user_type']) && $auth['user_type'] == 'V' && !empty($_REQUEST['purchase_plan']) && $_REQUEST['purchase_plan'] > 0) {
            $token_obj = generateEnrollmentToken();
            $token_enroll = $token_obj->data->attributes->appToken;
            $header = array(
                "Authorization:Bearer ".$token_enroll,
                "x-api-key:".$apiKey,
                "Content-Type:application/json"
            );
            if($_POST['card_pay'] == 0) {
                $customer_res = createCustomerId($header,$company_id);
                // if($customer_res == false) {
                //     if($_POST['ptype'] == "plan" && !empty($_POST['curr_plan_id'])) {
                //         $curr_plan_id = $_POST['curr_plan_id'];
                //         db_query('UPDATE ?:companies SET plan_id=?i WHERE `company_id` = ?i',$curr_plan_id,$company_id);
                //         unset($_REQUEST['company_data']['company']);
                //     }
                //     fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan&pay=1');
                //     exit;
                // }
                $customerId = $customer_res->data->id;
                $_card_number = str_replace(" ","",$_POST['add_payment_data']['card_number']);
                $add_payment_data = $_POST['add_payment_data'];
                $add_billing_data  = $_POST['add_billing_data'];
                $cc_res = createTokenForCC($header,$customerId,$_card_number,$add_payment_data,$add_billing_data);
                $card_token = $cc_res->data->id;
                $error = paymentValidate($cc_res);
                if($error) {
                   fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan&pay=1');
                   exit;
                }
                if(!empty($card_token)) {
                    $_POST['selcard'] = $customerId."|".$card_token;
                    if($_POST['add_payment_data']['save_card'] == 'Y') {
                        addCardDetails('Add',$header,$card_token,$customerId,$auth['user_id'],$_card_number);
                    }
                }
            }
            $api = 'payments';
            $_amount=0;
            if($_POST['ptype'] == "plan") {
                $_amount=$_POST['amount'];
            } else if($_POST['ptype'] == "addons") {
                $_amount=$_POST['total'];
            }
            $payment_data = getEnrollmentPaymentDetails($_amount);
            $ccpayment = callVendorEnrollmentDigitzApi($api,$payment_data,$header);
            $error = paymentValidate($ccpayment);
            if($error) {
                if($_POST['ptype']=="plan" && !empty($_POST['curr_plan_id'])) {
                    $curr_plan_id = $_POST['curr_plan_id'];
                    db_query('UPDATE ?:companies SET plan_id=?i WHERE `company_id` = ?i',$curr_plan_id,$company_id);
                }
                fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan&pay=1');
            }
            if($ccpayment->data->attributes->transaction->message == 'Success') {
                addPaymentData($company_id);
                $_payment_data = array();
                $_payment_data['status'] = 'Success';
                $_payment_data['invoice'] = $ccpayment->data->attributes->transaction->invoice;
                $pid = $_POST['company_data']['plan_id'];
                db_query("UPDATE ?:plan_payment_details SET ?u WHERE company_id = ?i and plan_id=?i and `type`=?s", $_payment_data, $company_id,$pid,$_POST['ptype']);

                $user_id = db_get_field("SELECT `user_id` FROM ?:users WHERE company_id = ?i AND user_type = 'V'", $company_id);
                if(!empty($user_id)) {
                    $id = db_get_field("SELECT id FROM ?:plan_payment_details WHERE company_id = ?i", $company_id);
                    if(empty($id)) {
                        db_query('UPDATE ?:users SET password_change_timestamp=1 WHERE `user_id` = ?i',$user_id);
                    }
                }
                $company = Company::model()->find($company_id);
                $addon_names = array();
                if($_POST['addon_id'] && $_POST['ptype'] == 'addons') {
                    $addon_id = $_POST['addon_id'];
                    $addon_names = db_get_array("SELECT id,`name`,price,payment_frequency FROM ?:plan_addons_details where id in ($addon_id) and `status`='A'");
                }
                /** @var \Tygh\Mailer\Mailer $mailer */
                $mailer = Tygh::$app['mailer'];
                $plan = '';
                if($_POST['ptype'] == 'plan') {
                    $plan = VendorPlan::model()->find($company->plan_id);
                }
                $user_email = fn_get_user_email($auth['user_id']);
                $mailer->send(array(
                    'to' => $user_email.','.$company->getEmail('company_support_department') ?: 'company_support_department',
                    'from' => 'default_company_support_department',
                    'data' => array(
                        'plan' => $plan!='' ? $plan->plan : '',
                        'addon_names' => $addon_names,
                    ),
                    'tpl' => 'addons/vendor_enrollment/addons_mail.tpl',
                ), 'A');
                fn_set_notification('N', __(''), 'Payment Completed Successfully','K');
                fn_redirect('vendor_enrollment.manage&company_id='.$company_id);
                exit;
                //fn_redirect('companies.update&m=1&company_id='.$company_id);
            } else {
                $_payment_data['status'] = 'Failed';
                db_query("UPDATE ?:plan_payment_details SET ?u WHERE company_id = ?i and plan_id=?i and `type`=?s", $_payment_data, $company_id,$pid,$_POST['ptype']);
            }
    } else if(!empty($action) && $action == "Add") {
        $token = generateEnrollmentToken();
        $token = $token->data->attributes->appToken;
        $header = array(
            "Authorization:Bearer ".$token,
            "x-api-key:".$apiKey,
            "Content-Type:application/json"
        );
        $customer_res = createCustomerId($header,$company_id);
        // if($customer_res == false) {
        //     fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan&pay=1');
        //     exit;
        // }
        $customerId = $customer_res->data->id;
        $_card_number = str_replace(" ","",$_POST['add_payment_data']['card_number']);
        $add_payment_data = $_POST['add_payment_data'];
        $add_billing_data  = $_POST['add_billing_data'];
        $cc_res = createTokenForCC($header,$customerId,$_card_number,$add_payment_data,$add_billing_data);
        $card_token = $cc_res->data->id;
        $error = paymentValidate($cc_res);
        if($error) {
            fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan&pay=1');
            exit;
        }
        if(!empty($card_token)) {
            addCardDetails($action,$header,$card_token,$customerId,$auth['user_id'],$_card_number);
            fn_set_notification('N', __('notice'), 'Your Card is added successfully');
            fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan&pay=1');
            exit;
        }
    } else if(!empty($action) && $action == "Edit") {
        $token = generateEnrollmentToken();
        $token = $token->data->attributes->appToken;
        $header = array(
            "Authorization:Bearer ".$token,
            "x-api-key:".$apiKey,
            "Content-Type:application/json"
        );
        $customerId = $_POST['cust_id'];
        $edit_payment_data = $_POST['edit_payment_data'];
        $_card_number = str_replace(" ","",$edit_payment_data['card_number']);
        $edit_billing_data  = $_POST['edit_billing_data'];
        $cc_res = createTokenForCC($header,$customerId,$_card_number,$edit_payment_data,$edit_billing_data);
        $card_token = $cc_res->data->id;
        $error = paymentValidate($cc_res);
        if($error) {
            fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan&pay=1');
            exit;
        }
        if(!empty($card_token)) {
            addCardDetails($action,$header,$card_token,$customerId,$auth['user_id'],$_card_number);
            fn_set_notification('N', __('notice'), 'Your Card is updated successfully');
            fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan&pay=1');
            exit;
        }
    } else if(!empty($action) && $action == "Remove") {
        $card_id = $_POST['card_id'];
        $c_token = db_get_field('SELECT card_token FROM ?:card_details WHERE id=?i',$card_id);
        db_query('DELETE FROM ?:card_details WHERE `id` = ?i',$card_id);
        db_query('UPDATE ?:companies SET payment_token=?s WHERE company_id = ?i and payment_token=?s','',$company_id,$c_token);
        fn_set_notification('N', __('notice'), 'Your Card id deleted');
        fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan&pay=1');

    } else if(!empty($action) && $action == "Default") {
        $card_id = $_POST['card_id'];
        $c_details = array('isDefault'=>0);
        db_query('UPDATE ?:card_details SET ?u WHERE `user_id` = ?i',$c_details,$auth['user_id']);
        $c_details = array('isDefault'=>1);
        db_query('UPDATE ?:card_details SET ?u WHERE `id` = ?i',$c_details,$card_id);
        $card_token = db_get_field("SELECT `card_token` FROM ?:card_details WHERE id = ?i", $card_id); 
        db_query('UPDATE ?:companies SET payment_token=?s WHERE `company_id` = ?i',$card_token,$company_id);
        fn_set_notification('N', __('notice'), 'Your Card is set as default');
        fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan&pay=1');
        exit;

    } else if(!empty($_POST['plan_downgrade']) && $_POST['plan_downgrade'] > 0) {
        $downgrade_details = array('downgrade_plan_id'=>$_POST['plan_downgrade']);
        $plan_id = $_POST['company_data']['plan_id'];
        //$pid = db_get_field("SELECT id FROM ?:plan_payment_details WHERE company_id = ?i and plan_id=?i and `type`='plan'", $company_id,$plan_id);
        $id = db_get_field("SELECT company_id FROM ?:companies WHERE company_id = ?i", $company_id);
        //!empty($pid) &&
        if(!empty($id)) {
            db_query('UPDATE ?:companies SET ?u WHERE company_id = ?i',$downgrade_details,$company_id);
            fn_set_notification('N', __(''), 'You have successfully downgrade plan and that take effect on next billing cycle');
            fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan');
        }
        // else {
        //     fn_set_notification('E', __(''), "Please first make payment of current plan by clicking on current plan then you will downgrade any plan");
        //     fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan');
        // }
    }
}
function createCustomerId($header,$company_id) {
    $customer_api = 'customers';
    $company_data = fn_get_company_data($company_id);
    $company_name = $company_data['company'];
    // $merchant_id = $company_data['digitzs_connect_account_id'];
    // if(empty($company_data['digitzs_connect_account_id'])) { 
    //     $msg = '<p><b style="color:#258D78"></b> <span style="color:#000;">To create customer id for credit card payment, you must first enable your merchant processing account by applying with the Digitzs Payment Gateway. Go to Vendor -> <a href="/vendor.php?dispatch=companies.update&m=1&company_id='.$company_id.'"> <b>Digitzs Connect</b></a> to be approved with your Digitzs Account.</span></p>';
    //     fn_set_notification('', __(''), $msg,'K');
    //     //fn_set_notification('E', __('error'), 'Digitzs vendor merchant id not created'); 
    //     return false;
    // }
    $merchant_id = !empty(Registry::get('addons.digitzs_connect.admin_merchant_id'))? Registry::get('addons.digitzs_connect.admin_merchant_id'):'wesave-legalnamel-718156330-2651564-1616505426';
    $post = array(
        'data' => array('type' =>'customers','attributes' => array('merchantId'=>strval($merchant_id),'name'=>$company_name,'externalId'=>$company_id))
      );
    $post = json_encode($post);
    return callVendorEnrollmentDigitzApi($customer_api,$post,$header);
}
function createTokenForCC($header,$customerId,$card_number,$payment_data,$billing_data) {
    $customer_api = 'tokens';
    $post = array(
        'data' => array('type' =>$customer_api,'attributes' => array('tokenType'=>'card','customerId'=>strval($customerId),'label'=>$payment_data['card_type'],'protected'=>true,'card'=>array('type'=>$payment_data['card_type'],'holder'=>$payment_data['cardholder_name'],'number'=>$card_number,'expiry'=>$payment_data['expiry_month'].$payment_data['expiry_year'],'protected'=>true),'billingAddress'=>array('line1'=>$billing_data['billing_address'],'city'=>$billing_data['billing_city'],'state'=>$billing_data['billing_state'],'zip'=>$billing_data['billing_zipcode'],'country'=>$billing_data['billing_country'])))
      );
    $post = json_encode($post);
    return callVendorEnrollmentDigitzApi($customer_api,$post,$header);
}
function getCardDetails($header,$token_id) {
    $post = array();
    $post = json_encode($post);
    return callVendorEnrollmentDigitzApi('tokens/'.$token_id,$post,$header);
}
function addCardDetails($action,$header,$card_token,$customerId,$userId,$card_number) {
    $c_details = array('user_id'=>$userId,'card_token'=>$card_token,'customer_id'=>strval($customerId),'isDefault'=>'0');
    $id = db_get_field("SELECT id FROM ?:card_details WHERE `card_token` = ?i and `user_id` = ?i", $card_token,$userId);
    if($action == "Add") { // && $action == "Add"  if($action == "Edit"
         db_query("INSERT INTO ?:card_details ?e", $c_details);
    } else if($action== "Edit") {
        $card_id = $_POST['card_id'];
        unset($c_details['isDefault']);
        db_query('UPDATE ?:card_details SET ?u WHERE `id` = ?i',$c_details,$card_id);
    }
}
function paymentValidate($ccpayment) {
    $error=false;
    if(isset($ccpayment->errors)) {
        foreach($ccpayment->errors as $pay) {
            $valid=validateError($pay);
            fn_set_notification('E', __('error'), $valid);
            $error=true;
        }
    }
    return $error;
}
function getEnrollmentPaymentDetails($amount) {
    $merchant_id = !empty(Registry::get('addons.digitzs_connect.admin_merchant_id'))? Registry::get('addons.digitzs_connect.admin_merchant_id'):'wesave-legalnamel-718156330-2651564-1616505426';
    $total = 0;
    if($amount!=0) {
        $total=$amount;
    }
    $currency = $_POST['currency_code'];
    $selcard = explode("|",$_POST['selcard']);
    $paymentData = array("data" => array(
    "type" => "payments",
    "attributes" => array(
        "paymentType" => "token",
        "merchantId" => $merchant_id,
        "token" => array(
            "customerId" => $selcard[0],
            "tokenId" => $selcard[1],
        ),
        "transaction" => array(
            "amount" => strval(round($total*100)),
            "currency" => $currency,
            "invoice" => strval(mt_rand())
        )
    )
    )
    );
    return json_encode($paymentData);
}
function generateEnrollmentToken() {
    $appKey = 'V5Y10mcDeotpPUJ5xVd4L4Isi8ra0xfeLo3Z0uqaZREHYUIatuinXaJ4Q5Bngh6g';
    $apiKey = !empty(Registry::get('addons.digitzs_connect.api_key')) ? Registry::get('addons.digitzs_connect.api_key') : 'lO6grwpF7n5MZt9Qc0U2T6l0Cp9DOERm6XcMDwtY';
    $tokenApi = 'auth/token';
    $post = array(
      'data' => array('type' =>'auth','attributes' => array('appKey'=>$appKey))
    );
    $post = json_encode($post);
    $header = array(
      "x-api-key:".$apiKey,
      "Content-Type: application/json"
    );
    return callVendorEnrollmentDigitzApi($tokenApi,$post,$header);
}
function callVendorEnrollmentDigitzApi($api,$post,$header) {
    $curl = curl_init();
    $modes = Registry::get('addons.digitzs_connect.modes');
    $endPoint = '';
    if($modes =="Test") {
      $endPoint = "https://test.digitzsapi.com/test/";
    } else {
      $endPoint = "https://test.digitzsapi.com/test/";
    }
    $carray =  array();
    if(@count(json_decode($post)) > 0) {
        $carray =  array(
            CURLOPT_URL => $endPoint.$api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => $header,
        );
    }
    else {
        $carray =  array(
            CURLOPT_URL => $endPoint.$api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $header,
        );
    }
    curl_setopt_array($curl,$carray);
    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response);
  }
function addPaymentData($company_id) {
    $apiKey = !empty(Registry::get('addons.digitzs_connect.api_key')) ? Registry::get('addons.digitzs_connect.api_key') : 'lO6grwpF7n5MZt9Qc0U2T6l0Cp9DOERm6XcMDwtY';
    $_plan_id=$_POST['company_data']['plan_id'];
    $_payment_data = array();
    $token = generateEnrollmentToken();
    $token = $token->data->attributes->appToken;
    $header = array(
        "Authorization:Bearer ".$token,
        "x-api-key:".$apiKey,
        "Content-Type:application/json"
    );
    if(!empty($_POST['selcard'])) {
        $selcard = explode("|",$_POST['selcard']);
        $token_res = getCardDetails($header,$selcard[1]);
        $_payment_data['billing_address'] = $token_res->data->attributes->billingAddress->line1;
        $_payment_data['billing_city'] = $token_res->data->attributes->billingAddress->city;
        $_payment_data['country'] = $token_res->data->attributes->billingAddress->country;
        $_payment_data['state'] = $token_res->data->attributes->billingAddress->state;
        $_payment_data['zipcode'] = $token_res->data->attributes->billingAddress->zip;
        $_payment_data['card_holder_name'] = $token_res->data->attributes->card->holder;
        $_payment_data['card_token'] = $selcard[1];
    }
    $_payment_data['company_id'] = $company_id;
    if(!empty($_POST['plan_days'])) {
        $_payment_data['plan_days'] = $_POST['plan_days'];
    }
    $_payment_data['plan_date'] = time();
    $_payment_data['plan_id'] = $_plan_id;
    $_payment_data['type'] = $_POST['ptype'];

    if($_POST['ptype'] == "plan") {
        $payid = db_get_field("SELECT id FROM ?:plan_payment_details WHERE company_id = ?i and plan_id=?i and `type`='plan'", $company_id,$_plan_id);  
        $_payment_data['amount'] = trim($_POST['amount']);
        if(empty($payid)) {
            db_query("INSERT INTO ?:plan_payment_details ?e", $_payment_data);
        } else {
            db_query("UPDATE ?:plan_payment_details SET ?u WHERE company_id = ?i and plan_id=?i and `type`='plan'", $_payment_data, $company_id,$_plan_id);
        }
        db_query('UPDATE ?:companies SET plan_id=?i WHERE `company_id` = ?i',$_plan_id,$company_id);
    } else if($_POST['ptype'] == "addons") {
        $_addon_id=explode(",",$_POST['addon_id']); //$_POST['company_data']['addon_id']
        $_amount = explode(",",trim($_POST['aamount']));
        $_payment_data['amount'] = trim($_POST['total']);
        // $_payment_data['aamount'] = trim($_POST['aamount']);
        // $_payment_data['addon_id'] = trim($_POST['addon_id']);
        db_query("INSERT INTO ?:plan_payment_details ?e", $_payment_data);
        $presult = db_query('select id from ?:plan_payment_details order by id desc limit 0,1');
        if($row = mysqli_fetch_assoc($presult)) {
            $last_id=$row['id'];
            $_addon_data=array();
            $_addon_data['company_id'] = $_payment_data['company_id'];
            $_addon_data['plan_date'] = $_payment_data['plan_date'];
            $_addon_data['plan_id'] = $_payment_data['plan_id'];
            foreach($_addon_id as $key=>$aid) {
                $_addon_data['addon_id'] = $_addon_id[$key];
                $_addon_data['amount'] = $_amount[$key];
                $_addon_data['pid'] = $last_id;
                db_query("INSERT INTO ?:addons_payment_details ?e", $_addon_data);
            }
        }
    }
}
function validateError($err) {
    $error = $err->detail;
    if(strpos($err->detail,'data.attributes.card.holder') !== false) {
        $error=str_replace('data.attributes.card.holder','Card holder name',$err->detail);
    } else if(strpos($err->detail, 'data.attributes.card.number') !== false) {
        $error=str_replace('data.attributes.card.number','Card number',$err->detail);
    } else if(strpos($err->detail, 'data.attributes.card.code') !== false) {
        $error=str_replace('data.attributes.card.code','Card cvv',$err->detail);
    } else if(strpos($err->detail, 'data.attributes.card.expiry') !== false) {
        $error=str_replace('data.attributes.card.expiry','Card Valid thru',$err->detail); 
    } else if(strpos($err->detail, 'data.attributes.billingAddress.line1') !== false) {
        $error=str_replace('data.attributes.billingAddress.line1','Billing address',$err->detail); 
    } else if(strpos($err->detail, 'data.attributes.billingAddress.city') !== false) {
        $error=str_replace('data.attributes.billingAddress.city','Billing city',$err->detail); 
    } else if(strpos($err->detail, 'data.attributes.billingAddress.country') !== false) {
        $error=str_replace('data.attributes.billingAddress.country','Billing country',$err->detail); 
    } else if(strpos($err->detail, 'data.attributes.billingAddress.state') !== false) {
        $error=str_replace('data.attributes.billingAddress.state','Billing state',$err->detail);
    } else if(strpos($err->detail, 'data.attributes.billingAddress.zip') !== false) {
        $error=str_replace('data.attributes.billingAddress.zip','Billing zipcode',$err->detail); 
    } else if(strpos($err->detail,'data.attributes.card.type')!==false){
        $error=str_replace('data.attributes.card.type','Card type',$err->detail); 
    } else if(strpos($err->detail,'data.attributes.label')!==false){
        $error=str_replace('data.attributes.label','Card number',$err->detail);
    } else if(strpos($err->detail,'data.attributes.transaction.amount')!==false){
        $error=str_replace('data.attributes.transaction.amount','Transaction Amount',$err->detail);
    } else if(strpos($err->detail,'data.attributes.customerId')!==false){
        $error=str_replace('data.attributes.customerId','Customer Id',$err->detail);
    }
    
    return $error;
}
