<?php
 defined('BOOTSTRAP') or die('Access denied'); $schema['affiliate_plans'] = [ 'restrict' => ['POST'] ]; $schema['banners_manager'] = [ 'restrict' => ['POST'] ]; $schema['product_groups'] = [ 'restrict' => ['POST'] ]; $schema['payouts'] = [ 'restrict' => ['POST'] ]; return $schema; 