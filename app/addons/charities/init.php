<?php

/*
 * Copyright 2015, 1st Source IT, LLC, EZ Merchant Solutions
 */
if( !defined('BOOTSTRAP') ) die('Access denied');

use Tygh\Registry;

fn_register_hooks(
					 'fill_auth',
					 'change_order_status',
					 'get_order_info',
					 'create_seo_name_pre'
				 );

$v = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
define('CH_LOG_LEVEL', 0);
Registry::set('runtime.ch_log_level', CH_LOG_LEVEL);
if( class_exists('ez_log') ) {
	ez_log::init('charities', CH_LOG_LEVEL, '', false);
}
?>
