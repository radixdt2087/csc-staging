<?php
use Tygh\Registry;
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($mode == 'create_new'){
        if(isset($_REQUEST['company_id'])&& !empty($_REQUEST['company_id']) && isset($_REQUEST['subdomain'])&& !empty($_REQUEST['subdomain'])){
            $id = db_get_field("SELECT id FROM ?:wk_vendor_subdomain WHERE subdomain = ?s AND company_id != ?i",$_REQUEST['subdomain'], $_REQUEST['company_id']);
            if(empty($id)){
                $data = array(
                    'company_id' => $_REQUEST['company_id'],
                    'subdomain' =>  $_REQUEST['subdomain'],
                    'status'    => $_REQUEST['status']
                );
                db_query('REPLACE INTO ?:wk_vendor_subdomain ?e', $data);
            }else{
                fn_set_notification("E",__("warning"),__("subdomain_not_available_type_another_subdomain"));
            }
        }elseif(isset($_REQUEST['subdomain']) && empty($_REQUEST['subdomain']) && isset($_REQUEST['company_id']) && !empty($_REQUEST['company_id'])){
            db_query("DELETE FROM ?:wk_vendor_subdomain WHERE company_id = ?i",$_REQUEST['company_id']);
        }
        $suffix = '.manage';
    }

    if ($mode == 'update_status') 
    { 
        if (fn_vendor_subdomain_change_status($_REQUEST['id'], $_REQUEST['status'])) {
            fn_set_notification('N', __('notice'), __('status_changed'));
        } else {
            fn_set_notification('E', __('error'), __('error_status_not_changed'));
        Registry::get('ajax')->assign('return_status', $status_from);
        }
        exit;
    }

    //
    // Delete seller_ads
    //
    if ($mode == 'm_delete') 
    {
        foreach ($_REQUEST['company_ids'] as $v) {
            fn_delete_vendor_subdomain_by_id($v);
        }
        $suffix = '.manage';
    }

    //
    //deleting any ads  
    //
    if ($mode == 'delete') 
    {
        if (!empty($_REQUEST['company_id'])) {
            fn_delete_vendor_subdomain_by_id($_REQUEST['company_id']);
        }
        $suffix = '.manage';
    }
    return array(CONTROLLER_STATUS_OK, 'wk_vendor_subdomain' . $suffix);
}

if($mode == 'manage')
{
    list($subdomain_list,$search) = fn_get_vendor_subdomain_list($_REQUEST);
    Registry::get('view')->assign('subdomainList',$subdomain_list);
    Registry::get('view')->assign('search',$search);
}

if($mode == 'update'){
    if(isset($_REQUEST['company_id'])){
        Registry::get('view')->assign('company_id',$_REQUEST['company_id']);
        $data = db_get_row("SELECT * FROM ?:wk_vendor_subdomain WHERE company_id = ?i",$_REQUEST['company_id']);
        Registry::get('view')->assign('data',$data);
    }
}