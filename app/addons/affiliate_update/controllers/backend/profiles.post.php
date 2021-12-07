<?php 

use Tygh\Registry;
use Tygh\Tools\Url;

    if (!defined('BOOTSTRAP')) { die('Access denied'); } 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        
        $domain = $_SERVER['HTTP_REFERER'];
        $url = parse_url($domain);
        $domain_name = $url['host'];
        
        $userData = $_REQUEST['user_data'];
        
        $phoneNumber = preg_replace( '/[^0-9]/', '', $userData['phone']);

        $parts = explode("@", $userData['email']);
        $username = $parts[0];

        $firstName = $userData['firstname'];
        $lastName = $userData['lastname'];

        $email         = $userData['email'];
        $password      = $userData['password1'];
        $firstname     = ($firstName != "") ? $firstName : $username;
        $lastname      = ($lastName != "") ? $lastName : "";
        $phone         = $phoneNumber;
        $domain        = $domain_name;
        $affId         = 0;
        $profileStatus = $_REQUEST['dispatch'];

        $companyId = $userData['company_id'];



        // $getPlanData = "";
        // if($userData['plan_id'] != "")
        // {
        //     $planId = $userData['plan_id'];
        //     $getPlanData = db_get_array("SELECT * FROM ?:vendor_plans WHERE `plan_id` = $planId");
        // }

        $data = [
            'firstName'  => $firstname,
            'lastName'   => $lastname,
            'email'      => $email,
            'phone'      => $phone,
            'password'   => $password,
            'role_id'    => 4,
            'createdBy'  => $affId,
            'domainName' => $domain,
            'profileStatus' => $profileStatus,
            //'planData' => json_encode($getPlanData),
        ];

        $data_json = json_encode($data);
        $Link = Registry::get('addons.merchant_affiliation.ich_link');
        if($mode == 'add')
        {
            $url = $Link.'/api/userSingup';
            //$url = 'http://ich.dev.radixweb.net/api/userSingup';


        }

        if($mode == 'update')
        {
           // $url = 'http://ich.dev.radixweb.net/api/userUpdate';
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

        //print_r($response);
        //die();

    }


?>
