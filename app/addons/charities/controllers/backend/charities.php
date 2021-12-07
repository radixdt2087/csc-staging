<?php

/*
 * Copyright 2015, 1st Source IT, LLC, EZ Merchant Solutions
 */
if( !defined('BOOTSTRAP') ) die('Access denied');

use Tygh\Registry;
use Tygh\Settings;

// Compatibility
$dir_addons = Registry::get('config.dir.addons');
$index_script = Registry::get('config.admin_index');

$v = explode(DIRECTORY_SEPARATOR, __FILE__);
$addon_name = $v[count($v)-4];

/* Every addon must have this to bootstrap the ez_common addon
if( file_exists($f=ez_fix_path($dir_addons."ez_common/lib/upgrade.php")) ) {
	require_once($f);
} else {	// Need to force update of the tools
	require_once(ez_fix_path($dir_addons."$controller/lib/upgrade/upgrade.php"));		// copy of common version of tools - used to bootstrap the ez_common addon
}
*/

switch($mode) {
	case 'log':
		if( class_exists('ez_log') ) {
			$log_lines = 10;
			$l_buf = array();
			switch($action) {
				case 'clear': 
					ez_log::clear_log($addon_name); 
					fn_set_notification('N', $controller, "Cleared log file");
					break;
				case 'truncate':
					$lines = (empty($_REQUEST['truncate_lines']) && $dispatch_extra) 
								? $dispatch_extra 
								: (empty($_REQUEST['truncate_lines']) ? 100 : $_REQUEST['truncate_lines']);
					$entries = ez_log::truncate_log($addon_name, $lines);
					fn_set_notification('N', $controller, "Truncated log to $entries lines");
					break;
				case 'show':
					$lines = min($log_lines, (empty($_REQUEST['show_lines']) && $dispatch_extra) 
								? $dispatch_extra 
								: (empty($_REQUEST['show_lines']) ? 100 : $_REQUEST['show_lines']));
					ez_log::show_log($addon_name, $lines);
					exit;
			}	// switch
		} else {	// function exists
			fn_set_notification('E', __("error"), "$mode not supported.", 'K');
		}
		break;
	case 'check_install':
		if( file_exists($f = Registry::get('config.dir.addons')."$addon_name/upgrade/post_upgrade_all_versions.php") )
			include($f);
		if( function_exists('addon_install_setup') ) {
			$result = addon_install_setup($controller);
			if( $result === false )
				$result = "Failed";
			else $result = sprintf("Okay: Current version=%s, next upgrade=%s", 
								   (empty($result['cur_ver'])?"not set":$result['cur_ver']),
								   (empty($result['next_upgrade'])?"next login":("after ".date("m/d/Y H:i:s",$result['next_upgrade'])))
								   );
			fn_set_notification($result == "Failed" ? 'E' : 'N', "$controller.$mode", $result, 'K');
		} else {	// function exists
			fn_set_notification('E', __("error"), "$mode not supported.", 'K');
		}
		fn_redirect($addon_name.".manage");	
		break;
	case 'version':
		if( function_exists('addon_current_version') ) {
			$ez_common = 'ez_common';
			addon_init($ez_common);
			$ez_version = addon_current_version($ez_common);
			if( empty($ez_version) )
				$ez_version = "not installed or missconfigured";
			addon_init($controller);
			$version = addon_current_version($controller);
			$msg = PRODUCT_NAME.": ".PRODUCT_VERSION." ".PRODUCT_EDITION."<br/>EZ Common: $ez_version<br/>$controller: $version";
			fn_set_notification('N', "$controller.$mode", $msg, true);
		} else {	// function exists
			fn_set_notification('E', __("error"), "$mode not supported.", 'K');
		}
		fn_redirect("addons.manage");
		break;
	case 'install_lang_vars':
		if( function_exists('addon_install_lang_vars') )
			addon_install_lang_vars($controller);
		else
			ch_install_lang_vars($action);
		fn_redirect("charities.manage");
		break;
		
	case 'manage':
		if( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_REQUEST['affiliates']) ) {
			foreach($_REQUEST['affiliates'] as $affiliate_id => $c_data) {
				if( $affiliate_id || $c_data['name'] )
					ez_ch::update_affiliate($c_data);
			}
			fn_set_notification("N", "$controller", "Affiliates saved");
			fn_redirect("$controller.$mode");
		} elseif( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_REQUEST['global']) ) {
			ez_ch::set_ch_data(CH_TYPE_GLOBAL, 0, $_REQUEST['global']);
			fn_set_notification('N', __("notice"), "Set globals");
		}
		
		$sorting = empty($_REQUEST['sorting']) ? '' : $_REQUEST['sorting'];
		$sort_by = $colation = '';
		if( $sorting )
			list($sort_by, $colation) = ez_ch::sorting($sorting);
		if( $colation && $colation == 'DESC' )
			$colation = 'ASC';
		else
			$colation = 'DESC';
		
		$affiliates = ez_ch::get_affiliates($sorting);
		$view = Registry::get('view');
		$view->assign('sort_by', $sort_by);
		$view->assign('colation', $colation);
		
		$view->assign('affiliates', $affiliates);
		$view->assign('total_commissions', ez_ch::total_earned());
		$view->assign('total_paid', ez_ch::total_paid());
		
		$global = ez_ch::get_ch_data(CH_TYPE_GLOBAL);
		$view->assign('global', $global);
		
		$default = empty($global['default']['affiliate_id']) ? 0 : $global['default']['affiliate_id'];
		$view->assign('default_affiliate', $default);
		
		$override = empty($global['override']['affiliate_id']) ? 0 : $global['override']['affiliate_id'];
		$view->assign('override_affiliate', $override);
		
		$view->assign('affiliate_menu', ez_ch::affiliate_menu('A', __("ch_not_set")));
		$view->assign('affiliate_status_menu', ez_ch::affiliate_status_menu());
		break;
		
	case 'detail':
		$affiliate_id = (integer)$action;
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			fn_trusted_vars (
				'affiliate'
				);
			$ch = ez_ch::update_affiliate($_REQUEST['affiliate']);
			$affiliate_id = $ch['affiliate_id'];
			if( empty($_REQUEST['affiliate']['affiliate_id']) ) {	// new affilaite
				$msg = __("ch_created_new_affiliate");
			} else {
				$msg = __("ch_updated_affiliate");
				ch_check_image_pairs(fn_attach_image_pairs('affiliate', 'charity', $ch['affiliate_id']));
			}
			fn_set_notification('N', __("notice"), $msg);
			fn_redirect("$controller.$mode.$affiliate_id");
		}
		$view = Registry::get('view');
		$affiliate = ez_ch::get_affiliate($affiliate_id);
		$view->assign('affiliate_status_menu', ez_ch::affiliate_status_menu());
		$view->assign('affiliate', $affiliate);
		$global = ez_ch::get_ch_data(CH_TYPE_GLOBAL);
		$view->assign('global', $global);
		break;
	case 'delete_affiliate':
		$affiliate_id = $action;
		ez_ch::delete_affiliate($affiliate_id);
		fn_set_notification('W', __("notice"), "Deleted affiliate", 'K');
		fn_redirect("charities.manage");
	case 'tracking':
		$tr_search = array('colation'=>'DESC',
						   'sort_by'=>'timestamp',
						   'order_id'=>'',
						   'from'=>empty($_REQUEST['posted']) ? '30 days ago' : '',
						   'to'=>'',
						   'status'=>'',
						   'affiliate_id'=>'',
						   'output'=>'screen',
						   'posted'=>'1',
						   'subtotal_only'=>0,
						   'pay_key' => 0
						   );
		$sort_menu = array('timestamp' => __("order_date"),
						   'affiliate_id' => __("ch_affiliate"),
						   'order_id' => __("order_id"),
						   'status' => __("status"),
						   'commission' => __('ch_item_commission')
						   );
						   
		foreach($tr_search as $k => &$v) {
			if( isset($_REQUEST[$k]) )
				$v = $_REQUEST[$k];
		}
		switch($tr_search['sort_by']) {
			case 'status':
			case 'order_id':
				$tr_search['colation'] = 'ASC';
		}

		if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			fn_redirect("$controller.$mode&amp;".http_build_query($tr_search));
		}

		$totals = $tracking = $affilaites = $total_commissions = array();
		if( !empty($tr_search['posted']) ) {
			$tracking = ez_ch::get_tracking($tr_search);
			$affiliates = ez_ch::get_affiliates();
			$totals = array('orders_count'=>0, 'orders_total'=>0, 'total_earned'=>0);
			foreach($tracking as &$tr) {
				$affiliate_id = $tr['affiliate_id'];
				$totals['orders_count']++;
				$totals['orders_total'] += $tr['order_subtotal'];
				$totals['total_earned'] += $tr['commission'];
				if( empty($total_commissions[$affiliate_id]['commission']) )
					$total_commissions[$affiliate_id] = array('commission'=>0, 'name'=>$affiliates[$affiliate_id]['name']);
				$total_commissions[$affiliate_id]['commission'] += $tr['commission'];
				if( $tr_search['output'] == 'csv' ) {
					$tr['name'] = $affiliates[$affiliate_id]['name'];
					
					$tr['date'] = date("m/d/Y H:i:s", $tr['timestamp']);
					unset($tr['timestamp'], $tr['affiliate_id']);
					$tr['commission'] = sprintf("%0.2f", $tr['commission']);
					$tr['pay_key'] = !empty($tr['pay_key']) ? date("m/d/Y", $tr['pay_key']) : '';
				}
			}
			if( $tr_search['output'] == 'csv' ) {
				ch_tracking_csv($tracking, $affiliates);
				exit;
			}
		} else {
			$tracking = array();
		}
		$view = Registry::get('view');
		$view->assign('tracking', $tracking);
		$view->assign('total_commissions', $total_commissions);
		$view->assign('tr_search', $tr_search);
		$view->assign('tr_status_menu', ez_ch::tracking_status_menu() );
		$view->assign('tr_sort_menu', $sort_menu);
		$view->assign('affiliates', ez_ch::get_affiliates() );
		$view->assign('affiliate_menu', ez_ch::affiliate_menu());
		$view->assign('totals', $totals);
		break;
		
	case 'payments':
	    $pay_search = array('colation'=>'DESC',
                                   'sort_by'=>'pay_key',
                                   'from'=>empty($_REQUEST['posted']) && empty($_REQUEST['pay_key']) ? '30 days ago' : '',
                                   'to'=>'',
                                   'affiliate_id'=>'',
                                   'output'=>'screen',
                                   'posted'=>'1',
                                   'pay_key' => 0
                                   );
        $sort_menu = array('pay_key' => __("ch_payment_date"),
                                   'affiliate_id' => __("ch_affiliate"),
                                   );
                                   
        foreach($pay_search as $k => &$v) {
            if( isset($_REQUEST[$k]) )
                $v = $_REQUEST[$k];
        }
        if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
            fn_redirect("$controller.$mode&amp;".http_build_query($pay_search));
        }

        $payments = $affiliates = $totals = array();
	    if( !empty($pay_search['posted']) || !empty($pay_search['pay_key']) ) {
            $payments = ez_ch::get_payments($pay_search);
            $affiliates = ez_ch::get_affiliates();
            $totals = array('orders_count'=>0, 'orders_total'=>0, 'total_paid'=>0);
            foreach($payments as $pd) {
                $totals['orders_count'] += $pd['orders_count'];
                $totals['orders_total'] += $pd['orders_total'];
                $totals['total_paid'] += $pd['commission'];
            }
	    }	
        $view = Registry::get("view");
        $view->assign('payments', $payments);
        $view->assign('affiliates', $affiliates);
        $view->assign('affiliate_menu', ez_ch::affiliate_menu());
		$view->assign('pay_search', $pay_search);
        $view->assign('totals', $totals);
	    break;
	    
	case 'mark_paid':
	    $affiliates = ez_ch::get_affiliates();
	    $params = $_REQUEST;
	    unset($params['dispatch'], $params['subtotal_only']);
	    $trs = ez_ch::get_tracking($params);
	    $params['status'] = CH_PAID_TRACKING;
	    $payments = array();
	    $pay_key = TIME;
	    $global = ez_ch::get_ch_data(CH_TYPE_GLOBAL);
	    $commission_rate = $global['commission'];
		$counts = array('processed'=>0, 'skipped'=>0, 'total_earned'=>0, 'total_paid'=>0);
	    foreach($trs as $tr) {
			if( $tr['status'] == CH_PAID_TRACKING ) {
				$counts['skipped']++;
				continue;
			}
	        $affiliate_id = $tr['affiliate_id'];
	        $af =& $affiliates[$affiliate_id];
	        $af['total_earned'] -= $tr['commission'];
	        $af['total_paid'] += $tr['commission'];
			
			$counts['total_earned'] += $tr['commission'];
			$counts['total_paid'] += $tr['commission'];
			$counts['processed']++;
			
			$order_id = $tr['order_id'];
	        if( empty($payments[$affiliate_id])) {
	            $payments[$affiliate_id]['commission'] = 0;
	            $payments[$affiliate_id]['orders_total'] = 0;
	            $payments[$affiliate_id]['orders_count'] = 0;
	        }
	        $payments[$affiliate_id]['commission'] += $tr['commission'];
	        $payments[$affiliate_id]['orders_total'] += $tr['order_subtotal'];
	        $payments[$affiliate_id]['orders_count']++;
	        $tr['status'] = CH_PAID_TRACKING;
			$tr['pay_key'] = $pay_key;
	        ez_ch::update_tracking($tr);
	    }
	    foreach($payments as $affiliate_id => &$pay_data) {
	        ez_ch::update_affiliate($affiliates[$affiliate_id]);
	        $pay_data['affiliate_id'] = $affiliate_id;
	        $pay_data['pay_key'] = $pay_key;
	        $pay_data['commission_rate'] = $commission_rate;
	        ez_ch::update_payment($pay_data);
	    }
		$msg = str_replace('%[processed]%', $counts['processed'], str_replace(
									'%[skipped]%', $counts['skipped'], str_replace(
											'%[total_earned]%', $counts['total_earned'], str_replace(
												'%[total_paid]%', $counts['total_paid'], __("ch_pay_notify")
												)
											)
									)
							);
		fn_set_notification('N', __("notice"), $msg, 'K');
	    fn_redirect(fn_url("$controller.payments").sprintf("&amp;pay_key=%d", $pay_key));
		break;
	

	case 'test':
		switch($action) {
			case 'process_order':
				if( !isset($_REQUEST['notest']) )
					fn_define('charities_testing', true);
				$order_id = $dispatch_extra ? $dispatch_extra : 91;
				$order_info = fn_get_order_info($order_id);
				ez_ch::process_order($order_info);
				exit;
			case 'process_all':
			    if( !isset($_REQUEST['notest']) )
			        fn_define('charities_testing', true);
			    $cnt = 0;
			    foreach( db_get_fields("SELECT order_id FROM ?:orders") as $order_id) {
			        $order_info = fn_get_order_info($order_id);
			        ez_ch::process_order($order_info);
			        $cnt++;
			    }
			    die("Processed $cnt orders");
			case 'show_order':
				$order_id = $dispatch_extra ? $dispatch_extra : 96;
				$o = fn_get_order_info($order_id);
				die("<pre>order $order_id: ".print_r($o,true) );
			case 'affiliates':
				ch_dbg("affiliates:".print_r(ez_ch::get_affiliates(),true), true);
				exit;
			
			case 'csv':
				fn_redirect("$controller.tracking?output=csv&amp;posted=1");
				exit;
			case 'clear_donations':
//				db_query("UPDATE ?:charities SET total_donated=0.0");
//				db_query("UPDATE ?:ez_charities_products SET total_commission_amount=0.0");
				db_query("TRUNCATE TABLE ?:ez_charities_tracking");
				db_query("DELETE FROM ?:ez_charities_data WHERE type=?s", CH_TYPE_ORDER);
				fn_set_notification("W", $action, "Truncated tracking/ch_data tables", 'K');
				fn_redirect("charities.manage");
				break;
			case 'show_data':
				$types = array(CH_TYPE_GLOBAL, CH_TYPE_USER, CH_TYPE_ORDER);
				printf("<pre>");
				foreach($types as $t) {
					$ar = db_get_array("SELECT * FROM ?:ez_ch_data WHERE type=?s", $type);
					$v = unserialize($ar['data']);
					$msg = sprintf("Type='%s', object_id=%d, data: ", $t, $ar['object_id']);
					switch($t) {
						case CH_TYPE_ORDER:
						case CH_TYPE_GLOBAL:
							$msg .= sprintf("%s", print_r($v,true));
							break;
						default:
							$msg .= sprintf("%d", $v);
					}
					printf("%s\n", $msg);
				}
				exit;
		}
		die("Unknown action '$action'");
		
	default:
		if( file_exists($f = ez_fix_path($dir_addons."ez_common/lib/std_addon_controller.php")) )
			include($f);
		break;
}
return array(CONTROLLER_STATUS_OK);
?>