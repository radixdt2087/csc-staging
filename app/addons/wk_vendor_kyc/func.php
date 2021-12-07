<?php
/******************************************************************
# Vendor Kyc   ---      Vendor KYC                        *
# ----------------------------------------------------------------*                                   *
# copyright Copyright (C) 2010 webkul.com. All Rights Reserved.   *
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL     *
# Websites: http://webkul.com                                     *
*******************************************************************
*/
use Tygh\Registry;
use Tygh\Settings;
use Tygh\Mailer;
use Tygh\Navigation\LastView;
use Tygh\Storage;

/**
   *Set notification at add-on installation time regarding language file.
*/

function fn_wk_vendor_kyc_install()
{
  fn_set_notification('I',__("wk_vendor_kyc_note"),__("except_english_langauge"));
}

/**
   *Fuction which get addon setting data.
   *
   * @param  int  $company_id   Company Id
   * @return array    $cache        Setting Data
   *
*/

function fn_wk_vendor_kyc_get_setting_data($company_id = null)
{
  static $cache;
  if (empty($cache['settings_' . $company_id])) {
    $settings = Settings::instance()->getValue('wk_vendor_kyc_tpl_data', '', $company_id);
    $settings = unserialize($settings);

    if (empty($settings)) {
      $settings = array();
    }
    $cache['settings_' . $company_id] = $settings;
  }
  return $cache['settings_' . $company_id];
}

/**
*
  *Function which get all kyc type.
  *
  * @param  array      $params Request           Data
  * @param  Integer    $items_per_page           Items Per Page
  * @param  String     $lang_code                Current Cart Langauge
  * @return array      $wk_vendor_kyc_type_data  return All kYC Types
  * @return array      $params Sorting           Data
  *
*/
function fn_wk_vendor_kyc_get_kyc_type($params,$items_per_page = 0, $lang_code = CART_LANGUAGE)
{
	 $params = LastView::instance()->update('wk_vendor_kyc', $params);
    // Set default values to input params
   $default_params = array (
        'page' => 1,
        'items_per_page' => $items_per_page
    );
   $params = array_merge($default_params, $params);
    $condition = $join = $group =  $limit='';   
      // Define fields that should be retrieved     
      $fields = array (
          '?:wk_vendor_kyc_type.kyc_id',
          '?:wk_vendor_kyc_type.status',
          '?:wk_vendor_kyc_type.is_required',
          '?:wk_vendor_kyc_type_description.description'
      );
     $sortings = array (
    'kyc_id' => '?:wk_vendor_kyc_type.kyc_id',
    'status'=>'?:wk_vendor_kyc_type.status',
    'is_required'=>'?:wk_vendor_kyc_type.is_required',
    'kyc_type'=>'?:wk_vendor_kyc_type_description.description'

    );    
     if(isset($params['kyc_id']) && !empty($params['kyc_id']))
      {
        $condition .= db_quote("AND kyc_id =?i",$params['kyc_id']);
      }
      if (isset($params['kyc_type'])&& !empty($params['kyc_type'])) {
        
          $condition .= db_quote(" AND description LIKE ?l", "%{$params['kyc_type']}%");
      } 
    $sorting =db_sort($params, $sortings, 'kyc_id', 'asc');
    if (!empty($params['items_per_page'])) {
    $params['total_items'] = db_get_field("SELECT COUNT(DISTINCT(?:wk_vendor_kyc_type.kyc_id)) FROM ?:wk_vendor_kyc_type");
      $limit = db_paginate($params['page'], $params['items_per_page'],$params['total_items']);
    } 
    $wk_vendor_kyc_type_data=db_get_array("SELECT " . implode(', ', $fields) . " FROM ?:wk_vendor_kyc_type LEFT JOIN ?:wk_vendor_kyc_type_description ON ?:wk_vendor_kyc_type_description.object_id = ?:wk_vendor_kyc_type.kyc_id AND lang_code = ?s WHERE 1 $condition $sorting  $limit",$lang_code);
    return array($wk_vendor_kyc_type_data, $params);
}
/**
  *
  *For Deleting KYC Type
  *
  * @param  int     $kyc_id    Unique Kyc Id
  *
*/
function fn_wk_vendor_kyc_delete_kyc_type($kyc_id){
	db_query("DELETE FROM ?:wk_vendor_kyc_type WHERE kyc_id=?i",$kyc_id);
	db_query("DELETE FROM ?:wk_vendor_kyc_type_description WHERE object_id=?i",$kyc_id);
}
/**
*
  *
  *For Uploading KYC
  *
  * @param   array     $kyc_data    Upload Kyc Data
  * @param   array     $files       Uploaded KYC files
  * @return  int       $kyc_id      Return Kyc Id
  *
*/
function fn_wk_vendor_kyc_upload_kyc($kyc_data,$files = null)
{
  $send_notification=false;
  $company_id=0;
  if(isset($kyc_data['upload_kyc_data']['company_id'])){
    $company_id=$kyc_data['upload_kyc_data']['company_id'];
  }
  $directory='kycs'.'/'.$company_id;
  $kyc_id=0;
  if ($files != null) {
      $uploaded_datas = $files;
  } else {
      $uploaded_datas = fn_filter_uploaded_data('kyc_attach_file');
  }
  $get_uploade_file_paths=array();
  foreach ($uploaded_datas as $key => $value) {
    $get_uploade_file_paths[$key]=$value['name'];
  }
   $data=array(
    'kyc_type'=>$kyc_data['upload_kyc_data']['kyc_type'],
    'kyc_id_number'=>$kyc_data['upload_kyc_data']['kyc_id'],
    'company_id'=>$company_id,
    'upload_date'=>TIME,
        );
   $is_required=db_get_field("SELECT is_required FROM ?:wk_vendor_kyc_type WHERE kyc_id=?i",$kyc_data['upload_kyc_data']['kyc_type']);
   if($is_required =='Y'){
     $data['is_required']=1;
   }else
   {
    $data['is_required']=0;
   }
   if(isset($kyc_data['kyc_id'])&& !empty($kyc_data['kyc_id']))
   {
      $kyc_id=$kyc_data['kyc_id'];
      db_query("UPDATE ?:wk_vendor_kyc_data SET ?u WHERE kyc_id=?i",$data,$kyc_data['kyc_id']);
      $data=array(
        'kyc_name'=>$kyc_data['upload_kyc_data']['kyc_name']
        );
      foreach (fn_get_translation_languages() as $data['lang_code'] => $_v) {
              db_query("UPDATE  ?:wk_vendor_kyc_data_description SET ?u WHERE object_id=?i",$data,$kyc_id);
        }
   }
   else
   {
      $kyc_id=db_query("INSERT INTO ?:wk_vendor_kyc_data ?e",$data);
      $data=array(
        'object_id'=>$kyc_id,
        'kyc_name'=>$kyc_data['upload_kyc_data']['kyc_name']
        );
          foreach (fn_get_translation_languages() as $data['lang_code'] => $_v) {
              db_query("INSERT INTO ?:wk_vendor_kyc_data_description ?e",$data);
          }
      $send_notification=true;
   }
    foreach ($uploaded_datas as $key => $uploaded_data) {
       $filename = $uploaded_data['name'];
       list($filesize, $filename) = Storage::instance('wk_vendor_kyc')->put($directory . '/' . $filename, array(
            'file' => $uploaded_data['path']
        ));
    }
    foreach ($get_uploade_file_paths as $key => $file_name) {
          $data=array(
          'kyc_id'=>$kyc_id,
          'kyc_file'=>$file_name
        );
        db_query("INSERT INTO ?:wk_vendor_kyc_files ?e",$data);
      }
    if($send_notification==true){ 
      $wk_kyc_setting_data=fn_wk_vendor_kyc_get_setting_data();
      if(isset($wk_kyc_setting_data['ch_admin'])&& $wk_kyc_setting_data['ch_admin']=='y'){
        $tpl_file="addons/wk_vendor_kyc/wk_vendor_kyc_upload_kyc_notify_to_admin.tpl";
        fn_wk_vendor_kyc_send_notification_to_admin($kyc_id,$wk_kyc_setting_data['notify_admin_mail_sub'],$wk_kyc_setting_data['admin_notify_mail_text'],$tpl_file);
      }
    }
    return $kyc_id;
}

/**
*
  *Get All Kyc Dtata
  *
  * @param    array         $params              Request data
  * @param    Integer       $items_per_page      Itemes Per page
  * @param    String        $lang_code           Current Cart Langauge
  * @return   array         $wk_vendor_kyc_data  Return all KYC Data
  * @return   array         $params              Return all Sorting Data
  *
*/
function fn_wk_vendor_kyc_get_kyc_data($params,$items_per_page = 0,$lang_code = CART_LANGUAGE)
{
   
   $params = LastView::instance()->update('kyc_search', $params);
    // Set default values to input params
   $default_params = array (
        'page' => 1,
        'items_per_page' => $items_per_page
    );
   $params = array_merge($default_params, $params);
   $condition = $join = $group =  $limit='';   
    $fields = array (
          '?:wk_vendor_kyc_data.kyc_id',
          '?:wk_vendor_kyc_data.kyc_type',
          '?:wk_vendor_kyc_data.kyc_id_number',
          '?:wk_vendor_kyc_data.company_id',
          '?:wk_vendor_kyc_data.upload_date',
          '?:wk_vendor_kyc_data.status',
          '?:wk_vendor_kyc_data.reason',
          '?:wk_vendor_kyc_data_description.kyc_name',
      );
     $sortings = array (
    'kyc_id' => '?:wk_vendor_kyc_data.kyc_id',
    'kyc_type'=>'?:wk_vendor_kyc_data.kyc_type',
    'kyc_id_number'=>'?:wk_vendor_kyc_data.kyc_id_number',
    'company_id' => '?:wk_vendor_kyc_data.company_id',
    'upload_date'=>'?:wk_vendor_kyc_data.upload_date',
    'status'=>'?:wk_vendor_kyc_data.status',
    'kyc_name'=>'?:wk_vendor_kyc_data_description.kyc_name',
    ); 
    if(isset($params['kyc_id']) && !empty($params['kyc_id']))
    {
      $condition .= db_quote("AND kyc_id =?i",$params['kyc_id']);
    }
    if (isset($params['kyc_type'])&& !empty($params['kyc_type'])) {
      
        $condition .= db_quote(" AND kyc_type LIKE ?l", "%{$params['kyc_type']}%");
    }
    if (isset($params['kyc_name']) && !empty($params['kyc_name'])) {
     
        $condition .= db_quote(" AND kyc_name LIKE ?l", "%{$params['kyc_name']}%");
    }  
    if (isset($params['company_id']) && !empty($params['company_id'])) {
     
        $condition .= db_quote(" AND company_id =?i",$params['company_id']);
    }  
    if (isset($params['kyc_status']) && !empty($params['kyc_status'])) {
     
        $condition .= db_quote(" AND status LIKE ?l", "%{$params['kyc_status']}%");
    }  
    $company_id=Registry::get('runtime.company_id');
    // fn_print_r($company_id);
    // if (AREA != 'A') {
    //     $condition.=db_quote('AND ?:wk_vendor_kyc_data.company_id = ?i', $company_id);
    // }
    // else if($company_id!=0)
    // {
    //   $condition.=db_quote('AND ?:wk_vendor_kyc_data.company_id = ?i', $company_id);
    // }
    $sorting =db_sort($params, $sortings, 'kyc_id', 'asc');
    if (!empty($params['items_per_page'])) {
    if($company_id==0 && AREA == 'A')
    {
       $params['total_items'] = db_get_field("SELECT COUNT(DISTINCT(?:wk_vendor_kyc_data.kyc_id)) FROM ?:wk_vendor_kyc_data WHERE 1 $condition");
    }
    else
    {
       $params['total_items'] = db_get_field("SELECT COUNT(DISTINCT(?:wk_vendor_kyc_data.kyc_id)) FROM ?:wk_vendor_kyc_data WHERE 1 AND ?:wk_vendor_kyc_data.company_id=?i $condition",$company_id);
    }
      $limit = db_paginate($params['page'], $params['items_per_page'],$params['total_items']);
    } 
    if($company_id==0 && AREA == 'A'){
       $wk_vendor_kyc_data=db_get_array("SELECT " . implode(', ', $fields) . " FROM ?:wk_vendor_kyc_data LEFT JOIN ?:wk_vendor_kyc_data_description ON ?:wk_vendor_kyc_data_description.object_id = ?:wk_vendor_kyc_data.kyc_id AND lang_code = ?s
      WHERE 1 $condition $sorting  $limit",$lang_code);
    }
    else
    {
      $wk_vendor_kyc_data=db_get_array("SELECT " . implode(', ', $fields) . " FROM ?:wk_vendor_kyc_data LEFT JOIN ?:wk_vendor_kyc_data_description ON ?:wk_vendor_kyc_data_description.object_id = ?:wk_vendor_kyc_data.kyc_id AND lang_code = ?s
      WHERE 1 AND ?:wk_vendor_kyc_data.company_id =?i $condition $sorting  $limit",$lang_code,$company_id);
    }
   return array($wk_vendor_kyc_data, $params);
}


/**
  *
  *Get KYC Type description 
  *
  * @param   Integer   $kyc_type_id   Kyc type id
  * @param   String    $lang_code     Current Cart Langauge
  * @return  String    $description   Return KYC type name
  *
*/
function fn_wk_vendor_kyc_get_kyc_type_description($kyc_type_id,$lang_code= CART_LANGUAGE)
{
  return db_get_field("SELECT description FROM ?:wk_vendor_kyc_type_description WHERE object_id=?i",$kyc_type_id);
}

/**
  *
  *Download KYC Files
  * @param  Integer  $kyc_id   KYC id
  *
*/
function fn_wk_vendor_kyc_download_kyc_file($kyc_id)
{
    $kyc_files = db_get_array("SELECT kyc_file FROM ?:wk_vendor_kyc_files WHERE kyc_id = ?i", $kyc_id); 
    $kyc_name=db_get_field("SELECT kyc_name FROM ?:wk_vendor_kyc_data_description WHERE object_id = ?i AND lang_code=?s", $kyc_id,CART_LANGUAGE); 
    $company_id = db_get_field("SELECT company_id FROM ?:wk_vendor_kyc_data WHERE kyc_id = ?i", $kyc_id);
    $dir = "var/wk_vendor_kyc/kycs/".$company_id."/";
    $kyc_filess=array();
    $count=0;
    foreach ($kyc_files as $key => $value) {
     $count++;
    }
    if($count>1)
    {
        foreach ($kyc_files as $key => $value) {
           $file = $dir . $value['kyc_file'];
           if (file_exists($file)){
             $kyc_filess[$key]=$file;
          }
        }
        $files =$kyc_filess;
        $zipname = $kyc_name.'_'.$company_id.'_'.TIME.'.'.'zip';
        $zip = new ZipArchive;
        $zip->open($zipname, ZipArchive::CREATE);
        foreach ($files as $key =>$file) {
          $zip->addFile($file);
        }
        $zip->close();
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
    }
    else{
        foreach ($kyc_files as $key => $value) {
          $file = $dir . $value['kyc_file'];
          if (file_exists($file))
          {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Connection: Keep-Alive');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . sprintf("%u", filesize($file)));
            set_time_limit(0);
            readfile($file);
            exit;
          }
        } 
      }        
}


/**
  *
  *Delete KYC Files
  *
  * @param int  $kyc_id  KYC id
  *
*/
function fn_wk_vendor_kyc_delete_kyc_file($kyc_id)
{
    $kyc_files = db_get_array("SELECT kyc_file FROM ?:wk_vendor_kyc_files WHERE kyc_id = ?i", $kyc_id);  
      $company_id = db_get_field("SELECT company_id FROM ?:wk_vendor_kyc_data WHERE kyc_id = ?i", $kyc_id);
      $dir = "var/wk_vendor_kyc/kycs/".$company_id."/";
     db_query("DELETE FROM ?:wk_vendor_kyc_data WHERE kyc_id=?i",$kyc_id);
     db_query("DELETE FROM ?:wk_vendor_kyc_data_description WHERE object_id=?i",$kyc_id);
    db_query("DELETE FROM ?:wk_vendor_kyc_files WHERE kyc_id=?i",$kyc_id);
    foreach ($kyc_files as $key => $value) {
     Storage::instance('wk_vendor_kyc')->delete($dir . '/' . $value['kyc_file']);
   }
}
// Send KYC Upload REQUEST To Vendor
/**
     * Actions between change company status and send mail
     *
     * @param int    $company_id   Company ID
     * @param string $status_to    Status to letter
     * @param string $reason       Reason text
     * @param string $status_from  Status from letter
     * @param bool   $skip_query   Skip query flag
     * @param bool   $notify       Notify flag
     * @param array  $company_data Company data
     * @param array  $user_data    User data
     * @param bool   $result       Updated flag
*/
function fn_wk_vendor_kyc_change_company_status_before_mail(&$company_id, &$status_to, &$reason, &$status_from, &$skip_query, &$notify, &$company_data, &$user_data, &$result)
{
  $check_kyc=db_get_field("SELECT upload_kyc_request_sent FROM ?:companies WHERE company_id=?i",$company_id);
  if($check_kyc==0)
  {
    $day=$hour=$minitue=0;
    $wk_kyc_setting_data=fn_wk_vendor_kyc_get_setting_data();
    if($wk_kyc_setting_data['vendor_status_from']==$status_to && isset($wk_kyc_setting_data['upload_mail_text'])&& !empty($wk_kyc_setting_data['upload_mail_text'])){
      if (isset($wk_kyc_setting_data['upload_mail_sub'])) {
          $subject=$wk_kyc_setting_data['upload_mail_sub'];
      }
      if(isset($wk_kyc_setting_data['days']))
          $day=$wk_kyc_setting_data['days'];
       if(isset($wk_kyc_setting_data['hours']))
          $hour=$wk_kyc_setting_data['hours'];
       if(isset($wk_kyc_setting_data['minute']))
          $minitue=$wk_kyc_setting_data['minute'];
      $mail_text=$wk_kyc_setting_data['upload_mail_text'];
      $validity_period_time=TIME+($day*24*60*60)+($hour*60*60)+($minitue*60);      
      $validity_period_time=date('d-m-y\,H:i:s', $validity_period_time);
      $mail_content = str_replace('[#validity_period#]', $validity_period_time, $mail_text);
      $tpl_file="addons/wk_vendor_kyc/wk_vendor_kyc_upload_kyc_request.tpl";
      fn_wk_vendor_kyc_send_mail($mail_content,$company_data['email'],$subject,$tpl_file,'');
      $data=array(
        'upload_kyc_request_sent'=>1,
        'upload_kyc_request_time'=>TIME
        );
      db_query('UPDATE ?:companies SET ?u WHERE company_id=?i',$data,$company_id);
    }
  }
}

/**
     * Send Notifications
     *
     * @param int     $kyc_id         KYC ID
     * @param string  $subject        Subject
     * @param string  $mail_content   Mail text
     * @param string  $tpl_file       TPL File
     *
*/
function fn_wk_vendor_kyc_send_notification($kyc_id,$subject,$mail_content,$tpl_file)
{
  $mail_content=wk_vendor_kyc_replace_placeholders($kyc_id,$mail_content);
  $company_id=db_get_field("SELECT company_id FROM ?:wk_vendor_kyc_data WHERE kyc_id=?i",$kyc_id);
  $to=db_get_field("SELECT email FROM ?:companies WHERE company_id=?i",$company_id);
 $kyc_files = db_get_array("SELECT kyc_file FROM ?:wk_vendor_kyc_files WHERE kyc_id = ?i", $kyc_id); 
  $dir = "var/wk_vendor_kyc/kycs/".$company_id."/";
  $attachments=array();
  foreach ($kyc_files as $key => $value) {
      $file = $dir . $value['kyc_file'];
      if (file_exists($file)){
        $attachments[$value['kyc_file']] = $file;
      }
  }
  fn_wk_vendor_kyc_send_mail($mail_content,$to,$subject,$tpl_file,$attachments);
}
/**
     * Send Notification to admin when kyc get uploaded by vendor
     *
     * @param int     $kyc_id         KYC ID
     * @param string  $subject        Subject
     * @param string  $mail_content   Mail text
     * @param string  $tpl_file       TPL File
     *
*/
function fn_wk_vendor_kyc_send_notification_to_admin($kyc_id,$subject,$mail_content,$tpl_file)
{
  $mail_content=wk_vendor_kyc_replace_placeholders($kyc_id,$mail_content);
  $company_id=db_get_field("SELECT company_id FROM ?:wk_vendor_kyc_data WHERE kyc_id=?i",$kyc_id);
  $to='default_company_support_department';
 $kyc_files = db_get_array("SELECT kyc_file FROM ?:wk_vendor_kyc_files WHERE kyc_id = ?i", $kyc_id); 
  $dir = "var/wk_vendor_kyc/kycs/".$company_id."/";
  $attachments=array();
  foreach ($kyc_files as $key => $value) {
      $file = $dir . $value['kyc_file'];
      if (file_exists($file)){
        $attachments[$value['kyc_file']] = $file;
      }
  }
  fn_wk_vendor_kyc_send_mail($mail_content,$to,$subject,$tpl_file,$attachments);
}

/**
     * Send Notifications
     *
     * @param string  $mail_content   Mail text
     * @param string  $to             Receiver Email Id
     * @param string  $subject        Subject
     * @param string  $tpl_file       TPL File
     * @param array  $attachments     Attachments Files 
     *
*/
function fn_wk_vendor_kyc_send_mail($mail_content,$to,$subject,$tpl_file,$attachments)
{

   Mailer::sendMail(array(
      'to' => $to,
      'from' =>'default_company_support_department',
      'data' => array(
          'mail_content' => $mail_content,
          'subject'=>$subject
          ),
      'attachments' => $attachments,
      'tpl' =>$tpl_file
      ));
}

/**
     *Replace Mail Text Placeholder.
     *
     * @param int     $kyc_id       Kyc Id
     * @param array   $mail_text    Mail Text
     * @param string  lang_code     Current Langauge
     *
*/
function wk_vendor_kyc_replace_placeholders($kyc_id,$mail_text,$lang_code = CART_LANGUAGE)
{
  $data=db_get_array("SELECT ?:wk_vendor_kyc_data.*, ?:wk_vendor_kyc_data_description.kyc_name FROM ?:wk_vendor_kyc_data LEFT JOIN ?:wk_vendor_kyc_data_description ON ?:wk_vendor_kyc_data.kyc_id = ?:wk_vendor_kyc_data_description.object_id AND lang_code = ?s WHERE ?:wk_vendor_kyc_data.kyc_id=?i ", $lang_code,$kyc_id);
    $data=$data[0];
   if($data['status']=='N')
   {
     $status=__('new');
   }
   else if($data['status']=='A')
   {
      $status=__('accepted');
   }
   else
   {
      $status=__('rejected');
   }
  $company_name=fn_get_company_name($data['company_id']);
  $mail_content = str_replace('[#company_name#]', $company_name, $mail_text);
  $mail_content = str_replace('[#kyc_type#]', fn_wk_vendor_kyc_get_kyc_type_description($data['kyc_type']), $mail_content);
  $mail_content = str_replace('[#kyc_name#]', $data['kyc_name'], $mail_content);
  $mail_content = str_replace('[#company_id#]', $data['company_id'], $mail_content);
  $mail_content = str_replace('[#kyc_unique_id_number#]',$data['kyc_id_number'], $mail_content);
  $mail_content = str_replace('[#kyc_status#]',$status, $mail_content);
  $mail_content = str_replace('[#reason#]',$data['reason'], $mail_content);
  return $mail_content;
}

/**
 * Gets company data array
 *
 * @param array $params Array of search params:
 * @param array $auth Array of user authentication data (e.g. uid, usergroup_ids, etc.)
 * @param int $items_per_page
 * @param string $lang_code 2-letter language code (e.g. 'en', 'ru', etc.)
 * @return array Array:
 */
function fn_wk_vendor_kyc_get_companies(&$params, &$fields, &$sortings, &$condition, $join, $auth, $lang_code, $group){
  if(Registry::get('runtime.mode')=='kyc_request_log'){
      array_push($fields,"?:companies.upload_kyc_request_sent");
      array_push($sortings,"?:companies.upload_kyc_request_sent");  
      array_push($fields,"?:companies.upload_kyc_request_time");
      array_push($sortings,"?:companies.upload_kyc_request_time");  
      array_push($fields,"?:companies.wk_upload_exp");
      array_push($sortings,"?:companies.wk_upload_exp"); 
      $condition.=db_quote("AND ?:companies.upload_kyc_request_sent =1");
  }
}

function fn_wk_vendor_kyc_check_kyc_exist($company_id){
  $count_kyc=db_get_field("SELECT COUNT(DISTINCT(?:wk_vendor_kyc_data.kyc_id)) FROM ?:wk_vendor_kyc_data WHERE company_id=?i",$company_id);
  return $count_kyc;
}
function fn_wk_vendor_kyc_send_upload_request($company_id){
    $day=$hour=$minitue=0;
    $company_email=db_get_field("SELECT email FROM ?:companies WHERE company_id=?i",$company_id);
    $wk_kyc_setting_data=fn_wk_vendor_kyc_get_setting_data();
    if(isset($wk_kyc_setting_data['upload_mail_text'])&& !empty($wk_kyc_setting_data['upload_mail_text'])){
      if (isset($wk_kyc_setting_data['upload_mail_sub'])) {
          $subject=$wk_kyc_setting_data['upload_mail_sub'];
      }
      if(isset($wk_kyc_setting_data['days']))
          $day=$wk_kyc_setting_data['days'];
       if(isset($wk_kyc_setting_data['hours']))
          $hour=$wk_kyc_setting_data['hours'];
       if(isset($wk_kyc_setting_data['minute']))
          $minitue=$wk_kyc_setting_data['minute'];
      $mail_text=$wk_kyc_setting_data['upload_mail_text'];
      $validity_period_time=TIME+($day*24*60*60)+($hour*60*60)+($minitue*60);      
      $validity_period_time=date('d-m-y\,H:i:s', $validity_period_time);
      $mail_content = str_replace('[#validity_period#]', $validity_period_time, $mail_text);
      $tpl_file="addons/wk_vendor_kyc/wk_vendor_kyc_upload_kyc_request.tpl";
      fn_wk_vendor_kyc_send_mail($mail_content,$company_email,$subject,$tpl_file,'');
      $data=array(
        'upload_kyc_request_sent'=>1,
        'upload_kyc_request_time'=>TIME,
        'wk_upload_exp'=>0
        );
      db_query('UPDATE ?:companies SET ?u WHERE company_id=?i',$data,$company_id);
    }
}