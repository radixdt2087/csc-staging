<?php
 use Tygh\Registry; defined('BOOTSTRAP') or die('Access denied'); if ($_SERVER['REQUEST_METHOD'] == 'POST') { if ($mode == 'm_update') { $fields_data = db_get_array("SELECT field_id, profile_show, profile_required FROM ?:profile_fields"); Registry::set('sd_affiliate_fields_data', $fields_data); } } 