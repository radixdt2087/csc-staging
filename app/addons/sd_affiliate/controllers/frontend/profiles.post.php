<?php
 use Tygh\Registry; use Tygh\Enum\AffiliateUserTypes; 
 defined('BOOTSTRAP') or die('Access denied'); 
 if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
 	if ($mode == 'save_custom_affiliate_parameter') { 
 		if (!empty($_REQUEST['custom_affiliate_parameter'])) { 
 			if (!empty($auth['user_id']) && $auth['user_type'] == AffiliateUserTypes::PARTNER) { sd_MDk4YjFmYTdkOWQzMjI0ZjNjZDM2Mzhj( $_REQUEST['custom_affiliate_parameter'], $auth['user_id'] ); } 
 		} else { fn_set_notification('E', __('error'), __('addons.sd_affiliate.empty_custom_affiliate_parameter')); 
 		} 
 			return [CONTROLLER_STATUS_REDIRECT, 'profiles.update&selected_section=affiliates']; 
 		} return; 
 		} if ($mode == 'update' || $mode == 'add') { 
 			 
 			$user_type = (isset($_REQUEST['user_type'])) ? $_REQUEST['user_type'] : $auth['user_type']; 
 			$profile_fields = fn_get_profile_fields($user_type); 

 			$server = $_SERVER['SERVER_NAME'];
 			$subdomain = explode(".", $server);
 			$getSubdomain=db_get_array("SELECT * FROM ?:wk_vendor_subdomain WHERE subdomain = '".$subdomain[0]."'");

 			if(!empty($getSubdomain)){
				$vendor_id = $getSubdomain[0]['company_id'];
 			}else{
 			  	$vendor_id = "";
 			}
 		    
 			Tygh::$app['view']->assign(['profile_fields' => $profile_fields, 'vendor_id' => $vendor_id]); 

 			if ($user_type == AffiliateUserTypes::PARTNER && !empty($auth['user_id']) && $mode == 'update') { 
 				$is_approved = sd_Mzc5Y2M4OWVmNjZlYWU0NzA5ZDRkY2Ez($auth['user_id']); 
 				$general_affiliate_parameter = Registry::get('addons.sd_affiliate.custom_affiliate_parameter'); 
 				if ($is_approved && !empty($general_affiliate_parameter)) { 
 					Registry::set('navigation.tabs.affiliates', [ 'title' => __('affiliates_partnership'), 'js' => true ]); 
 					Tygh::$app['view']->assign( 'custom_affiliate_parameter', sd_NjIxNWQ3YzM3ZTMzZTFiNDgxYmIxMTAx($auth['user_id']) ); 
 				} Tygh::$app['view']->assign('general_affiliate_parameter', $general_affiliate_parameter); 
 			} 
 		} elseif ($mode == 'check_custom_affiliate_parameter') { 
 			if (!empty($_REQUEST['affiliate_parameter'])) {
 			 sd_MDZlMGM3ODdiZTYwMWMzZGI0MWQ2OWM4($_REQUEST['affiliate_parameter'], true); 
 			} else { 
 				fn_set_notification('E', __('error'), __('addons.sd_affiliate.empty_custom_affiliate_parameter')); 
 			} die(); 
 		} elseif ($mode == 'delete_custom_affiliate_parameter') {
 			 if (!empty($auth['user_id'])) {
 			  sd_MDk4YjFmYTdkOWQzMjI0ZjNjZDM2Mzhj('', $auth['user_id']); 
 			} return [CONTROLLER_STATUS_REDIRECT, 'profiles.update&selected_section=affiliates']; 
 		} 