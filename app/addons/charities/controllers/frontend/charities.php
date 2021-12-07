<?php

/*
 * Copyright 2015, 1st Source IT, LLC, EZ Merchant Solutions
 */
if( !defined('BOOTSTRAP') ) die('Access denied');
use Tygh\Registry;

switch($mode) {
	case 'version':
		$ez_common = 'ez_common';
		addon_init($ez_common);
		$ez_version = addon_current_version($ez_common);
		if( empty($ez_version) )
			$ez_version = "not installed or missconfigured";
		addon_init($controller);
		$version = addon_current_version($controller);
		$msg = PRODUCT_NAME.": ".PRODUCT_VERSION." ".PRODUCT_EDITION."<br/>EZ Common: $ez_version<br/>$controller: $version";
		fn_set_notification('N', "$controller.$mode", $msg, true);
		fn_redirect(Registry::get('config.customer_index'));
		break;
	case 'profile':
		$affiliate_id=empty($_REQUEST['affiliate_id']) ? ($action ? $action: 0) : $_REQUEST['affiliate_id'];
		// Check if requested affiliate is active
		if( !ez_ch::affiliate_status($affiliate_id, CH_STATUS_ACTIVE) )
			$affiliate_id = 0;
			
		if( !$affiliate_id )
			return array(CONTROLLER_STATUS_NO_PAGE);
		$ch = ez_ch::get_affiliate($affiliate_id, CART_LANGUAGE);
		if( empty($ch) || $ch['status'] != CH_STATUS_ACTIVE )
			return array(CONTROLLER_STATUS_NO_PAGE);
		$view = Registry::get('view');
		$view->assign('affiliate', $ch);
		$view->assign('page_title', sprintf("%s %s %s", $ch['name'], __("charity"), __("profile")) );
		return array(CONTROLLER_STATUS_OK);
	case 'request':
		$affiliate_id=empty($_REQUEST['affiliate_id']) ? ($action ? $action: 0) : $_REQUEST['affiliate_id'];
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			fn_trusted_vars (
				'affiliate'
				);
			if( fn_image_verification('register', $_REQUEST) == false ) {
				fn_save_post_data('affiliate');
				return array(CONTROLLER_STATUS_REDIRECT, "charities.request");
			}
			$ch = ez_ch::update_affiliate($_REQUEST['affiliate']);
			if( empty($_REQUEST['affiliate']['affiliate_id']) ) {	// new affilaite
				$affiliate_id = $ch['affiliate_id'];
				$msg = __("ch_created_new_affiliate");
			} else {
				$msg = __("ch_updated_affiliate");
				ch_check_image_pairs(fn_attach_image_pairs('affiliate', 'charity', $ch['affiliate_id']));
			}
			fn_set_notification('N', __("notice"), $msg);
			fn_redirect("$controller.$mode.$affiliate_id");
		}
		$ch = ez_ch::get_affiliate($affiliate_id, CART_LANGUAGE);
		if( $affiliate_id && $ch ) {	// Check password
			if( empty($_SESSION['auth']['charity_authenticated'][$affiliate_id]) ) {
				fn_redirect("charities.authenticate.$affiliate_id");
			}
		}
		$view = Registry::get('view');
		$restored_affiliate = fn_restore_post_data('affiliate');
		if( $restored_affiliate )
			$ch = fn_array_merge($ch, $restored_affiliate);
			
		$view->assign('affiliate', $ch);
		$view->assign('page_title', sprintf("%s", __("ch_charity_request_title")) );
		return array(CONTROLLER_STATUS_OK);
		break;
		
	case 'authenticate':
		$affiliate_id = $action;
		if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['password']) ) {
			$ch = ez_ch::get_affiliate($affiliate_id);
			if( ez_ch::auth_encode($affiliate_id, $_REQUEST['password']) != $ch['password'] ) {
				$_SESSION['auth']['ch_auth_error'] = __("ch_bad_password");
				fn_redirect("charities.authenticate.$affiliate_id");
			}
			$_SESSION['auth']['charity_authenticated'][$affiliate_id] = true;
			if( !empty($_SESSION['auth']['user_id']) ) {	// Setup this user with MyAccount menu item
				ez_ch::set_ch_data(CH_TYPE_CHARITY_ADMIN, $_SESSION['auth']['user_id'], $affiliate_id);
			}
			fn_redirect("charities.request.$affiliate_id");
		}
		$html = '
<div class="ch-password-notification" >
	<form action="'.fn_url("charities.authenticate.$affiliate_id").'" method="post" id="charities_auth_form">';
		if( !empty($_SESSION['auth']['ch_auth_error']) ) {
			$html .= '<div class="ch-charity-auth-error">'.$_SESSION['auth']['ch_auth_error'].'</div>';
			unset($_SESSION['auth']['ch_auth_error']);
		}
		$html .= '
		<div class="ty-float-left ch-charity-password">
			'.ucwords(__("password")).':&nbsp;
			<input type="password" name="password" value="" />
		</div>
		<div class="ty-float-left">
			<input type="submit" name="dispatch[charities.authenticate.$affiliate_id]" value="'.__("login").'" />
		</div>
		<div class="ty-clear-both"></div>
	</form>
</div>	
';
		fn_set_notification('I', __("ch_charity_password"), $html, 'K');
		return array(CONTROLLER_STATUS_OK, "charities.list");
		
	case 'list':
		Registry::get('view')->assign('affiliates', ez_ch::get_affiliates());
		Registry::get('view')->assign('page_title', sprintf("%s", __("ch_charity_listing")) );
		return array(CONTROLLER_STATUS_OK);
	case 'assign':
		$auth =& $_SESSION['auth'];
		if( !empty($auth['user_id']) && !empty($_REQUEST['affiliate_id']) ) {
			ez_ch::assign_charity($_REQUEST['affiliate_id'], $auth['user_id']);
			$ch = ez_ch::get_affiliate($_REQUEST['affiliate_id']);
			$msg = str_replace('[%charity%]', $ch['short_name'], __("ch_notice_assigned"));
			fn_set_notification("N", __("notice"), $msg);
			if( defined('AJAX_REQUEST') )
				exit;
			return array(CONTROLLER_STATUS_OK, empty($_REQUEST['c_url']) ? "charities.list" : urldecode($_REQUEST['c_url']));
		}
		fn_set_notification("E", __("error"), "Internal error - no user/affiliate", 'K');
		if( defined('AJAX_REQUEST') )
			exit;
		return array(CONTROLLER_STATUS_OK, empty($_REQUEST['c_url']) ? "charities.list" : urldecode($_REQUEST['c_url']));
		
		
	case 'test':
		switch($action) {
			case 'show_auth':
				die("<pre>Auth:".print_r($_SESSION['auth'],true) );
			case 'clear_pass':
				unset($_SESSION['auth']['charity_authenticated']);
				fn_set_notification("N", __("notice"), "Cleared chartity password");
				fn_redirect("charities.list");
		}
		die("Unknown action '$action'");
}
die("Unknown mode '$mode'");
//		die("$controller.$mode&affiliate_id=$_REQUEST[affiliate_id] hit");
?>