<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;
use Tygh\Tools\Url;



if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $getDonationPoint = $_REQUEST['reward_points']['12']['amount'];
    if(!empty($getDonationPoint))
    {
        $Link = Registry::get('addons.merchant_affiliation.ich_link');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $Link."/api/getDonationPercentage/".$getDonationPoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: c1299fb6-f865-6526-05a1-6fe9871669f3"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }

    }

}


?>
