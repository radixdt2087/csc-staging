<?php
/*****************************************************************************
*                                                                            *
*          All rights reserved! CS-Commerce Software Solutions               *
* 			https://www.cs-commerce.com/license-agreement.html 				 *
*                                                                            *
*****************************************************************************/
if (!defined('BOOTSTRAP')) { die('Access denied'); }
$schema = array(   
	'speedup_general' => array(
		'general_info'=>array(
			'type' => 'title'			
		),
		'clss_status'=>array(
			'type' => 'hidden',
			'default' => '0'			
		),
		'clss_admin_status'=>array(
			'type' => 'hidden',
			'default' => '0'			
		),
		
		'speedup_info'=>array(
			'type' => 'func_info',
			'value'=>'fn_cls_speedup_run_scan_info'			
		),
		'speedup_tech_settings'=>array(
			'type' => 'title'			
		),		
		'speedup_cluster_size' => array(
			'type' => 'selectbox',
			'default' => '2',			
			'tooltip' => true,
			'variants'=>array(
				'1'=>'1',
				'2'=>'2',
				'3'=>'3'
			)
		),
		'speedup_exclude_import' => array(
			'type' => 'checkbox',
			'default' => 'Y',			
			'tooltip' => true,
		),
		'speedup_cron_key' => array(
			'type' => 'input',
			'default' => 'HEL765FW',			
			'tooltip' => true,
		),
	),		 	  	  		 	 	 	
);


return $schema;