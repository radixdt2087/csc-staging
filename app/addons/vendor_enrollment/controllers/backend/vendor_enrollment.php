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

use Tygh\Enum\NotificationSeverity;
use Tygh\Models\VendorEnrollment;
use Tygh\Registry;
use Tygh\Tygh;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if($mode == 'manage_permission') {
    $permission_list = db_get_array("SELECT * from ?:vendors_permission");
    Tygh::$app['view']->assign('permission_list', $permission_list);
}
if ($mode == "update" || $mode == "add") {
    $addons_list = db_get_array("SELECT id,`name` from ?:plan_addons_details where `status` = ?s",'A');
    Tygh::$app['view']->assign('addons_list', $addons_list);
    $companies = fn_get_short_companies();
    Tygh::$app['view']->assign('companies', $companies);
    if($mode == "update") {
        $permission = db_get_row("SELECT * from ?:vendors_permission where id=?i",$_REQUEST['id']);
        Tygh::$app['view']->assign('permission_data', $permission);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $suffix='';
   // if ($mode == 'add' || $mode == 'update') {
        $_data = array();
        $vendor_permission = $_POST['vendor_permission'];
        $_data['name'] = $vendor_permission['name'];
        $_data['modules'] = $vendor_permission['module'];
        $_data['tabs'] = $vendor_permission['tabs'];
        $_data['addons'] = $vendor_permission['addons'];
        $_data['vendors'] = $vendor_permission['vendors'];
        $_data['status'] = $vendor_permission['status'];
        if ($mode == 'add') {
            $id = db_query("INSERT INTO ?:vendors_permission ?e", $_data);
            $suffix = ".update?id=$id";
        } else if ($mode == 'update') {
            $id = $_REQUEST['id'];
            db_query("UPDATE ?:vendors_permission SET ?u WHERE id=?i", $_data, $id);
            $suffix = ".update?id=$id";
        }
        return array(CONTROLLER_STATUS_OK, 'vendor_enrollment' . $suffix);
   // }
}
if ($mode == 'update_status') {
    $id = $_REQUEST['id'];
    $status_data = array();
    $status_data['status'] = $_REQUEST['status'];
    db_query("UPDATE ?:vendors_permission SET ?u WHERE id=?i", $status_data, $id);
}
if ($mode == 'manage') {
    $company=array();
    if(Registry::get('runtime.company_id')) {
        $company=array('company_id'=>Registry::get('runtime.company_id'));
    }
    $params = array_merge(
        array(
            'items_per_page'      => Registry::get('settings.Appearance.admin_elements_per_page'),
        ),
        $_REQUEST,
        array(
            'return_params'       => true,
            'get_companies_count' => true,
            'lang_code'           => DESCR_SL,
        ),
        $company
    );
    list($plan_charges, $search) = VendorEnrollment::model()->findMany($params);
  
    foreach($plan_charges as $key=>$plan) {
        $pay_mode = '';
        if($plan->type == 'plan') {
            $periodicity = db_get_field('SELECT periodicity FROM ?:vendor_plans WHERE plan_id=?i',$plan->plan_id);
            if($periodicity == 'month') {
                $pay_mode ='+1 months';
            } else if($periodicity == 'year') {
                $pay_mode ='+1 years';
            }
        } else if($plan->type == 'addons') {
            $resultset=db_query("SELECT distinct pad.id,pad.payment_frequency FROM ?:plan_payment_details ppd INNER JOIN ?:addons_payment_details apd on apd.pid = ppd.id INNER JOIN ?:plan_addons_details pad on pad.id = apd.addon_id WHERE ppd.plan_id = ".$plan->plan_id);
            if($row = mysqli_fetch_assoc($resultset)) {
                $plan_charges[$key]->frequency = $row['payment_frequency'];
            }
            if($plan_charges[$key]->frequency == 'Month') {
                $pay_mode ='+1 months';
            } else if($plan_charges[$key]->frequency=='Year') {
                $pay_mode ='+1 years';
            }
        }
        $exp_date = strtotime($pay_mode, $plan->plan_date);
        if(!empty($plan->plan_days)) {
            $no_days = $plan->plan_days;
            $plan_exp_date = strtotime(' + '.$plan->plan_days.' days',$exp_date);
            $exp_date = $plan_exp_date;
        }
        $plan_charges[$key]->exp_date = $exp_date;
    }

    Tygh::$app['view']->assign('plan_charges', $plan_charges);
    Tygh::$app['view']->assign('search', $search);
} else if ($mode == 'vendor_details') {
    $apiKey = !empty(Registry::get('addons.digitzs_connect.api_key')) ? Registry::get('addons.digitzs_connect.api_key') : 'lO6grwpF7n5MZt9Qc0U2T6l0Cp9DOERm6XcMDwtY';
    $_REQUEST['id'] = empty($_REQUEST['id']) ? 0 : $_REQUEST['id'];
    $selected_section = (empty($_REQUEST['selected_section']) ? 'general' : $_REQUEST['selected_section']);
    $plan_charges_details = db_get_array("SELECT pd.id,pd.billing_address,pd.billing_city,pd.country,pd.`state`,pd.zipcode,pd.amount,pd.plan_date,pd.card_holder_name,pd.type,pd.card_token,apd.addon_id,pd.plan_id,pd.company_id,c.company,vd.plan,apd.amount aamount FROM ?:plan_payment_details pd LEFT JOIN ?:vendor_plan_descriptions vd on pd.plan_id=vd.plan_id LEFT JOIN ?:companies c on c.company_id=pd.company_id LEFT JOIN ?:addons_payment_details apd on apd.pid=pd.id WHERE pd.id=?i", $_REQUEST['id']);
    $token = generateCardEnrollmentToken();
    $token = $token->data->attributes->appToken;
    $header = array(
        "Authorization:Bearer ".$token,
        "x-api-key:".$apiKey,
        "Content-Type:application/json"
    );
    foreach($plan_charges_details as $k=>$plan_charge) {
        $tokenRes = getVendorCardDetails($header,$plan_charge['card_token']);
        $plan_charges_details[$k]['card_number'] = $tokenRes->data->attributes->card->number;
    }
    Tygh::$app['view']->assign('plan_charges_details', $plan_charges_details);
    $addon_data = db_get_array("SELECT id,`name` FROM ?:plan_addons_details where `status`='A'");
    Tygh::$app['view']->assign('addon_data',$addon_data);
   
} else if ($mode == 'cancel_plan') {

    $_data = array();
    $_data = array('plan_status'=>'Cancel');
    $id=$_REQUEST['id'];
    $company_id=Registry::get('runtime.company_id');
    $suffix = ".update&company_id=".$company_id."&selected_section=plan&cancel=1";
    //cancel plan
    db_query("UPDATE ?:plan_payment_details SET ?u WHERE plan_id = ?i and company_id=?i", $_data, $id,$company_id);
    //cancel plan from companies
    db_query("UPDATE ?:companies SET ?u WHERE plan_id = ?i and company_id=?i", $_data, $id,$company_id); 
    $_data = array();
    $_data = array('status'=>'Cancel');
    //cancel addons
    db_query("UPDATE ?:addons_payment_details SET ?u WHERE plan_id = ?i and company_id=?i", $_data, $id,$company_id); 
    fn_set_notification('N', __(''), 'Plan Cancel Successfully');
    return array(CONTROLLER_STATUS_OK, 'companies' . $suffix);

} else if ($mode == 'cancel_addons') {
    $_data = array('status'=>'Cancel');
    $id=$_REQUEST['id'];
    $company_id = Registry::get('runtime.company_id');
    $suffix = ".update&company_id=".$company_id."&selected_section=plan&cancel=1";
    db_query("UPDATE ?:addons_payment_details SET ?u WHERE addon_id = ?i and company_id=?i", $_data, $id,$company_id); 
    fn_set_notification('N', __(''), 'Addons Cancel Successfully');
    return array(CONTROLLER_STATUS_OK, 'companies' . $suffix);

} else if($mode == 'permission_manage') {
    $suffix = 'permission_manage';
    //return array(CONTROLLER_STATUS_OK, 'vendor_enrollment.' . $suffix);
}
function getVendorCardDetails($header,$token_id) {
    $post = array();
    $post = json_encode($post);
    return callVendorEnrollmentCardDigitzApi('tokens/'.$token_id,$post,$header);
}
function generateCardEnrollmentToken() {
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
    return callVendorEnrollmentCardDigitzApi($tokenApi,$post,$header);
}
function callVendorEnrollmentCardDigitzApi($api,$post,$header) {
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