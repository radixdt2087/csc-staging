<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'get_content') {
    $page_id = Registry::get('addons.cp_terms_and_conditions.terms_page_id');
    if (!empty($page_id)) {
        $page_content = fn_cp_terms_and_conditions_get_page_content($page_id);
    } else {
        $page_content = __('cp_terms_n_conditions_content');
    }
    echo $page_content;
    exit;
}