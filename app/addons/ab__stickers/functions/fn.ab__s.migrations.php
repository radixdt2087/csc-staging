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
use Tygh\Settings;
if (!defined('BOOTSTRAP')) {
die('Access denied');
}
function fn_ab__stickers_install()
{
$objects = [
['t' => '?:products',
'i' => [
['n' => 'ab__stickers_manual_ids', 'p' => 'mediumtext'],
['n' => 'ab__stickers_generated_ids', 'p' => 'mediumtext'],
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
$existing_indexes = db_get_array('SHOW INDEX FROM ' . $o['t'] . ' WHERE key_name = ?s', $index);
if (empty($existing_indexes) && !empty($keys)) {
db_query('ALTER TABLE ?p ADD INDEX ?p (?p)', $o['t'], $index, $keys);
}
}
}
}
}
}
Settings::instance()->updateValue('cron_key', fn_generate_password(15), 'ab__stickers');
fn_ab__stickers_migrate_v130_v120();
}
function fn_ab__stickers_migrate_v130_v120()
{
$description_table = '?:ab__sticker_descriptions';
$setting_type_exists = db_get_field("SHOW COLUMNS FROM {$description_table} WHERE `Field` = 'image_id'");
if (!empty($setting_type_exists)) {
db_query("ALTER TABLE {$description_table} DROP PRIMARY KEY");
db_query("ALTER TABLE {$description_table} DROP COLUMN image_id");
db_query("ALTER TABLE {$description_table} DROP INDEX sticker_lang_code_key");
db_query("ALTER TABLE {$description_table} ADD PRIMARY KEY (sticker_id, lang_code)");
}
}
