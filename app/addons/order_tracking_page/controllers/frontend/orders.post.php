<?php
/**
 * CS-Cart Order Status Tracker - order_tracking_page
 * 
 * PHP version 7.0
 * 
 * @category  Add-on
 * @package   CS_Cart
 * @author    WebKul Software Private Limited <support@webkul.com>
 * @copyright 2010 WebKul Software Private Limited
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version   GIT: 1.0
 * @link      Technical Support: Forum - http://webkul.com/ticket
 */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

if ($mode == 'details') {
    $lang_code = CART_LANGUAGE;
    if ($_REQUEST['order_id']) {
        $active_template    = Registry::get('addons.order_tracking_page.wk_select_template');
        $active_status_id   = '';
        $active_label_id    = '';
        $order_data         = fn_get_order_short_info($_REQUEST['order_id']);
        $statuses           = fn_get_statuses('O', array(), false, false, $lang_code);
        $statuses_arr       = array();
        
        foreach ($statuses as $key=> $status) {
            $a = array(
                'id'        => $status['status_id'],
                'status'    => $status['status'],
                'name'      => $status['description'],
                'inventory' => $status['params']['inventory']
            );

            if ($order_data['status'] == $status['status']) {
                $active_status_id = $status['status_id']; 
            }
            if($status['params']['inventory'] == 'I' && $status['status']!='B'){
            $statuses_arr[] = $status['status_id'];
        }
    }
        $condition  = "LEFT JOIN ?:wk_order_labels_description ON ?:wk_order_labels_description.id = ?:wk_order_labels.id";
        if(fn_allowed_for('ULTIMATE') && Registry::get('runtime.company_id')){
            $storefront_condition = 'AND ?:wk_order_labels.storefront_id = '.Registry::get('runtime.company_id').' AND ?:wk_order_labels_description.lang_code = "'.$lang_code.'"';
        }
        else{
        $storefront_condition = "";
        }
        $labels     = db_get_array("SELECT ?:wk_order_labels.*, ?:wk_order_labels_description.title, ?:wk_order_labels_description.description FROM ?:wk_order_labels $condition AND ?:wk_order_labels_description.lang_code = ?i WHERE ?:wk_order_labels.statusad = ?i $storefront_condition ORDER BY position ASC", $lang_code,1);
   
        foreach ($labels as $key => $label) {
            if(fn_allowed_for('ULTIMATE')){
                if($label['storefront_id'] == Registry::get('runtime.company_id') || Registry::get('runtime.company_id') == 0){
            
            $labels[$key]['statuses'] = unserialize($label['statuses']);
            if (in_array($active_status_id, $labels[$key]['statuses'])) {
                $active_label_id = $labels[$key]['statuses'][0];
            }
        }
        else{
            unset($labels[$key]);
            continue;
        }
    }
    else{
        $labels[$key]['statuses'] = unserialize($label['statuses']);
            if (in_array($active_status_id, $labels[$key]['statuses'])) {
                $active_label_id = $labels[$key]['statuses'][0];
            }
    }
        }
        //fn_print_die($labels);
        if (!empty($active_label_id)) {
            $labels_data    = array();
            $flag           = true;
            $active_flag    = false;
            $active_number  = 0;
            $j              = 0;
            
            
            foreach ($labels as $key => $label) {

                    if(!in_array($label['statuses'][0],$statuses_arr) || $label['statuses'][0] != $active_label_id || !in_array($active_label_id,$statuses_arr)){
                       if(!in_array($label['statuses'][0],$statuses_arr)){
                $j++;
                $act_img    = fn_get_image_pairs($label['id'], 'wk_otp_activated_icon', 'M', true, false, $lang_code);
                $deact_img  = fn_get_image_pairs($label['id'], 'wk_otp_deactivated_icon', 'M', true, false, $lang_code);
                
                if (empty($label['title'])) {
                    fn_order_tracking_page_default_lang_data($label, $lang_code);
                }
                
                $a = array(
                    'id'            => $label['id'],
                    'title'         => $label['title'],
                    'description'   => $label['description'],
                    'act_img'       => isset($act_img['icon']['image_path'])?$act_img['icon']['image_path']:"",
                    'deact_img'     => isset($deact_img['icon']['image_path'])?$deact_img['icon']['image_path']:"",
                    'label_status'  => $flag?'D':'U',
                    'bar_status'    => $flag?'D':'U',
                );

                if ($active_flag) {
                    $a['label_status']                  = 'A';
                    $a['bar_status']                    = 'U';
                   // $labels_data[$key-1]['bar_status']  = 'A';
                    $active_number                      = $j;
                    $active_flag                        = false;
                }
                
                if ($label['statuses'][0] == $active_label_id) {
                    $flag           = false;
                    $active_flag    = true;
                }
                $labels_data[] = $a;
            }
            else{
                continue;
            }
        }
            else{
                $j++;
                $act_img    = fn_get_image_pairs($label['id'], 'wk_otp_activated_icon', 'M', true, false, $lang_code);
                $deact_img  = fn_get_image_pairs($label['id'], 'wk_otp_deactivated_icon', 'M', true, false, $lang_code);
                
                if (empty($label['title'])) {
                    fn_order_tracking_page_default_lang_data($label, $lang_code);
                }
                
                $a = array(
                    'id'            => $label['id'],
                    'title'         => $label['title'],
                    'description'   => $label['description'],
                    'act_img'       => isset($act_img['icon']['image_path'])?$act_img['icon']['image_path']:"",
                    'deact_img'     => isset($deact_img['icon']['image_path'])?$deact_img['icon']['image_path']:"",
                    'label_status'  => $flag?'I':'U',
                    'bar_status'    => $flag?'I':'U',
                );

                if ($active_flag) {
                    $a['label_status']                  = 'A';
                    $a['bar_status']                    = 'U';
                   // $labels_data[$key-1]['bar_status']  = 'A';
                    $active_number                      = $j;
                    $active_flag                        = false;
                }
                
                if ($label['statuses'][0] == $active_label_id) {
                    $flag           = false;
                    $active_flag    = true;
                }
                $labels_data[] = $a;
            break;
            }
        }
       
        foreach($labels_data as $key=>$label){
            if($label['bar_status'] != 'D' && $label['label_status']!='I'){
                $labels_data[$key-1]['bar_status'] = 'A';
            break;
            }
        }
        
            $labels_count = count($labels_data);
            
            if ($active_template == 'template_one') {
                $width = 140*($labels_count-1)+68; 
                Tygh::$app['view']->assign('section_width', $width);
            } elseif ($active_template == 'template_two') {
                $navigation_tabs = Registry::get('navigation.tabs');
                $navigation_tabs['track_order'] = array(
                    'title' => __('track_order'),
                    'js'    => true
                );
                Registry::set('navigation.tabs', $navigation_tabs);
                Tygh::$app['view']->assign('labels_count', $labels_count);
            } elseif ($active_template == 'template_three') {
                $css                                    = array();
                $css['section_width']                   = 40 * $labels_count + ($labels_count - 1) * 120 + 5;
                $css['section_media_query_end_width']   = $css['section_width'] + 20;
                $css['section_media_height']            = 30 * $labels_count + ($labels_count - 1) * 55 + 30;
                $css['section_media_width']             = 40 * $labels_count + ($labels_count - 1) * 80 + 5;
                $css['section_media_two_width']         = 30 * $labels_count + ($labels_count - 1) * 50 + 5;
                $css['section_media_query_start_width'] = $css['section_media_width'] + 20;
                $css['labels_count']                    = $labels_count;
                Tygh::$app['view']->assign('css', $css);
            } elseif ($active_template == 'template_four') {
                $css                                    = array();
                $css['section_width']                   = 80 * $labels_count + ($labels_count - 1) * 80 + 5;
                $css['section_media_query_end_width']   = $css['section_width'] + 20;
                $css['media_width_one']                 = 60 * $labels_count + ($labels_count - 1) * 60 + 5;
                $css['media_width_two']                 = 40 * $labels_count + ($labels_count - 1) * 40 + 5;
                $css['section_media_query_start_width'] = $css['media_width_one']+ 20;
                $css['section_height']                  = 40 * $labels_count + ($labels_count - 1) * 45 + 20;
                $css['labels_count']                    = $labels_count;
                Tygh::$app['view']->assign('css', $css);
            }
            
            Tygh::$app['view']->assign('labels_data', $labels_data);  
            Tygh::$app['view']->assign('active_template', $active_template);  
        }
    }
}
