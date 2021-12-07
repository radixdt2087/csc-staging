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

if ($mode == 'manage') {
    $lang_code              = DESCR_SL;
    $backend_deafult_lang   = Registry::get('settings.Appearance.backend_default_language');
    $statuses               = fn_get_statuses('O', array(), false, false, DESCR_SL);
    $statuses_arr           = array();
    
    foreach ($statuses as $key => $status) {
        $a = array(
            'id'    => $status['status_id'],
            'name'  => $status['description']
        );
        $statuses_arr[] = $a;
    }

    //show data
    $condition  = "LEFT JOIN ?:wk_order_labels_description ON ?:wk_order_labels_description.id = ?:wk_order_labels.id";
    if(fn_allowed_for('ULTIMATE') && Registry::get('runtime.company_id')){
            $storefront_condition = 'AND ?:wk_order_labels.storefront_id = '.Registry::get('runtime.company_id');
        }
        else{
        $storefront_condition = "";
        }
    
    $labels     = db_get_array("SELECT ?:wk_order_labels.*, ?:wk_order_labels_description.title, ?:wk_order_labels_description.description FROM ?:wk_order_labels $condition AND ?:wk_order_labels_description.lang_code = ?s $storefront_condition ORDER BY position ASC", $lang_code);
    foreach ($labels as $key => $label) {
        if(fn_allowed_for('ULTIMATE')){
            if($label['storefront_id'] == Registry::get('runtime.company_id') || Registry::get('runtime.company_id') == 0){
                
                $storefront_name = db_get_field("SELECT company from ?:companies where company_id = ?i",$label['storefront_id']);
                $labels[$key]['storefront_name'] = $storefront_name;
                
                $labels[$key]['statuses'] = unserialize($label['statuses']);
        
        foreach ($labels[$key]['statuses'] as $k => $status) {
            foreach ($statuses_arr as $s_k => $s_status) {
                if ($status == $s_status['id']) {
                    $labels[$key]['statuses'][$k] = $s_status['name'];
                }
            }
            
            if (empty($label['title'])) {
                fn_order_tracking_page_default_lang_data($labels[$key], $lang_code);
            }
            
        }
    }
    else{
        unset($labels[$key]);
        continue;
    }
}
    else{
        $labels[$key]['statuses'] = unserialize($label['statuses']);
        
        foreach ($labels[$key]['statuses'] as $k => $status) {
            foreach ($statuses_arr as $s_k => $s_status) {
                if ($status == $s_status['id']) {
                    $labels[$key]['statuses'][$k] = $s_status['name'];
                }
            }
            
            if (empty($label['title'])) {
                fn_order_tracking_page_default_lang_data($labels[$key], $lang_code);
            }
        }

    }
    }
    

    Tygh::$app['view']->assign('labels', $labels);  
}

if ($mode == 'update') {
    $lang_code = DESCR_SL;
    
    //update data
    if ($action == 'add') {
        $seleted_status = array();
        //remove the empty values of status
        foreach ($_REQUEST['label_setting']['select_status'] as $key => $val) {
            if (!empty($val)) {
                $seleted_status[] = $val;
            }
        }
        
        if (!preg_match ("/^[a-zA-Z-' ]*$/", $_REQUEST['label_setting']['title']) ) {
            fn_set_notification('E', __('error'), "Number not allowed in State Title");
                return array(CONTROLLER_STATUS_OK,"wk_order_tracking.update?id=".$_REQUEST['id']);
            }

        if(strlen($_REQUEST['label_setting']['title']) > 30){
                fn_set_notification('E', __('error'), "Title Too Big");
                return array(CONTROLLER_STATUS_OK,"wk_order_tracking.update?id=".$_REQUEST['id']);
            }

        if(ctype_space($_REQUEST['label_setting']['title'])){
                fn_set_notification('E', __('error'), "Please enter Title");
                return array(CONTROLLER_STATUS_OK,"wk_order_tracking.update?id=".$_REQUEST['id']);
            }
        
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != 0 && !empty($seleted_status)) {
            //update entry
            $data = array(
                'position' => $_REQUEST['label_setting']['position'],
                'statuses' => serialize($seleted_status),
                'statusad' => $_REQUEST['label_setting']['state_ad'],
                
            );
            db_query("UPDATE ?:wk_order_labels SET ?u WHERE id = ?i", $data, $_REQUEST['id']);
            $desc_data = array(
                'title'         => $_REQUEST['label_setting']['title'],
                'description'   => $_REQUEST['label_setting']['description'],
            );
            $id_lang_exist = db_get_field('SELECT id FROM ?:wk_order_labels_description WHERE id=?i AND lang_code=?s', $_REQUEST['id'], $lang_code);

            if (empty($id_lang_exist)) {
                $desc_data['lang_code'] = $lang_code;
                $desc_data['id']        = $_REQUEST['id'];
                db_query('INSERT INTO ?:wk_order_labels_description ?e', $desc_data);
            } else {
                db_query("UPDATE ?:wk_order_labels_description SET ?u WHERE id=?i AND lang_code=?s", $desc_data, $_REQUEST['id'], $lang_code);
            }
            fn_attach_image_pairs('wk_otp_activated_icon', 'wk_otp_activated_icon', $_REQUEST['id'], $lang_code);
            fn_attach_image_pairs('wk_otp_deactivated_icon', 'wk_otp_deactivated_icon', $_REQUEST['id'], $lang_code);
        } elseif (isset($_REQUEST['id']) && !empty($seleted_status)) {
            //new entry
            if(fn_allowed_for('ULTIMATE') && Registry::get('runtime.company_id') == 0){
                $store_ids = db_get_array('SELECT company_id FROM ?:companies');
                foreach($store_ids as $key=>$value){
            $data = array(
                'position' => $_REQUEST['label_setting']['position'],
                'statuses' => serialize($seleted_status),
                'statusad' => $_REQUEST['label_setting']['state_ad'],
                'storefront_id' => $value['company_id']
            );
            $id             = db_query('INSERT INTO ?:wk_order_labels ?e', $data);
            $_REQUEST['id'] = $id;
            
            if (!empty($id)) {
                $desc_data = array(
                    'id'            => $id,
                    'title'         => $_REQUEST['label_setting']['title'],
                    'description'   => $_REQUEST['label_setting']['description'],
                );
                
                foreach (fn_get_translation_languages() as $desc_data['lang_code'] => $_v) {
                    db_query('INSERT INTO ?:wk_order_labels_description ?e', $desc_data);
                }
                fn_attach_image_pairs('wk_otp_activated_icon', 'wk_otp_activated_icon', $_REQUEST['id'], $lang_code);
                fn_attach_image_pairs('wk_otp_deactivated_icon', 'wk_otp_deactivated_icon', $_REQUEST['id'], $lang_code);
            }
        }
        }
        else{
            $data = array(
                'position' => $_REQUEST['label_setting']['position'],
                'statuses' => serialize($seleted_status),
                'statusad' => $_REQUEST['label_setting']['state_ad'],
                'storefront_id' => Registry::get('runtime.company_id')
            );
            $id             = db_query('INSERT INTO ?:wk_order_labels ?e', $data);
            $_REQUEST['id'] = $id;
            
            if (!empty($id)) {
                $desc_data = array(
                    'id'            => $id,
                    'title'         => $_REQUEST['label_setting']['title'],
                    'description'   => $_REQUEST['label_setting']['description'],
                );
                
                foreach (fn_get_translation_languages() as $desc_data['lang_code'] => $_v) {
                    db_query('INSERT INTO ?:wk_order_labels_description ?e', $desc_data);
                }
                fn_attach_image_pairs('wk_otp_activated_icon', 'wk_otp_activated_icon', $_REQUEST['id'], $lang_code);
                fn_attach_image_pairs('wk_otp_deactivated_icon', 'wk_otp_deactivated_icon', $_REQUEST['id'], $lang_code);
            }
        }
        }
        return array(CONTROLLER_STATUS_OK,"wk_order_tracking.update?id=".$_REQUEST['id']);
    }

    //show data
    $statuses                   = fn_get_statuses('O', array(), false, false, DESCR_SL); 
    $statuses_arr               = array();
    if(fn_allowed_for('ULTIMATE') && Registry::get('runtime.company_id')){
        $storefront_condition = "AND storefront_id = ".Registry::get('runtime.company_id');
    }
    else{
        $storefront_condition = "";
    }

    $selected_statuses          = db_get_array("SELECT statuses FROM ?:wk_order_labels WHERE 1 $storefront_condition");
    $selected_statuses_array    = array();
    
    foreach ($selected_statuses as $key => $_status) {
        $s = unserialize($_status['statuses']);
        foreach ($s as $k => $v) {
            $selected_statuses_array[] = $v;
        }
    }
    
    if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
        if(fn_allowed_for('ULTIMATE') && Registry::get('runtime.company_id')){
            $storefront_condition = "AND ?:wk_order_labels.storefront_id = ".Registry::get('runtime.company_id');
        }
        else{
            $storefront_condition = "";
        }
        $condition  = "LEFT JOIN ?:wk_order_labels_description ON ?:wk_order_labels_description.id = ?:wk_order_labels.id";
        $label_data = db_get_row("SELECT ?:wk_order_labels.* , ?:wk_order_labels_description.title, ?:wk_order_labels_description.description FROM ?:wk_order_labels $condition AND ?:wk_order_labels_description.lang_code = ?s WHERE ?:wk_order_labels.id = ?i $storefront_condition", $lang_code, $_REQUEST['id']);
        if(isset($label_data['statuses'])){
        $label_data['statuses'] = unserialize($label_data['statuses']);
        }
        if(empty($label_data)){
            return array(CONTROLLER_STATUS_NO_PAGE);
        }
        
        foreach ($statuses as $key=> $status) {
            if (isset($status['params']['inventory'])) {
                $a = array(
                    'id'    => $status['status_id'],
                    'name'  => $status['description']
                );
                
                if (in_array($status['status_id'], $label_data['statuses'])) {
                    $a['selected'] = true;
                } elseif (in_array($status['status_id'], $selected_statuses_array)) {
                    continue;
                }
                $statuses_arr[] = $a;
            }
        }
        if (empty($label_data['title'])) {
            fn_order_tracking_page_default_lang_data($label_data, $lang_code);
        }
        $label_data['activate_icon'] = fn_get_image_pairs($_REQUEST['id'], 'wk_otp_activated_icon', 'M', true, false, $lang_code);
        $label_data['deactivate_icon'] = fn_get_image_pairs($_REQUEST['id'], 'wk_otp_deactivated_icon', 'M', true, false, $lang_code);
        Tygh::$app['view']->assign('label_setting', $label_data);
    } else {
        // $total_label_count = db_get_field("SELECT count(id) as `count` FROM ?:wk_order_labels WHERE 1");
        // if ($total_label_count >= 6) {
        //     fn_set_notification('E', __('error'), __('max_limit_of_creating_labels'));
            
        //     return array(CONTROLLER_STATUS_OK, 'wk_order_tracking.manage');
        // }
        
        foreach ($statuses as $key=> $status) {
            if (isset($status['params']['inventory'])  && !in_array($status['status_id'], $selected_statuses_array)) {
                $a = array(
                    'id'    => $status['status_id'],
                    'name'  => $status['description']
                );
                $statuses_arr[] = $a;
            }
        }
       if(count($statuses_arr) == 0){
        fn_set_notification('E', __('error'), "No more states available");
            
        return array(CONTROLLER_STATUS_OK, 'wk_order_tracking.manage');
       }
    }
    
    Tygh::$app['view']->assign('statuses', $statuses_arr);
    Tygh::$app['view']->assign('total_objects', count($statuses_arr));
}

if ($mode == 'get_status_list') {
    $statuses       = fn_get_statuses('O', array(), false, false, DESCR_SL);
    $statuses_arr   = array();
    
    foreach ($statuses as $key=> $status) {
        $a = array(
            'id'    => $status['status_id'],
            'name'  => $status['description']
        );
        $statuses_arr[] = $a;
    }
    Tygh::$app['ajax']->assign('statuses', $statuses_arr);
    Tygh::$app['ajax']->assign('total_objects', count($statuses_arr));
}

if ($mode == 'delete') {
    if (isset($_REQUEST['id'])) {
        db_query("DELETE FROM ?:wk_order_labels WHERE id = ?i", $_REQUEST['id']);
        db_query("DELETE FROM ?:wk_order_labels_description WHERE id = ?i", $_REQUEST['id']);
        fn_set_notification('N', __('notice'), __('text_label_have_been_deleted'));
    }
    
    return array(CONTROLLER_STATUS_OK, 'wk_order_tracking.manage');
}

if ($mode == 'm_delete') {
    if (isset($_REQUEST['label_ids'])) {
        foreach ($_REQUEST['label_ids'] as $key => $id) {
            db_query("DELETE FROM ?:wk_order_labels WHERE id = ?i", $id);
            db_query("DELETE FROM ?:wk_order_labels_description WHERE id = ?i", $id);
        }
        fn_set_notification('N', __('notice'), __('text_labels_have_been_deleted'));

        return array(CONTROLLER_STATUS_OK, 'wk_order_tracking.manage');
    }
}