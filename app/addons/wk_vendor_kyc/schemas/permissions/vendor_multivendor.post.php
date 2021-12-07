<?php

$schema['controllers']['wk_vendor_kyc'] = array (
            'modes' => array(
                'upload_kyc' => array(
                    'permissions' => true,
                ), 
                'manage' => array(
                    'permissions' => true,
                ),
                'download' => array(
                    'permissions' => true,
                ),   
                 'add_kyc_type' => array(
                    'permissions' => false,
                ), 
                'm_kyc_type_delete' => array(
                    'permissions' => false,
                ),
                'delete' => array(
                    'permissions' => false,
                ),
                  'm_delete' => array(
                    'permissions' => false,
                ), 
                'accept' => array(
                    'permissions' => false,
                ),
                'reject' => array(
                    'permissions' => false,
                ),
                 'kyc_type' => array(
                    'permissions' => false,
                ),
                'kyc_request_log' => array(
                    'permissions' => false,
                ),
                'send_upload_request' => array(
                    'permissions' => false,
                ),
                'm_send_upload_request' => array(
                    'permissions' => false,
                )               
            ),
            'permissions' =>true,
);

return $schema;