<?php

/******************************************************************
# Vendor Kyc   ---      Vendor KYC                        *
# ----------------------------------------------------------------*                                   *
# copyright Copyright (C) 2010 webkul.com. All Rights Reserved.   *
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL     *
# Websites: http://webkul.com                                     *
*******************************************************************
*/
use Tygh\Registry;
use Tygh\Settings;
use Tygh\Mailer;
use Tygh\Navigation\LastView;
if (!defined('BOOTSTRAP')) { die('Access denied'); }

$wk_kyc_setting_data=fn_wk_vendor_kyc_get_setting_data();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	  if (!empty($dispatch_extra)) {
        if (!empty($_REQUEST['approval_data'][$dispatch_extra])) {
            $_REQUEST['approval_data'] = $_REQUEST['approval_data'][$dispatch_extra];
        }
    }
    //Add KYC Tpye
	if($mode == 'add_kyc_type'){
		$kyc_id=0;
		if (isset($_REQUEST['wk_vendor_kyc'])&& !empty($_REQUEST['wk_vendor_kyc'])) {
			$data=array(
				'status'=>'A',
				);
			if(isset($_REQUEST['wk_vendor_kyc']['is_required']))
			{
				$data['is_required']=$_REQUEST['wk_vendor_kyc']['is_required'];
			}else
			{
				$data['is_required']='N';
			}
			if(isset($_REQUEST['wk_vendor_kyc']['kyc_id'])&& !empty($_REQUEST['wk_vendor_kyc']['kyc_id']))
			{
				db_query("UPDATE ?:wk_vendor_kyc_type SET ?u WHERE kyc_id=?i",$data,$_REQUEST['wk_vendor_kyc']['kyc_id']);
				$kyc_id=$_REQUEST['wk_vendor_kyc']['kyc_id'];
			}
			else
			{
				$kyc_id=db_query("INSERT INTO ?:wk_vendor_kyc_type ?e",$data);
        	}
        	$data=array(
					'object_id'=>$kyc_id,
					'description'=>$_REQUEST['wk_vendor_kyc']['kyc_type']
				);
        	$check_description_existance=db_get_field("SELECT object_id FROM ?:wk_vendor_kyc_type_description WHERE object_id=?i AND lang_code =?s",$kyc_id,CART_LANGUAGE);
        	if(!empty($check_description_existance)){
        		db_query("UPDATE ?:wk_vendor_kyc_type_description SET ?u WHERE object_id=?i AND lang_code=?s",$data,$kyc_id,CART_LANGUAGE);
        	}
        	else{
        		foreach (fn_get_translation_languages() as $data['lang_code'] => $_v){
           			$kyc_id=db_query("INSERT INTO ?:wk_vendor_kyc_type_description ?e",$data);
        		}
        	}
   //      	if(!empty($kyc_id)){
			// 	return array(CONTROLLER_STATUS_OK, 'wk_vendor_kyc.update_kyc_type?kyc_id='.$kyc_id);
			// }
		}
		return array(CONTROLLER_STATUS_REDIRECT, "wk_vendor_kyc.kyc_type");
	}
	//Delete KYC Tpye
	if($mode =='kyc_type_delete'){
		if (isset($_REQUEST['kyc_id'])&& !empty($_REQUEST['kyc_id'])) {
			fn_wk_vendor_kyc_delete_kyc_type($_REQUEST['kyc_id']);
			fn_set_notification('N', __('notice'), __('kyc_deleted_sucessfully'));
		}
		return array(CONTROLLER_STATUS_REDIRECT, "wk_vendor_kyc.kyc_type");
	}
	//Delete Multiple KYC
	if($mode =='m_kyc_type_delete'){
		if (isset($_REQUEST['kyc_type_ids'])&& !empty($_REQUEST['kyc_type_ids'])) {
			foreach ($_REQUEST['kyc_type_ids'] as $key => $kyc_id) {
				fn_wk_vendor_kyc_delete_kyc_type($kyc_id);
			}
			fn_set_notification('N', __('notice'), __('kycs_deleted_sucessfully'));
		}
		return array(CONTROLLER_STATUS_REDIRECT, "wk_vendor_kyc.kyc_type");
	}
	//Upload KYC
	if($mode =='upload_kyc')
	{
		if(isset($_REQUEST['upload_kyc_data']) && !empty($_REQUEST['upload_kyc_data']) && isset($_REQUEST['file_kyc_attach_file']) && !empty($_REQUEST['file_kyc_attach_file'])){
			$kyc_id=fn_wk_vendor_kyc_upload_kyc($_REQUEST,null);
			fn_set_notification('N', __('notice'), __('kyc_uploaded_sucessfully'));
			if(!empty($kyc_id)){
				return array(CONTROLLER_STATUS_OK, 'wk_vendor_kyc.upload_kyc?kyc_id='.$kyc_id);
			}
		}
	}
	//Download KYC
	if ($mode == 'download') {
		if (isset($_REQUEST['kyc_id'])&& !empty($_REQUEST['kyc_id'])) {
			fn_wk_vendor_kyc_download_kyc_file($_REQUEST['kyc_id']);
		}
		return array(CONTROLLER_STATUS_OK, 'wk_vendor_kyc.manage');
	}
	//Delete KYC
	if ($mode == 'delete') {
		if (isset($_REQUEST['kyc_id'])&& !empty($_REQUEST['kyc_id'])) {
			fn_wk_vendor_kyc_delete_kyc_file($_REQUEST['kyc_id']);
			fn_set_notification('N', __('notice'), __('kyc_deleted_sucessfully'));
		}
		return array(CONTROLLER_STATUS_OK, 'wk_vendor_kyc.manage');
	}
	//Delete MUltiple KYC 
	if ($mode == 'm_delete') {
		if (isset($_REQUEST['kyc_data_ids'])&& !empty($_REQUEST['kyc_data_ids'])) {
			foreach ($_REQUEST['kyc_data_ids'] as $key => $kyc_id) {
				fn_wk_vendor_kyc_delete_kyc_file($kyc_id);
			}
		}
		return array(CONTROLLER_STATUS_OK, 'wk_vendor_kyc.manage');
	}
	//Accept KYC
	if($mode =='accept')
	{
		if(isset($_REQUEST['approval_data']['kyc_id']) && !empty($_REQUEST['approval_data']['kyc_id']))
		{
			$data=array(
				'status'=>'A',
				'reason'=>$_REQUEST['approval_data']['reason']
				);
			db_query("UPDATE ?:wk_vendor_kyc_data SET ?u WHERE kyc_id=?i",$data,$_REQUEST['approval_data']['kyc_id']);
			if(isset($_REQUEST['approval_data']['notify_user'])&&$_REQUEST['approval_data']['notify_user']=='Y'){
				$tpl_file="addons/wk_vendor_kyc/wk_vendor_kyc_upload_kyc_accept.tpl";
				fn_wk_vendor_kyc_send_notification($_REQUEST['approval_data']['kyc_id'],$wk_kyc_setting_data['accept_mail_sub'],$wk_kyc_setting_data['accept_mail_text'],$tpl_file);
			}
			fn_set_notification('N', __('notice'), __('status_changed'));
		}
		return array(CONTROLLER_STATUS_OK, 'wk_vendor_kyc.manage');
	}
	//Reject KYC
	if($mode =='reject')
	{
		if(isset($_REQUEST['approval_data']['kyc_id']) && !empty($_REQUEST['approval_data']['kyc_id']))
		{
			$data=array(
				'status'=>'R',
				'reason'=>$_REQUEST['approval_data']['reason']
				);
			db_query("UPDATE ?:wk_vendor_kyc_data SET ?u WHERE kyc_id=?i",$data,$_REQUEST['approval_data']['kyc_id']);
			if(isset($_REQUEST['approval_data']['notify_user'])&&$_REQUEST['approval_data']['notify_user']=='Y'){
				$tpl_file="addons/wk_vendor_kyc/wk_vendor_kyc_upload_kyc_reject.tpl";
				fn_wk_vendor_kyc_send_notification($_REQUEST['approval_data']['kyc_id'],$wk_kyc_setting_data['reject_mail_sub'],$wk_kyc_setting_data['reject_mail_text'],$tpl_file);
			}
			fn_set_notification('N', __('notice'), __('status_changed'));
		}
		return array(CONTROLLER_STATUS_OK, 'wk_vendor_kyc.manage');
	}
	//Accept multiple KYCS
	if($mode =='m_accept')
	{
		if(isset($_REQUEST['kyc_data_ids'])){
			foreach ($_REQUEST['kyc_data_ids'] as $key => $kyc_id) {
				$data=array(
					'status'=>'A',
					'reason'=>$_REQUEST['action_reason_accept']
					);
				db_query("UPDATE ?:wk_vendor_kyc_data SET ?u WHERE kyc_id=?i",$data,$kyc_id);
				if(isset($_REQUEST['action_notification_accept'])&& $_REQUEST['action_notification_accept']=='Y')
				{
					$tpl_file="addons/wk_vendor_kyc/wk_vendor_kyc_upload_kyc_accept.tpl";
					fn_wk_vendor_kyc_send_notification($kyc_id,$wk_kyc_setting_data['accept_mail_sub'],$wk_kyc_setting_data['accept_mail_text'],$tpl_file);
				}
			}
		   fn_set_notification('N', __('notice'), __('status_changed'));
		}
		return array(CONTROLLER_STATUS_OK, 'wk_vendor_kyc.manage');
	}
	//Reject multiple KYCS
	if($mode =='m_reject')
	{
		if(isset($_REQUEST['kyc_data_ids'])){
			foreach ($_REQUEST['kyc_data_ids'] as $key => $kyc_id) {
				$data=array(
					'status'=>'R',
					'reason'=>$_REQUEST['action_reason_reject']
					);
				db_query("UPDATE ?:wk_vendor_kyc_data SET ?u WHERE kyc_id=?i",$data,$kyc_id);
				if(isset($_REQUEST['action_notification_reject'])&& $_REQUEST['action_notification_reject']=='Y'){
					$tpl_file="addons/wk_vendor_kyc/wk_vendor_kyc_upload_kyc_reject.tpl";
					fn_wk_vendor_kyc_send_notification($kyc_id,$wk_kyc_setting_data['reject_mail_sub'],$wk_kyc_setting_data['reject_mail_text'],$tpl_file);
				}
			}
			 fn_set_notification('N', __('notice'), __('status_changed'));
		}
		return array(CONTROLLER_STATUS_OK, 'wk_vendor_kyc.manage');
	}
	//Delete KYC Files
	if($mode =='delete_file')
	{
		if(isset($_REQUEST['file_id'])&&isset($_REQUEST['kyc_id']))
		{
			 db_query("DELETE FROM ?:wk_vendor_kyc_files WHERE file_id=?i",$_REQUEST['file_id']);
		}
		return array(CONTROLLER_STATUS_OK, 'wk_vendor_kyc.upload_kyc?kyc_id='.$_REQUEST['kyc_id']);
	}
	
}
//Get All KYC Types And Manage
if($mode =='kyc_type'){
	list($wk_vendor_kyc, $search) = fn_wk_vendor_kyc_get_kyc_type($_REQUEST,Registry::get('settings.Appearance.admin_elements_per_page'));
	Registry::get('view')->assign('wk_vendor_kyc', $wk_vendor_kyc);
	Registry::get('view')->assign('search', $search); 
}
//Get All KYC And Manage
if($mode =='manage')
{
	list($kyc_data, $search)=fn_wk_vendor_kyc_get_kyc_data($_REQUEST,Registry::get('settings.Appearance.admin_elements_per_page'));
	Registry::get('view')->assign('kyc_data',$kyc_data);
	Registry::get('view')->assign('search', $search); 
}

//Manage Upload KYC
if($mode =='upload_kyc')
{
	$lang_code=CART_LANGUAGE;
	$wk_vendor_kyc_data=array();
	$wk_vendor_kyc=db_get_array("SELECT ?:wk_vendor_kyc_type.*, ?:wk_vendor_kyc_type_description.description FROM ?:wk_vendor_kyc_type LEFT JOIN ?:wk_vendor_kyc_type_description ON ?:wk_vendor_kyc_type.kyc_id = ?:wk_vendor_kyc_type_description.object_id AND lang_code = ?s WHERE ?:wk_vendor_kyc_type.status=?s", $lang_code,'A');
	if(empty($wk_vendor_kyc))
	{
		if(AREA =='A'){
			fn_set_notification('W', __('notice'), __('please_enable_or_add_kyc_type_first'));
		}
		else{
			fn_set_notification('W', __('notice'), __('you_can_not_upload_kyc_type_not_found'));
		}	
	}
	Registry::get('view')->assign('wk_vendor_kyc_types', $wk_vendor_kyc);
	if (isset($_REQUEST['kyc_id'])) {
		$wk_vendor_kyc_data=db_get_array("SELECT ?:wk_vendor_kyc_data.*, ?:wk_vendor_kyc_data_description.kyc_name FROM ?:wk_vendor_kyc_data LEFT JOIN ?:wk_vendor_kyc_data_description ON ?:wk_vendor_kyc_data_description.object_id = ?:wk_vendor_kyc_data.kyc_id AND lang_code = ?s WHERE ?:wk_vendor_kyc_data.kyc_id=?i",$lang_code,$_REQUEST['kyc_id']);
			$wk_vendor_kyc_data=$wk_vendor_kyc_data[0];
		$kycfiles=db_get_array("SELECT file_id,kyc_file FROM ?:wk_vendor_kyc_files WHERE kyc_id=?i",$_REQUEST['kyc_id']);
		foreach ($kycfiles as $key => $kyc_file) {
			$wk_vendor_kyc_data['kyc_file'][$kyc_file['file_id']]=$kyc_file['kyc_file'];
		}
	}
	Registry::get('view')->assign('wk_vendor_kyc_data', $wk_vendor_kyc_data);

	// Select Required KYC type
	$required_kyc_type=db_get_array("SELECT ?:wk_vendor_kyc_type.kyc_id, ?:wk_vendor_kyc_type_description.description FROM ?:wk_vendor_kyc_type LEFT JOIN ?:wk_vendor_kyc_type_description ON ?:wk_vendor_kyc_type.kyc_id = ?:wk_vendor_kyc_type_description.object_id AND lang_code = ?s WHERE ?:wk_vendor_kyc_type.status=?s AND ?:wk_vendor_kyc_type.is_required=?s", $lang_code,'A','Y');
	
	$kyc_required_description=array();
	if($required_kyc_type){
		foreach ($required_kyc_type as $key => $value) {
			$kyc_required_description[$key]=$value['description'];
		}
		//$description_string=implode(',',$kyc_required_description);
		Registry::get('view')->assign('description_string',$kyc_required_description);
	}
}
//Add/Update Kyc Type
if($mode =='update_kyc_type')
{
	$wk_vendor_kyc_type_data=array();
	$lang_code=CART_LANGUAGE;
	if (isset($_REQUEST['kyc_id'])) {
		$wk_vendor_kyc_type_data=db_get_array("SELECT ?:wk_vendor_kyc_type.*, ?:wk_vendor_kyc_type_description.description FROM ?:wk_vendor_kyc_type LEFT JOIN ?:wk_vendor_kyc_type_description ON ?:wk_vendor_kyc_type_description.object_id = ?:wk_vendor_kyc_type.kyc_id AND lang_code = ?s WHERE ?:wk_vendor_kyc_type.kyc_id=?i",$lang_code,$_REQUEST['kyc_id']);
		$wk_vendor_kyc_type_data=$wk_vendor_kyc_type_data[0];
	}
	Registry::get('view')->assign('wk_vendor_kyc_type_data', $wk_vendor_kyc_type_data);
}
//Preview Of Upload Request Mail Template 
if($mode == 'preview_html_upload_request')
{
        fn_trusted_vars('wk_vendor_kyc');
        $wk_vendor_kyc_settings_data=$_REQUEST['wk_vendor_kyc'];
        $body = $wk_vendor_kyc_settings_data['upload_mail_text'];
        Registry::get('view')->assign('body', $body);
        Registry::get('view')->display('addons/wk_vendor_kyc/views/wk_vendor_kyc/components/preview_popup.tpl');
        exit();
}
//Preview Of Accept KYC Mail Template 
if($mode == 'preview_html_accept_kyc')
{
        fn_trusted_vars('wk_vendor_kyc');
        $wk_vendor_kyc_settings_data=$_REQUEST['wk_vendor_kyc'];
        $body = $wk_vendor_kyc_settings_data['accept_mail_text'];
        Registry::get('view')->assign('body', $body);
        Registry::get('view')->display('addons/wk_vendor_kyc/views/wk_vendor_kyc/components/preview_popup.tpl');
        exit();
}
//Preview Of Reject KYC Mail Template 
if($mode == 'preview_html_reject_kyc')
{
        fn_trusted_vars('wk_vendor_kyc');
        $wk_vendor_kyc_settings_data=$_REQUEST['wk_vendor_kyc'];
        $body = $wk_vendor_kyc_settings_data['reject_mail_text'];
        Registry::get('view')->assign('body', $body);
        Registry::get('view')->display('addons/wk_vendor_kyc/views/wk_vendor_kyc/components/preview_popup.tpl');
        exit();
}
//Preview Of Upload Validity Period Expire Mail Template 
if($mode == 'preview_html_exp')
{
    fn_trusted_vars('wk_vendor_kyc');
    $wk_vendor_kyc_settings_data=$_REQUEST['wk_vendor_kyc'];
    $body = $wk_vendor_kyc_settings_data['exp_mail_text'];
    Registry::get('view')->assign('body', $body);
    Registry::get('view')->display('addons/wk_vendor_kyc/views/wk_vendor_kyc/components/preview_popup.tpl');
    exit();
}
if($mode == 'preview_html_admin_notify')
{
    fn_trusted_vars('wk_vendor_kyc');
    $wk_vendor_kyc_settings_data=$_REQUEST['wk_vendor_kyc'];
    $body = $wk_vendor_kyc_settings_data['admin_notify_mail_text'];
    Registry::get('view')->assign('body', $body);
    Registry::get('view')->display('addons/wk_vendor_kyc/views/wk_vendor_kyc/components/preview_popup.tpl');
    exit();
}

//Get Company Data For which Upload Request has Sent
if($mode=='kyc_request_log'){
	list($companies, $search) = fn_get_companies($_REQUEST, $auth, Registry::get('settings.Appearance.admin_elements_per_page'));
    Registry::get('view')->assign('companies', $companies);
    Registry::get('view')->assign('search', $search);

    Registry::get('view')->assign('countries', fn_get_simple_countries(true, CART_LANGUAGE));
    Registry::get('view')->assign('states', fn_get_all_states());
}
if($mode =='sendagain')
{
	if(isset($_REQUEST['company_id'])){
		$company_id=$_REQUEST['company_id'];
		$day=$hour=$minitue=0;
		$company_email=db_get_field("SELECT email FROM ?:companies WHERE company_id=?i",$_REQUEST['company_id']);
	   	$wk_kyc_setting_data=fn_wk_vendor_kyc_get_setting_data();
		if (isset($wk_kyc_setting_data['upload_mail_sub'])) {
		     	$subject=$wk_kyc_setting_data['upload_mail_sub'];
		  }
		if(isset($wk_kyc_setting_data['days']))
		     	$day=$wk_kyc_setting_data['days'];
		if(isset($wk_kyc_setting_data['hours']))
		     	$hour=$wk_kyc_setting_data['hours'];
		if(isset($wk_kyc_setting_data['minute']))
		     	$minitue=$wk_kyc_setting_data['minute'];
	      $mail_text=$wk_kyc_setting_data['upload_mail_text'];
	      $validity_period_time=TIME+($day*24*60*60)+($hour*60*60)+($minitue*60);      
	      $validity_period_time=date('d-m-y\,H:i:s', $validity_period_time);
	      $mail_content = str_replace('[#validity_period#]', $validity_period_time, $mail_text);
	      $tpl_file="addons/wk_vendor_kyc/wk_vendor_kyc_upload_kyc_request.tpl";
	      fn_wk_vendor_kyc_send_mail($mail_content,$company_email,$subject,$tpl_file,'');
	      $data=array(
	        'upload_kyc_request_sent'=>1,
	        'upload_kyc_request_time'=>TIME,
	        'wk_upload_exp'=>0
	        );
	    db_query('UPDATE ?:companies SET ?u WHERE company_id=?i',$data,$company_id);
	    fn_set_notification('N', __('notice'), __('request_sent_again'));
	}
    return array(CONTROLLER_STATUS_OK, 'wk_vendor_kyc.kyc_request_log');
}
// Send Upload Kyc request to exiting Vendor
if($mode =='send_upload_request'){
	if(isset($_REQUEST['company_id'])){
		fn_wk_vendor_kyc_send_upload_request($_REQUEST['company_id']);
		fn_set_notification('N', __('notice'), __('request_sent_successfully'));
	}
	return array(CONTROLLER_STATUS_OK, 'companies.manage');
}
// Send Upload Kyc request to multiple exiting Vendor
if($mode =='m_send_upload_request'){
	
	if(isset($_REQUEST['company_ids'])){
		foreach ($_REQUEST['company_ids'] as $key => $value) {
			fn_wk_vendor_kyc_send_upload_request($value);
		}
		fn_set_notification('N', __('notice'), __('request_sent_successfully'));
	}
	return array(CONTROLLER_STATUS_OK, 'companies.manage');
}