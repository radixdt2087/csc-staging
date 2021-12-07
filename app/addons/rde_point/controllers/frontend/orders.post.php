<?php 

if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;
use Tygh\Tools\Url;

if ($mode == 'details') 
{
    $getCurrentId = $auth['user_id'];
    $getUserDetils = db_get_array("SELECT * FROM ?:users WHERE user_id = $getCurrentId");   
    if(!empty($getUserDetils))
    {
        $userEmail = $getUserDetils[0]['email'];
        $Link = Registry::get('addons.merchant_affiliation.ich_link');
        /* GET USER REWARD POINTS */
        $url = $Link."/api/getPoints/$userEmail";
        // CURL INITIATE
        $ch = CURL_INIT();
        CURL_SETOPT($ch, CURLOPT_URL, $url);
        CURL_SETOPT($ch, CURLOPT_HTTPHEADER, ARRAY('CONTENT-TYPE: APPLICATION/JSON'));
        CURL_SETOPT($ch, CURLOPT_POST, 1);
        //CURL_SETOPT($CH, CURLOPT_POSTFIELDS,$DATA_JSON);
        CURL_SETOPT($ch, CURLOPT_RETURNTRANSFER, TRUE);
        CURL_SETOPT($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response  = CURL_EXEC($ch);
        CURL_CLOSE($ch);
        $getResult = json_decode($response);
        $getTotalRewardPoint = 0;
        foreach($getResult as $result){
            if($result->purchaseId == $_GET['order_id'] && $result->pointsFrom == "Online Purchase") 
            {
                $getTotalRewardPoint += $result->points;
            }
        }
    }
    $data = Registry::get('view')->assign('rewardPoints', $getTotalRewardPoint);
}
?>