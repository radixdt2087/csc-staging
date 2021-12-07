<?php
/*****************************************************************************
*                                                                            *
*          All rights reserved! CS-Commerce Software Solutions               *
* 			https://www.cs-commerce.com/license-agreement.html 				 *
*                                                                            *
*****************************************************************************/
use Tygh\Registry;
use Tygh\CscLiveSearch;
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD']=="POST"){
	return;
}
$cls_settings = CscLiveSearch::_get_option_values();
$_view = CscLiveSearch::_view();	
$_view->assign('cls_settings', $cls_settings);

