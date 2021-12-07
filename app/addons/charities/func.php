<?php
/*****************************
* Copyright 2016, 2017, 2018 1st Source IT, LLC
* All rights reserved.
* Permission granted for use as
* long as this copyright notice, associated text and
* links remain in tact.
* Licensed for a single domain and a single instance of EZ-cart.
* Additional licenses can be purchased for additonal sites.
*
* http://www.ez-ms.com
* http://www.ez-om.com
* http://www.1sit.com*
*
* End copyright notification
*/

if ( !defined('AREA') ) { die('Access denied'); }
use Tygh\Registry;

if( !function_exists('ez_fix_path') ) {
	function ez_fix_path($s) {
		if( DIRECTORY_SEPARATOR != '/' )
			$s = str_replace('/', DIRECTORY_SEPARATOR, $s);
		return $s;
	}
}
function ch_dbg($s, $pre=false, $level=0) {return charities_dbg($s, $pre, $level);}
function charities_dbg($s, $pre=false, $level=0) {
	if( $pre )	printf("<pre>%s</pre>", htmlspecialchars($s));
	else ch_log($s, $level);
	return $s;
}

define('ch_logfile', dirname(__FILE__)."/charities.log");

function ch_log($s, $level=0) {
	static $first = true;
	if( false && class_exists('ez_log') ) {
		ez_log::add_msg('charities', $s, $level);
	} else {
		if( $level <= Registry::get('runtime.ch_log_level') ) {
			$buf = (array)Registry::get('runtime.ch_log');
			if( $first ) {
				register_shutdown_function('chlogit');
				$first = false;
				$s = sprintf("[%s] %s", date("m/d/Y H:i:s", TIME), $s);
			}
			$buf[] = $s;
			Registry::set('runtime.ch_log', $buf);
		}
	}
	return $s;
}

function chlogit() {
	$fname = ch_logfile;
	$buf = (array)Registry::get('runtime.ch_log');
	if($buf) {
		file_put_contents($fname, implode("\n", $buf)."\r\n", FILE_APPEND);
		Registry::set('runtime.ch_log', array());
	}
}


if( function_exists('addon_install_setup') ) {
	// Do the first installation stuff
	function charities_install_setup() {
		addon_install_setup('charities');
	}
	
	function charities_lang_vars() {
		$lang_vars = array();
		if( file_exists($f = ez_fix_path(Registry::get('config.dir.addons')."charities/lib/lang_vars.php")) )
			include($f);
		return $lang_vars;
	}
	
	function charities_hooks() {
		$hooks = array();
		if( file_exists($f = ez_fix_path(Registry::get('config.dir.addons')."charities/lib/hook_vars.php")) )
			include($f);
		return $hooks;
	}	// end function 
}	// function_exists

// Addon specific Hooks and functions go here


// Hooks

define('CH_NEW_TRACKING', 'N');
define('CH_PAID_TRACKING', 'P');
define('CH_STATUS_ACTIVE', 'A');
define('CH_STATUS_PENDING', 'P');
define('CH_STATUS_DECLINED', 'X');
define('CH_STATUS_DISABLED', 'D');

define('CH_TYPE_ORDER', 'O');
define('CH_TYPE_USER', 'U');
define('CH_TYPE_GLOBAL', 'G');
define('CH_TYPE_CHARITY_ADMIN', 'A');

define('CH_END_OF_DAY', (60*60*24)-1);

if( Registry::get('addons.charities.test_mode') == 'Y' ) {
	error_reporting(E_ALL);
	ini_set('display_errors', true);
}

class ez_ch {
	
	Public static function authenticate($affiliate_id, $password) {
		$ch = self::get_affiliate($affiliate_id);
		if( $ch && $ch['password'] == auth_encode($affiliate_id, $password) )
			return true;
		return empty($password) ? true : false;
	}
	
	Public static function auth_encode($affiliate_id, $password) {
		return fn_generate_salted_password($password, $affiliate_id);
	}
	
	Public static function get_affiliate($affiliate_id, $lang_code=DESCR_SL) {
		$cache = array();
		$global = self::get_ch_data(CH_TYPE_GLOBAL);
		$global_commission = $global['commission'];
		
		if( !empty($cache[$affiliate_id][$lang_code]) )
			return $cache[$affiliate_id][$lang_code];
		$data = db_get_row("SELECT a.*, ad.name, ad.short_name, ad.mission_statement, ad.seo_name, ad.description FROM ?:ez_charities_affiliates AS a
						   LEFT JOIN ?:ez_charities_affiliate_descriptions AS ad
						   		ON a.affiliate_id=ad.affiliate_id AND ad.lang_code=?s
						   WHERE a.affiliate_id=?i", $lang_code, $affiliate_id);
		if( empty($data) ) {	// unknown affiliate...  Deleted?
			$data = array('affiliate_id'=>$affiliate_id, 'name'=>'', 'short_name' => '', 'rate'=>$global_commission);
		} else {
			if( empty($data['rate']) )
				$data['rate'] = $global_commission;
			$data['extra'] = empty($data['extra']) ? array() : unserialize($data['extra']);
			$v = (array)fn_get_image_pairs($data['affiliate_id'], 'charity', 'M', false, true);
			$data['image'] = $v;
		}
		return $cache[$affiliate_id][$lang_code] = $data;
	}
	
	Public static function affiliate_menu($status='', $insert='', $lang_code=DESCR_SL) {
		$menu = array();
		if( $insert )
			$menu[''] = $insert;
			
		$condition = AREA == 'A' 
			? ($status 
					? "WHERE a.status='$status' " 
					: '' 
				)
			: "WHERE a.status='".($status ? $status : 'A')."'" ;
		$menu += db_get_hash_single_array("SELECT a.affiliate_id, ad.short_name 
											FROM ?:ez_charities_affiliates AS a
											LEFT JOIN ?:ez_charities_affiliate_descriptions AS ad
												ON a.affiliate_id=ad.affiliate_id AND ad.lang_code=?s
											$condition ORDER BY short_name ASC", array('affiliate_id', 'short_name'), $lang_code);
		return $menu;
	}
	
	Public static function affiliate_status_menu($insert='', $lang_code=DESCR_SL) {
		$menu = array(	CH_STATUS_PENDING 	=> __("ch_status_pending"),
						CH_STATUS_ACTIVE 	=> __("ch_status_active"),
						CH_STATUS_DISABLED	=> __("ch_status_disabled"),
						CH_STATUS_DECLINED 	=> __("ch_status_declined")
						);
		if( $insert )
			$menu = array_unshift($menu, array('' => __($insert)));
		return $menu;
	}
	
	Public static function sorting($sort_str) {
		$x = explode(':', $sort_str);
		$sort_by = empty($x[0]) ? '' : $x[0];
		$colation = empty($x[1]) ? 'ASC' : $x[1];
		return array($sort_by, $colation);
	}
	
	Public static function get_affiliates($sorting='', $lang_code=DESCR_SL) {
		$global = self::get_ch_data(CH_TYPE_GLOBAL);
		$global_commission = $global['commission'];
		
		$condition = AREA == 'A' ? '' : "WHERE a.status='A' ";
		list($sort_by, $colation) = self::sorting($sorting);
		if( !$sort_by )
			$sort_by = 'name';
			
		$affiliates = db_get_hash_array("SELECT a.*, ad.name, ad.short_name, ad.mission_statement, 
								 		ad.seo_name, ad.description FROM ?:ez_charities_affiliates AS a
								 		LEFT JOIN ?:ez_charities_affiliate_descriptions AS ad
												ON a.affiliate_id=ad.affiliate_id AND ad.lang_code=?s 
								 $condition ORDER BY $sort_by $colation", 'affiliate_id', $lang_code);
		foreach($affiliates as $affiliate_id => &$ch) {
			if( empty($ch['rate']) )
				$ch['rate'] = $global_commission;
			$ch['extra'] = empty($ch['extra']) ? array() : unserialize($ch['extra']);
			$v = (array)fn_get_image_pairs($ch['affiliate_id'], 'charity', 'M', false, true);
			$ch['image'] = $v;
		}
		return $affiliates;
	}
	
	Private static function filter_seo_name($seo_name) {
		$seo_name = preg_replace(';^charity-;', '', $seo_name);
		$from = array('_', ' ', "\t", "\n", "'", ";", ".", ",", "!", "?", "!");
		return trim(strtolower( str_replace($from, '-', $seo_name)), "\t\n\r\0\x0B-");
	}
	
	Public static function seo_prefix() {
		return strtolower(__("ch_charity"))."-";
	}
	
	Public static function update_affiliate($data) {
		if( !empty($data['seo_name']) )
			$data['seo_name'] = self::filter_seo_name($data['seo_name']);
			
		if( isset($data['extra']) )
			$data['extra'] = serialize($data['extra']);
			
		if( empty($data['affiliate_id']) ) {
			unset($data['affiliate_id']);
			$data['timestamp'] = TIME;
			$data['affiliate_id'] = db_query("INSERT INTO ?:ez_charities_affiliates ?e", $data);
//			db_query("INSERT INTO ?:ez_charities_affiliate_descriptions ?e", $data);
			// 5/13/18: Add copy to all languages when creating affiliate
			foreach(Registry::get('languages') as $lc => $junk) {
				$data['lang_code'] = $lc;
				db_query("INSERT INTO ?:ez_charities_affiliate_descriptions ?e", $data);
			}
		} else {					
			db_query("UPDATE ?:ez_charities_affiliates SET ?u WHERE affiliate_id=?i", $data, $data['affiliate_id']);
			db_query("UPDATE ?:ez_charities_affiliate_descriptions SET ?u WHERE affiliate_id=?i", $data, $data['affiliate_id']);
		}
		if( isset($data['stored_password']) && !empty($data['password']) && $data['stored_password'] != $data['password']) {	// new password
			$data['password'] = self::auth_encode($data['affiliate_id'], $data['password']);
			db_query("UPDATE ?:ez_charities_affiliates SET password=?s WHERE affiliate_id=?i", $data['password'], $data['affiliate_id']);
		}
		if( !empty($data['seo_name']) && !empty($data['affiliate_id']) ) {
			db_query("DELETE FROM ?:seo_names WHERE object_id=?i AND type='h' AND lang_code=?s",
					 								$data['affiliate_id'], 
													//self::seo_prefix().$data['seo_name'],
													DESCR_SL);
			$data['seo_name'] = preg_replace(';^'.self::seo_prefix().';', '', fn_create_seo_name($data['affiliate_id'], 'h', $data['seo_name']));
			db_query("UPDATE ?:ez_charities_affiliate_descriptions SET seo_name=?s WHERE affiliate_id=?i", $data['seo_name'], $data['affiliate_id']);
		}
		return $data;
	}
	Public static function get_ch_data($type, $object_id=0) {
		$s_data = db_get_field("SELECT data FROM ?:ez_charities_data WHERE type=?s AND object_id=?i", $type, $object_id);
		if( $s_data ) {
			$d = unserialize($s_data);
			if( $type == CH_TYPE_GLOBAL && $d && Registry::get('addons.charities.use_default') == 'N' )
				$d['default']['affiliate_id'] = 0;
			if( $type == CH_TYPE_GLOBAL && $d && Registry::get('addons.charities.use_override') == 'N' )
				$d['override']['affiliate_id'] = 0;
			return $d;
		}
		return false;
	}
	
	Public static function affiliate_status($affiliate_id, $status=CH_STATUS_ACTIVE) {
		$ch = self::get_affiliate($affiliate_id);
		return !empty($ch['status']) ? $ch['status'] == $status : false;
	}
	
	Public static function set_ch_data($type, $object_id, $data) {
		$s_data = serialize($data);
		$v = array('type'=>$type, 'object_id'=>$object_id, 'data'=>$s_data);
		db_query("REPLACE INTO ?:ez_charities_data ?e", $v);
		return $data;
	}
	
	Public static function delete_affiliate($affiliate_id, $tracking=false) {
		db_query("DELETE FROM ?:ez_charities_affiliates WHERE affiliate_id=?i", $affiliate_id);
		db_query("DELETE FROM ?:ez_charities_affiliate_descriptions WHERE affiliate_id=?i", $affiliate_id);
		if( $tracking )
			db_query("DELETE FROM ?:ez_charities_tracking WHERE affiliate_id=?i", $affiliate_id);
		fn_delete_image_pairs($affiliate_id, 'charity');
	}
	
	Public static function get_payment($pay_key) {
	    $pay_cache = array();
	    if( !empty($pay_cache[$pay_key]) )
	        return $pay_cache[$pay_key];
	        
	   return $pay_cache[$pay_key] = db_get_row("SELECT * FROM ?:ez_charities_payments WHERE pay_key=?i", $pay_key);
	}
	
	Public static function get_payments($params) {
	    $conditions = 'WHERE 1';
	    if( !empty($params['pay_key']))
	        $conditions .= sprintf(" AND pay_key=%d", $params['pay_key']);
	    if( !empty($params['affiliate_id']))
	      $conditions .= sprintf(" AND affiliate_id=%d", $params['affiliate_id']);
	    if( !empty($params['from']))
	        $conditions .= sprintf(" AND pay_key>=%d", strtotime($params['from']));
	    if( !empty($params['to']))
            $conditions .= sprintf(" AND pay_key<=%d", strtotime($params['to']) + CH_END_OF_DAY);
        return db_get_array("SELECT * FROM ?:ez_charities_payments $conditions");
	}
	
	Public static function update_payment($data) {
	    if( empty($data['pay_key'])) {
	        unset($data['pay_key']);
	        $data['pay_key'] - db_query("REPLACE INTO ?:ez_charities_payments ?e", $data);
	    } else {
	        db_query("REPLACE INTO ?:ez_charities_payments ?e", $data);
	    }
	    return $data;
	}
	
	Public static function delete_payment($pay_key) {
	    db_query("DELETE FROM ?:ez_charities_payements WHERE pay_key=".$pay_key);
	}
	        
	Public static function total_earned($from=0, $to=0) {
		static $v = null;
		if( $v !== null )
			return $v;
		$condition = '';
		if( $from )
			$condition = "WHERE timestamp >= $from";
		if( $to )
			$condition .= $condition ? " AND timestamp <= $to"
									 : "WHERE timestamp <= $to";
		return $v = fn_format_price(db_get_field("SELECT SUM(total_earned) as total FROM ?:ez_charities_affiliates $condition"));
	}
	
	Public static function total_paid($from=0, $to=0) {
		static $v = null;
		if( $v !== null )
			return $v;
		$condition = '';
		if( $from )
			$condition = "WHERE timestamp >= $from";
		if( $to )
			$condition .= $condition ? " AND timestamp <= $to"
									 : "WHERE timestamp <= $to";
		return $v = fn_format_price(db_get_field("SELECT SUM(total_paid) as total FROM ?:ez_charities_affiliates $condition"));
	}
		
	Public static function update_tracking($data) {
		if( empty($data['order_id']) )
			die(__FUNCTION__.": missing order_id");
		return db_query("REPLACE INTO ?:ez_charities_tracking ?e", $data);
	}
	
	Public static function get_tracking($params) {
		$subtotal_only = false;
		$colation = "DESC";
		if( !empty($params['colation']) ) {
			$colation = $params['colation'];
		}
		$sort_by = "ORDER BY timestamp $colation";
		if( !empty($params['sort_by']) ) {
			$sort_by = "ORDER BY $params[sort_by] $colation";
		}
		$where = 'WHERE 1';
		if( !empty($params['order_id']) ) {
			$where .= " AND order_id=$params[order_id]";
		}
		if( !empty($params['from']) ) {
			$where .= " AND timestamp >= ".strtotime($params['from']);
		}
		if( !empty($params['to']) ) {
			$where .= " AND timestamp <= " .(strtotime($params['to']) + CH_END_OF_DAY);
		}
		if( !empty($params['pay_key'])) {
		    $where .= " AND pay_key=$params[pay_key]";
		}
		if( !empty($params['status']) ) {
			$where .= " AND status='$params[status]'";
		}
		if( !empty($params['affiliate_id']) ) {
			$where .= " AND affiliate_id=$params[affiliate_id]";
		}
		if( !empty($params['subtotal_only']) ) {
			$subtotal_only = $params['subtotal_only'];
		}
		
		if( !$subtotal_only )
			return db_get_array("SELECT * FROM ?:ez_charities_tracking $where $sort_by");
			
		// Build into subtotals by affiliate
		$base = array('affiliate_id'=>0, 'order_id'=>"All", 'timestamp'=>0, 
					  'status'=>(empty($params['status'])?"N":$params['status']),
					  'order_subtotal'=>0, 'commission'=>0, 'commission_rate'=>'N/A',
					  'pay_key'=>0, 'commission_from'=>'N/A'
					  );
		$tr = array();
		$tracking = db_get_array("SELECT * FROM ?:ez_charities_tracking $where $sort_by");
		foreach($tracking as $tr_data) {
			$id = $tr_data['affiliate_id'];
			if( empty($tr[$id]) ) {
				$tr[$id] = $base;
				$tr[$id]['affiliate_id'] = $id;
			}
			$tr[$id]['timestamp'] = max($tr_data['timestamp'], $tr[$id]['timestamp']);
			$tr[$id]['order_subtotal'] += $tr_data['order_subtotal'];
			$tr[$id]['commission'] += $tr_data['commission'];
			$tr[$id]['pay_key'] = max($tr_data['pay_key'], $tr[$id]['pay_key']);
		}
		ksort($tr);
		return $tr;
	}
	
	Public static function delete_tracking($order_id) {
		$params = array('order_id'=>$order_id);
		// Ensure affiliate amounts are debited correctly
		$tr = self::get_tracking($params);
		foreach($tr as $row) {
			// First do the affiliate
			$ch = self::get_affiliate($row['affiliate_id']);
			// 10/20/15 - bug - changed from total_commission to total_earned.
			if( isset($ch['total_earned']) )
				$ch['total_earned'] = max(0, $ch['total_earned'] - $row['commission']);
			else
				$ch['total_earned'] = 0;
			// 10/20/15 - bug - Changed from update_commission to update_affiliate
			self::update_affiliate($ch);
			
			db_query("DELETE FROM ?:ez_charities_tracking WHERE order_id=?i",
					 $row['order_id']);
			db_query("DELETE FROM ?:ez_charities_data WHERE type=?s AND object_id=?i",
					 CH_TYPE_ORDER, $row['order_id']);
			ch_log("Deleted tracking for order_id={$row['order_id']}");
		}
	}
	
	
	Public static function id_to_code($product_id) {
		static $ids = array();
		if( !empty($ids[$product_id]) )
			return $ids[$product_id];
		return $ids[$product_id] = db_get_field("SELECT product_code FROM ?:products WHERE product_id=?i",
												$product_id);
	}
	Public static function code_to_id($product_code) {
		static $codes = array();
		if( isset($codes[$product_code]) )
			return $codes[$product_code];
		return $codes[$product_code] = db_get_field("SELECT product_id FROM ?:products WHERE product_code=?s", $product_code);
	}
	
	Public static function commission_value($amount, $commission) {
		if( strpos($commission, '%') !== false ) {
			return $amount * (floatval($commission)/100);
		} 
		return $commission;
	}
	
	Public static function tracking_status_menu() {
		return array('N'=>__("new"), 'P'=>__("ch_paid"));
	}
	
	Private static function order_gift_certificate_value(&$o) {
		$amt = 0;
		if( !empty($o['use_gift_certificates']) ) {
			foreach($o['use_gift_certificates'] as $gc_id => $gc_info) {
				$amt += $gc_info['amount'];
			}
		}
		return $amt;
	}
	
	Public static function process_order(&$order, $force_charity=false) {
		$order_id = $order['order_id'];
		$gift_cert_amount = self::order_gift_certificate_value($order);
		$discount = (empty($order['subtotal_discount']) ? 0 : $order['subtotal_discount'])
					/* +
					(empty($order['discount']) ? 0 : $order['discount'])*/ ;
		$commission_gross = max(0, $order['subtotal'] - $gift_cert_amount - $discount);
		fn_set_hook('charities_process_order_commision', $order, $commission_gross);
$pre = defined('charities_testing');
		$global = self::get_ch_data(CH_TYPE_GLOBAL);
		$default = $global['default']['affiliate_id'];
		$override = empty($global['override']['affiliate_id']) ? 0 : $global['override']['affiliate_id'];
		$user = empty($order['user_id']) ? 0 : (integer)self::get_ch_data(CH_TYPE_USER, $order['user_id']);

		if( $user && !ez_ch::affiliate_status($user, CH_STATUS_ACTIVE) ) {
			$bad_ch = ez_ch::get_affiliate($user);
			fn_set_notification('E', __("warning"), str_replace('[%SHORT_NAME%]', $bad_ch['short_name'], __("ch_inactive_charity_message")), 'K');
			$user = $default;
		}
		if( !$user )	// Couldn't find user charity (deleted?)
			$user = $default;
		if( !$override && $force_charity )
			$user = $force_charity;
		$ch_user = ez_ch::get_affiliate($user);
		
		$rate = $global['commission'] ? $global['commission'] : 0;
		$commission_from = Registry::get('addons.charities.commission_from');
		// 12/12/18: $user is an id, not a structure.  Change to use $ch_user from about.
		if( $commission_from == 'charity' && !empty($ch_user['rate']) )
			$rate = $ch_user['rate'];
		$commission = fn_format_price(self::commission_value($commission_gross, $rate));
		$_SESSION['auth']['charities']['affiliate_id'] = $user;
			
		// 12/16/15: Change to only use current() when tr is not empty.
		$tr = self::get_tracking(array('order_id'=>$order_id));
		if( !empty($tr) )
			$tr = current($tr);
			
		if(defined('charities_testing')) {
        }
		$prior_commission = empty($tr['commission']) ? 0 : $tr['commission'];
		$prior_affiliate_id = empty($tr['affiliate_id']) ? 0 : $tr['affiliate_id'];
		$prior_status = empty($tr['status']) ? '' : $tr['status'];
		$tr = array_merge($tr, array('order_id'=> $order['order_id'],
										'timestamp'=>$order['timestamp'],
										'status' => CH_NEW_TRACKING,
										'order_subtotal' =>  $commission_gross,
										'commission'=>$commission,
										'affiliate_id' => 0,
										'commission_rate'=>$rate,
										'commission_from'=>$commission_from
									)
					);

		$ch = array();
		if( $override ) {	// override everything
			$ch = self::get_affiliate($override);
		} elseif( $user ) {
			$ch = self::get_affiliate($user);
		} elseif( $default ) {	// use default
			$ch = self::get_affiliate($default);
		}
		if( $ch ) {
			// Don't update rate
			$ch['total_earned'] += $commission;
			if( $prior_affiliate_id && $prior_affiliate_id != $ch['affiliate_id'] ) {
				$old_ch = self::get_affiliate($prior_affiliate_id);
				if( $old_ch ) {
					$old_ch['total_earned'] -= $prior_commission;
					self::update_affiliate($old_ch);
				}
			} else {	// same affiliate
				$ch['total_earned'] -= $prior_commission;			
			}
			unset($ch['rate']);
			self::update_affiliate($ch);
		}
		$tr['affiliate_id'] = empty($ch['affiliate_id']) ? 0 : $ch['affiliate_id'];
		fn_set_hook('charities_process_order_tracking', $order, $tr);
		self::update_tracking($tr);
if( $pre ) ch_dbg("\ttracking:".print_r(current(self::get_tracking(array('order_id'=>$order['order_id']))),true), $pre);
	}
	
	Public static function pay_tracking(&$t_ar) {
		$affiliate_data = array();
		$total_payments = array();
		foreach($t_ar as &$tr) {
			$a_id = $tr['affiliate_id'];
			if( empty($affiliate_data[$a_id]) ) {
				$affiliate_data[$a_id] = $ch = self::get_affiliate($a_id);
				$tr['status'] = CH_STATUS_PAID;
				$affilaite_data[$a_id]['total_earned'] = max(0, $ch['total_earned'] - $tr['commission']);
				$affiliate_data[$a_id]['total_paid'] += $tr['commission'];
				// Totals for this operation only
				if( empty($total_payments[$a_id]) )
					$total_payments[$a_id] = $tr['commission'];
				else
					$total_payments[$a_id] += $tr['commission'];
			}
		}	// foreach t_ar
		
		// Update the affiliates
		foreach($affiliate_data as $a_id => $ch) {
			self::update_affiliate($ch);
			ch_log(sprintf("Paid affiliate '%s', \$%0.2F ", $ch['short_name'], $total_payments[$a_id]));
		}
		return $total_payments;
	}		
	
	Public static function mark_affiliate_paid($affiliate_id, $start_time="", $end_time="") {
		if( empty($start_time) )
			$start_time = strtotime("last month");
		else
			$start_time = strtotime($start_time);
		if( empty($end_time) )
			$end_time = strtotime("today");
		else
			$end_time = strtotime($end_time) + CH_END_OF_DAY;
			
		$cur_earned = (float)db_get_field("SELECT total_earned FROM ?:ez_charities_affiliates WHERE affiliate_id=?i", $affiliate_id);
		$cur_track_commission = (float)db_get_field("SELECT SUM(commission) 
												FROM ?:ez_charities_tracking 
												WHERE affiliate_id=?i 
													AND timestamp >= ?i 
													AND timestamp <= ?i",
														 $affiliate_id, $start_time, $end_time);
		$earned = $cur_earned - $cur_track_commission;
		db_query("UPDATE ?:ez_charities_affiliates SET total_paid=total_paid+?d, total_earned=?d WHERE affiliate_id=?i", $cur_track_commission, $earned, $affiliate_id);
		db_query("UPDATE ?:ez_charities_tracking SET status=?s WHERE affiliate_id=?i
				 	AND timestamp >= ?i 
					AND timestamp <= ?i",
				 CH_NEW_TRACKING, $affiliate_id, $start_time, $end_time);
		return $cur_track_commission;
	}	
	
	Public static function assign_charity($affiliate_id, $user_id) {
		self::set_ch_data(CH_TYPE_USER, $user_id, $affiliate_id);
		if( isset($_SESSION['auth']['user_id']) && $_SESSION['auth']['user_id'] == $user_id )
			$_SESSION['auth']['charities']['affiliate_id'] = $affiliate_id;
		return $affiliate_id;
	}
} // class ez_ch

function ch_install_lang_vars($force=false) {
	include(dirname(__FILE__)."/lib/lang_vars.php");
	$cnt = 0;
	if( !empty($lang_vars) ) {
		$langs = db_get_fields("SELECT lang_code FROM ?:languages");
		if( empty($langs) )
			$langs = array('en');

		foreach($lang_vars as $var => $val) {
			if( $force || (!($cur = db_get_field("SELECT name FROM ?:language_values WHERE name=?s AND lang_code='en'", $var))) ) {
				$cnt++;
				foreach($langs as $lang) {
					$dat = array('lang_code' => $lang, 'name' => $var, 'value' => $val);
					db_query("REPLACE INTO ?:language_values ?e", $dat);
				}
			}
		}
	}
	if( $cnt ) {
		$msg = sprintf("Installed %d language variables into %d languages", $cnt, count($langs));
		fn_set_notification('N', __("notice"), $msg, 'K');
	}
}


// Check images for max_image_size and if outside, resize and update DB
function ch_check_image_pairs($pair_ids) {
	if( empty($pair_ids) )
		return;
	$max_image_size = Registry::ifGet('addons.charities.max_image_size', '2048');
	if( !$max_image_size )	// no limit
		return;
		
	foreach($pair_ids as $pair_id) {
		$pair_data = db_get_row("SELECT image_id, detailed_id FROM ?:images_links WHERE pair_id=?i", $pair_id);
		if( !empty($pair_data['image_id']) ) {
			$xy = db_get_row("SELECT * FROM ?:images WHERE image_id=?i", $pair_data['image_id']);
			if( $xy['image_x'] > $max_image_size || $xy['image_y'] > $max_image_size ) {
				ch_resize_image($xy, $max_image_size);
				fn_set_notification('W', __("notice"), str_replace('[%SIZE%]', $max_image_size, __("ch_image_resize_msg")), 'K');
			}
		}
		if( !empty($pair_data['detailed_id']) ) {
			$xy = db_get_row("SELECT * FROM ?:images WHERE image_id=?i", $pair_data['detailed_id']);
			if( $xy['image_x'] > $max_image_size || $xy['image_y'] > $max_image_size ) {
				ch_resize_image($xy, $max_image_size);
				fn_set_notification('W', __("notice"), str_replace('[%SIZE%]', $max_image_size, __("ch_image_resize_msg")), 'K');
			}
		}
	}	// foreach
}

function ch_resize_image($xy, $max) {
	$path = sprintf("%s/%s/%s", 'images/detailed', floor($xy['image_id'] / MAX_FILES_IN_DIR), $xy['image_path']);
	$factor = $xy['image_x'] > $max ? ($max/$xy['image_x']) : 1;
	$factor = min($factor, $xy['image_y'] > $max ? ($max/$xy['image_y']) : 1);
	$x = $xy['image_x'] * $factor;
	$y = $xy['image_y'] * $factor;
	fn_resize_image($path, $x, $y);
	db_query("UPDATE ?:images SET image_x=?i, image_y=?i WHERE image_id=?i", $x,$y,$xy['image_id']);
}

function ar_load_default_charties() {
	if( file_exists($a = dirname(__FILE__)."/lib/database/ez_charities_affiliates.sql") ) {
		if( !db_get_field("SELECT count(*) FROM ?:ez_charities_affiliates WHERE 1") ) {
			$data = db_quote(file_get_contents($a));
			db_query($data);
		}
	}
			
	if( file_exists($ad = dirname(__FILE__)."/lib/database/ez_charities_affiliate_descriptions.sql") ) {
		if( !db_get_field("SELECT count(*) FROM ?:ez_charities_affiliate_descriptions WHERE 1") ) {
			$data = db_quote(file_get_contents($ad));
			db_query($data);
		}
	}
	$pair_sources = array(1=>'/media/images/addons/charities/the-neo-fund-logo.png',
						 2=>'/media/images/addons/charities/tip_stacked_logo_small1.jpg');
	
	//list($theme_path, $theme_name) = fn_get_customer_layout_theme_path();
	// force to use responsive directly from themes_repository.
	$theme_path = "var/themes_repository/responsive";
	foreach($pair_sources as $object_id => $src_file) {
		$src = str_replace(DIR_ROOT.'/', '', $theme_path.$src_file);
		list($x, $y, $mime_type) = fn_get_image_size($src);
		$image_basename = basename($src);
		$image_data = array('image_x'=>$x, 'image_y'=>$y, 'image_path'=>$image_basename);
		$image_id = db_query("INSERT INTO ?:images ?e", $image_data);
		
		$path = "detailed/" . floor($image_id / MAX_FILES_IN_DIR);
		fn_copy($src, "images/$path/$image_basename");
		
		$pair_data = array('object_id'=>$object_id, 
						   'object_type'=>'charity', 
						   'image_id'=>0, 'detailed_id'=> $image_id,
						   'type'=>'M');
		db_query("INSERT INTO ?:images_links ?e", $pair_data);
	}
	
	$seo_names = array(1=>ez_ch::seo_prefix().'theneofund', 2=>ez_ch::seo_prefix().'tipnw');
	foreach($seo_names as $object_id => $name) {
		$data = array('name'=>$name, 'object_id'=>$object_id, 'company_id'=>0, 'type'=>'h', 'lang_code'=>'en');
		db_query("REPLACE INTO ?:seo_names ?e", $data);
	}
}
	

// Hooks

// If process_on == place_order
function fn_charities_place_order($order_id, $action, $order_status, &$cart, &$auth) {
	if( $action == 'save' )
		return;	// Admin edit.
	$order_info = fn_get_order_info($order_id);
	ez_ch::process_order($order_info);
}
// Or if process_on == status
function fn_charities_change_order_status($to, $from, &$order_info, $force_notification, $order_statuses, $place_order) {
	if( defined('charities_testing') ) {
		fn_define('DEVELOPMENT', true);
		error_reporting(E_ALL);
		ini_set('display_errors', true);
	}
	$ok_orders = array('P', 'O');	// status where can apply (not F, D, B, or N)
	$no_contribution = array('F', 'D', 'I');	// failed, declined or canceled
	$contribute = in_array($to, $ok_orders) || !in_array($to, $no_contribution);
	// only update if not already exists.
	$tr = ez_ch::get_tracking(array('order_id'=>$order_info['order_id']) );
	if( $contribute ) {
		ez_ch::process_order($order_info);
	} else {
		ez_ch::delete_tracking($order_info['order_id']);
	}
}
function fn_charities_get_order_info(&$order, &$additional_data) {
	// 10/19/16: Added check of $order
	if( empty($order) )
		return;
	$tr_data = current( ez_ch::get_tracking(array('order_id'=>$order['order_id'])) );
	if( !empty($tr_data['affiliate_id']) )
		$tr_data['affiliate'] = ez_ch::get_affiliate($tr_data['affiliate_id']);
	$order['charities'] = $tr_data;
}

function fn_charities_fill_auth(&$auth, &$user_data, $area, &$original_auth) {
	if( $area == 'C' ) {
		$global = ez_ch::get_ch_data(CH_TYPE_GLOBAL);
		$default = empty($global['default']['affiliate_id']) ? 0 : $global['default']['affiliate_id'];
		if( empty($auth['user_id']) ) {	// not logged in
			$auth['charities']['affiliate_id'] = $default;
		} else {
			// logged in and charity selected
			$user = ez_ch::get_ch_data(CH_TYPE_USER, $auth['user_id']);
			if( $user && !ez_ch::affiliate_status($user, CH_STATUS_ACTIVE) ) {
				$bad_ch = ez_ch::get_affiliate($user);
				fn_set_notification('E', __("warning"), str_replace('[%SHORT_NAME%]', $bad_ch['short_name'], __("ch_inactive_charity_message")), 'K');
				$user = $default;
			}
			$auth['charities']['affiliate_id'] = $user ? $user : $default;
			$auth['charities']['admin']['affiliate_id'] = ez_ch::get_ch_data(CH_TYPE_CHARITY_ADMIN, $auth['user_id']);
		}
	}
}

function fn_charities_create_seo_name_pre($object_id, $object_type, &$object_name) {
	if( $object_type == 'h' )
		if( !preg_match(';^'.ez_ch::seo_prefix().';', $object_name) )	// Not already set
			$object_name = ez_ch::seo_prefix().$object_name;
}
?>