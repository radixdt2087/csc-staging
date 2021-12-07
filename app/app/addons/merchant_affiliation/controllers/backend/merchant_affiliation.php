<?php 
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }


if($mode ==  'link') {

	$user_id = Tygh::$app['session']['auth']['user_id'];

	if ($user_id) {
		$link = $user_id;
	}else{
		$link = "Please login first";
	}

	$view = Registry::get('view');
  	$view->assign('link', $link);

}


