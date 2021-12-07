<?php
use Illuminate\Support\Collection;
use Tygh\Tools\Url;
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied');} 

function fn_my_changes_get_upload_product_import_video($parentTab, $tabName)
{
    $checkData = db_get_array("SELECT * FROM ?:video_document WHERE `parent_name` = '$parentTab' AND `tab_name` = '$tabName'");
    echo $checkData[0]['url'];
}

function fn_my_changes_get_upload_product_import_details($parentTab, $tabName, $uploadType)
{
    $checkData = db_get_array("SELECT * FROM ?:video_document WHERE `parent_name` = '$parentTab' AND `tab_name` = '$tabName' AND `upload_type` = '$uploadType'");
    return $checkData;
}

?>