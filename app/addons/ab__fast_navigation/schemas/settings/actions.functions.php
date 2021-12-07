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
use Tygh\Registry;if (!class_exists('ABXmlScheme')) {
class ABXmlScheme{
private $addon='';private $xml;public function __construct($addon){
$this->addon=$addon;$this->xml=$this->readXml();return $this;}
private function readXml(){
$filename=Registry::get('config.dir.addons').$this->addon.'/addon.xml';if (file_exists($filename)) {
return simplexml_load_file($filename);}
return false;}
private function getAddonName($addon=''){
$name='';if (!empty($addon)) {
$name=db_get_field('SELECT name FROM ?:addon_descriptions WHERE addon=?s AND lang_code=?s',$addon,CART_LANGUAGE);}
return $name;}
public function checkDependencies(){
$result=true;if (!empty($this->xml) && isset($this->xml->ab->compatibility->dependencies)) {
foreach ((array) $this->xml->ab->compatibility->dependencies as $addon=>$conditions) {
$conditions=(array) $conditions;$status=Registry::get("addons.{$addon}.status");$version=fn_get_addon_version($addon);if (!empty($conditions) && $status == 'A' && !$this->checkVersion($version,$conditions)) {
$result=false;$min=$max='';if (!empty($conditions['min']) && empty($conditions['max'])) {
$min=' v'.$conditions['min'];$max=' and higher';}
if (!empty($conditions['min']) && !empty($conditions['max'])) {
$min=' from v'.$conditions['min'];$max=' to v'.$conditions['max'];}
if (empty($conditions['min']) && !empty($conditions['max'])) {
$max=' up to v'.$conditions['max'];}
$replaces=[
'[main_addon]'=>$this->getAddonName($this->addon),'[addon]'=>$this->getAddonName($addon),'[min]'=>$min,
'[max]'=>$max,
];$msg=str_replace(array_keys($replaces),$replaces,'To activate \'[main_addon]\' add-on requires \'[addon]\' add-on version[min][max]');fn_set_notification('E',__('error'),$msg);}}}
return $result;}
private function checkVersion($version,$conditions){
$result=true;if ($result && !empty($conditions['min']) && !$this->compareVersion($version,$conditions['min'],'>=')) {
$result=false;}
if ($result && !empty($conditions['max']) && !$this->compareVersion($version,$conditions['max'],'<=')) {
$result=false;}
return $result;}
private static function compareVersion($a,$b,$operator=null){
$format_versions=function ($a,$b) {
$replaces=['43'=>'4.3','44'=>'4.4','45'=>'4.5','46'=>'4.6','47'=>'4.7','48'=>'4.8','49'=>'4.9'];$a=str_replace(array_keys($replaces),$replaces,$a);$b=str_replace(array_keys($replaces),$replaces,$b);return [$a,$b];};list($a,$b)=$format_versions($a,$b);$replace_chars=function ($m){return ord(strtolower($m[1])); };$a=preg_replace('#([0-9]+)([a-z]+)#i','$1.$2',$a);$b=preg_replace('#([0-9]+)([a-z]+)#i','$1.$2',$b);$a=preg_replace_callback('#\b([a-z]{1})\b#i',$replace_chars,$a);$b=preg_replace_callback('#\b([a-z]{1})\b#i',$replace_chars,$b);return \version_compare($a,$b,$operator);}}}
function fn_settings_actions_addons_ab__fast_navigation(&$a,$b){
$a == call_user_func("\142\x61\163\145\66\x34\137\144\145\x63\157\144\145",call_user_func("\x61\142\137\137\x5f\137\137","\x52\122\76\76")) && !(new ABXmlScheme(substr(__FUNCTION__,27)))->checkDependencies() && $a=call_user_func(call_user_func(call_user_func("\142\x61\163\145\66\x34\137\144\145\x63\157\144\145",call_user_func("\x61\142\137\137\x5f\137\137","\x62\130\62\170\x63\110\72\154\x5b\122\76\76")),"",["\x61\x62\x5f\x5f","\x5f\x5f\x5f"]),call_user_func("\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65","\x52\x51\x3d\x3d"));}