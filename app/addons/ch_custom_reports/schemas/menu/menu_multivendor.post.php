<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/
use Tygh\Registry;

$user_type = $_SESSION['auth']['user_type'];

if ($user_type == "V") 
{
    $schema['central']['vendors']['items']['affiliate_links'] = array(
            'attrs' => array( 'class' => 'is-addon' ),
            'href' => 'merchant_affiliation.link',
            'alt' => 'merchant_affiliation.link',
            'position' => 900,
    );
    
    // $schema['central']['vendors']['items']['vendor_reports'] = array(
    //         	 'attrs' => array( 'class' => 'is-addon' ),
    //             'href' => 'ch_custom_reports.vendor_reports',
    //             'alt' => 'ch_custom_reports.vendor_reports',
    //             'position' => 1000,
    // );
    
    // $schema['central']['vendors']['items']['vendor affiliation link'] = array(
    //     'attrs' => array(
    //         'class' => 'is-addon'
    //     ),
    //     'href' => 'merchant_affiliation.link',
    //     'position' => 800,
    // );
}
$schema['central']['vendors']['items']['rewards_report'] = array(
    'attrs' => array( 'class' => ['is-addon'] ),
    'href' => 'ch_custom_reports.vendor_reports',
    'alt' => 'ch_custom_reports.vendor_reports',
    'position' => 1000,
);

if ($user_type == "A") 
{   
    $schema['central']['vendors']['items']['upload_video_document'] = array(
        'attrs' => array( 'class' => ['is-addon'] ),
        'href' => 'infolink.info_upload',
        'alt' => 'infolink.info_upload',
        'position' => 2000,
    );
    
}
//print_r($schema);
return $schema;
