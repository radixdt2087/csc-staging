<?php
 defined('BOOTSTRAP') or die('Access denied'); if ($_SERVER['REQUEST_METHOD'] == 'POST') { return; } if ($mode == 'picker') { $plans = sd_ODJhMjFlOTZhMDc4YWEwMWRkODE2OTcx(CART_LANGUAGE, $_REQUEST); Tygh::$app['view']->assign('plans', $plans); Tygh::$app['view']->display('addons/sd_affiliate/pickers/affiliate_plans/picker_contents.tpl'); exit; }