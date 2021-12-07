<?php
use Tygh\Registry;
if ($mode == 'view') {
    $aff_product = array();
    $aff_merchant_details = array();
    Registry::set('config.tweaks.disable_block_cache',true);
    if(!empty($_REQUEST['id'])) {
        $product_id = $_REQUEST['id'];
        //$merchant_id = $_REQUEST['merchant_id'];
        $product_api = 'products/'.$product_id;
        $aff_product = getProduct($product_api);
        //$aff_product['Description'] = str_replace("�","'",$aff_product['Description']);
        $aff_merchant_details = getMerchant($aff_product['MerchantId']);
    }
    Tygh::$app['view']->assign('aff_product', $aff_product);
    Tygh::$app['view']->assign('aff_merchant_details', $aff_merchant_details);
}
function getProduct($api) {
    $product = indiAPI($api);
	return $product;
}
function getMerchant($merchant_id) {
    $company_details = db_get_row("SELECT company_id,company from ?:companies WHERE affiliate_merchant=?s",$merchant_id);
    $mdetails = indiAPI('merchants/'.$merchant_id);
    $mdetails = array_merge($company_details, $mdetails);
	return $mdetails;
}

?>