<?php

use Tygh;
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if($mode == "view") {
    $rdeData = "5670";
    //die($rdeData);
    Tygh::$app['view']->assign('rdeData', $rdeData);
}