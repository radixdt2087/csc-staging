<?php

$schema['wk_vendor_kyc'] = array(
   'permissions' => array('GET' => 'view_wk_vendor_kyc', 'POST' => 'manage_wk_vendor_kyc'),
   'modes' => array(
        'delete' => array(
           'permissions' => 'manage_wk_vendor_kyc'
 	    )
    ),
);
$schema['tools']['modes']['update_status']['param_permissions']['table']['wk_vendor_kyc'] = 'manage_wk_vendor_kyc';

return $schema;

?>