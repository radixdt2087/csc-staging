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

include_once(Registry::get('config.dir.schemas') . 'exim/vendors_purchase.functions.php');

$schema = array(
    'section' => 'vendors_purchase',
    'pattern_id' => 'vendors_purchase',
    'name' => __('vendors_purchase'),
    'key' => array('id'),
    'order' => 0,
    'table' => 'plan_payment_details',
    'permissions' => array(
        'edition' => 'MULTIVENDOR',
        //'import' => 'manage_vendors',
        'export' => 'vendor_enrollment',
    ),
    'condition' => array(
        'conditions' => array(
            '&plan_payment_details.status' => 'Success',
        ),
    ),
    'options' => array(
        'lang_code' => array(
            'title' => 'language',
            'type' => 'languages',
            'default_value' => array(DEFAULT_LANGUAGE),
        ),
        'images_path' => array(
            'title' => 'images_directory',
            'description' => 'text_images_directory',
            'type' => 'input',
            'default_value' => 'exim/backup/images/',
            'notes' => __('text_file_editor_notice', array('[href]' => fn_url('file_editor.manage?path=/'))),
        ),
    ),
    // 'range_options' => array(
    //     'selector_url' => 'companies.manage',
    //     'object_name' => __('vendors'),
    // ),
    // 'import_process_data' => array(
    //     'import_status' => array(
    //         'function' => 'fn_exim_vendors_import_status',
    //         'args' => array('$object'),
    //         'import_only' => true,
    //     ),
    // ),
    // 'import_after_process_data' => array(
    //     'init_vendor_logos' => array(
    //         'function' => 'fn_exim_vendor_init_logos',
    //         'args' => array('$primary_object_id', '$object', '$object_exists'),
    //         'import_only' => true,
    //     ),
    // ),
    'export_fields' => array(
        'Vendor name' => array(
            'db_field' => 'card_holder_name',
            'required' => true,
            'alt_key' => true,
        ),
        'Status' => array(
            'db_field' => 'status',
        ),
        'Plan Date' => array(
            'db_field' => 'plan_date',
            'process_get' => array('fn_timestamp_to_date', '#this'),
            'convert_put' => array('fn_date_to_timestamp', '#this'),
            'return_result' => true,
            'default' => array('time')
        ),
        'Address' => array(
            'db_field' => 'billing_address',
        ),
        'City' => array(
            'db_field' => 'billing_city',
        ),
        'Country' => array(
            'db_field' => 'country',
        ),
        'State' => array(
            'db_field' => 'state',
        ),
        'Zipcode' => array(
            'db_field' => 'zipcode',
        ),
        'Amount' => array(
            'db_field' => 'amount',
        ),
        'Purchase Type' => array(
            'db_field' => 'type',
        ),
        // 'Shippings' => array(
        //     'db_field' => 'shippings',
        // ),
        // 'Logo for the customer area' => array(
        //     'process_get' => array('fn_exim_vendors_export_logo', '#key', 'theme', '@images_path'),
        //     'process_put' => array('fn_exim_vendors_import_logo', '%E-mail%', 'theme', '#this'),
        //     'linked' => false,
        // ),
        // 'Logo for invoices' => array(
        //     'process_get' => array('fn_exim_vendors_export_logo', '#key', 'mail', '@images_path'),
        //     'process_put' => array('fn_exim_vendors_import_logo', '%E-mail%', 'mail', '#this'),
        //     'linked' => false,
        // ),
        
        // 'Date added' => array(
        //     'db_field' => 'timestamp',
        //     'process_get' => array('fn_timestamp_to_date', '#this'),
        //     'convert_put' => array('fn_date_to_timestamp', '#this'),
        //     'return_result' => true,
        //     'default' => array('time')
        // ),
    ),
);

return $schema;
