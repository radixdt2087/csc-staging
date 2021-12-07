<?php 
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }
fn_register_hooks(
    'url_set_locations',
    'url_post',
    'seo_get_name_post',
    'get_route',
    'get_products',
    'get_cart_products'
);
