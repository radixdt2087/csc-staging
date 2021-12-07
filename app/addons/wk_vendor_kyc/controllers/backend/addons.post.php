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
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($mode == 'update' && $_REQUEST['addon'] == 'wk_vendor_kyc' && (!empty($_REQUEST['wk_vendor_kyc'])))
        {
            fn_trusted_vars('wk_vendor_kyc');
            fn_wk_vendor_kyc_setting_data($_REQUEST['wk_vendor_kyc']);  
        }
    }
    if ($mode == 'update') {
        if ($_REQUEST['addon'] == 'wk_vendor_kyc') {
            $wk_vendor_kyc=fn_wk_vendor_kyc_get_setting_data();
            Registry::get('view')->assign('wk_vendor_kyc_settings_data', $wk_vendor_kyc);
        }
    }
       
function fn_wk_vendor_kyc_setting_data($wk_vendor_kyc,$company_id = null)
{
    if (!$setting_id = Settings::instance()->getId('wk_vendor_kyc_tpl_data', '')) {
        $setting_id = Settings::instance()->update(array(
            'name' =>           'wk_vendor_kyc_tpl_data',
            'section_id' =>     0,
            'section_tab_id' => 0,
            'type' =>           'A', 
            'position' =>       0,
            'is_global' =>      'N',
            'handler' =>        ''
        ));
    }   
    Settings::instance()->updateValueById($setting_id, serialize($wk_vendor_kyc), $company_id);
}
?>