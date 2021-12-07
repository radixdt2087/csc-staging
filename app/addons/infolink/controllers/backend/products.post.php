<?php
use Illuminate\Support\Collection;
use Tygh\Tools\Url;
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied');} 

function fn_my_changes_get_label_details($tab, $labelName)
{ 
    $lavelValue = db_get_array("SELECT * FROM ?:label_value WHERE tab_name = '$tab' and label_name = '$labelName'"); 
    echo $lavelValue[0]['label_details'];
}


function fn_my_changes_get_upload_product_video($parentTab, $tabName)
{
    $checkData = db_get_array("SELECT * FROM ?:video_document WHERE `parent_name` = '$parentTab' AND `tab_name` = '$tabName'");
    echo $checkData[0]['url'];
}

function fn_my_changes_get_upload_product_details($parentTab, $tabName, $uploadType)
{
    $checkData = db_get_array("SELECT * FROM ?:video_document WHERE `parent_name` = '$parentTab' AND `tab_name` = '$tabName' AND `upload_type` = '$uploadType'");
    return $checkData;
}
//{$a|php_function_name:param2:param3:...}
//{assign var="price" value='General'+'Name'|fn_my_changes_get_label_details}

// $view = Registry::get('view');
        
// $view->assign(['lavelValue' => $lavelValue]);

// function fn_my_changes_get_label_details()
// {
    
// }

//$('head').append("<style>label::after{ content: 'Click'}</style>");
//echo "<style>label::after {content: ' - Remember this';}</style>";

?>