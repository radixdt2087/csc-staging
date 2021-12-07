<?php

/*
 * Copyright 2015, 1st Source IT, LLC, EZ Merchant Solutions
 */
if( !defined('BOOTSTRAP') ) die('Access denied');
use Tygh\Registry;

if( empty($_REQUEST['user_id']) )
	return array(CONTROLLER_STATUS_OK);

$ch_global = ez_ch::get_ch_data(CH_TYPE_GLOBAL);
if( $_SERVER['REQUEST_METHOD'] == 'POST' 
			 && isset($_REQUEST['user_data']['charities']['affiliate_id']) ) {
	$uid = $_REQUEST['user_id'];
	ez_ch::set_ch_data(CH_TYPE_USER, $uid, $_REQUEST['user_data']['charities']['affiliate_id']);
} elseif($_SERVER['REQUEST_METHOD'] != 'POST' ) {
	$view = Registry::get('view');
	$view->assign('affiliate_menu', ez_ch::affiliate_menu());
	$user_data = $view->getTemplateVars('user_data');
	$user_aff = ez_ch::get_ch_data(CH_TYPE_USER, $_REQUEST['user_id']);
	$default = Registry::get('addons.charities.use_default') != 'N' 
				? $ch_global['default']['affiliate_id']
				: 0;
	$user_data['charities']['affiliate_id'] = $user_aff 
		? $user_aff 
		: $default;
//ch_dbg("$controller.$mode: user_aff=$user_aff, session[affiliate_id]=".$_SESSION['auth']['charities']['affiliate_id'],true);
	$view->assign('user_data', $user_data);
}
return array(CONTROLLER_STATUS_OK);
?>