<?php

use Tygh\Registry;
use Tygh\Tools\Url;

if ( !defined('AREA') ) { die('Access denied'); }

$infoLink = $_REQUEST['upload_data'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $infoLink) {

    $file_name = $_FILES['uploadVideoDocument']['name'];
    $file_size =$_FILES['uploadVideoDocument']['size'];
    $file_tmp =$_FILES['uploadVideoDocument']['tmp_name'];
    $file_type=$_FILES['uploadVideoDocument']['type'];
    $file_ext=strtolower(end(explode('.',$_FILES['uploadVideoDocument']['name'])));
    
    if($file_ext){
        ini_set('upload_max_filesize', '10M');
        ini_set('post_max_size', '10M');
        ini_set('max_input_time', 300);
        ini_set('max_execution_time', 300);
       
        $currentTime = time();
        $parentTab = $_POST['parentTab'];
        $tabName = $_POST['tabName'];
        $uploadType = $_POST['uploadType'];
        $file_path = "images/New_Product_Guidance_Docs/".$currentTime.$file_name;

        if ($_FILES["uploadVideoDocument"]["size"] < 2000000 && $_FILES["uploadVideoDocument"]["size"] != 0) 
        {

            $allowed_extension_video = array("webm","mp4");
            $allowed_extension_document = array("pdf");
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

            if($parentTab == 'vendor')
            {
                $url = 'infolink.vendor_upload';

            } elseif($parentTab == 'affiliate_links'){

                $url = 'infolink.affiliate_links';

            } elseif($parentTab == 'shipping_links'){

                $url = 'infolink.shipping_upload';
        
            } elseif($parentTab == 'taxes_links'){

                $url = 'infolink.taxes_upload';

            } elseif($parentTab == 'product_manage_links'){

                $url = 'infolink.product_manage';
            
            } elseif($parentTab == 'import_data'){

                $url = 'infolink.import_data';

            }else {

                $url = 'infolink.info_upload';
            }


            $checkOptionValidation = FALSE;
            if(in_array($file_extension, $allowed_extension_video) && $uploadType == "video")
            {
                $checkOptionValidation = TRUE;

            } elseif(in_array($file_extension, $allowed_extension_document) && $uploadType == "document"){
                
                $checkOptionValidation = TRUE;
            }

            if($checkOptionValidation == TRUE)
            {
                move_uploaded_file($file_tmp, $file_path);

                $checkData = db_get_array("SELECT * FROM ?:video_document WHERE `parent_name` = '$parentTab' AND `tab_name` = '$tabName' AND `upload_type` = '$uploadType'");


                // $insertData = "INSERT INTO ?:video_document (`id`, `parent_name`, `tab_name`, `upload_type`, `url`) VALUES ('', '$parentTab', '$tabName', '$uploadType', '$file_path')";
                // db_query($insertData);

                if(empty($checkData) || $uploadType == "video")
                {
                    $insertData = "INSERT INTO ?:video_document (`id`, `parent_name`, `tab_name`, `upload_type`, `url`) VALUES ('', '$parentTab', '$tabName', '$uploadType', '$file_path')";
                    db_query($insertData);
                } else{
                    $updateData = "UPDATE ?:video_document SET `url` = '$file_path' WHERE `parent_name` = '$parentTab' AND `tab_name` = '$tabName' AND `upload_type` = '$uploadType'";
                    db_query($updateData);
                }

                $statusMessage = 'N';
                $headerMessage = 'Successfully';
                $message = 'uploaded';
                //fn_set_notification('N', 'Successfully', 'uploaded.', 'K'); //return array(CONTROLLER_STATUS_REDIRECT, 'infolink.info_upload&section=aa');
            } else {

                $statusMessage = 'E';
                $headerMessage = 'Error';
                $message = 'Please Upload valid file.';
            }

        }else{  

            $statusMessage = 'E';
            $headerMessage = 'Error';
            $message = 'Sorry, your file is too large.';

            if($parentTab == 'vendor')
            {
                $url = 'infolink.vendor_upload';

            } elseif($parentTab == 'affiliate_links'){

                $url = 'infolink.affiliate_links';

            } elseif($parentTab == 'shipping_links'){

                $url = 'infolink.shipping_upload';
        
            } elseif($parentTab == 'taxes_links'){

                $url = 'infolink.taxes_upload';
                
            } elseif($parentTab == 'product_manage_links'){

                $url = 'infolink.product_manage';

            } elseif($parentTab == 'import_data'){

                $url = 'infolink.import_data';

            }else {

                $url = 'infolink.info_upload';
            }

        }

    }else{
        $statusMessage = 'E';
        $headerMessage = 'Error';
        $message = 'Something went wrong please try again.';
    }

    fn_set_notification($statusMessage, $headerMessage, $message, 'K');
    fn_redirect($url, true);

}

?>