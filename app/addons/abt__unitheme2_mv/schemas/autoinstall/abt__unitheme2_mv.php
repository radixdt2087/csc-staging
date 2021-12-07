<?php
/*******************************************************************************************
*   ___  _          ______                     _ _                _                        *
*  / _ \| |         | ___ \                   | (_)              | |              © 2020   *
* / /_\ | | _____  _| |_/ /_ __ __ _ _ __   __| |_ _ __   __ _   | |_ ___  __ _ _ __ ___   *
* |  _  | |/ _ \ \/ / ___ \ '__/ _` | '_ \ / _` | | '_ \ / _` |  | __/ _ \/ _` | '_ ` _ \  *
* | | | | |  __/>  <| |_/ / | | (_| | | | | (_| | | | | | (_| |  | ||  __/ (_| | | | | | | *
* \_| |_/_|\___/_/\_\____/|_|  \__,_|_| |_|\__,_|_|_| |_|\__, |  \___\___|\__,_|_| |_| |_| *
*                                                         __/ |                            *
*                                                        |___/                             *
* ---------------------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license and accept    *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
use Tygh\Registry;
$addon = basename(__FILE__, '.php');
$theme = 'abt__unitheme2';
$style = 'Default';
$styles = glob(Registry::get('config.dir.themes_repository') . $theme . '/styles/data/*.less');
if (!empty($styles)) {
array_walk($styles, function (&$style, $key) {
$style = basename($style, '.less');
});
$style = $styles[array_rand($styles)];
}
$schema = [
'config' => [
'addon' => $addon,
'theme' => $theme,
'style' => $style,
'layouts' => [
[
'file' => 'layouts_multivendor_default.xml',
'from_layout_id' => 'responsive|layouts_multivendor_default.xml',
'width' => 16,
'layout_width' => 'fluid',
'min_width' => 280,
'max_width' => 1240,
],
[
'file' => 'layouts_multivendor_fixed.xml',
'from_layout_id' => 'responsive|layouts_multivendor_fixed.xml',
'width' => 16,
'layout_width' => 'fluid',
'min_width' => 280,
'max_width' => 1240,
],
[
'is_default' => 1,
'file' => 'layouts_multivendor_advanced.xml',
'from_layout_id' => 'responsive|layouts_multivendor_advanced.xml',
'width' => 16,
'layout_width' => 'fluid',
'min_width' => 280,
'max_width' => 1240,
],
],
],
'steps' => [
'mv_layouts' => [
['fn_abt__ut2_mv_copy_layouts'],
['fn_abt__ut2_mv_add_layouts', '@layouts', '@style'],
],
'install_extradata_mv' => [
['fn_autoinstall_extradata_mv_menu'],
],
],
];
return $schema;
