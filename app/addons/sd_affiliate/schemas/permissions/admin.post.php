<?php
 defined('BOOTSTRAP') or die('Access denied'); $schema['aff_statistics'] = [ 'permissions' => 'manage_affiliate', ]; $schema['banners_manager'] = [ 'permissions' => 'manage_affiliate', ]; $schema['payouts'] = [ 'permissions' => 'manage_affiliate', ]; $schema['product_groups'] = [ 'permissions' => 'manage_affiliate', ]; $schema['partners'] = [ 'permissions' => 'manage_affiliate', ]; $schema['affiliate_plans'] = [ 'permissions' => 'manage_affiliate', ]; $schema['tools']['modes']['update_status']['param_permissions']['table']['aff_groups'] = 'manage_affiliate'; $schema['tools']['modes']['update_status']['param_permissions']['table']['affiliate_plans'] = 'manage_affiliate'; $schema['tools']['modes']['update_status']['param_permissions']['table']['aff_banners'] = 'manage_affiliate'; $schema['tools']['modes']['update_status']['param_permissions']['table']['affiliate_payouts'] = 'manage_affiliate'; return $schema; 