<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2021 Radixweb  *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

defined('BOOTSTRAP') or die('Access denied');

fn_register_hooks(
	'render_block_content_pre',
	'init_templater'
);

function indiAPI($api){
	$curl = curl_init();
	$indi_API = 'https://api.indiplatform.com/v1/public/catalog/productcatalog/'.$api;
	curl_setopt_array($curl, array(
		CURLOPT_URL => $indi_API,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
		'Ocp-Apim-Subscription-Key: 961744d50016482e8d0309e9dc3ec032'
		),
	));
    $response = curl_exec($curl);
	curl_close($curl);
	$responseArr = array();

	if($response['Results']) {
        $responseDetails = (array)json_decode($response);
        if($responseDetails['Results']) {
            $responseArr = $responseDetails['Results'];
        } else {
            $responseArr = $responseDetails;

        }
	}
	return $responseArr;
}