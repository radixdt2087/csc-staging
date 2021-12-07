<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_cp_terms_and_conditions_get_page_content($page_id)
{
    $page_content = db_get_field("SELECT description FROM ?:page_descriptions WHERE page_id = ?i AND lang_code = ?s", $page_id, CART_LANGUAGE);
    if (empty($page_content)) {
        $page_content = __("terms_and_conditions_content");
    }
    return $page_content;
}

function fn_cp_terms_and_conditions_get_page_title($page_id)
{
    $page_title = fn_get_page_name($page_id);
    if (empty($page_title)) {
        $page_title = __("checkout_terms_n_conditions_name");
    }
    return $page_title;
}