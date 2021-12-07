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
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
use Tygh\Registry;
foreach (glob(Registry::get('config.dir.addons') . '/abt__unitheme2/functions/fn.abt__ut2_*.php') as $functions) {
require_once $functions;
}
function fn_abt__ut2_install()
{
$objects = [
['t' => '?:pages',
'i' => [
['n' => 'abt__ut2_microdata_schema_type', 'p' => 'varchar(32) NOT NULL DEFAULT \'\''],
],
],
['t' => '?:bm_grids',
'i' => [
['n' => 'abt__ut2_extended', 'p' => 'char(1) NOT NULL DEFAULT \'0\''],
['n' => 'abt__ut2_padding', 'p' => 'varchar(20) NOT NULL DEFAULT \'\''],
],
],
['t' => '?:bm_grids',
'i' => [
['n' => 'abt__ut2_show_in_tabs', 'p' => 'char(1) NOT NULL DEFAULT \'N\''],
['n' => 'abt__ut2_use_ajax', 'p' => 'char(1) NOT NULL DEFAULT \'N\''],
],
],
['t' => '?:banners',
'i' => call_user_func(function () {
$devices_enabled_fields = [
'tablet' => fn_get_schema('abt__ut2_banners', 'tablet', 'php', true),
'mobile' => fn_get_schema('abt__ut2_banners', 'mobile', 'php', true),
];
$fields = [
'use_avail_period' => ['p' => 'char(1) NOT NULL DEFAULT \'N\'', 'add_sql' => ['ALTER TABLE ?:banners CHANGE `type` `type` CHAR(20) NOT NULL DEFAULT \'G\'']],
'avail_from' => ['p' => 'int(11) NOT NULL DEFAULT \'0\''],
'avail_till' => ['p' => 'int(11) NOT NULL DEFAULT \'0\''],
'button_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'button_text_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'button_text_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'button_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'button_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'title_font_size' => ['p' => 'varchar(7) NOT NULL DEFAULT \'18px\''],
'title_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'title_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'title_font_weight' => ['p' => 'varchar(4) NOT NULL DEFAULT \'300\''],
'title_tag' => ['p' => 'enum(\'div\',\'h1\',\'h2\',\'h3\') NOT NULL DEFAULT \'div\''],
'background_image_size' => ['p' => 'enum(\'cover\',\'contain\') NOT NULL DEFAULT \'cover\''],
'title_shadow' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'description_font_size' => ['p' => 'varchar(7) NOT NULL DEFAULT \'13px\''],
'description_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'description_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'description_bg_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'description_bg_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'main_image' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'background_image' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'object' => ['p' => 'enum(\'image\',\'video\') NOT NULL DEFAULT \'image\''],
'background_color' => ['p' => 'varchar(11) NOT NULL DEFAULT \'\''],
'background_color_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'class' => ['p' => 'varchar(100) NOT NULL DEFAULT \'\''],
'color_scheme' => ['p' => 'enum(\'light\',\'dark\') NOT NULL DEFAULT \'light\''],
'content_valign' => ['p' => 'enum(\'top\',\'center\',\'bottom\') NOT NULL DEFAULT \'center\''],
'content_align' => ['p' => 'enum(\'left\',\'center\',\'right\') NOT NULL DEFAULT \'center\''],
'content_full_width' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'content_bg' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'padding' => ['p' => 'varchar(27) NOT NULL DEFAULT \'\''],
'how_to_open' => ['p' => 'enum(\'in_this_window\',\'in_new_window\',\'in_popup\') NOT NULL DEFAULT \'in_this_window\''],
'data_type' => ['p' => 'enum(\'url\',\'blog\',\'promotion\') NOT NULL DEFAULT \'url\''],
'youtube_use' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'youtube_autoplay' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
'youtube_hide_controls' => ['p' => 'char(1) NOT NULL DEFAULT \'N\''],
];
$t = [];
foreach ($fields as $f => $data) {
if (!preg_match('/_image$/', $f)) {
$data['n'] = "abt__ut2_{$f}";
$t[] = $data;
}
foreach ($devices_enabled_fields as $device => $device_fields) {
if (in_array($f, $device_fields)) {
if (!preg_match('/_image$/', $f)) {
$data['n'] = "abt__ut2_{$device}_{$f}";
$t[] = $data;
}
$t[] = ['n' => "abt__ut2_{$device}_{$f}_use_own", 'p' => 'char(1) NOT NULL DEFAULT \'N\''];
}
}
}
$t[] = ['n' => 'abt__ut2_tablet_use', 'p' => 'char(1) NOT NULL DEFAULT \'N\''];
$t[] = ['n' => 'abt__ut2_mobile_use', 'p' => 'char(1) NOT NULL DEFAULT \'N\''];
return $t;
}),
],
['t' => '?:banner_descriptions',
'i' => call_user_func(function () {
$devices_enabled_fields = [
'tablet' => fn_get_schema('abt__ut2_banners', 'tablet'),
'mobile' => fn_get_schema('abt__ut2_banners', 'mobile'),
];
$fields = [
'button_text' => ['p' => 'varchar(50) NOT NULL DEFAULT \'\''],
'title' => ['p' => 'varchar(255) NOT NULL DEFAULT \'\''],
'url' => ['p' => 'varchar(255) NOT NULL DEFAULT \'\''],
'description' => ['p' => 'mediumtext'],
'youtube_id' => ['p' => 'varchar(15) NOT NULL DEFAULT \'\''],
];
$t = [];
foreach ($fields as $f => $data) {
$t[] = ['n' => "abt__ut2_{$f}", 'p' => $data['p']];
foreach ($devices_enabled_fields as $device => $device_fields) {
if (in_array($f, $device_fields)) {
$t[] = ['n' => "abt__ut2_{$device}_{$f}", 'p' => $data['p']];
$t[] = ['n' => "abt__ut2_{$device}_{$f}_use_own", 'p' => 'char(1) NOT NULL DEFAULT \'N\''];
}
}
}
return $t;
}),
],
['t' => '?:static_data',
'i' => [
['n' => 'abt__ut2_mwi__status', 'p' => 'char(1) NOT NULL DEFAULT \'N\''],
['n' => 'abt__ut2_mwi__text_position', 'p' => 'varchar(32) NOT NULL DEFAULT \'bottom\''],
['n' => 'abt__ut2_mwi__dropdown', 'p' => 'char(1) NOT NULL DEFAULT \'N\''],
['n' => 'abt__ut2_mwi__label_color', 'p' => 'varchar(11) NOT NULL DEFAULT \'\''],
['n' => 'abt__ut2_mwi__label_background', 'p' => 'varchar(11) NOT NULL DEFAULT \'\''],
],
],
['t' => '?:static_data_descriptions',
'i' => [
['n' => 'abt__ut2_mwi__desc', 'p' => 'mediumtext'],
['n' => 'abt__ut2_mwi__text', 'p' => 'mediumtext'],
['n' => 'abt__ut2_mwi__label', 'p' => 'varchar(100) NOT NULL DEFAULT \'\''],
],
],
];
if (!empty($objects) && is_array($objects)) {
foreach ($objects as $o) {
$fields = db_get_fields('DESCRIBE ' . $o['t']);
if (!empty($fields) && is_array($fields)) {
if (!empty($o['i']) && is_array($o['i'])) {
foreach ($o['i'] as $f) {
if (!in_array($f['n'], $fields)) {
db_query('ALTER TABLE ?p ADD ?p ?p', $o['t'], $f['n'], $f['p']);
if (!empty($f['add_sql']) && is_array($f['add_sql'])) {
foreach ($f['add_sql'] as $sql) {
db_query($sql);
}
}
}
}
}
if (!empty($o['indexes']) && is_array($o['indexes'])) {
foreach ($f['indexes'] as $index => $keys) {
$existing_indexes = db_get_array('SHOW INDEX FROM ?p WHERE key_name = ?s', $o['t'], $index);
if (empty($existing_indexes) && !empty($keys)) {
db_query('ALTER TABLE ?p ADD INDEX ?p (?p)', $o['t'], $index, $keys);
}
}
}
}
}
}
fn_abt__ut2_refresh_icons();
fn_abt__ut2_migration_v4113a_v4113b();
fn_abt__ut2_migration_v4113b_v4113c();
fn_abt__ut2_migration_v4115c_v4115b();
fn_abt__ut2_migration_v4122a_v4121d();
}
function fn_abt__ut2_migration_v4113a_v4113b()
{
$remove_fields = [
'?:bm_blocks' => [
'abt__ut2_show_on_desktop',
'abt__ut2_show_on_mobile',
],
];
foreach ($remove_fields as $table => $fields) {
$available_fields = db_get_fields('DESCRIBE ' . $table);
if (!empty($available_fields)) {
foreach ($fields as $field) {
if (in_array($field, $available_fields)) {
db_query("ALTER TABLE {$table} DROP COLUMN $field");
}
}
}
}
}
function fn_abt__ut2_migration_v4113b_v4113c()
{
$replaces = [
'src=\'.\'' => 'src=\'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\'',
'src="."' => 'src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="',
];
foreach ($replaces as $search => $replace) {
db_query('UPDATE ?:static_data_descriptions
SET abt__ut2_mwi__text = REPLACE(abt__ut2_mwi__text, ?s, ?s)
WHERE abt__ut2_mwi__text LIKE CONCAT(\'%\', ?s, \'%\')', $search, $replace, $search);
}
}
function fn_abt__ut2_migration_v4115c_v4115b()
{
$old_settings = db_get_array('SELECT * FROM ?:abt__ut2_settings WHERE `section` = "product_list" AND `name` = "lazy_load"');
if (!empty($old_settings)) {
$query = [];
foreach ($old_settings as $setting_data) {
$query[] = db_quote('(?s, ?s, ?i, ?s, ?s)', 'general', 'lazy_load', $setting_data['company_id'], $setting_data['lang_code'], $setting_data['value']);
}
db_query('INSERT IGNORE INTO ?:abt__ut2_settings (`section`, `name`, `company_id`, `lang_code`, `value`) VALUES ?s', implode(',', $query));
db_query('DELETE FROM ?:abt__ut2_settings WHERE `section` = "product_list" AND `name` = "lazy_load"');
}
}

function fn_abt__ut2_migration_v4122a_v4121d()
{
$is_exist = db_get_field('SHOW COLUMNS FROM ?:abt__ut2_less_settings LIKE ?s', 'company_id');
if (!empty($is_exist)) {
db_query('ALTER TABLE ?:abt__ut2_less_settings DROP PRIMARY KEY, ADD PRIMARY KEY (section,name,style)');
db_query('ALTER TABLE ?:abt__ut2_less_settings DROP COLUMN company_id');
}
}
function fn_abt__unitheme2_get_products_post(&$products, $params, $lang_code)
{
$prohibited_controllers = ['call_requests'];
if (AREA == 'C' && Registry::get('addons.discussion.status') == 'A' && !in_array(Registry::get('runtime.controller'), $prohibited_controllers) && empty($params['get_conditions']) && $products) {
$company_cond = '';
if (Registry::ifGet('addons.discussion.product_share_discussion', 'N') == 'N') {
$company_cond = fn_get_discussion_company_condition('?:discussion.company_id');
}
$posts = db_get_hash_single_array('SELECT p.product_id, ifnull(count(dp.post_id),0) as discussion_amount_posts
FROM ?:discussion
INNER JOIN ?:products as p ON (?:discussion.object_id = p.product_id)
INNER JOIN ?:discussion_posts as dp ON (?:discussion.thread_id = dp.thread_id AND ?:discussion.object_type = \'P\' ?p)
WHERE dp.status = \'A\' and p.product_id in (?n)
GROUP BY p.product_id', ['product_id', 'discussion_amount_posts'], $company_cond, array_keys($products));
foreach ($products as $p_id => $p) {
$products[$p_id]['discussion_amount_posts'] = !empty($posts[$p_id]) ? $posts[$p_id] : 0;
}
}
}
function fn_abt__unitheme2_get_products($params, &$fields, $sortings, $condition, &$join, $sorting, $group_by, $lang_code, $having)
{
$settings = fn_get_abt__ut2_settings();
$auth = &Tygh::$app['session']['auth'];
if (AREA == 'C' && $settings['product_list']['show_qty_discounts'] == 'Y') {
$join .= db_quote(' LEFT JOIN ?:product_prices AS opt_prices ON opt_prices.product_id = products.product_id AND opt_prices.lower_limit > 1 AND opt_prices.usergroup_id IN (?n)', $auth['usergroup_ids']);
$fields[] = ' (opt_prices.product_id IS NOT NULL) AS ab__is_qty_discount';
}
}
function fn_abt__unitheme2_get_products_pre(&$params, $items_per_page, $lang_code)
{
fn_abt__ut2_required_products_get_products_pre($params);
fn_abt__ut2_bestsellers_get_products_pre($params, $lang_code);
fn_abt__ut2_customers_also_bought_get_products_pre($params);
}
function fn_abt__unitheme2_description_tables_post(&$description_tables)
{
$description_tables[] = 'abt__ut2_settings';
}
function fn_abt__ut2_get_sub_or_parent_categories($value, $block, $block_scheme)
{
if (empty($_REQUEST['category_id'])) {
return [];
}
$category_id = $_REQUEST['category_id'];
$return = [];
if (!empty($block['properties']['abt__ut2_show_parents']) && $block['properties']['abt__ut2_show_parents'] === 'Y') {
$categories = fn_get_categories_list_with_parents([$category_id]);
if (!empty($categories[$category_id]['parents'])) {
$return['current_category'] = $categories[$category_id];
$return['parents'] = empty($categories[$category_id]['parents']) ? [] : fn_sort_array_by_key($categories[$category_id]['parents'], 'id_path');
} else {
$block['properties']['abt__ut2_show_siblings'] = 'Y';
}
}
if (empty($block['properties']['abt__ut2_show_children']) || $block['properties']['abt__ut2_show_children'] === 'Y') {
$return['subcategories'] = fn_get_subcategories($_REQUEST['category_id']);
if (empty($return['subcategories'])) {
$block['properties']['abt__ut2_show_siblings'] = 'Y';
}
}
if (!empty($block['properties']['abt__ut2_show_siblings']) && $block['properties']['abt__ut2_show_siblings'] === 'Y') {
if (empty($return['current_category'])) {
$parent_category_id = db_get_field('SELECT parent_id FROM ?:categories WHERE company_id = ?i AND category_id = ?i', fn_get_runtime_company_id(), $_REQUEST['category_id']);
} else {
$parent_category_id = $return['current_category']['parent_id'];
}
$return['siblings'] = fn_get_subcategories($parent_category_id);
}
return $return;
}
function fn_abt__ut2_get_light_menu($params)
{
$return = [];
uasort($params['item_ids'], 'abt_ut2_sort_item_positions');
foreach ($params['item_ids'] as $key => $menu) {
$get_params = [
'section' => 'A',
'get_params' => true,
'icon_name' => '',
'use_localization' => true,
'status' => 'A',
'request' => [
'menu_id' => $key,
],
'multi_level' => true,
'generate_levels' => true,
];
$get_params['abt__ut2_light_menu'] = Registry::get('settings.abt__device') == 'mobile';
$m = [];
$m['menus'] = fn_top_menu_form(fn_get_static_data($get_params));
$icons = fn_get_image_pairs(array_keys($m['menus']), 'abt__ut2/menu-with-icon', 'M', true, false);
foreach ($m['menus'] as $m_key => &$item) {
$item['image'] = array_shift($icons[$m_key]);
if (Registry::get('settings.abt__device') == 'desktop' && $params['properties']['abt_menu_icon_items'] == 'Y' && $item['subitems']) {
$subicons = fn_get_image_pairs(array_keys($item['subitems']), 'abt__ut2/menu-with-icon', 'M', true, false);
foreach ($item['subitems'] as $subkey => &$subitem) {
$subitem['image'] = array_shift($subicons[$subkey]);
}
}
}
if ($menu['abt__ut2_menu_state'] == 'hide_items') {
$m['menu_name'] = fn_get_menu_name($key);
}
$m['user_class'] = $menu['abt__ut2_custom_class'];
$return[$key] = $m;
}
return [$return];
}
function abt_ut2_sort_item_positions($a, $b)
{
return ($a['position'] - $b['position']);
}
function fn_abt__ut2_check_versions()
{
$ret = [
'core' => PRODUCT_NAME . ': version ' . PRODUCT_VERSION . ' ' . PRODUCT_EDITION . (PRODUCT_STATUS != '' ? (' (' . PRODUCT_STATUS . ')') : '') . (PRODUCT_BUILD != '' ? (' ' . PRODUCT_BUILD) : ''),
];
$theme = Tygh::$app['storefront']->theme_name;
$ret['theme'] = [
'id' => $theme,
'name' => __($theme),
];
if ($theme == 'abt__unitheme2') {
$data = json_decode(fn_get_contents(Registry::get('config.dir.root') . "/design/themes/$theme/manifest.json"), true);
if ($data !== false) {
$ret['theme']['manifest_version'] = "v{$data['ab']['version']} " . __('from') . " {$data['ab']['date']}";
$ret['theme']['addon_version'] = 'v' . fn_get_addon_version($theme);
}
}
fn_set_hook('abt__check_versions', $ret, $theme);
return $ret;
}
function fn_abt__ut2_refresh_icons($addon = 'abt__unitheme2')
{
$repo_path = Registry::get('config.dir.themes_repository') . $addon;
$file_content = fn_get_contents($repo_path . "/css/addons/{$addon}/icons.less");
$file_content = str_replace('media/custom_fonts', 'media/fonts/addons/' . $addon, $file_content);
file_put_contents(Registry::get('config.dir.design_backend') . "css/addons/{$addon}/front_icons.less", $file_content);
$extensions = ['eot', 'woff', 'ttf', 'svg'];
$fonts_dir = Registry::get('config.dir.design_backend') . "media/fonts/addons/{$addon}/";
fn_mkdir($fonts_dir);
for ($i = 0; $i < count($extensions); $i++) {
if (file_exists($repo_path . '/media/custom_fonts/uni2-icons.' . $extensions[$i])) {
copy($repo_path . '/media/custom_fonts/uni2-icons.' . $extensions[$i], $fonts_dir . 'uni2-icons.' . $extensions[$i]);
}
}
}

function fn_abt__unitheme2_check_is_installation_correct($is_mv = true)
{
$answ = ['is_ok' => true, 'descr' => 'ok'];
if ($is_mv) {
$theme_mv_addon = Registry::get('addons.abt__unitheme2_mv');
if (empty($theme_mv_addon)) {
$answ['is_ok'] = false;
$answ['descr'] = 'uninstalled';
}
if (!empty($theme_mv_addon) && $theme_mv_addon['status'] == 'D') {
$answ['is_ok'] = false;
$answ['descr'] = 'disabled';
}
}
return $answ;
}

function fn_abt__ut2_check_clone_theme($storefront_id = null)
{
$result = [];
if (!is_null($storefront_id)) {
$settings = fn_get_abt__ut2_settings();
if ($settings['general']['check_clone_theme'] == 'Y') {
foreach (Tygh::$app['storefront.repository']->find(['storefront_id' => $storefront_id])[0] as $storefront) {
if ($storefront->theme_name != 'abt__unitheme2'
&& file_exists(Registry::get('config.dir.design_frontend') . $storefront->theme_name . '/templates/addons/abt__unitheme2/hooks/grid/abt__content.override.tpl')
) {
$result[$storefront->storefront_id] = '<a target=\'_blank\' href=\'' . fn_url('themes.manage') . "'>{$storefront->name}</a>";
}
}
if (!empty($result)) {
if (count($result) == 1) {
fn_set_notification('W', __('warning'), __('abt__ut2.clone_theme.notification', ['[link]' => fn_url('abt__ut2.settings')]), 'S');
} else {
fn_set_notification('W', __('warning'), __('abt__ut2.clone_themes.notification', ['[storefront_list]' => implode(', ', $result), '[link]' => fn_url('abt__ut2.settings')]), 'S');
}
}
}
}
fn_abt__unitheme2_get_products_layout_pre();
return $result;
}

function fn_abt__unitheme2_get_products_layout_pre($params = [])
{
if (AREA == 'C') {
$device = Registry::get('settings.abt__device');
Registry::set('settings.Appearance.default_products_view', Registry::get("settings.abt__ut2.product_list.default_products_view.{$device}"));
}
}

function fn_abt__ut2_format_price($string, $currency, $span_id, $class)
{
if ($currency['decimals'] &&
$currency['decimals_separator'] &&
strpos($string, $currency['decimals_separator']) !== false &&
in_array($class, ['ty-price-num', 'ty-list-price ty-nowrap'])) {
static $price_format = '';
if (empty($price_format)) {
$price_format = Registry::get('settings.abt__ut2.general.price_format');
}
if ($price_format !== 'default') {
$regexp = '/' . $span_id . '"[\s]+class="' . $class . '">(.*)<\/span>/U';
$price_str = [];
preg_match($regexp, $string, $price_str);
$price_str = $price_str[1];
$tag = $price_format === 'superscript_decimals' ? 'sup' : 'sub';
$open_tag = '<' . $tag . '>';
$regexp = '/' . quotemeta($currency['decimals_separator']) . '(\d{' . $currency['decimals'] . '})$/';
$count = 0;
$tmp = preg_replace($regexp, $open_tag . '$1</' . $tag . '>', $price_str, -1, $count);
$string = str_replace($price_str, $tmp, $string);
if (\Tygh\Enum\YesNo::toBool($currency['after']) && $currency['symbol'] && $count && $currency['symbol'] !== $currency['thousands_separator']) {
$string = str_replace($currency['symbol'], $open_tag . $currency['symbol'] . '</' . $tag . '>', $string);
}
}
}
return $string;
}

function fn_abt__ut2_split_elements_for_menu($elements = [], $cols = 4, $items_in_big_cols = null, $big_cols_count = null)
{
if ($cols == 1) {
return [$elements];
}
$return = [];
$big_cols_local_counter = 0;
for ($i = 0; $i < $cols; $i++) {
if (!empty($elements)) {
$return[$i] = [];
$local_counter = 0;
$in_col = $items_in_big_cols >= 2 ? $items_in_big_cols - 1 : 1;
if ($big_cols_local_counter < $big_cols_count || !$big_cols_count) {
$in_col = $items_in_big_cols;
$big_cols_local_counter++;
}
foreach ($elements as $id => $elem) {
if ($local_counter == $in_col) {
break;
}
$local_counter++;
$return[$i][$id] = $elem;
unset($elements[$id]);
}
}
}
return $return;
}
