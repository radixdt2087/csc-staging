<?php
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

$userId = $_GET['user_id'];

if($userId)
{
    $getUser = db_get_array("SELECT * FROM ?:users WHERE user_id = $userId"); 

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

?>
