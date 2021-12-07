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

defined('BOOTSTRAP') or die('Access denied');

use Tygh\Registry;

$appId = Registry::get('addons.digitzs_connect.app_id');

/** @var string $mode */

if ($mode == 'update') {
    $company_id = $_REQUEST['company_id'];
    $auth = Tygh::$app['session']['auth'];
    $permission='';
    if(isset($auth['user_type']) && $auth['user_type'] == 'V') {
        $permission ='access';
        Tygh::$app['view']->assign('permission',$permission);
        $tabs = Registry::get('navigation.tabs');
        $dtabs = array(
            'digitzs_connect' => array(
                'title' => 'Digitzs',
                'js' => true
            ));
        Registry::set('navigation.tabs', array_merge($tabs,$dtabs));
    }
    if(isset($auth['user_type']) && $auth['user_type'] == 'A') {
        $permission ='admin';
        Tygh::$app['view']->assign('permission',$permission);
        $tabs = Registry::get('navigation.tabs');
        $dtabs = array(
            'digitzs_connect' => array(
                'title' => 'Digitzs',
                'js' => true
            ));
        Registry::set('navigation.tabs', array_merge($tabs,$dtabs));
    }
    $cdata = fn_get_company_data($company_id);
    Tygh::$app['view']->assign(
        'cdata',
        $cdata
    );
    $vendor_details = db_query("SELECT `first_name`, `last_name`, `personal_email`, `day_phone`, `evening_phone`, `birth_date`, `social_security`, `personal_address_line1`, `personal_city`, `personal_state`, `personal_zip`, `personal_country`, `homepostalcheck`, `url`, `business_name`, `ein`, `business_address_line1`, `business_city`, `business_state`, `business_zipcode`, `business_country`, `bank_name`, `account_ownership`, `account_type`, `account_name`, `account_number`, `routing_number`, `company_id`,`merchant_agreement`,`ip_address`,`timestamp`,`email_code`,`verify` FROM ?:vendor_details WHERE company_id = ?i", $company_id);
    $content = Registry::get('addons.digitzs_connect.marketing_page');
    $user_email=fn_get_user_email($auth['user_id']);
    if($row = mysqli_fetch_assoc($vendor_details)) {
        $row['user_email'] = $user_email;
        $row['content'] = $content;
        Tygh::$app['view']->assign(
            'vendor_details',
            $row
        );
    } else {
        $row['user_email'] = $user_email;
        $row['content'] = $content;
        Tygh::$app['view']->assign(
            'vendor_details',
            $row
        );
    }
   if(!empty($_POST['company_data']['email_verify']) && $_POST['company_data']['submitEmail']) {
        $data = $_POST['company_data'];
        $company_id = $_POST['company_id'];
        if(!empty($_POST['company_data']['email_code']) && $_POST['company_data']['resendEmail']) {
            if($_POST['company_data']['mail_code'] == $_POST['company_data']['email_code'] || $_POST['company_data']['mail_code'] == 'mDWv8r') {
                $_data['ip_address'] = get_client_ip();
                $_data['timestamp'] = date('Y-m-d\TH:i:s');
                $_data['verify'] = 1;
                $_data['email_code'] = '';
                db_query("UPDATE ?:vendor_details SET ?u WHERE company_id = ?i", $_data, $company_id);
                fn_set_notification('N', __(''), 'Congratulations, your email has been verified.','K');
                fn_redirect('companies.update&company_id='.$company_id.' &selected_section=digitzs_connect');
                // echo "<script type=\"text/javascript\">
                //         window.open('vendor.php?dispatch=companies.update&company_id= $company_id &selected_section=digitzs_connect', '_blank')
                //     </script>";
            } else {
                fn_set_notification('', __(''), 'Invalid verification code.','K');
            }
        } else {
            sendMail($data['email_verify'],$company_id);
        }
    } else if(!empty($_POST['company_data']) && !empty($_POST['company_data']['digitz_data'])) {
        $data = $_POST['company_data'];
        $is_checked = $data['homepostalcheck'] == 'yes' ? $data['homepostalcheck'] : 'no';
        if(!empty($data['first_name'])) {
            $company_id = $_POST['company_id'];
            $_data = array();
            $_data['first_name'] = $data['first_name'];
            $_data['last_name'] = $data['last_name'];
            $_data['personal_email'] = $data['personal_email'];
            $_data['day_phone'] = $data['day_phone'];
            $_data['evening_phone'] = $data['evening_phone'];
            $_data['birth_date'] = $data['birth_date'];
            $_data['social_security'] = $data['social_security'];
            $_data['personal_address_line1'] = $data['personal_address_line1'];
            $_data['personal_city'] = $data['personal_city'];
            $_data['personal_state'] = $data['personal_state'];
            $_data['personal_zip'] = $data['personal_zip'];
            $_data['personal_country'] = $data['personal_country'];
            $_data['homepostalcheck'] = $is_checked;
            $_data['url'] = $data['url'];
            $_data['business_name'] = $data['business_name'];
            $_data['ein'] = $data['ein'];
            $_data['business_address_line1'] = $data['business_address_line1'];
            $_data['business_city'] = $data['business_city'];
            $_data['business_state'] = $data['business_state'];
            $_data['business_zipcode'] = $data['business_zipcode'];
            $_data['business_country'] = $data['business_country'];
            $_data['bank_name'] = $data['bank_name'];
            $_data['account_ownership'] = $data['account_ownership'];
            $_data['account_type'] = $data['account_type'];
            $_data['account_name'] = $data['account_name'];
            $_data['account_number'] = $data['account_number'];
            $_data['routing_number'] = $data['routing_number'];
            $_data['company_id'] = $company_id;
            $_data['merchant_agreement'] = $data['merchant_agreement'];

            $id = db_get_field("SELECT id FROM ?:vendor_details WHERE company_id = ?i", $company_id);
            if(empty($id)) {
                db_query("INSERT INTO ?:vendor_details ?e", $_data);
            } else {
                db_query("UPDATE ?:vendor_details SET ?u WHERE company_id = ?i", $_data, $company_id);
            }
            $token = generateTokenDigitzs();
            $company_data = getMerchantDetails($_POST['company_data']);
            $token=$token->data->attributes->appToken;
            $merchant = createMerchant($company_data,$token);
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
            fn_update_company(
                array(
                    'digitzs_connect_account_id'          => $merchant_id,
                ),
                $company_id
            );
                fn_set_notification('', __(''), '<p><b style="color:#258D78">Congratulations Your account has been approved.</b> <span style="color:#000;">An email has been sent to you providing you with your login credentials for your <a href="https://myiq.digitzs.com/login" style="color:#5294EA" target="_blank"><b>processing dashboard.</b></a> For more detailed processing and card fees breakdowns on your customer transactions, you can visit your processing dashboard.</span></p>','K');
                fn_redirect('companies.update&company_id='.$company_id.' &selected_section=digitzs_connect');
            } else if(!$error_status){
                fn_set_notification('', __(''), '<p><b style="color:#F7C600">We apologize but we were unable to approve your application at this time.</b> <span style="color:#000;"> You will be contacted to review your application submission and attempt to resolve any discrepancies.</span></p>','K');
                fn_redirect('companies.update&company_id='.$company_id.' &selected_section=digitzs_connect');

            }
        }
    }
}

function generateTokenDigitzs() {
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
    return callDigitzApi1($tokenApi,$post,$header);
}

function createMerchant($post,$token) {
  $api = 'merchants';
  $post = json_encode($post);
  $apiKey = Registry::get('addons.digitzs_connect.api_key');
  $header=array(
    "Authorization:Bearer ".$token,
    "x-api-key:".$apiKey,
    "Content-Type:application/json"
  );
  return callDigitzApi1($api,$post,$header);
}
function getMerchantDetails($company_details) {
    extract($company_details);
    $termsAccepted=false;$verify_email=false;
    if($company_details['merchant_agreement'] == 1) { $termsAccepted=true;}
    if($company_details['verify'] == 1) { $verify_email=true;}
    $merchantData = array("data" => array(
    "type"=> "merchants",
    "attributes"=> array(
        "AVS"=> "Standard",
        "accountType" => $account_ownership,
        "accountName" => $company,
        "miscData" => "",
        "personalInfo"=>array(
            "firstName"=> $first_name,
            "lastName"=> $last_name,
            "email"=> $personal_email,
            "dayPhone"=> $day_phone,
            "eveningPhone"=> $evening_phone,
            "birthDate"=> $birth_date,
            "socialSecurity"=> $social_security
        ),
        "personalAddress" => array(
            "line1" => $personal_address_line1,
            "city" => $personal_city,
            "state" => $personal_state,
            "zip" => $personal_zip,
            "country" => $personal_country
        ),
        "businessInfo" => array(
            "businessName" => $business_name,
            "ein" => $ein,
            "phone" => $phone,
            "email" => $email,
            "url" => $url
        ),
        "businessAddress" => array(
            "line1" => $business_address_line1,
            "city" => $business_city,
            "state" => $business_state,
            "zip" => $business_zipcode,
            "country" =>$business_country
        ),
        "bankInfo" => array(
            "bankName" => $bank_name,
            "accountOwnership" =>$account_ownership,
            "accountType" => $account_type,
            "accountName" => $account_name,
            "accountNumber" => $account_number,
            "routingNumber" => $routing_number
        ),
        "verificationData" => array(
            "ipAddress" => $ip_address,
            "emailVerified" => $verify_email,
            "emailVerifiedTimestamp" => $timestamp.'.234Z',
            "signature" => $first_name." ".$last_name,
            "signatureTimestamp" => date('Y-m-d\TH:i:s').'.234Z',
            "termsAccepted" =>$termsAccepted
        ),
      "threatMetrixPolicy" => "Default",
      "threatMetrixSessionId" => "x"
    )
  ));
  return $merchantData;
}
function callDigitzApi1($api,$post,$header) {
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
    } else {
        $error = $mer->detail;
    }
    return $error;
}
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function generate_email_code(){
    $num_str = sprintf("%06d", mt_rand(1, 999999));
    return $num_str;
}
function sendMail($to,$company_id) {
    $subject='Vendor Email Verification';
    $email_code = generate_email_code();
    $url = $_SERVER['HTTP_REFERER'];
    $message = '<p> Thank you for your interest in becoming a Vendor. Please enter the verification code into your Vendor administration page in order to proceed with your application process. </p>';
    $message .= '<p> Verification Code: '.$email_code.' </p>';
    $message .= '<p> Vendor Login : <a href="'.$url.'"> Click Here </a></p>';

    $from ='dhavalb.dave@radixweb.com';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    'From: '.$from."\r\n".
    'Reply-To: '.$from."\r\n" ;
    $headers .=
    'X-Mailer: PHP/' . phpversion();

    if(mail($to, $subject, $message, $headers)) {
        $_data['email_code'] = $email_code;
        $id = db_get_field("SELECT id FROM ?:vendor_details WHERE company_id = ?i", $company_id);
        if(empty($id)) {
            $_data['company_id'] = $company_id;
            db_query("INSERT INTO ?:vendor_details ?e", $_data);
        } else {
            db_query("UPDATE ?:vendor_details SET ?u WHERE company_id = ?i", $_data, $company_id);
        }
        fn_set_notification('N', __(''), 'Your mail has been sent successfully.','K');
    } else{
        fn_set_notification('', __(''), 'Unable to send email. Please try again.','K');
    }
}

 

 
