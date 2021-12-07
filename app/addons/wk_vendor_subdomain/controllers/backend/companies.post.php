<?php
use Tygh\Registry;
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($mode == 'update'){
        fn_trusted_vars('company_data');
        if(isset($_REQUEST['company_data']['subdomain'])){
            if(empty($_REQUEST['company_data']['subdomain'])){
                db_query('DELETE FROM ?:wk_vendor_subdomain WHERE company_id = ?i', $_REQUEST['company_id']);
            }else{
                $id = db_get_field("SELECT id FROM ?:wk_vendor_subdomain WHERE subdomain = ?s AND company_id != ?i",$_REQUEST['company_data']['subdomain'], $_REQUEST['company_id']);
                if(empty($id)){
                    $data = array(
                        'company_id' => $_REQUEST['company_id'],
                        'subdomain' =>  trim($_REQUEST['company_data']['subdomain'])
                    );
                    $check = db_get_field("SELECT subdomain FROM ?:wk_vendor_subdomain WHERE company_id = ?i",$_REQUEST['company_id']);
                    if($check != $_REQUEST['company_data']['subdomain']){
                        if(Registry::get('runtime.company_id')){
                            $data['status'] = 'D';
                        }
                        db_query('REPLACE INTO ?:wk_vendor_subdomain ?e', $data);
                    }
                }else{
                    fn_set_notification("E",__("warning"),__("subdomain_not_available_type_another_subdomain"));
                }
            } 
        }
    }
}
if($mode == 'update'){
    if(isset($_REQUEST['company_id'])&&!empty($_REQUEST['company_id'])){
        $values = db_get_row("SELECT subdomain,status FROM ?:wk_vendor_subdomain WHERE  company_id = ?i",$_REQUEST['company_id']);
        if($values){
            Registry::get('view')->assign('subdomain',$values['subdomain']);
            Registry::get('view')->assign('sub_domain_status',$values['status']);
        }
    }
}