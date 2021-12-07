<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
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
use Tygh\Registry;
use Tygh\Ajax;

if ($mode == 'update') {
	$company_id = $_REQUEST['company_id'];
	$companyDetails = fn_get_company_data($_REQUEST['company_id']);
	if($companyDetails['affiliate_merchant'] != ''){
		$image_path = db_get_field("SELECT image_path FROM ?:images WHERE image_id = ?i", $companyDetails['vendor_store_banner']['detailed_id']);
		Tygh::$app['view']->assign('image_path',$image_path);
		Tygh::$app['view']->assign('affiliate_merchant',$companyDetails['affiliate_merchant']);
	}
}
if($mode == 'products'){
	Registry::set('config.tweaks.disable_block_cache',true);
	$company_id = $_REQUEST['company_id'];
		if (defined('AJAX_REQUEST')) {
			$currentPage = $_REQUEST['page'];
		}else{	
			$currentPage = 1;
		}
	$pageSize = ($currentPage == 1)?13:12;
	
	$companyDetails = fn_get_company_data($_REQUEST['company_id']);
	if($companyDetails['affiliate_merchant'] != ''){
		$image_path = '';
		$image_path = db_get_array('SELECT m.image_path FROM ?:images_links AS l LEFT JOIN ?:images AS m ON l.detailed_id = m.image_id WHERE l.object_id = ?i AND l.object_type = "logos" AND l.detailed_id <> 0 ',$company_id);
		
		if(isset($image_path[0]['image_path'])){
			Tygh::$app['view']->assign('image_path',$image_path[0]['image_path']);
		}
		
		$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.indiplatform.com/v1/public/catalog/productcatalog/products?page='.$currentPage.'&pagesize='.$pageSize.'&merchantid='.$companyDetails['affiliate_merchant'].'',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Ocp-Apim-Subscription-Key:961744d50016482e8d0309e9dc3ec032'
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$responseData = json_decode($response);

		$product = array();
		if($responseData->Results){
			foreach($responseData->Results as $key => $value){
				$product[] = array("product" => $value->Name,"company_name" => $companyDetails['company'],"price" => $value->Price,"Image" => $value->Image,"buy_now_url" => $value->BuyUrl,"Description" => $value->Description,"status" => 'A',"affiliate_product" => $value->Id,"product_id" => 238655 );
				$product[$key]['main_pair']['detailed']['image_path'] =  $value->Image;
				if(!empty($product) && $product[$key]['price'] == ""){
					unset($product[$key]);
				} 
			}
		}
			//echo "<pre>";print_r($product); exit;
		$search = array("area" => 'C',"use_caching" => 0,"page" => $responseData->Page,"items_per_page" => $responseData->PageSize,"company_id" => "","sort_by" => "product","sort_order" => "asc","parent_product_id" => 0,"sort_order_rev" => "desc","total_items" => $responseData->Found);
		$params['Page'] = $responseData->Page;
		$selected_layout = fn_get_products_layout($params);
		if (defined('AJAX_REQUEST')) {
			Tygh::$app['view']->assign('products', $product);
			Tygh::$app['view']->assign('search', $search);
			Tygh::$app['view']->assign('companydata',$companyDetails);
			Tygh::$app['view']->assign('selected_layout', $selected_layout);
			Tygh::$app['view']->assign('affiliate_merchant',$companyDetails['affiliate_merchant']);
			Tygh::$app['view']->assign('company_id', $company_id);
			Registry::get('view')->display('addons/affiliate_merchant_product/hooks/companies/products.override.tpl');
		 	exit;
		}else{
			Tygh::$app['view']->assign('products', $product);
			Tygh::$app['view']->assign('search', $search);
			Tygh::$app['view']->assign('companydata',$companyDetails);
			Tygh::$app['view']->assign('selected_layout', $selected_layout);
			Tygh::$app['view']->assign('affiliate_merchant',$companyDetails['affiliate_merchant']);
			Tygh::$app['view']->assign('company_id', $company_id);
		}
	
	}
}
if($mode == 'catalog'){
	$params = $_REQUEST;
    $params['status'] = 'A';
    $params['get_description'] = 'Y';
    /** @var \Tygh\Storefront\Storefront $storefront */
    $storefront = Tygh::$app['storefront'];
    if ($storefront->getCompanyIds()) {
        $params['company_id'] = $storefront->getCompanyIds();
	}
	$vendors_per_page = Registry::get('settings.Vendors.vendors_per_page');
    list($companies, $search) = fn_get_companies($params, $auth, $vendors_per_page);
	foreach ($companies as &$company) {
		$companydata = fn_get_company_data($company['company_id']);
		if($companydata['affiliate_merchant'] != ''){
			$image_path = '';
			$image_path = db_get_array('SELECT m.image_path FROM ?:images_links AS l LEFT JOIN ?:images AS m ON l.detailed_id = m.image_id WHERE l.object_id = ?i AND l.object_type = "logos" AND l.detailed_id <> 0 ',$company['company_id']);
			if(isset($image_path[0]['image_path'])){ 
				$company['logos'] = fn_get_logos($company['company_id']);
				$company['affiliate_merchant_image'] = $image_path[0]['image_path'];
				$company['affiliate_merchant'] = $companydata['affiliate_merchant'];
				$company['url'] = $companydata['url'];
				$company['affiliate_product_count'] = $companydata['affiliate_product_count'];
				$company = fn_filter_company_data_by_profile_fields($company);
			}
		}else{
			$company['logos'] = fn_get_logos($company['company_id']);
			$company = fn_filter_company_data_by_profile_fields($company);
		}
	}

	Tygh::$app['view']->assign('companies', $companies);
	Tygh::$app['view']->assign('search', $search);
}
