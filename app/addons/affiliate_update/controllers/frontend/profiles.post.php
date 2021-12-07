<?php 

use Tygh\Registry;
use Tygh\Tools\Url;

    if (!defined('BOOTSTRAP')) { die('Access denied'); }
    $Link = Registry::get('addons.merchant_affiliation.ich_link');
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $domain = $_SERVER['HTTP_REFERER'];
        $url = parse_url($domain);
        $domain_name = $url['host'];
        $sdata = parse_str($domain, $output);
        $affliateID = (isset($output['aff_id']) && !empty($output['aff_id']))? $output['aff_id'] : 0;
        $userData = $_REQUEST['user_data'];
        $phoneNumber = preg_replace( '/[^0-9]/', '', $userData['phone']);
        $email         = $userData['email'];
        $password      = $userData['password1'];
        $firstname     = $userData['firstname'];
        $lastname      = $userData['lastname'];
        $user_type     = $userData['user_type'];
        $phone         = $phoneNumber;
        $domain        = $domain_name;
        $affId         = (!empty($affliateID)) ? $affliateID : $_COOKIE['affilateId'];
        $profileStatus = $_REQUEST['dispatch'];
 	//$profileStatus = 'profile.add';
        $subdomain     = $userData['subdomain'];
        if(!empty($subdomain) && $affId == "")
        {
            //$companyId = $checkDomain[0]['company_id'];
            $checkUser = db_get_array("SELECT * FROM ?:users WHERE `company_id` = '$subdomain' ORDER BY user_id ASC LIMIT 1");
            $affId = $checkUser[0]['user_id'];
        }
        $getUser = db_get_array("SELECT * FROM ?:users ORDER BY user_id DESC LIMIT 1");
        $data = [
            'firstName'  => $firstname,
            'lastName'   => $lastname,
            'email'      => $email,
            'phone'      => $phone,
            'password'   => $password,
            'role_id'    => 3,
            'createdBy'  => ($affId == null || $affId == 1) ? 0 : $affId,
            'csc_id'     => $getUser[0]['user_id'],
            'domainName' => $domain,
            'profileStatus' => 'profile.add',
        ];
        $data_json = json_encode($data);
        $url = $Link.'/api/userSingup';
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
        //setcookie("affilateId", "", time() - 3600);
        unset($_COOKIE['affilateId']);
        setcookie('affilateId', null, -1, '/');
        

        // echo "<pre>";
          //   print_r($response);
        // echo "</pre>";
       // die();
        // die;
        //print_r($data_json);
        //die();
       // $url = 'https://staging.wesave.com/index.php/api/userSingup';
        //$url = 'https://wesave-prod-apiservice.azurewebsites.net/public/api/userSingup';
    //    $url = 'http://ich.dev.radixweb.net/api/userSingup';
        // curl initiate
        // $ch = curl_init();
    	// curl_setopt($ch, CURLOPT_URL, $url);
    	// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     	// curl_setopt($ch, CURLINFO_HEADER_OUT, true);
     	// curl_setopt($ch, CURLOPT_POST, 1);
           // curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        // $response  = curl_exec($ch);        
        // curl_close($ch);
        // echo "<pre>";
        //     print_r($response);
        // echo "</pre>";
        // die();
        // print_r($response);
        // die();
        //setcookie("affilateId", "", time() - 3600);
        
        
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        // CURLOPT_URL => "https://staging.wesave.com/index.php/api/userSingup",
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => "",
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => "POST",
        // $response = curl_exec($curl);        
        // print_r($response);
        // die;
        // unset($_COOKIE['affilateId']);
        // setcookie('affilateId', null, -1, '/');

        if($profileStatus == "profiles.update")
        {

            $url = $Link.'/api/userUpdate';

	        /// $url = 'http://ich.dev.radixweb.net/api/userUpdate';
            // curl initiate
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            $response  = curl_exec($ch);
            curl_close($ch);
            //print_r($response);
            //die();
        }
 
    }
    
?>


