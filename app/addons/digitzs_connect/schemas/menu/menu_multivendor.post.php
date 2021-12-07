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
if(isset($dauth['user_type']) && $dauth['user_type'] == 'V' && AREA == 'A') {
    $company_id = $dauth['company_id'];     
    $schema['central']['vendors']['items']['digitzs_connect.digitzs'] = array(
        'attrs' => array(
            'class'=>'is-addon',           
        ),
        'href' => 'companies.update&m=1&company_id='.$company_id,
        'position' => 10000,
   
    );
}


return $schema;