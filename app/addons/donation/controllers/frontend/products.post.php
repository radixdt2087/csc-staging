<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;
use Tygh\Tools\Url;

//preg_match_all('!\d+!', $_SERVER['QUERY_STRING'], $matches);

$str = $_SERVER['QUERY_STRING'];

// echo "<pre>";
//     print_r($_SERVER);
//     echo "</pre>";
$productId = strstr($str, 'product_id=');
preg_match_all('!\d+!', $productId, $matches);
$Link = Registry::get('addons.merchant_affiliation.ich_link');
if(isset($matches[0][0]))
{

    // Get Product Details
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://$_SERVER[HTTP_HOST]/api.php?_d=products%2F".$matches[0][0],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "authorization: Basic YWRtaW5Ad2VzYXZlLmNvbTpDUTUzQzkyUTMySlc1a1ZSNjlJT0htSFl4NzFXNEQ4TQ==",
          "cache-control: no-cache",
          "content-type: application/json",
          "postman-token: 7c5e7776-714a-563a-f664-2e4b1f12427f"
        ),
      ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
            $data = json_decode($response);
            
            if($data)
            {
                /*Get RDE Points */
                //$getUser = db_get_array("SELECT * FROM ?:users WHERE `company_id` = $data->company_id");
                //$userEmail = $getUser[0]['email'];
                $userId = $_SESSION['auth']['user_id'];
                $getUser = db_get_array("SELECT * FROM ?:users WHERE `user_id` = $userId");
                $userEmail = $getUser[0]['email'];
                // print_r($data->company_id);
                // die();
                $productCode = $data->product_code;

                $productCode = explode(" ",$productCode);
                $productCode = implode('@', $productCode);

                if($productCode == NULL){
                    $productCode = 0;
                }
                $Rdecurl = curl_init();
                curl_setopt_array($Rdecurl, array(
                   CURLOPT_URL => $Link."/api/calculation-rdepoint-donation/".$data->base_price."/".$productCode."/".$userEmail."/".$data->company_id."/".$data->product_id,
                   //CURLOPT_URL => "https://staging.wesave.com/index.php/api/getRdepoints/21.600000/7%2008235%2008944%208/boysmember19@yopmail.com/14",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "authorization: Basic YWRtaW5Ad2VzYXZlLmNvbTpDUTUzQzkyUTMySlc1a1ZSNjlJT0htSFl4NzFXNEQ4TQ==",
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "postman-token: 7c5e7776-714a-563a-f664-2e4b1f12427f"
                    ),
                ));

                $Rderesponse = curl_exec($Rdecurl);
                $Rdeerr = curl_error($Rdecurl);

                //die($Rderesponse);
               // print_r($Rderesponse);

                curl_close($Rdecurl);
                $Rderesponse = ($Rderesponse != "") ? $Rderesponse : "NA";
                if ($Rdeerr) {
                    echo "cURL Error #:" . $Rdeerr;
                } 
        
                
                /* End get rde points */

                /* Get Mutiplier */
                $mutipliercurl = curl_init();

                curl_setopt_array($mutipliercurl, array(
                CURLOPT_URL => $Link."/api/getMutiplierData",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "postman-token: 13c90fde-743c-038d-fa4b-81f356de5f8a"
                ),
                ));

                $mutiplierresponse = curl_exec($mutipliercurl);
                $mutipliererr = curl_error($mutipliercurl);

                curl_close($mutipliercurl);

                if ($mutipliererr) {
                    echo "cURL Error #:" . $mutipliererr;
                }
                /* End Get Mutiplier */
                
                if($data->company_id != ""){
                    $getPayout = db_get_array("SELECT * FROM ?:vendor_payouts WHERE company_id = $data->company_id ORDER BY payout_id DESC LIMIT 1");
                    $commission = $getPayout[0]['commission'];
                    $calculateDonation = ($data->base_price * $commission) / 100;
                    $getDonation = db_get_array("SELECT * FROM ?:reward_points WHERE usergroup_id = 12");
                    $calculateDonationWithPer = ($calculateDonation * $getDonation[0]['amount']) / 100;
                    
                    preg_match( "/(-+)?\d+(\.\d{1,2})?/" , $calculateDonationWithPer, $matches);
                    preg_match( "/(-+)?\d+(\.\d{1,3})?/" , $data->price, $pricePoint);
                    $pricePointValue = ($mutiplierresponse * $pricePoint[0]);

                    
                    $Rderesponse = json_decode($Rderesponse, TRUE);
                    
                    $rdeDonation = $matches[0];
                    if(!empty($Rderesponse['donation']))
                    {
                        $rdeDonation = $Rderesponse['donation'];
                    }
                    $rewardsPoint = $Rderesponse['rewardsPoint'];
                    if(empty($userId)){
                        $rewardsPoint = "[Sign in to view reward point]";
                    }

                    $data = Registry::get('view')->assign(['donation' => $rdeDonation, 'rewardsPoint' => $rewardsPoint, 'pricePoint' => $pricePointValue]);
                    //$data = Registry::get('view')->assign(['donation' => $matches[0], 'rewardsPoint' => "1", 'pricePoint' => $pricePointValue]);
                    //print_r($data);
                }
            }
    }
}

?>





