<?php

$schema['wk_vendor_subdomain'] = array (
    'permissions' => array ('GET' => 'view_wk_vendor_subdomain', 'POST' => 'manage_wk_vendor_subdomain'),
    'modes' => array (
        'delete' => array (
            'permissions' => 'manage_wk_vendor_subdomain'
        ),
        'm_delete' => array (
            'permissions' => 'manage_wk_vendor_subdomain'
        )
    ),
);

$schema['tools']['modes']['update_status']['param_permissions']['table']['wk_vendor_subdomain'] = 'manage_wk_vendor_subdomain';

return $schema;