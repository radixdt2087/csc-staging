<?php

/******************************************************************
# Vendor Kyc   ---      Vendor KYC                        *
# ----------------------------------------------------------------*                                   *
# copyright Copyright (C) 2010 webkul.com. All Rights Reserved.   *
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL     *
# Websites: http://webkul.com                                     *
*******************************************************************
*/

$schema['central']['vendors']['items']['wk_vendor_kyc']=array(
		'attrs' => array(
        'class'=>'is-addon'
    	),
	    'href' => 'wk_vendor_kyc.manage',
    	'position' => 800,
    	'subitems' => array(
    		'wk_vendor_kyc_manage' => array(
	            'href' => 'wk_vendor_kyc.manage',
	            'position' => 100
	        ),
	        'wk_vendor_kyc_type' => array(
	            'href' => 'wk_vendor_kyc.kyc_type',
	            'position' => 200
	        ),
	        'kyc_request_log' => array(
	            'href' => 'wk_vendor_kyc.kyc_request_log',
	            'position' => 300
	        ),
	    )
    );
return $schema;
