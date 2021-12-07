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
if (!defined('BOOTSTRAP')) {
    die('Access denied');
    }
$dauth = Tygh::$app['session']['auth']; 
$company_id = $dauth['company_id'];     
$href='vendor_enrollment.manage';
if(isset($dauth['user_type']) && $dauth['user_type'] == 'V' && AREA == 'A') {
    $href.='&company_id='.$company_id;
    $schema['central']['vendors']['items']['vendor_enrollment.myplan'] = array(
        'attrs' => array(
            'class'=>'is-addon',           
        ),
        'href' => 'companies.update&company_id='.$company_id.'&selected_section=plan',
        'position' => 1000,
    
    );
}
$schema['central']['vendors']['items']['vendor_enrollment.package'] = array(
    'attrs' => array(
        'class'=>'is-addon',           
    ),
    'href' => $href,
    'position' => 1000,

);

$schema['central']['vendors']['items']['vendor_enrollment.addons'] = array(
    'attrs' => array(
        'class'=>'is-addon',
    ),
    'href' => 'vendor_enrollment.manage_addons',
    'position' => 1000,

);
if(isset($dauth['user_type']) && $dauth['user_type'] == 'A' && AREA == 'A') {
    $schema['central']['vendors']['items']['vendor_enrollment.permission'] = array(
        'attrs' => array(
            'class'=>'is-addon',
        ),
        'href' => 'vendor_enrollment.manage_permission',
        'position' => 1000,

    );
    $schema['top']['administration']['items']['export_data']['subitems']['vendor_enrollment.vendors_purchase'] = [
        'href' => 'exim.export?section=vendors_purchase',
        'position' => 600,
    ];
}

return $schema;