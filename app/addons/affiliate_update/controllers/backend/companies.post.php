<?php 

use Tygh\Registry;
use Tygh\Tools\Url;

    if (!defined('BOOTSTRAP')) { die('Access denied'); } 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $domain = $_SERVER['HTTP_REFERER'];
        $url = parse_url($domain);
        $domain_name = $url['host'];
        
        $userData      = $_REQUEST['company_data'];
        
        $phoneNumber = preg_replace( '/[^0-9]/', '', $userData['phone']);

        $parts = explode("@", $userData['email']);
        $username = $parts[0];

        $email         = $userData['email'];
        $password      = "Chetu123";
        $firstname     = $username;
        $lastname      = "";
        $companyName   = $userData['company'];
        $phone         = $phoneNumber;
        $domain        = $domain_name;
        $affId         = 0;
        $profileStatus = $_REQUEST['dispatch'];

        $getPlanData = "";
        if($userData['plan_id'] != "")
        {
            $planId = $userData['plan_id'];
            $getPlanData = db_get_array("SELECT * FROM ?:vendor_plans WHERE `plan_id` = $planId");
        }

        $data = [
            'firstName'  => $firstname,
            'lastName'   => $lastname,
            'email'      => $email,
            'companyName' => $companyName,
            'phone'      => $phone,
            'password'   => $password,
            'role_id'    => 4,
            'createdBy'  => $affId,
            'domainName' => $domain,
            'profileStatus' => $profileStatus,
            'planData' => json_encode($getPlanData),
        ];

        $data_json = json_encode($data);
        $Link = Registry::get('addons.merchant_affiliation.ich_link');
        if($mode == 'add')
        {
            $url = $Link.'/api/userSingup';
        }

        if($mode == 'update')
        {
            $url = $Link.'/api/userUpdate';
        }

        // curl initiate
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response  = curl_exec($ch);
        curl_close($ch);

        // print_r($response);
        // die();

    }


?>
