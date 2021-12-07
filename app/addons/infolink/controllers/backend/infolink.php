<?php 
	
	use Illuminate\Support\Collection;
	use Tygh\Tools\Url;
	use Tygh\Registry;
	
	// if (!defined('BOOTSTRAP')) { die('Access denied');} 
	

	// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// 	die();
	// }

	
	// if($mode ==  'info_upload') 
	// {
	// 	$str = "Hello";
	// 	$view = Registry::get('view');
    //     $view->assign('str',$str);

	// }

	// $uploadData = db_get_array("SELECT * FROM ?:video_document");
    // $view = Registry::get('view');
    // $view->assign(['uploadData' => $uploadData]);

	// $data = db_query("DELETE FROM ?:label_value WHERE id = 101");
	// print_r($data);
	// die();
	if($mode == 'delete')
	{
		$id = $_GET['id'];

		//$delete = db_query("DELETE FROM ?:video_document WHERE parent_name = '$parentTab' AND  tab_name = '$tabName' AND upload_type = '$uploadType'");

		$delete = db_query("DELETE FROM ?:video_document WHERE id = '$id'");

		$statusMessage = 'N';
        $headerMessage = 'Deleted';
        $message = 'successfully.';
		fn_set_notification($statusMessage, $headerMessage, $message, 'K');

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
		
		fn_redirect($url, true);
	}

	function fn_my_changes_get_upload_details($parentTab, $tabName, $uploadType)
	{
		$checkData = db_get_array("SELECT * FROM ?:video_document WHERE `parent_name` = '$parentTab' AND `tab_name` = '$tabName' AND `upload_type` = '$uploadType'");
		echo $checkData[0]['url'];
	}

	function fn_my_changes_get_upload_details_function($parentTab, $tabName, $uploadType)
	{
		$checkData = db_get_array("SELECT * FROM ?:video_document WHERE `parent_name` = '$parentTab' AND `tab_name` = '$tabName' AND `upload_type` = '$uploadType'");
		return $checkData;
	}

?>