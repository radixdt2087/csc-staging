<?php

/*
 * Copyright 2016, 2020 1st Source IT, LLC, EZ Merchant Solutions
 * All rights reserved.
 * Resale prohibited.
 */
if( !defined('BOOTSTRAP') ) die('Access denied');

use Tygh\Registry;

if( $_SERVER['REQUEST_METHOD'] != 'POST' ) {	// Global template variables
	$view = Registry::get('view');
	$view->assign('charities_total_contribution', $x['total_contribution'] = (ez_ch::total_paid() + ez_ch::total_earned()) );
	$view->assign('active_charities', $x['active_charities'] = ez_ch::affiliate_menu('A'));
	$view->assign('active_charities_count', $x['active_charities_count'] = count($x['active_charities']) );
	$view->assign('all_active_charities', ez_ch::get_affiliates() );
	$user = array();
	if( !empty($_SESSION['auth']['user_id']) ) {
		$user_affiliate_id = ez_ch::get_ch_data(CH_TYPE_USER, $_SESSION['auth']['user_id']);
		if( $user_affiliate_id )
			$user = ez_ch::get_affiliate($user_affiliate_id);
	}
	$global = ez_ch::get_ch_data(CH_TYPE_GLOBAL);
	$override = empty($global['override']['affiliate_id']) ? 0 : $global['override']['affiliate_id'];
	$view->assign('override', $override);
	
	$default = ez_ch::get_affiliate($global['default']['affiliate_id']);
	$name = $user ? $user['name'] : (empty($default) ? '' : $default['name']);
	if( !$user )
		$user = $default;
	if( !$override && !empty($_SESSION['cart']['selected_charity']) ) {
		$user = $_SESSION['cart']['selected_charity'];
	}
											   
	$view->assign('current_charity_name',$x['current_charity_name'] = $name);
	$view->assign('default_charity', $default);
	$view->assign('current_charity', $user);
	// 2/6/20: Fixed spelling of 'commission' from 'commision'
	if( Registry::get('addons.charities.commission_from') == 'system') 
		$commission = $global['commission'];
	else
		$commission = $user['rate'];
		
	$view->assign('commission_rate', $commission);
	$view->assign('commission_type', strpos($commission, '%') !== false ? 'percent' : 'absolute');
	
	// 12/12/18: Added additional info for current cart total and contribution.
	// 2/6/20: added 'total' element to empty check
	if( !empty($_SESSION['cart']['total']) ) {
		$view->assign('contribution_basis', $_SESSION['cart']['total']);
		// 3/25/20 - add floatval for commission.  Gets rid of % in 4%
		$view->assign('contribution', $_SESSION['cart']['total'] * (floatval($commission)/100));
	}
//ch_log("Init: $controller.$mode: global settings:".print_r($x,true) );
}
// 12/12/18: Added support for selecting charity on checkout.
if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if( !empty($_REQUEST['selected_charity']) ) {
		// Update user's profile - only set if user is logged in
		if( $uid = $_SESSION['auth']['user_id'] ) {
			ez_ch::set_ch_data(CH_TYPE_USER, $uid, $_REQUEST['selected_charity']);
		}
	}
}

return array(CONTROLLER_STATUS_OK);
?>