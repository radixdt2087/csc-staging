<?php 
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }
fn_register_hooks(
	'change_company_status_before_mail',
	'get_companies'
);

Registry::set('config.storage.wk_vendor_kyc', array(
    'prefix' => 'wk_vendor_kyc',
    'secured' => true,
    'dir' => Registry::get('config.dir.var')
));
