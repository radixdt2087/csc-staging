<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;
use Tygh\Tools\Url;


/* Get Donation Percentage */
$getDonationPercentage = db_get_array("SELECT * FROM ?:reward_points WHERE usergroup_id = 12");
if($getDonationPercentage){
    $donationPercentage = $getDonationPercentage[0]['amount'];
    if($donationPercentage != ""){
        $Link = Registry::get('addons.merchant_affiliation.ich_link');
        $donationcurl = curl_init();
        curl_setopt_array($donationcurl, array(
        CURLOPT_URL => $Link."/api/getDonationPercentage/".$donationPercentage,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: 7bb3be52-f878-2379-124c-c3a8d9eb0cb9"
        ),
        ));
        $donationresponse = curl_exec($donationcurl);
        $err = curl_error($donationcurl);
        curl_close($donationcurl);
        //print_r($donationresponse);
    }
}

/* End Get Donation Percentage */
