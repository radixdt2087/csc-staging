<?php
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

$companyID = $_GET['company_id'];
if($companyID)
{
    $getUser = db_get_array("SELECT * FROM ?:users WHERE company_id = $companyID");
    if($getUser)
    {
        $Link = Registry::get('addons.merchant_affiliation.ich_link');
        $email = $getUser[0]['email'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $Link."/api/getLpoDetails/$email",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $response = json_decode($response, TRUE);

        $view = Registry::get('view');
        $view->assign('lpoDetails', $response);

    }
}

function fn_my_changes_get_upload_product_vendor_details($parentTab, $tabName, $uploadType)
{
    $checkData = db_get_array("SELECT * FROM ?:video_document WHERE `parent_name` = '$parentTab' AND `tab_name` = '$tabName' AND `upload_type` = '$uploadType'");
    return $checkData;
}

function fn_my_changes_get_label_details_digitzs($tab, $labelName)
{ 
    $lavelValue = db_get_array("SELECT * FROM ?:label_value WHERE tab_name = '$tab' and label_name = '$labelName'"); 
    echo $lavelValue[0]['label_details'];
}
?>
