<?php
use Tygh\Enum\NotificationSeverity;
use Tygh\Models\VendorAddons;
use Tygh\Registry;
use Tygh\Tygh;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'manage_addons' || $mode == "update") { 
    $addon=array();
    if(!empty($_REQUEST['id'])) {
        $addon=array('id'=>$_REQUEST['id']);
    }
    $params = array_merge(
        array(
            'items_per_page'      => Registry::get('settings.Appearance.admin_elements_per_page'),
        ),
        $_REQUEST,
        array(
            'return_params'       => true,
            'lang_code'           => DESCR_SL,
        ),
        $addon
    );
    list($addons_data, $search) = VendorAddons::model()->findMany($params);
    Tygh::$app['view']->assign('addons_data', $addons_data);
    Tygh::$app['view']->assign('search', $search);

    // $payment_details = db_query("SELECT * FROM ?:plan_addons_details WHERE company_id = ?i and plan_id=?i and `status`='Success' order by id desc limit 0,1", $company_id,$_pid);
    //         if($row = mysqli_fetch_assoc($payment_details)) {
    //             Tygh::$app['view']->assign(
    //                 'payment_details',
    //                 $row
    //             );
    //         }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode == 'update' || $mode == 'add') {
        if ($mode == 'add' && fn_allowed_for('ULTIMATE:FREE')) {
            return array(CONTROLLER_STATUS_DENIED);
        }
        $suffix='';
        $_addon_data = array();
        $_addon_data['name'] = $_POST['addon_data']['name'];
        $_addon_data['price'] = $_POST['addon_data']['price'];
        $_addon_data['short_desc'] = $_POST['addon_data']['short_desc'];
        $_addon_data['long_desc'] = $_POST['addon_data']['long_desc'];
        $_addon_data['status'] = $_POST['addon_data']['status'];
        $_addon_data['payment_frequency'] = $_POST['addon_data']['payment_frequency'];
        if(!empty($_POST['addon_data']['allow_package'])) {
            $_addon_data['allow_package'] = $_POST['addon_data']['allow_package'];
        }
        if(!empty($_POST['addon_data']['prorate_charge'])) {
            $_addon_data['prorate_charge'] = $_POST['addon_data']['prorate_charge'];
        }
        $file_addons_data = $_FILES['file_addons_data'];
        $file_addons_data_video = $_FILES['file_addons_data_video'];
        if ($mode == 'add') {
            $id = db_query("INSERT INTO ?:plan_addons_details ?e", $_addon_data);
            $filename = UploadFiles($file_addons_data,$id,'images');
            $vid_filename = UploadFiles($file_addons_data_video,$id,'video');
            if(!empty($filename)) {
                $_addon_data['product_img'] = $filename;
            }
            if(!empty($vid_filename)) {
                $_addon_data['product_video'] = $vid_filename;
            }
            db_query("UPDATE ?:plan_addons_details SET ?u WHERE id=?i", $_addon_data,$id);
            $suffix = ".update?id=$id";
        } else if($mode == 'update') { 
            if(!empty($_REQUEST['id'])) {
                $id = $_REQUEST['id'];
                $filename = UploadFiles($file_addons_data,$id,'images');
                $vid_filename = UploadFiles($file_addons_data_video,$id,'video');
                if(!empty($filename)) {
                    $_addon_data['product_img'] = $filename;
                }
                if(!empty($vid_filename)) {
                    $_addon_data['product_video'] = $vid_filename;
                }
                db_query("UPDATE ?:plan_addons_details SET ?u WHERE id=?i", $_addon_data, $id);
                $suffix = ".update?id=$_REQUEST[id]";
            }
        }
        return array(CONTROLLER_STATUS_OK, 'vendor_addons' . $suffix);
    }
}
if ($mode == 'update_status') {
    $id = $_REQUEST['id'];
    $status_data = array();
    $status_data['status'] = $_REQUEST['status'];
    db_query("UPDATE ?:plan_addons_details SET ?u WHERE id=?i", $status_data, $id);
}
function UploadFiles($addons_data,$id,$type) {
    $upload = '';
    if(isset($addons_data)) {
        $errors= array();
        $file_name = $id.$addons_data['name'];
        $file_size = $addons_data['size'];
        $file_tmp = $addons_data['tmp_name'];
        $file_type = $addons_data['type'];
        $file_ext=strtolower(end(explode('.',$addons_data['name'])));
        $extensions= array("jpeg","jpg","png");
        $extensions_arr = array("mp4","avi","3gp","mov","mpeg");
        if($type == 'images') {
            $upload = $_SERVER['DOCUMENT_ROOT'].'/images/addons/';
            if(in_array($file_ext,$extensions) === false) {
                $errors[]="extension not allowed, please choose a JPEG or PNG file.";
            }
        } else if($type == 'video') {
            $upload = $_SERVER['DOCUMENT_ROOT'].'/videos/addons/';
            if(in_array($file_ext,$extensions_arr) === false) {
                $errors[]="extension not allowed, please choose a mp4, avi, 3gp, mov or mpeg file.";
            }
        }
        $status=false;
        //if(empty($errors) == true) {            
           if(move_uploaded_file($file_tmp,$upload.$file_name)) {
                return $file_name;
           } else {
                return $status;
           }
         } else {
             foreach($errors as $error) {
                fn_set_notification('E', __(''), $error);
             }
           // return $status;
         }
}
?>