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
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
function fn_abt__unitheme2_dispatch_assign_template()
{
Registry::set('settings.abt__ut2', fn_get_abt__ut2_settings());
Registry::set('settings.abt__device', fn_abt__ut2_get_device_type());
}
function fn_abt__ut2_get_device_type()
{
static $device_type = '';
if (!empty($device_type)) {
return $device_type;
}
if (defined('CONSOLE') || !isset($_SERVER['HTTP_USER_AGENT']) || !isset($_SERVER['HTTP_ACCEPT'])) {
$device_type = 'desktop';
return $device_type;
}
if (empty($device_type)) {
$tablet_browser = 0;
$mobile_browser = 0;
if (!empty($_SERVER['HTTP_USER_AGENT'])) {
$http_user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$mobile_agents = ['w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi',
'avan', 'benq', 'bird', 'blac', 'blaz', 'brew', 'cell', 'cldc', 'cmd-',
'dang', 'doco', 'eric', 'hipt', 'inno', 'ipaq', 'java', 'jigs', 'kddi',
'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-', 'maui', 'maxo', 'midp',
'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-', 'newt', 'noki',
'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox', 'qwap', 'sage',
'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar', 'sie-',
'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi',
'wapp', 'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-', ];
if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/', $http_user_agent)) {
$tablet_browser++;
}
if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/', $http_user_agent)) {
$mobile_browser++;
}
if (in_array(substr($http_user_agent, 0, 4), $mobile_agents)) {
$mobile_browser++;
}
if (strpos($http_user_agent, 'opera mini') > 0) {
$mobile_browser++;
$stock_ua = isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : '');
if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/', strtolower($stock_ua))) {
$tablet_browser++;
}
}
}
if (!empty($_SERVER['HTTP_ACCEPT'])) {
if (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0
|| isset($_SERVER['HTTP_X_WAP_PROFILE'])
|| isset($_SERVER['HTTP_PROFILE'])) {
$mobile_browser++;
}
}
$device_type = ($tablet_browser ? 'tablet' : ($mobile_browser ? 'mobile' : 'desktop'));
}
return $device_type;
}
function fn_get_abt__ut2_settings($type = '', $full_info = false, $style = '', $from_cache = true)
{
static $settings;
$lang_code = DESCR_SL;
$cache_prefix = 'abt__ut2';
$key = "_{$lang_code}_" . md5('settings' . $type . ($full_info ? 'true' : 'false') . $style);
$cache_tables = ['abt__ut2_settings', 'abt__ut2_less_settings'];
$cache_level = ['static'];
if (fn_allowed_for('MULTIVENDOR')) {
$cache_tables[] = 'companies';
}
if ($type != 'less') {
$cache_level[] = 'storefront';
}
Registry::registerCache([$cache_prefix, $key], $cache_tables, Registry::cacheLevel([$cache_level]), true);
$is_device = function ($i) {
return (empty($i['is_for_all_devices']) || $i['is_for_all_devices'] != 'Y');
};
$is_group = function ($i) {
return (!empty($i['is_group']) && $i['is_group'] == 'Y' && !empty($i['items']));
};
if (empty($settings[$key])) {
if ($cache = Registry::get($key) && !empty($cache) && is_array($cache) && $from_cache) {
$settings[$key] = $cache;
} else {
$schema_settings = fn_get_schema('abt__ut2_settings', ($type == 'less' ? 'less_' : '') . 'objects');
ksort($schema_settings);
uasort($schema_settings, function ($a, $b) {
if (empty($a['position'])) {
$a['position'] = 10000;
}
if (empty($b['position'])) {
$b['position'] = 10000;
}
return version_compare($a['position'], $b['position']);
});
foreach ($schema_settings as &$schema_setting) {
ksort($schema_setting['items']);
uasort($schema_setting['items'], function ($a, $b) {
return version_compare($a['position'], $b['position']);
});
}
$db_settings = [];
if ($type == 'less') {
$db_temp = db_get_array('SELECT * FROM ?:abt__ut2_less_settings WHERE style = ?s', $style);
} else {
$db_temp = db_get_array('SELECT * FROM ?:abt__ut2_settings WHERE lang_code = ?s ?p', $lang_code, fn_get_company_condition('?:abt__ut2_settings.company_id'));
}
if (!empty($db_temp)) {
foreach ($db_temp as $db_item) {
$db_settings[$db_item['section']][$db_item['name']] = unserialize($db_item['value']);
}
}
foreach ($schema_settings as $s => $section) {
if (!empty($section['items'])) {
$items = [];
foreach ($section['items'] as $k => $i) {
if ($is_group($i)) {
if ($full_info) {
$items[$k] = $i;
foreach ($items[$k]['items'] as $sub_k => $sub_i) {
if (isset($db_settings[$s][$k . '.' . $sub_k])) {
if ($is_device($sub_i) && isset($items[$k]['items'][$sub_k]['value'])) {
$items[$k]['items'][$sub_k]['value'] = array_merge($items[$k]['items'][$sub_k]['value'], $db_settings[$s][$k . '.' . $sub_k]);
} else {
$items[$k]['items'][$sub_k]['value'] = $db_settings[$s][$k . '.' . $sub_k];
}
} elseif ($type == 'less' && !empty($sub_i['value_styles'][$style])) {
$items[$k]['items'][$sub_k]['value'] = $sub_i['value_styles'][$style];
}
}
} else {
foreach ($i['items'] as $sub_k => $sub_i) {
$items[$k][$sub_k] = $sub_i['value'];
if (isset($db_settings[$s][$k . '.' . $sub_k])) {
if ($is_device($sub_i) && isset($items[$k][$sub_k])) {
$items[$k][$sub_k] = array_merge($items[$k][$sub_k], $db_settings[$s][$k . '.' . $sub_k]);
} else {
$items[$k][$sub_k] = $db_settings[$s][$k . '.' . $sub_k];
}
} elseif ($type == 'less' && !empty($sub_i['value_styles'][$style])) {
$items[$k][$sub_k] = $sub_i['value_styles'][$style];
}
if (!empty($sub_i['suffix']) && $type != 'less') {
if ($is_device($sub_i)) {
$items[$k][$sub_k]['desktop'] .= $sub_i['suffix'];
$items[$k][$sub_k]['tablet'] .= $sub_i['suffix'];
$items[$k][$sub_k]['mobile'] .= $sub_i['suffix'];
} else {
$items[$k][$sub_k] .= $sub_i['suffix'];
}
}
}
}
} else {
if ($full_info) {
$items[$k] = $i;
if (isset($db_settings[$s][$k])) {
if ($is_device($i) && isset($items[$k]['value'])) {
$items[$k]['value'] = array_merge($items[$k]['value'], $db_settings[$s][$k]);
} else {
$items[$k]['value'] = $db_settings[$s][$k];
}
} elseif ($type == 'less' && !empty($i['value_styles'][$style])) {
$items[$k]['value'] = $i['value_styles'][$style];
}
} else {
$items[$k] = $i['value'];
if (isset($db_settings[$s][$k])) {
if ($is_device($i) && isset($items[$k])) {
$items[$k] = array_merge($items[$k], $db_settings[$s][$k]);
} else {
$items[$k] = $db_settings[$s][$k];
}
} elseif ($type == 'less' && !empty($i['value_styles'][$style])) {
$items[$k] = $i['value_styles'][$style];
}
if (!empty($i['suffix']) && $type != 'less') {
if ($is_device($i)) {
$items[$k]['desktop'] .= $i['suffix'];
$items[$k]['tablet'] .= $i['suffix'];
$items[$k]['mobile'] .= $i['suffix'];
} else {
$items[$k] .= $i['suffix'];
}
}
}
}
}
if (!empty($items)) {
$settings[$key][$s] = $items;
}
}
}
Registry::set($key, $settings[$key]);
}
}
return $settings[$key];
}
function fn_update_abt__ut2_settings($data, $type = '', $style = '')
{
$lang_code = DESCR_SL;
$company_id = fn_get_runtime_company_id();
$settings = fn_get_abt__ut2_settings($type, true, $style, false);
foreach ($settings as $section => $items) {
foreach ($items as $k => $item) {
if (!empty($item['is_group']) && $item['is_group'] == 'Y' && !empty($item['items'])) {
foreach ($item['items'] as $sub_k => $sub_item) {
$name = $k . '.' . $sub_k;
fn_update_abt__ut2_setting($type, $section, $name, $company_id, $lang_code, $sub_item, $data[$section][$name], $style);
}
} else {
$name = $k;
fn_update_abt__ut2_setting($type, $section, $name, $company_id, $lang_code, $item, $data[$section][$name], $style);
}
}
}
return true;
}
function fn_update_abt__ut2_setting($type, $section, $name, $company_id, $lang_code, $item, $v, $style)
{
$v = serialize($v);
$d = [
'section' => $section,
'name' => $name,
'company_id' => $company_id,
'lang_code' => $lang_code,
'value' => $v,
];
if ($type == 'less') {
$d['style'] = $style;
db_query('REPLACE INTO ?:abt__ut2_less_settings ?e', $d);
if ($section != 'addons') {
$less_file = Registry::get('config.dir.design_frontend') . 'abt__unitheme2/styles/data/' . $style;
if (file_exists($less_file)) {
$less = fn_get_contents($less_file);
if (!empty($less)) {
$v = unserialize($v);
if ($item['type'] == 'checkbox') {
$v = ($v == 'Y') ? 'true' : 'false';
}
$v .= (!empty($item['suffix'])) ? $item['suffix'] : '';
$name = str_replace('.', '_', $name);
$less_var = '@abt__ut2_' . $section . '_' . $name . ': ' . $v . ";\n";
if (preg_match('/@abt__ut2_' . $section . '_' . $name . ':.*?;/m', $less)) {
$less = preg_replace('/(*ANYCRLF)@abt__ut2_' . $section . '_' . $name . ':.*?;$/m', str_replace("\n", '', $less_var), $less);
} else {
$less .= $less_var;
}
fn_put_contents($less_file, $less);
}
}
} else {
list($addon) = explode('.', $name);
$less_file = Registry::get('config.dir.root') . "/design/themes/abt__unitheme2/css/addons/{$addon}/styles/data/{$style}";
$v = unserialize($v);
if ($item['type'] == 'checkbox') {
$v = ($v == 'Y') ? 'true' : 'false';
}
$v .= (!empty($item['suffix'])) ? $item['suffix'] : '';
$name = str_replace('.', '_', $name);
$less_var = '@abt__ut2_' . $section . '_' . $name . ': ' . $v . ";\n";
if (!file_exists($less_file)) {
fn_put_contents($less_file, $less_var);
} else {
$less = fn_get_contents($less_file);
if (preg_match('/@abt__ut2_' . $section . '_' . $name . ':.*?;/m', $less)) {
$less = preg_replace('/(*ANYCRLF)@abt__ut2_' . $section . '_' . $name . ':.*?;$/m', str_replace("\n", '', $less_var), $less);
} else {
$less .= $less_var;
}
fn_put_contents($less_file, $less);
}
}
fn_clear_cache('assets');
} else {
$n = db_get_field('SELECT `name` FROM ?:abt__ut2_settings
WHERE `section` = ?s AND `name` = ?s ?p', $section, $name, fn_get_company_condition('?:abt__ut2_settings.company_id'));
if (empty($n)) {
foreach (fn_get_translation_languages() as $d['lang_code'] => $l) {
db_query('INSERT INTO ?:abt__ut2_settings ?e', $d);
}
} else {
db_query('REPLACE INTO ?:abt__ut2_settings ?e', $d);
}
if (!isset($item['multilanguage']) || $item['multilanguage'] == 'N') {
db_query('UPDATE ?:abt__ut2_settings SET value = ?s WHERE `section` = ?s AND `name` = ?s ?p', $v, $section, $name, fn_get_company_condition('?:abt__ut2_settings.company_id'));
}
}
return true;
}
function fn_abt__ut2_get_empty_core_color_variables($show_notification = false)
{
$empty_color_variables = [];
if (empty($_SESSION['ab__color_vars_checked']) && defined('DEVELOPMENT') && DEVELOPMENT == true) {
$_SESSION['ab__color_vars_checked'] = true;
$view = Tygh::$app['view'];
$variables_list = $view->getTemplateVars('props_schema');
$current_style_list = $view->getTemplateVars('current_style')['data'];
$style_name = $view->getTemplateVars('current_style')['name'];
$color_vars = array_merge($variables_list['colors']['fields'], fn_abt__ut2_get_theme_less_color_variables($show_notification, $style_name));
foreach ($color_vars as $color_var_id => $color_var) {
if (empty($current_style_list[$color_var_id])) {
$_tmp = (strpos($color_var_id, 'abt__ut2') !== false) ? 'theme' : 'core';
$empty_color_variables[$_tmp][$color_var_id] = ($show_notification && !empty($color_var['link']) ? "<a href='{$color_var['link']}' target='_blank'>" : '') .
str_replace(['<br>', '<br/>'], ' ', __($color_var['description'])) .
($show_notification ? '</a>' : '');
}
}
if ($show_notification && !empty($empty_color_variables)) {
$msg = __('abt__ut2.less_settings.is_absent', ['[style]' => $style_name]);
if (!empty($empty_color_variables['core'])) {
$msg .= '<br/><b>' . __('abt__ut2.less_settings.is_absent.core') . '</b>:<ul><li>' . implode('</li><li>', $empty_color_variables['core']) . '</li></ul>';
}
if (!empty($empty_color_variables['theme'])) {
$msg .= '<br/><b>' . __('abt__ut2.less_settings.is_absent.theme') . '</b>:<ul><li>' . implode('</li><li>', $empty_color_variables['theme']) . '</li></ul>';
}
$msg .= '<br/>' . __('abt__ut2.less_settings.is_absent.recomend_to_add');
fn_set_notification('W', __('warning'), $msg);
}
}
return $show_notification === true ? '' : $empty_color_variables;
}
function fn_abt__ut2_get_theme_less_color_variables($show_notification = false, $style = '')
{
$ret = [];
foreach (fn_get_schema('abt__ut2_settings', 'less_objects') as $section_name => $section) {
foreach ($section['items'] as $name => $val) {
if (isset($val['type']) && $val['type'] === 'colorpicker') {
$ret["abt__ut2_{$section_name}_{$name}"] = ['description' => "abt__ut2.less_settings.$section_name.$name"];
if ($show_notification) {
$ret["abt__ut2_{$section_name}_{$name}"]['link'] =
fn_url('abt__ut2.less_settings&style=' . $style . '.less&selected_section=' . $section_name
. "&highlighted={$section_name}.{$name}", 'A');
}
} elseif (!empty($val['items'])) {
foreach ($val['items'] as $subsection_var_name => $subsection_var_val) {
if (isset($subsection_var_val['type']) && $subsection_var_val['type'] === 'colorpicker') {
$ret["abt__ut2_{$section_name}_{$name}_{$subsection_var_name}"] = ['description' => "abt__ut2.less_settings.$section_name.$name.$subsection_var_name"];
if ($show_notification) {
$ret["abt__ut2_{$section_name}_{$name}_{$subsection_var_name}"]['link'] =
fn_url('abt__ut2.less_settings&style=' . $style . '.less&selected_section=' . $section_name
. "&highlighted={$section_name}.{$name}.{$subsection_var_name}", 'A');
}
}
}
}
}
}
return $ret;
}

function fn_abt__unitheme2_styles_block_files(&$styles)
{
if (AREA == 'C' && Registry::get('runtime.layout.theme_name') == 'abt__unitheme2') {
$style_id = Registry::get('runtime.layout.style_id') . '.less';
$settings = fn_get_abt__ut2_settings('less', true, $style_id, true);
$temp_styles = [];
if (!empty($settings['addons'])) {
foreach ($styles as $style) {
if (preg_match('@design/themes/abt__unitheme2/css/addons/(' . implode('|', array_keys($settings['addons'])) . ')/styles.less$@', $style['file'], $match)) {
$addon = $match[1];
$root = Registry::get('config.dir.root') . '/';
$style_file = "design/themes/abt__unitheme2/css/addons/{$addon}/styles/data/{$style_id}";
$new_style['file'] = $root . $style_file;
$new_style['relative'] = $style_file;
$new_style['media'] = '';
if (!file_exists($root . $style_file) && !empty($settings['addons'][$addon]['items'])) {
$d = [];
foreach ($settings['addons'][$addon]['items'] as $setting => $setting_data) {
$value = $setting_data['value'];
if ($setting_data['type'] == 'checkbox') {
$value = $setting_data['value'] == 'Y' ? 'true' : 'false';
}
$value .= (!empty($setting_data['suffix'])) ? $setting_data['suffix'] : '';
$d[] = sprintf('@abt__ut2_addons_%s_%s: %s;', $addon, $setting, $value);
}
fn_put_contents($root . $style_file, implode("\n", $d) . "\n");
}
$temp_styles[] = $new_style;
}
$temp_styles[] = $style;
}
}
if (!empty($temp_styles)) {
$styles = $temp_styles;
}
}
}
