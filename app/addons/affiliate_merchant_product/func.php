<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2021 Radixweb*
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/
use Tygh\Registry;

defined('BOOTSTRAP') or die('Access denied');

function fn_affiliate_merchant_product_render_block_content_pre($template_variable,$field,$block_schema,$block) {
	
	if($_REQUEST['dispatch'] == 'index.index') {
		Registry::set('config.tweaks.disable_block_cache',true);
		$bname = $block['name'];
		$merchantId = '';
		if($bname == "Featured Products") {
			$merchantId = '2a12f2fb4cae4a44205921ea5377d90b';
		} else if($bname == "Hot Deals") {
			$merchantId = '8f1f312053972e6b956e2990468050c7';
		} else if($bname == "Vendor Product List") {
			$merchantId = '212523e82117f4561e5b8692e0bca5b1';
		} else if($bname == "Newly Added") {
			$merchantId = '0f6847fa92b38cbab238918d9acdaf40';
		} else if($bname == "Newly Added in Category") {
			$merchantId = '7f90c96f950a88e450e648da4c87afc7';
		}
		if($merchantId != '') {
			$companyId = db_get_field("SELECT company_id FROM ?:companies WHERE affiliate_merchant=?s",$merchantId);
			$aff_merchant_details['company_id'] = $companyId;
			$blockmerchant = getBlocksMerchant($merchantId);
			$ProductArr = getProducts($merchantId);
			$products_res = array();
			$products_res[] = (object)$blockmerchant;
			foreach ($ProductArr as $key => $value) {
				$products_res[] = $value;
			}
			Tygh::$app['view']->assign('productdata', $products_res);
			Tygh::$app['view']->assign('aff_merchant_details', $aff_merchant_details);
		}
	}
}
function fn_affiliate_merchant_product_init_templater($company_id){
	$mode = Registry::get('runtime.mode');
	if(($mode == 'index' || $mode == 'search') && $company_id > 0){
		$image = db_get_array('SELECT m.image_path FROM ?:images_links AS l LEFT JOIN ?:images AS m ON l.detailed_id = m.image_id WHERE l.object_id = ?i AND l.object_type = "logos" AND l.detailed_id <> 0 ',$company_id);
		if(!empty($image)) {
			return $image[0]['image_path'];
		}
	}
}
function getBlocksMerchant($merchantid) {
	$api = 'merchants/'.$merchantid;
	$response = indiAPI($api);

	return $response;
}
function getProducts($merchantId) {
	$products = array();
	$page = rand(1,10);
	$api = 'products?page='.$page.'&pagesize=10&merchantid='.$merchantId;
	$response = indiAPI($api);
	if(count($response) <= 6) {
		$response = getProducts($merchants);
	}
	if($page == 1) {
		unset($response[0]);
	}
	return $response;
}