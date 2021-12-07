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
if (!defined('BOOTSTRAP')) {
die('Access denied');
}

function fn_ab__stickers_exim_get_sticker_appearance($sticker_id, $field, $lang_code)
{
static $stickers = [];
if (empty($stickers[$lang_code])) {

$repository = Tygh::$app['addons.ab__stickers.repository'];
list($stickers[$lang_code]) = $repository->find(['get_icons' => false], 0, $lang_code);
}
return isset($stickers[$lang_code][$sticker_id]['appearance'][$field]) ? $stickers[$lang_code][$sticker_id]['appearance'][$field] : '';
}

function fn_ab__stickers_exim_set_sticker_appearance($sticker_id, $field, $value, $lang_code)
{
static $stickers = [];
if (empty($stickers[$lang_code])) {

$repository = Tygh::$app['addons.ab__stickers.repository'];
list($stickers[$lang_code]) = $repository->find(['get_icons' => false], 0, $lang_code);
}
$sticker_data = array_merge($stickers[$lang_code][$sticker_id], ['appearance' => [$field => $value]]);
return fn_ab__stickers_update_sticker($sticker_id, ['sticker_data' => $sticker_data], $lang_code);
}

function fn_ab__stickers_exim_add_storefront_condition(&$conditions)
{
$conditions[] = db_quote('FIND_IN_SET (?i, storefront_ids)', Tygh::$app['storefront']->storefront_id);
}

function fn_ab__stickers_exim_get_image_url($sticker_id, $lang_code = CART_LANGUAGE)
{
$image_path = '';
$image_id = db_get_field('SELECT image_id FROM ?:ab__sticker_images WHERE sticker_id = ?i AND lang_code = ?s', $sticker_id, $lang_code);
$image = fn_get_image_pairs($image_id, 'ab__stickers', 'M', true, true, $lang_code);
if (!empty($image) && !empty($image['icon'])) {
$image_path = $image['icon']['image_path'];
}
return $image_path;
}

function fn_ab__stickers_exim_set_image_url($sticker_id, $lang_code = DESCR_SL, $image_path = [], $is_high_res = \Tygh\Enum\YesNo::NO)
{
if (!empty($image_path[$lang_code])) {
static $stickers = [];
if (empty($stickers[$lang_code])) {

$repository = Tygh::$app['addons.ab__stickers.repository'];
list($stickers[$lang_code]) = $repository->find(['get_icons' => false], 0, $lang_code);
}
$sticker_image_name = 'ab__sticker_img_image';
$_REQUEST["{$sticker_image_name}_data"] = [
[
'type' => 'M',
'object_id' => 0,
],
];
$_REQUEST["file_{$sticker_image_name}_icon"] = [$image_path[$lang_code]];
$_REQUEST["type_{$sticker_image_name}_icon"] = ['url'];
$_REQUEST["is_high_res_{$sticker_image_name}_icon"] = [$is_high_res];
return fn_ab__stickers_update_sticker($sticker_id, ['sticker_data' => $stickers[$lang_code][$sticker_id]], $lang_code);
}
return 0;
}

function fn_ab__stickers_exim_unset_sticker_id(&$object = [])
{
unset($object['sticker_id']);
}
