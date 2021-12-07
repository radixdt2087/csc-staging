<?php

/*
 * Copyright 2011, 1st Source IT, LLC, EZ Merchant Solutions
 */
if( !defined('BOOTSTRAP') ) die('Access denied');
Use Tygh\Registry;

function ch_tracking_csv_header($row) {
	$header = array(	
						'date'			=> 'Date',
						'order_id'		=> 'Order ID',
						'status'			=> 'Status',
						'order_subtotal'	=> 'Order subtotal',
						'name'	=> 'Affiliate',
						'commission'		=> 'Commission',
						'commission_rate'	=> 'Commision Rate',
						'pay_key'	=> 'Payment date'
						);
	return $header;
}

define('CH_SEP', ',');
define('CH_EOL', "\n");

function ch_tracking_csv(&$tr, &$ch) {
	$header = ch_tracking_csv_header(current($tr));
	reset($tr);
	$s = '';
	foreach($header as $k => $title) {
		$row[] = $title;
	}
	$s .= implode(CH_SEP, $row).CH_EOL;
	foreach($tr as $data) {
		$row = array();
		foreach($header as $k => $junk) {
			$row[] = $data[$k];
		}
		$s .= implode(CH_SEP, $row).CH_EOL;
	}
	ch_sendStream($s);
	exit;
//ch_log(__FUNCTION__.": csv:\n$s", true); exit;
//ch_log(__FUNCTION__.": tr:".print_r($tr,true), true); exit;
}

function ch_sendStream($str, $type='csv', $filenameHint='') {
	
	if( !$filenameHint )
		$filenameHint = date("Ymd")."_commission_tracking";
	
	switch ($type) {
	case "exe": $ctype="application/octet-stream";
	break;
	case "pdf": $ctype="application/pdf";
	break;
	case "zip": $ctype="application/zip";
	break;
	case "doc": $ctype="application/msword";
	break;
	case "xls": $ctype="application/vnd.ms-excel";
	break;
	case "ppt": $ctype="application/vnd.ms-powerpoint";
	break;
	case "gif": $ctype="image/gif";
	break;
	case "png": $ctype="image/png";
	break;
	case "jpe": case "jpeg":
	case "jpg": $ctype="image/jpg";
	break;
	case "csv": $ctype="application/octet-stream";
//	case "csv": $ctype="text/text";
	break;
	default: $ctype="application/force-download";
	}
	
	$fileName = $filenameHint .'.' .$type;
	
	$mimeType = $ctype;
	header("Content-Type: " .$mimeType);
	header('Expires: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Content-Disposition: attachment; filename="' .$fileName .'"');
	header('Pragma: no-cache');
   
   set_time_limit(0);
   echo $str;
}
?>