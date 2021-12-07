<?php
/*****************************
* Copyright 2009, 2010, 2011, 2012, 2013, 2014 1st Source IT, LLC
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
if( !defined('BOOTSTRAP') ) die('Access denied');
use Tygh\Registry;

$v = explode(DIRECTORY_SEPARATOR, __FILE__);
$addon_name = $v[count($v)-4];
include(ez_fix_path(Registry::get('config.dir.addons')."$addon_name/config.php"));

if( file_exists($f = ez_fix_path(Registry::get('config.dir.addons')."ez_common/lib/auth_processing.php")) ) {
	include($f);
} else if( !defined('RESTRICTED_ADMIN') ) {
	$test = strstr($_SERVER['HTTP_HOST'], 'test.') ? "test." : '';
	fn_set_notification('W', "$addon_name: $controller.$mode", "EZ Common Addon Tools is not installed.  Please install it manually from http://www.{$test}ez-ms.com/private/ez_common4.tgz", true);
}

return array(CONTROLLER_STATUS_OK);
?>
