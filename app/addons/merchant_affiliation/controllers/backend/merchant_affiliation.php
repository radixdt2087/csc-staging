<?php 
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if($mode ==  'link') {

	$user_id = Tygh::$app['session']['auth']['user_id'];
	$company_id = Tygh::$app['session']['auth']['company_id'];

	if ($user_id) {
		$link = array($user_id, $company_id);
	}else{
		$link = "Please login first";
	}

	///$getSeo = db_get_array("SELECT * FROM ?:seo_names WHERE `object_id` = '$company_id'");

	$linkto = "LPO Name";

	$view = Registry::get('view');
  	$view->assign(['link' => $link, 'lpoName' => $linkto]);

}

function fn_my_changes_get_upload_product_affiliate_details($parentTab, $tabName, $uploadType)
{
    $checkData = db_get_array("SELECT * FROM ?:video_document WHERE `parent_name` = '$parentTab' AND `tab_name` = '$tabName' AND `upload_type` = '$uploadType'");
    return $checkData;
}



