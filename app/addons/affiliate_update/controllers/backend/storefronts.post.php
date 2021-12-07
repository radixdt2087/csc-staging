<?php 

use Tygh\Registry;
use Tygh\Tools\Url;

    if (!defined('BOOTSTRAP')) { die('Access denied'); } 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        
        $storefront_data  = $_REQUEST['storefront_data'];
        $categoryName = $storefront_data['name'];
        $storeFrontUrl = $storefront_data['url'];

        $data = [
            'category'  => $categoryName,
            'base_url'   => $storeFrontUrl,
        ];

        $data_json = json_encode($data);
        $Link = Registry::get('addons.merchant_affiliation.ich_link');
        if($mode == 'add')
        {
            $url = $Link.'/api/getStoreFrontUrl';
        }

        if($mode == 'update')
        {
            $url = $Link.'/api/getStoreFrontUrl';
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
