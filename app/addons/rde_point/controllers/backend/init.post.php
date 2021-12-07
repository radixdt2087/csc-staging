<?php 

use Tygh\Registry;
use Tygh\Tools\Url;


$getCurrentId = $auth['user_id'];
$getUserDetils = db_get_array("SELECT * FROM ?:users WHERE user_id = $getCurrentId");
if(!empty($getUserDetils))
{
    $userEmail = $getUserDetils[0]['email'];
    $Link = Registry::get('addons.merchant_affiliation.ich_link');
    /* GET USER REWARD POINTS */
    $url = $Link."/api/getPoints/$userEmail";
    //$url = "https://staging.wesave.com/index.php/api/getPoints/$userEmail";
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
    foreach($getResult as $result)
    {
        $getUserData = db_get_array("SELECT * FROM ?:user_data WHERE `user_id` = '$getCurrentId' AND `type` = 'W'");
        $getrewardData = db_get_array("SELECT * FROM ?:reward_point_changes WHERE `transaction_id` = '$result->id'");
        if(empty($getrewardData))
        {
            //if(!empty($getUserData) && $result->points != 0)
            if(!empty($getUserData))
            {   
                $pointsData = $getUserData[0]['data'];
                $getPoints = preg_replace('/[^0-9]/', '', $pointsData);
                if($result->refund != 1)
                {
                    $addPoints = $result->points + $getPoints;
                    $storePoint = "i:".$addPoints.";";
                    $query = "UPDATE cscart_user_data SET `data`='$storePoint' WHERE `cscart_user_data`.`user_id` = '$getCurrentId' AND `cscart_user_data`.`type` = 'W'";
                    $databaseResult = db_query($query);
                    $timestamp = time();
                    $insertData = "INSERT INTO `cscart_reward_point_changes` (`change_id`, `user_id`, `amount`, `timestamp`, `action`, `reason`, `transaction_id`) VALUES (NULL, '$getCurrentId', '$result->points', '$timestamp', 'A', 'Online Purchases', $result->id)";
                    db_query($insertData);
                } else {
                    if($result->point >= $getPoints){
                        $subtractPoints = $result->points - $getPoints;
                    } else {
                        $subtractPoints = $getPoints - $result->points;
                    }
                    $storePoint = "i:".$subtractPoints.";";
                    $query = "UPDATE cscart_user_data SET `data`='$storePoint' WHERE `cscart_user_data`.`user_id` = '$getCurrentId' AND `cscart_user_data`.`type` = 'W'";
                    $databaseResult = db_query($query);
                    $timestamp = time();
                    $rewardPoint = '-'.$result->points;
                    $insertData = "INSERT INTO `cscart_reward_point_changes` (`change_id`, `user_id`, `amount`, `timestamp`, `action`, `reason`, `transaction_id`) VALUES (NULL, '$getCurrentId', '$rewardPoint', '$timestamp', 'S', 'Online Purchases', $result->id)";
                    db_query($insertData);
                }
            } else {
                //if($result->points != 0) {
                    $storePoint = "i:".$result->points.";";
                    $query = "INSERT INTO `cscart_user_data` (`user_id`, `type`, `data`) VALUES ($getCurrentId, 'W', '$storePoint')";
                    db_query($query);
                    $timestamp = time();
                    $insertData = "INSERT INTO `cscart_reward_point_changes` (`change_id`, `user_id`, `amount`, `timestamp`, `action`, `reason`, `transaction_id`) VALUES (NULL, $getCurrentId, $result->points, $timestamp, 'A', 'Online Purchases', $result->id)";
                    db_query($insertData);
                //}
            }
        }
    }
}
?>