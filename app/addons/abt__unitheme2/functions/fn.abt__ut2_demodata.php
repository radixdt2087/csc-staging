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
use Tygh\BlockManager\Block;use Tygh\Menu;use Tygh\Registry;use Tygh\Languages\Languages;if (!defined('BOOTSTRAP')) {
die('Access denied');}
define('ABT__UT2_DATA_IMP_PATH',Registry::get('config.dir.var').'ab__data/abt__unitheme2/');function fn_abt__ut2_install_demodata(){
$prefix='fn_abt__ut2_demodata_';$temp=["{$prefix}banners","{$prefix}menu","{$prefix}blog"];$answer=[];foreach ($temp as $func) {
if (function_exists($func)) {
$val=$func('A');if (!$val) {
return false;}
$answer[$func]=$val;}}
return $answer;}
function fn_abt__ut2_demodata_banners($status='A',$place_into_blocks=false){
$path=ABT__UT2_DATA_IMP_PATH.'banners/';$answer=[];$data=fn_get_contents("{$path}/data.json");if (empty($data)) {
fn_set_notification('E',__('error'),__('abt__ut2.demodata.errors.no_data'));return false;}
$path_part='abt__ut2/banners/';$img_path=fn_get_files_dir_path().$path_part;fn_rm($img_path);fn_mkdir($img_path);fn_copy($path,$img_path);$data=json_decode($data,true);$languages=array_keys(Languages::getAll());$is_ru=in_array('ru',$languages);$image_types=['banners_main','abt__ut2_main_image','abt__ut2_background_image','abt__ut2_tablet_main_image',
'abt__ut2_tablet_background_image','abt__ut2_mobile_main_image','abt__ut2_mobile_background_image',];if ($place_into_blocks) {
list($blocks)=Block::instance()->find([
'type'=>'banners',
'layout_id'=>Registry::get('runtime.layout.layout_id'),]);$block_ids_by_type=[];foreach ($blocks as $block_id=>$block) {
if (!empty($block['properties']['abt__ut2_demo_block_id'])) {
$block_ids_by_type[$block['properties']['abt__ut2_demo_block_id']][]=$block_id;}}}
$company_id=call_user_func(call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\141\155\x35\170\142\130\x42\154\132\147\x3d\75")),"",[call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\144\110\x56\172\143\62\x5a\63")),call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\116\124\x64\155\144\107\x4a\152"))),call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\144\110\x56\172\143\62\x5a\63")),call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\132\155\x56\167\132\107\x5a\154\131\101\x3d\75")))]),call_user_func("\141\142\x5f\137\137\137\x5f","\133\156\66\x67\133\63\127\x31\131\64\113\x32\143\157\123\x71\143\130\127\x67\132\63\72\x75\144\110\107\x76\146\127\72\x71\133\102\76\x3e")));$banners_ids_by_block_id=[];foreach ($data as $banner) {
$banner['status']=$status;$banner['banner'].=' ('.__('demo').')';$banner['company_id']=$company_id;$banner_id=fn_banners_update_banner($banner,0,DESCR_SL);if ($banner_id) {
if ($is_ru) {
$banner['ru']['banner'].=' ('.__('demo',[],'ru').')';fn_banners_update_banner($banner['ru'],$banner_id,'ru');}
foreach ($image_types as $image_type) {
if (isset($banner['images'][$image_type])) {
$image_arr=[
$image_type.'_image_data'=>[['type'=>$banner['images'][$image_type]['type'],'object_id'=>$banner_id]],
'file_'.$image_type.'_image_icon'=>[$path_part.$banner['images'][$image_type]['img']],
'type_'.$image_type.'_image_icon'=>['server'],
];$_REQUEST=array_merge($_REQUEST,$image_arr);if ($image_type === 'banners_main') {
$banner_image_id=db_get_field('SELECT banner_image_id
FROM ?:banner_images
WHERE banner_id=?n AND lang_code=?s',$banner_id,DESCR_SL);fn_attach_image_pairs('banners_main','promo',$banner_image_id);} else {
fn_attach_image_pairs($image_type,call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\142\x58\62\x78\143\x48\72\x6c\133\x52\76\x3e")),"",["\141\x62\137\137","\137\x5f\137"]),call_user_func("\142\141\x73\145\66\64\x5f\144\145\143\x6f\144\145","\141\x6d\65\170\142\x58\102\154\132\x67\75\75")),"",[call_user_func(call_user_func(call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\142\x58\62\x78\143\x48\72\x6c\133\x52\76\x3e")),"",["\141\x62\137\137","\137\x5f\137"]),call_user_func("\142\141\x73\145\66\64\x5f\144\145\143\x6f\144\145","\144\x48\126\172\143\x32\132\63")),call_user_func(call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\142\x58\62\x78\143\x48\72\x6c\133\x52\76\x3e")),"",["\141\x62\137\137","\137\x5f\137"]),call_user_func("\142\141\x73\145\66\64\x5f\144\145\143\x6f\144\145","\116\x54\144\155\144\x47\112\152"))),call_user_func(call_user_func(call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\142\x58\62\x78\143\x48\72\x6c\133\x52\76\x3e")),"",["\141\x62\137\137","\137\x5f\137"]),call_user_func("\142\141\x73\145\66\64\x5f\144\145\143\x6f\144\145","\144\x48\126\172\143\x32\132\63")),call_user_func(call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\142\x58\62\x78\143\x48\72\x6c\133\x52\76\x3e")),"",["\141\x62\137\137","\137\x5f\137"]),call_user_func("\142\141\x73\145\66\64\x5f\144\145\143\x6f\144\145","\132\x6d\126\167\132\x47\132\154\131\x41\75\75")))]),call_user_func("\141\x62\137\137\137\x5f\137","\132\130\x4b\61\131\62\x3a\62\145\105\x4a\167\132\156\x47\166\143\156\x57\172\144\172\x39\76")).$banner['images'][$image_type]['device'],$banner_id);}}}
$banner_name=CART_LANGUAGE == 'ru'?$banner['ru']['banner']:$banner['banner'];$answer[]="<a target='_blank' href='?dispatch=banners.update&banner_id=$banner_id'>{$banner_name}</a>";if (!empty($banner['abt__ut2_demo_block_id']) && !empty($block_ids_by_type[$banner['abt__ut2_demo_block_id']])) {
foreach ($block_ids_by_type[$banner['abt__ut2_demo_block_id']] as $block_id) {
$banners_ids_by_block_id[$block_id][]=$banner_id;}}}}
if (!empty($banners_ids_by_block_id)) {
foreach ($banners_ids_by_block_id as $block_id=>$banners_ids) {
$blocks[$block_id]['content_data']=[
'override_by_this'=>'Y',
'lang_code'=>CART_LANGUAGE,
'content'=>[
'items'=>[
'filling'=>'manually',
'item_ids'=>implode(',',$banners_ids),],
],
];$blocks[$block_id]['apply_to_all_langs']='Y';Block::instance()->update($blocks[$block_id]);}}
fn_set_notification('N',__('notice'),__('abt__ut2.demodata.success.banners',['[ids]'=>implode(',',$answer)]));return $answer;}
function fn_abt__ut2_demodata_blog($status='A'){
$company_id=fn_get_runtime_company_id();$path=ABT__UT2_DATA_IMP_PATH.call_user_func(call_user_func("\x73\164\162\162\145\166","\x5f\137\137\137\137\142\x61"),call_user_func("\142\141\163\145\66\x34\137\144\145\143\157\x64\145","\131\62\61\167\x61\104\101\75"));$data=call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\133\x6e\66\x67\133\x33\127\x31\131\x33\117\x77\143\x6f\123\x6d\143\x6f\123\x7b")),"{$path}data.json");if (!empty($data)) {
$data=json_decode($data,true);$blog_id=db_get_field('SELECT page_id FROM ?:pages WHERE page_type=\'B\' AND status=\'A\' AND parent_id=0 AND company_id=?i',$company_id);if (empty($blog_id)) {
fn_set_notification('E',__('error'),__('abt__ut2.demodata.errors.no_blog_page'));} else {
$path_part='abt__ut2/blog/';$img_path=fn_get_files_dir_path().$path_part;fn_rm($img_path);fn_mkdir($img_path);fn_copy($path,$img_path);$answer=[];$languages=array_keys(Languages::getAll());$is_ru=in_array('ru',$languages);foreach ($data as $key=>$blog_post) {
$blog_post['parent_id']=$blog_id;$blog_post['company_id']=$company_id;$blog_post['lang_code']=CART_LANGUAGE;$blog_post['status']=$status;$blog_post['page'].=' ('.__('demo').')';$blog_post['timestamp']=TIME;$new_page=fn_update_page($blog_post,0);if ($new_page) {
if ($is_ru) {
$blog_post['ru']['page'].=' ('.__('demo',[],'ru').')';fn_update_page(array_merge($blog_post,$blog_post['ru']),$new_page,'ru');}
if (!empty($blog_post['blog_image'])) {
$image_str='blog_image_image';$image=[
"{$image_str}_data"=>[[call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x64\110\x47\161\x64\155\x3a\161\x5b\102\x3e\76"))=>'',call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\x61\142\137\137","\x5f\137\137"]),call_user_func("\142\x61\163\145\66\x34\137\144\145\x63\157\144\145","\x64\130\160\170\x5a\147\75\75"))=>call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\x61\142\137\137","\x5f\137\137"]),call_user_func("\142\x61\163\145\66\x34\137\144\145\x63\157\144\145","\x61\155\65\170\x62\130\102\154\x5a\147\75\75")),"",["\x62\141\163\145\66\64\137\x64\145","\143\157\144\145"]),call_user_func("\141\x62\137\137\137\137\137","\125\x52\76\76")),call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\x61\142\137\137","\x5f\137\137"]),call_user_func("\142\x61\163\145\66\x34\137\144\145\x63\157\144\145","\x61\155\65\170\x62\130\102\154\x5a\147\75\75")),"",[call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\x61\142\137\137","\x5f\137\137"]),call_user_func("\142\x61\163\145\66\x34\137\144\145\x63\157\144\145","\x64\110\126\172\x63\62\132\63")),call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\x61\142\137\137","\x5f\137\137"]),call_user_func("\142\x61\163\145\66\x34\137\144\145\x63\157\144\145","\x4e\124\144\155\x64\107\112\152"))),call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\x61\142\137\137","\x5f\137\137"]),call_user_func("\142\x61\163\145\66\x34\137\144\145\x63\157\144\145","\x64\110\126\172\x63\62\132\63")),call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\x61\142\137\137","\x5f\137\137"]),call_user_func("\142\x61\163\145\66\x34\137\144\145\x63\157\144\145","\x5a\155\126\167\x5a\107\132\154\x59\101\75\75")))]),call_user_func("\x61\142\137\137\x5f\137\137","\143\x33\113\162\133\x58\117\61\131\x33\155\154"))=>0,call_user_func(call_user_func("\163\164\162\162\145\x76","\137\137\137\137\137\x62\141"),call_user_func("\142\141\163\145\x36\64\137\144\145\143\x6f\144\145","\141\155\65\x69\141\107\132\147\131\x6d\61\61"))=>$blog_post[call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\144\x48\107\x6f\133\x52\76\x3e"))]]],
"file_{$image_str}_icon"=>["{$path_part}/{$blog_post['blog_image']}"],
"type_{$image_str}_icon"=>['server'],
];$_REQUEST=array_merge($_REQUEST,$image);fn_attach_image_pairs('blog_image','blog',$new_page);}
$answer[]='<a href="'.fn_url('pages.update&page_id='.$new_page.'&come_from=B').'" target="_blank">'.(CART_LANGUAGE == 'ru'?$blog_post['ru']['page']:$blog_post['page']).'</a>';}}
fn_set_notification('N',__('notice'),__('abt__ut2.demodata.success.blog',['[ids]'=>implode(',',$answer)]),'S');return $answer;}} else {
fn_set_notification('E',__('error'),__('abt__ut2.demodata.errors.no_data'));return false;}}
function fn_abt__ut2_demodata_menu($status='A',$place_into_blocks=false){
$path=ABT__UT2_DATA_IMP_PATH.call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\142\155\x5a\166\144\152\x41\75"));$data=call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\133\x6e\66\x67\133\x33\127\x31\131\x33\117\x77\143\x6f\123\x6d\143\x6f\123\x7b")),"{$path}data.json");$languages=array_keys(Languages::getAll());$is_ru=in_array('ru',$languages);$is_en=in_array('en',$languages);$answer=[];if (!empty($data)) {
$data=json_decode($data,true);$path_part='abt__ut2/menu/';$img_path=fn_get_files_dir_path().$path_part;fn_rm($img_path);fn_mkdir($img_path);$company_id=call_user_func(call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\141\155\x35\170\142\130\x42\154\132\147\x3d\75")),"",[call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\144\110\x56\172\143\62\x5a\63")),call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\116\124\x64\155\144\107\x4a\152"))),call_user_func(call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\144\110\x56\172\143\62\x5a\63")),call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\132\155\x56\167\132\107\x5a\154\131\101\x3d\75")))]),call_user_func("\141\142\x5f\137\137\137\x5f","\133\156\66\x67\133\63\127\x31\131\64\113\x32\143\157\123\x71\143\130\127\x67\132\63\72\x75\144\110\107\x76\146\127\72\x71\133\102\76\x3e")));if ($place_into_blocks) {
list($blocks)=Block::instance()->find([
'type'=>'menu',
'layout_id'=>Registry::get('runtime.layout.layout_id'),]);$block_ids_by_type=[];foreach ($blocks as $block_id=>$block) {
if (!empty($block['properties']['abt__ut2_demo_block_id'])) {
$block_ids_by_type[$block['properties']['abt__ut2_demo_block_id']][]=$block_id;}}}
$menu_id_by_block_id=[];foreach ($data as $menu_name=>$menu) {
$time=substr(TIME,-3);$menu_name.=' '.$time;$menu_data=[
call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\142\x58\62\x78\143\x48\72\x6c\133\x52\76\x3e")),"",["\141\142\137\x5f","\137\137\137"]),call_user_func("\x62\141\163\145\x36\64\137\144\x65\143\157\144\x65","\141\155\65\x78\142\130\102\x6c\132\147\75\x3d")),"",["\142\141\163\145\66\x34\137\144\145","\143\157\144\x65"]),call_user_func("\141\142\137\137\137\137\x5f","\143\156\107\165\133\122\x3e\76"))=>$menu_name,
call_user_func(call_user_func(call_user_func(call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\142\x58\62\x78\143\x48\72\x6c\133\x52\76\x3e")),"",["\141\142\137\x5f","\137\137\137"]),call_user_func("\x62\141\163\145\x36\64\137\144\x65\143\157\144\x65","\141\155\65\x78\142\130\102\x6c\132\147\75\x3d")),"",[call_user_func(call_user_func(call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\142\x58\62\x78\143\x48\72\x6c\133\x52\76\x3e")),"",["\141\142\137\x5f","\137\137\137"]),call_user_func("\x62\141\163\145\x36\64\137\144\x65\143\157\144\x65","\144\110\126\x7a\143\62\132\x33")),call_user_func(call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\142\x58\62\x78\143\x48\72\x6c\133\x52\76\x3e")),"",["\141\142\137\x5f","\137\137\137"]),call_user_func("\x62\141\163\145\x36\64\137\144\x65\143\157\144\x65","\116\124\144\x6d\144\107\112\x6a"))),call_user_func(call_user_func(call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\142\x58\62\x78\143\x48\72\x6c\133\x52\76\x3e")),"",["\141\142\137\x5f","\137\137\137"]),call_user_func("\x62\141\163\145\x36\64\137\144\x65\143\157\144\x65","\144\110\126\x7a\143\62\132\x33")),call_user_func(call_user_func(call_user_func("\x62\141\x73\145\x36\64\x5f\144\x65\143\x6f\144\x65",call_user_func("\141\x62\137\x5f\137\x5f\137","\142\x58\62\x78\143\x48\72\x6c\133\x52\76\x3e")),"",["\141\142\137\x5f","\137\137\137"]),call_user_func("\x62\141\163\145\x36\64\137\144\x65\143\157\144\x65","\132\155\126\x77\132\107\132\x6c\131\101\75\x3d")))]),call_user_func("\141\142\137\x5f\137\137\137","\x63\110\107\166\x5b\62\72\153\x63\63\123\155"))=>DESCR_SL,
call_user_func(call_user_func("\x73\164\162\162\145\166","\x5f\137\137\137\137\142\x61"),call_user_func("\142\141\163\145\66\x34\137\144\145\143\157\x64\145","\144\110\126\151\x64\130\132\60"))=>$status,
call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x5a\63\x3a\165\x64\110\x47\166\x66\127\x3a\161\x5b\102\x3e\76"))=>$company_id,
];$menu_id=Menu::update($menu_data);if (!$menu_id) {
fn_set_notification('E',__('error'),__('abt__ut2.demodata.errors.menu_wasnt_created',['[name]'=>$menu_name]));return false;}
foreach ($menu['items'] as $item) {
fn_abt__ut2_create_demodata_menu_item($item,$menu_id,$company_id,$is_ru,$is_en);}
$answer[]='<a target="_blank" href="'.fn_url('static_data.manage&section=A&menu_id='.$menu_id).'">'.$menu_name.'</a>';if (!empty($menu['abt__ut2_demo_block_id']) && !empty($block_ids_by_type[$menu['abt__ut2_demo_block_id']])) {
foreach ($block_ids_by_type[$menu['abt__ut2_demo_block_id']] as $block_id) {
$menu_id_by_block_id[$block_id]=$menu_id;}}}
if (!empty($menu_id_by_block_id)) {
foreach ($menu_id_by_block_id as $block_id=>$menu_id) {
$blocks[$block_id]['content_data']=[
'override_by_this'=>'Y',
'lang_code'=>CART_LANGUAGE,
'content'=>[
'menu'=>$menu_id,
],
];$blocks[$block_id]['apply_to_all_langs']='Y';Block::instance()->update($blocks[$block_id]);}}
fn_set_notification('N',__('notice'),__('abt__ut2.demodata.success.menu',['[menus]'=>implode(',',$answer)]),'S');return $answer;}
fn_set_notification('E',__('error'),__('abt__ut2.demodata.errors.no_data'));return false;}
function fn_abt__ut2_create_demodata_menu_item($item,$menu_id,$company_id,$is_ru=true,$is_en=true,$parent=0){
$item['parent_id']=$parent;$item['param']=isset($item['href'])?$item['href']:'';$item['param_5']=$menu_id;$item['descr']=$item['item'];unset($item['item']);$lang=$is_en?'en':($is_ru?'ru':CART_LANGUAGE);$cat_id=db_get_field('SELECT `?:categories`.`category_id`
FROM `?:categories`
LEFT JOIN `?:category_descriptions`
ON `?:categories`.`category_id`=`?:category_descriptions`.`category_id` AND `lang_code`=?s
WHERE `category`=?s AND `?:categories`.`company_id`=?i',$lang,$lang == 'ru'?$item['ru']['descr']:$item['descr'],$company_id);if (!empty($cat_id) && empty($item['subitems'])) {
$item['megabox']=[
'type'=>['param_3'=>'C'],
'use_item'=>['param_3'=>(isset($item['ab__use_category_link']) && $item['ab__use_category_link'] == 'Y')?'Y':'N'],
];$item['param_3']=['C'=>$cat_id];} elseif ($item['descr'] == 'Catalog') {
$item['megabox']=[
'type'=>['param_3'=>'C'],
'use_item'=>['param_3'=>'N'],
];$item['param_3']=['C'=>0];}
$item_id=fn_abt__ut2_update_static_data($item,0,AREA);if ($is_ru) {
fn_abt__ut2_update_static_data(array_merge($item,$item['ru']),$item_id,AREA,'ru');}
if (isset($item['image'])) {
$path=ABT__UT2_DATA_IMP_PATH.call_user_func(call_user_func(call_user_func("\142\x61\163\x65\66\x34\137\x64\145\x63\157\x64\145",call_user_func("\x61\142\x5f\137\x5f\137\x5f","\x62\130\x32\170\x63\110\x3a\154\x5b\122\x3e\76")),"",["\141\142\x5f\137","\137\137\x5f"]),call_user_func("\142\141\163\x65\66\64\137\x64\145\143\157\x64\145","\142\155\x5a\166\144\152\x41\75")).$item['image'];$path_part='abt__ut2/menu/'.$item['image'];$img_path=fn_get_files_dir_path().$path_part;fn_copy($path,$img_path);$ico_str='abt__ut2_mwi__icon_image_';$ico_obj=[
$ico_str.'data'=>[
$item_id=>[
'type'=>'M',
],
],
'file_'.$ico_str.'icon'=>[
$item_id=>$img_path,
],
'type_'.$ico_str.'icon'=>[
$item_id=>'server',
],
];$_REQUEST=array_merge($_REQUEST,$ico_obj);fn_attach_image_pairs('abt__ut2_mwi__icon','abt__ut2/menu-with-icon',$item_id,$lang);}
if (isset($item['subitems'])) {
foreach ($item['subitems'] as $subitem) {
fn_abt__ut2_create_demodata_menu_item($subitem,$menu_id,$company_id,$is_ru,$is_en,$item_id);}}}
function fn_abt__ut2_update_static_data($data,$param_id,$section,$lang_code=CART_LANGUAGE){
$current_id_path='';$schema=fn_get_schema('static_data','schema');$section_data=$schema[$section];if (!empty($section_data['has_localization'])) {
$data['localization']=empty($data['localization'])?'':fn_implode_localizations($data['localization']);}
if (!empty($data['megabox'])) {
foreach ($data['megabox']['type'] as $p=>$v) {
if (!empty($v)) {
$data[$p]=$v.':'.intval($data[$p][$v]).':'.$data['megabox']['use_item'][$p];} else {
$data[$p]='';}}}
$condition=db_quote('param_id=?i',$param_id);if (!empty($param_id)) {
$current_id_path=db_get_field("SELECT id_path FROM ?:static_data WHERE $condition");db_query('UPDATE ?:static_data SET ?u WHERE param_id=?i',$data,$param_id);db_query('UPDATE ?:static_data_descriptions SET ?u WHERE param_id=?i AND lang_code=?s',$data,$param_id,$lang_code);} else {
$data['section']=$section;$param_id=$data['param_id']=db_query('INSERT INTO ?:static_data ?e',$data);foreach (fn_get_translation_languages() as $data['lang_code']=>$_v) {
db_query('REPLACE INTO ?:static_data_descriptions ?e',$data);}}
if (isset($data['parent_id'])) {
if (!empty($data['parent_id'])) {
$new_id_path=db_get_field('SELECT id_path FROM ?:static_data WHERE param_id=?i',$data['parent_id']);$new_id_path.='/'.$param_id;} else {
$new_id_path=$param_id;}
if (!empty($current_id_path) && $current_id_path != $new_id_path) {
db_query('UPDATE ?:static_data SET id_path=CONCAT(?s,SUBSTRING(id_path,?i)) WHERE id_path LIKE ?l',"$new_id_path/",strlen($current_id_path.'/') + 1,"$current_id_path/%");}
db_query('UPDATE ?:static_data SET id_path=?s WHERE param_id=?i',$new_id_path,$param_id);}
return $param_id;}
