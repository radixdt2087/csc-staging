<?php
/*****************************************************************************
*                                                                            *
*          All rights reserved! CS-Commerce Software Solutions               *
* 			http://www.cs-commerce.com/license-agreement.html 				 *
*                                                                            *
*****************************************************************************/
use Tygh\Registry;
use Tygh\CscLiveSearch;
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode=="search" && !defined('AJAX_REQUEST')){
	$cls_settings = CscLiveSearch::_get_option_values();
	if ($cls_settings['autoredirect']=="Y"){
		$_view = CscLiveSearch::_view();
		$search = $_view->getTemplateVars('search');
		if ($search['total_items']==1){
			$products = $_view->getTemplateVars('products');
			$product = reset($products);
			return array(CONTROLLER_STATUS_REDIRECT, 'products.view?product_id='.$product['product_id']);
		}
	}
}

//Search post results

if ($mode == 'search') {

    $params = $_REQUEST;
    fn_add_breadcrumb(__('search_results'));
    if (!empty($params['search_performed']) || !empty($params['features_hash'])) {
		Registry::set('config.tweaks.disable_block_cache',true);
        $params = $_REQUEST;
        $params['extend'] = array('description');
        if (isset($params['order_ids'])) {
            $order_ids = is_array($params['order_ids']) ? $params['order_ids'] : explode(',', $params['order_ids']);
            foreach ($order_ids as $order_id) {
                if (!fn_is_order_allowed($order_id, $auth)) {
                    return [CONTROLLER_STATUS_NO_PAGE];
                }
            }
        }

        if ($items_per_page = fn_change_session_param(Tygh::$app['session']['search_params'], $_REQUEST, 'items_per_page')) {
            $params['items_per_page'] = $items_per_page;
        }
        if ($sort_by = fn_change_session_param(Tygh::$app['session']['search_params'], $_REQUEST, 'sort_by')) {
            $params['sort_by'] = $sort_by;
        }
        if ($sort_order = fn_change_session_param(Tygh::$app['session']['search_params'], $_REQUEST, 'sort_order')) {
            $params['sort_order'] = $sort_order;
		}

        list($products, $search) = fn_get_products($params, Registry::get('settings.Appearance.products_per_page'));

		fn_filters_handle_search_result($params, $products, $search);

        fn_gather_additional_products_data($products, array(
            'get_icon' => true,
            'get_detailed' => true,
            'get_additional' => true,
            'get_options'=> true
        ));
		if (!empty($products)) {
            Tygh::$app['session']['continue_url'] = Registry::get('config.current_url');
		}

		if(!empty($params['page'])) {
			$currentPage = $params['page'];
		} else {
			$currentPage = 1;
		}
		$pageSize = 42;
		$vendor_cnt = 0;
		list($ProductArr,$page,$vendor_cnt) = getSearchProducts($params,$currentPage,$pageSize);
		//$search_products = $ProductArr;//array_merge($ProductArr,$products);
		if(!empty($params['search_cid'])) {
			//$search_products = $ProductArr;
			$search['total_items'] = 0;
		}
		$tot_items = $page['Found'];
		$search['total_items'] =  $tot_items + $vendor_cnt; //$search['total_items'] +
		$search['page'] = $params['Page'] = $currentPage;
		$selected_layout = fn_get_products_layout($params);
		Tygh::$app['view']->assign('products', $ProductArr);
		Tygh::$app['view']->assign('search', $search);
		//Tygh::$app['view']->assign('merchant_res', $merchant_res);
		Tygh::$app['view']->assign('is_selected_filters', !empty($params['features_hash']));
		Tygh::$app['view']->assign('selected_layout', $selected_layout);
	}

//
// View product details
//
}

function getSearchProducts($params,$currentPage,$pageSize,$found = false) {
	$search_products = array();
	//$merchant_res = array();
	$vendors = array();
	$vcount=0; $dproducts=0;
	$api = 'products?page='.$currentPage;
	if(!empty($params['q'])) {
		$api.='&searchterm='.urlencode($params['q']);
		if(!empty($params['search_cid'])) {
			$merchantid =  $params['search_cid'];
			if($merchantid!='') {
				$api.="&merchantid=".$merchantid;
				//$merchant_res = getAffMerchant($merchantid);
			} else {
				$page = array('Page' => $currentPage,'PageSize' => $pageSize,'Found' => 0);
				return array($search_products,$page);
			}
		} else {
			if($currentPage == 1) {
				$vendors = db_get_array("SELECT company_id, company product,image_path img,affiliate_merchant merchantId,'Y' merchant FROM ?:companies c INNER JOIN ?:images_links il on c.company_id=il.object_id INNER JOIN ?:images i on i.image_id=il.detailed_id WHERE c.`status` = ?s AND company LIKE ?l","A","%$params[q]%");
				$search_products = $vendors;
				$vcount = count($vendors);
				$pageSize = $pageSize -  $vcount;
			}
		}

		$responseData = SearchIndiAPI($api,$pageSize);
		$ven_rec=0;
		if(count($responseData['Results']) > 1) {
			foreach($responseData['Results'] as $k=>$prd) {
				if($prd->IsUniversalLink == true) {
					$ven_rec++;
					continue;
				}
			}
			$page = array('Page' => $responseData['Page'],'PageSize' => $responseData['PageSize'],'Found' => ($responseData['Found'] - $ven_rec));
		}
		if(!$found) {
			$pageSize = $pageSize + $ven_rec;
			$responseData = SearchIndiAPI($api,$pageSize);
			if(count($responseData['Results']) > 1) {
				foreach($responseData['Results'] as $k=>$prd) {
					if($prd->IsUniversalLink == true) {
						$ven_rec++;
						continue;
					}
					$sproducts = array();
					$merchant_res = getAffMerchant($prd->MerchantId);
					$sproducts['product_id'] = 238655;
					$sproducts['price'] = $prd->Price;
					$sproducts['img'] = $prd->Image;
					$sproducts['product'] = $prd->Name;
					$sproducts['product_code'] = $prd->Id;
					$sproducts['merchantId'] = $prd->MerchantId;
					$sproducts['merchantName'] = $merchant_res['Name'];
					//$sproducts['merchantUrl'] = $merchant_res['BuyUrl'];
					//$sproducts['company_id'] = $merchant_res['company_id'];
					$sproducts['BuyUrl'] = $prd->BuyUrl;
					$sproducts['list_price'] = '';
					$sproducts['amount'] = '';
					$sproducts['category'] = '';
					$sproducts['category_id'] = '';
					$sproducts['labelBg'] = '';
					$search_products[]=$sproducts;
				}
			}
			$found = true;
		}
		return array($search_products,$page,count($vendors));
	}
}
function getAffMerchant($merchantid) {
	 $api = 'merchants/'.$merchantid;
 	 $response = indiAPI($api);
	//$response = db_get_row("SELECT company_id,company from ?:companies where affiliate_merchant = ?s",$merchantid);
	return $response;
}
function SearchIndiAPI($api,$pageSize){
	$api.='&pagesize='.$pageSize;
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

	$responseDetails = (array)json_decode($response);

	return $responseDetails;
}
