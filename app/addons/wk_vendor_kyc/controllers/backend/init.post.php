<?php

/******************************************************************
# Vendor Kyc   ---      Vendor KYC                        *
# ----------------------------------------------------------------*                                   *
# copyright Copyright (C) 2010 webkul.com. All Rights Reserved.   *
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL     *
# Websites: http://webkul.com                                     *
*******************************************************************
*/
if (!defined('BOOTSTRAP')) { die('Access denied'); }

$company_datas=db_get_array("SELECT company_id,upload_kyc_request_time FROM ?:companies WHERE upload_kyc_request_sent=?i AND wk_upload_exp=?i",1,0);
if(!empty($company_datas))
{
	$wk_kyc_setting_data=fn_wk_vendor_kyc_get_setting_data();
	$day=$wk_kyc_setting_data['days'];
	$hour=$wk_kyc_setting_data['hours'];
	$minitue=$wk_kyc_setting_data['minute'];
	$vendor_status_changes_to=$wk_kyc_setting_data['vendor_status'];
	$setting_timestamp=($day*24*60*60)+($hour*60*60)+($minitue*60);
	foreach ($company_datas as $key => $value) {
		$valid_time=$setting_timestamp+$value['upload_kyc_request_time'];
		 if(TIME >$valid_time)
	     {
	   		$check_kyc_data=db_get_array("SELECT kyc_id FROM ?:wk_vendor_kyc_data WHERE company_id=?i ",$value['company_id']);
	   		if(empty($check_kyc_data)){
	   			$data=array(
	   				'status'=>$vendor_status_changes_to,
	   				'wk_upload_exp'=>1
	   			);
	   			db_query('UPDATE ?:companies SET ?u WHERE company_id = ?i', $data, $value['company_id']);
	   			$to=db_get_field("SELECT email FROM ?:companies WHERE company_id = ?i",$value['company_id']);
	   			$wk_vendor_statu=fn_get_predefined_statuses('companies');
	   			foreach ($wk_vendor_statu as $status => $vendor_status){
	   				if($status==$vendor_status_changes_to){
	   					$new_status=$vendor_status;
	   				}	
	   			}
	   			$mail_text=$wk_kyc_setting_data['exp_mail_text'];
	   			$mail_content = str_replace('[#vendor_status#]', $new_status, $mail_text);
	   			$tpl_file="addons/wk_vendor_kyc/wk_vendor_kyc_upload_kyc_validity_exp.tpl";
	   			fn_wk_vendor_kyc_send_mail($mail_content,$to,$wk_kyc_setting_data['exp_mail_sub'],$tpl_file,'');
	   		}
	   		else
	   		{
	   			$check_kyc_data_type=db_get_array("SELECT COUNT(kyc_id) FROM ?:wk_vendor_kyc_data WHERE company_id=?i AND is_required=?i",$value['company_id'],1);
	   			$required_kyc_type=db_get_array("SELECT COUNT(kyc_id) FROM ?:wk_vendor_kyc_type WHERE is_required=?s",'Y');
	   			if($check_kyc_data_type!=$required_kyc_type){
	   				$data=array(
	   				'status'=>$vendor_status_changes_to,
	   				'wk_upload_exp'=>1
	   				);
		   			db_query('UPDATE ?:companies SET ?u WHERE company_id = ?i', $data, $value['company_id']);
		   			$to=db_get_field("SELECT email FROM ?:companies WHERE company_id = ?i",$value['company_id']);
		   			$wk_vendor_statu=fn_get_predefined_statuses('companies');
		   			foreach ($wk_vendor_statu as $status => $vendor_status){
		   				if($status==$vendor_status_changes_to){
		   					$new_status=$vendor_status;
		   				}	
		   			}
		   			$mail_text=$wk_kyc_setting_data['exp_mail_text'];
		   			$mail_content = str_replace('[#vendor_status#]', $new_status, $mail_text);
		   			$tpl_file="addons/wk_vendor_kyc/wk_vendor_kyc_upload_kyc_validity_exp.tpl";
		   			fn_wk_vendor_kyc_send_mail($mail_content,$to,$wk_kyc_setting_data['exp_mail_sub'],$tpl_file,'');
		   		}
	   		}

	    }
	}
}
