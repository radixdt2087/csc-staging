<?php
/*******************************************************************************************
*   ___  _          ______                     _ _                _                        *
*  / _ \| |         | ___ \                   | (_)              | |              © 2021   *
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
use Tygh\Enum\SiteArea;
use Tygh\Enum\Addons\Ab_stickers\StickerTypes;
if (!defined('BOOTSTRAP')) {
die('Access denied');
}

function fn_ab__stickers_update_product_pre(&$product_data, $product_id, $lang_code, $can_update)
{
if (!empty($product_data['ab__stickers_manual_ids'])) {
$product_data['ab__stickers_manual_ids'] = implode(',', $product_data['ab__stickers_manual_ids']);
}
}

function fn_ab__stickers_get_product_data_post(&$product_data, $auth, $preview, $lang_code)
{
if (!empty($product_data['ab__stickers_manual_ids'])) {
$product_data['ab__stickers_manual_ids'] = explode(',', $product_data['ab__stickers_manual_ids']);
}
if (!empty($product_data['ab__stickers_generated_ids'])) {
$product_data['ab__stickers_generated_ids'] = explode(',', $product_data['ab__stickers_generated_ids']);
}
}

function fn_ab__stickers_get_products($params, $fields, $sortings, &$condition, &$join, $sorting, $group_by, $lang_code, &$having)
{
if ($params['area'] == SiteArea::ADMIN_PANEL) {
if (!empty($params['ab__sticker_conditions'])) {
list($where, $joins, $stickers_having) = fn_ab__stickers_build_sticker_conditions_query($params['ab__sticker_conditions']);
$join .= ' ' . implode(' ', $joins);
$condition .= ' AND (' . $where . ')';
$having[] = $stickers_having;
}
if (!empty($params['ab__stickers_manual_ids']) || !empty($params['ab__stickers_generated_ids'])) {
if (!empty($params['ab__stickers_manual_ids'])) {
foreach ($params['ab__stickers_manual_ids'] as $sticker_id) {
$condition .= db_quote(' AND FIND_IN_SET(?i, ab__stickers_manual_ids)', $sticker_id);
}
}
if (!empty($params['ab__stickers_generated_ids'])) {
foreach ($params['ab__stickers_generated_ids'] as $sticker_id) {
$condition .= db_quote(' AND FIND_IN_SET(?i, ab__stickers_generated_ids)', $sticker_id);
}
}

$repository = Tygh::$app['addons.ab__stickers.repository'];
list($s) = $repository->find();
Tygh::$app['view']->assign('ab__stickers', $s);
}
}
}

function fn_ab__stickers_gather_additional_products_data_post($product_ids, $params, &$products, $auth, $lang_code)
{
if (AREA == SiteArea::STOREFRONT && !empty(Tygh::$app['view'])) {
static $static_product_stickers = [
'common' => [],
'bottom' => [],
'top' => [],
];
$all_stickers_on_page = [];
foreach ($products as &$product) {
if (!empty($product['ab__stickers_generated_ids']) || !empty($product['ab__stickers_manual_ids'])) {
$ab__stickers_generated_ids = empty($product['ab__stickers_generated_ids']) ? [] : (is_array($product['ab__stickers_generated_ids']) ? $product['ab__stickers_generated_ids'] : explode(',', $product['ab__stickers_generated_ids']));
$ab__stickers_manual_ids = empty($product['ab__stickers_manual_ids']) ? [] : (is_array($product['ab__stickers_manual_ids']) ? $product['ab__stickers_manual_ids'] : explode(',', $product['ab__stickers_manual_ids']));
$all_stickers_on_page[] = $product['ab__sticker_ids'] = implode(',', array_unique(array_merge($ab__stickers_generated_ids, $ab__stickers_manual_ids)));
}
}
$all_stickers_on_page = array_unique(explode(',', implode(',', $all_stickers_on_page)));
$position = $_REQUEST['dispatch'] === 'products.view' ? 'output_position_detailed_page' : 'output_position_list';
$display_on = 'lists';
$current_tmpl = '';
if ($_REQUEST['dispatch'] === 'products.view' && Registry::ifGet('ab__stickers.detailed_page', false) === true) {
$display_on = 'detailed_pages';
Registry::del('ab__stickers.detailed_page');
} else {
$block = Tygh::$app['view']->getTemplateVars('block');
if (!empty($block) && !empty($block['properties']['template'])) {
$current_tmpl = $block['properties']['template'];
} else {
$current_tmpl = fn_ab__stickers_get_category_page_layout_template();
Tygh::$app['view']->assign('ab__stickers_current_tmpl', $current_tmpl);
}
}

$repository = Tygh::$app['addons.ab__stickers.repository'];
list($stickers) = $repository->find([
'item_ids' => $all_stickers_on_page,
'forced_type' => StickerTypes::DYNAMIC,
'display_on' => $display_on == 'detailed_pages' ? $display_on : '',
'display_on_tmpl' => empty($current_tmpl) ? '' : $current_tmpl,
]);
$prohibited_positions = [];
if (!empty($current_tmpl)) {
$prohibited_positions = fn_ab__stickers_get_prohibited_list_positions();
}
if (!empty($stickers)) {
$static_sub_array = 'common';
if (!empty($prohibited_positions[$current_tmpl])) {
if (in_array('B', $prohibited_positions[$current_tmpl])) {
$static_sub_array = 'bottom';
} elseif (in_array('T', $prohibited_positions[$current_tmpl])) {
$static_sub_array = 'top';
}
}
foreach ($products as &$product) {
if (!empty($static_product_stickers[$static_sub_array][$static_sub_array][$product['product_id']])) {
$product['ab__stickers'] = $static_product_stickers[$static_sub_array][$product['product_id']];
} else {
$temp = [];
if (!empty($product['ab__sticker_ids']) && !empty($stickers)) {
$product_stickers = explode(',', $product['ab__sticker_ids']);
foreach ($product_stickers as $sticker) {
if (!empty($stickers[$sticker])) {
$temp[$stickers[$sticker][$position]][$sticker . '-' . $stickers[$sticker]['hash'] . '-' . $lang_code] = $stickers[$sticker];
}
}
}
foreach ($stickers as $sticker) {
if (StickerTypes::DYNAMIC == $sticker['type']) {
$sticker['conditions'] = unserialize($sticker['conditions']);
$res = fn_ab__stickers_validate_dynamic_sticker($sticker['conditions'], $product, $product_ids, $auth);
if (!empty($sticker['conditions']['conditions']) && $res['result'] === true) {
if (!empty($res['placeholders'])) {
$sticker['placeholders'] = serialize($res['placeholders']);
}
$key = $sticker['sticker_id'] . '-' . $sticker['hash'] . '-' . $lang_code . (empty($sticker['placeholders']) ? '' : '-' . md5($sticker['placeholders']));
$temp[$sticker[$position]][$key] = $sticker;
}
}
}
if (!empty($prohibited_positions) && !empty($prohibited_positions[$current_tmpl])) {
if (in_array('B', $prohibited_positions[$current_tmpl]) && !empty($temp['B'])) {
$temp['T'] = array_merge(empty($temp['T']) ? [] : $temp['T'], $temp['B']);
unset($temp['B']);
} elseif (in_array('T', $prohibited_positions[$current_tmpl]) && !empty($temp['T'])) {
$temp['B'] = array_merge(empty($temp['B']) ? [] : $temp['B'], $temp['T']);
unset($temp['T']);
}
}
foreach ($temp as &$o_position) {
uasort($o_position, function ($a, $b) {
return ($a['position'] < $b['position']) ? -1 : 1;
});
}
$static_product_stickers[$static_sub_array][$product['product_id']] = $product['ab__stickers'] = $temp;
}
}
}
}
}

function fn_ab__stickers_generate_thumbnail_pre($image_path, $width, $height, $make_box)
{
if (strpos($image_path, 'ab__stickers') !== false) {
$old_value = Registry::get('settings.Thumbnails.thumbnail_background_color');
if ($old_value !== '') {
Registry::set('settings.Thumbnails.thumbnail_background_color', '');
Registry::set('addons.ab__stickers.thumbnail_background_color_old_value', $old_value);
}
$old_value = Registry::get('settings.Thumbnails.convert_to');
if ($old_value !== 'original' || $old_value !== 'png') {
Registry::set('settings.Thumbnails.convert_to', 'original');
Registry::set('addons.ab__stickers.convert_to_old_value', $old_value);
}
}
}

function fn_ab__stickers_generate_thumbnail_post($th_filename, $lazy, $image_path, $width, $height, $image)
{
if (strpos($image_path, 'ab__stickers') !== false) {
$old_value = Registry::ifGet('addons.ab__stickers.thumbnail_background_color_old_value', '');
if ($old_value) {
Registry::set('settings.Thumbnails.thumbnail_background_color', $old_value);
Registry::del('addons.ab__stickers.thumbnail_background_color_old_value');
}
$old_value = Registry::ifGet('addons.ab__stickers.convert_to_old_value', '');
if ($old_value) {
Registry::set('settings.Thumbnails.convert_to', $old_value);
Registry::del('addons.ab__stickers.convert_to_old_value');
}
}
}

function fn_ab__stickers_update_language_post($language_data, $lang_id, $action)
{
if ($action == 'add') {

$repository = Tygh::$app['addons.ab__stickers.repository'];
$repository->duplicateImagesForLanguage($repository->getStickerIds(), $language_data['lang_code']);
}
}

function fn_ab__stickers_delete_languages_post($lang_ids, $lang_codes, $deleted_lang_codes)
{

$repository = Tygh::$app['addons.ab__stickers.repository'];
foreach ($deleted_lang_codes as $lang_code) {
$sticker_ids = $repository->getStickerIds();
foreach ($sticker_ids as $sticker_id) {
$image_id = db_get_field('SELECT image_id FROM ?:ab__sticker_images WHERE sticker_id = ?i AND lang_code = ?s', $sticker_id, $lang_code);
db_query('DELETE FROM ?:ab__sticker_images WHERE image_id = ?i', $image_id);
fn_delete_image_pairs($image_id, 'ab__stickers', 'M');
}
}
}
