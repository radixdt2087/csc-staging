<?php 

use Tygh\Registry;
use Tygh\Tools\Url;
// if (!defined('BOOTSTRAP')) { die('Access denied'); }

// //if(empty($_COOKIE['comAffilateId']))
// //{
//     /* Set Cookie */
//     $cookie_name = "comAffilateId";
    
//     $str = $_SERVER['QUERY_STRING'];
//     // echo "<pre>";
//     // print_r($_SERVER);
//     // echo "</pre>";
//     $companyId = strstr($str, 'company_id=');
//     preg_match_all('!\d+!', $companyId, $matches);
    
//     if(isset($matches[0][0])){
//         $companyId = $matches[0][0];
//         $checkUser = db_get_array("SELECT * FROM ?:users WHERE `company_id` = '$companyId'");
//         $affilate = $checkUser[0]['user_id'];
//     }
//     //die($affilate);
//     setcookie($cookie_name, $affilate, time() + (86400 * 30), "/");
//     /* End Set Cookie */
// //}
// print_r($_COOKIE['comAffilateId']);
// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";
    // if (!defined('BOOTSTRAP')) { die('Access denied'); } 
    // //print_r($_SERVER['REQUEST_METHOD']);
    // $userData = $_REQUEST['company_data'];
    // // if($userData){
    // //     print_r($userData);
    // //     die();
    // // }
  
    // if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    // {
    //     $domain = $_SERVER['HTTP_REFERER'];
    //     $url = parse_url($domain);
    //     $domain_name = $url['host'];
        
    //     $userData = $_REQUEST['company_data'];
        
    //     $phoneNumber = preg_replace( '/[^0-9]/', '', $userData['phone']);

    //     $parts = explode("@", $userData['email']);
    //     $username = $parts[0];

    //     $email         = $userData['email'];
    //     $password      = "Chetu123";
    //     $firstname     = $username;
    //     $lastname      = "";
    //     $phone         = $phoneNumber;
    //     $domain        = $domain_name;
    //     $affId         = 0;
    //     $profileStatus = $_REQUEST['dispatch'];

    //     $getPlanData = "";
    //     if($userData['plan_id'] != "")
    //     {
    //         $planId = $userData['plan_id'];
    //         $getPlanData = db_get_array("SELECT * FROM ?:vendor_plans WHERE `plan_id` = $planId");
    //     }

    //     $data = [
    //         'firstName'  => $firstname,
    //         'lastName'   => $lastname,
    //         'email'      => $email,
    //         'phone'      => $phone,
    //         'password'   => $password,
    //         'role_id'    => 4,
    //         'createdBy'  => $affId,
    //         'domainName' => $domain,
    //         'profileStatus' => $profileStatus,
    //         'planData' => json_encode($getPlanData),
    //     ];

    //     $data_json = json_encode($data);
    //     //print_r($data_json);
    //     //die();
        
        
    //     $url = 'https://staging.wesave.com/index.php/api/userSingup';
    //     // curl initiate
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     $response  = curl_exec($ch);
    //     curl_close($ch);
    //    // return $response;


    // }


?>