<?php
/***************************************************************************
*                                                                          *
*   © Simtech Development Ltd.                                             *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
***************************************************************************/

use Tygh\Enum\YesNo;

/**
 * Check if mod_rewrite is active and clean up templates cache
 */
function fn_settings_actions_addons_sd_youtube(&$new_value, $old_value)
{
    if ($new_value == 'D') {
        $filter_ids = db_get_fields('SELECT filter_id FROM ?:product_filters WHERE field_type = ?s', YesNo::YES);

        if (!empty($filter_ids)) {

            $filter_names = db_get_fields('SELECT filter FROM ?:product_filter_descriptions WHERE filter_id IN (?n) AND lang_code = ?s', $filter_ids, CART_LANGUAGE);

            fn_set_notification('W', __('warning'), __('addons.sd_youtube.sd_youtube_error_delete_filters', array(
                '[filter_name]' => implode(', ', $filter_names)
            )), 'I');

            $new_value = 'A';
        }
    }
}
