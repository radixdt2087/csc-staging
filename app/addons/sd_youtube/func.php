<?php
/***************************************************************************
*                                                                          *
*   © Simtech Development Ltd.                                             *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
***************************************************************************/

use Tygh\Registry;
use Tygh\Http;
use Tygh\Addons\SchemesManager;
use Tygh\VideoUrlParser;
use Tygh\BlockManager\ProductTabs;
use Tygh\Enum\YesNo;

defined('BOOTSTRAP') or die('Access denied');

function fn_sd_youtube_delete_youtube_filters()
{
    $filter_ids = db_get_fields('SELECT filter_id FROM ?:product_filters WHERE field_type = ?s', YesNo::YES);
    if (!empty($filter_ids)) {
        foreach ($filter_ids as $filter_id) {
            fn_delete_product_filter($filter_id);
        }
    }
}

function fn_sd_youtube_get_product_video_id($product_id, $lang_code = CART_LANGUAGE)
{
    $product_video_id = 0;

    if ($product_id) {
        $company_id = Registry::get('runtime.company_id');

        if (!$company_id) {
            $company_id = db_get_field('SELECT company_id FROM ?:products WHERE product_id = ?i', $product_id);
        }

        if ($company_id) {
            if (fn_allowed_for('ULTIMATE') && fn_ult_is_shared_product($product_id) == YesNo::YES && $company_id) {
                $product_video_id = db_get_field(
                    'SELECT youtube_link FROM ?:ult_product_descriptions WHERE product_id = ?i AND company_id = ?i',
                    $product_id, $company_id
                );
            } else {
                $product_video_id = db_get_field('SELECT youtube_link FROM ?:products WHERE product_id = ?i', $product_id);
            }
        }
    }

    return $product_video_id;
}

function fn_sd_youtube_get_product_position_youtube($product_id, $lang_code = CART_LANGUAGE)
{
    $product_position_youtube = 0;

    if ($product_id) {
    	$product_position_youtube = db_get_field('SELECT position_youtube FROM ?:products WHERE product_id = ?i', $product_id);
    }

    return $product_position_youtube;
}

function fn_sd_youtube_update_product_pre(&$product_data, $product_id, $lang_code, $can_update)
{
    if (!empty($product_data['youtube_link'])) {
        $parser = new VideoUrlParser();
        $product_data['youtube_link'] = $parser->getUrlId($product_data['youtube_link']);
        if (Registry::get('runtime.controller') == 'products' && Registry::get('runtime.mode') == 'update') {
            $product_data['show_youtube_video'] = !empty($product_data['show_youtube_video']) ? $product_data['show_youtube_video'] : YesNo::NO;
            $product_data['replace_main_image'] = !empty($product_data['replace_main_image']) ? $product_data['replace_main_image'] : YesNo::NO;
            $product_data['position_youtube'] = !empty($product_data['position_youtube']) ? $product_data['position_youtube'] : YesNo::NO;
        }
    } else {
        $product_data['replace_main_image'] = YesNo::NO;
        $product_data['position_youtube'] = YesNo::NO;

    }
}

function fn_sd_youtube_update_product_post($product_data, $product_id, $lang_code, $create)
{
    $parser = new VideoUrlParser();

    if (fn_allowed_for('MULTIVENDOR') && empty(Registry::get('runtime.company_id'))) {
        $company_id = db_get_field('SELECT company_id FROM ?:products WHERE product_id = ?i', $product_id);
    } else {
        $company_id = Registry::ifGet('runtime.company_id', $product_data['company_id']);
    }

    if (!empty($product_data['youtube_videos'])) {
        $table = db_quote(' ?:product_videos');
        $company_condition = '';
        if (fn_allowed_for('ULTIMATE') && !$create && fn_ult_is_shared_product($product_id) == YesNo::YES) {
            $table = db_quote(' ?:ult_product_videos');
        }

        if ($company_id) {
            $company_condition = db_quote(' AND company_id = ?i', $company_id);
        }

        db_query('DELETE FROM ?p WHERE product_id = ?i ?p', $table, $product_id, $company_condition);

        foreach ($product_data['youtube_videos'] as $video_data) {
            if (!empty($video_data['youtube_link'])) {
                $video_data['product_id'] = $product_id;
                $video_data['company_id'] = $company_id ? $company_id : $product_data['company_id'];
                $video_data['youtube_link'] = $parser->getUrlId($video_data['youtube_link']);
                db_query('REPLACE INTO ?p ?e', $table, $video_data);
            }
        }
    }

    if (fn_allowed_for('ULTIMATE') && !$create && fn_ult_is_shared_product($product_id) == YesNo::YES) {
        $update_all_vendors = !empty($_REQUEST['update_all_vendors']) ? $_REQUEST['update_all_vendors'] : array();

        if (!empty($update_all_vendors)) {
            foreach ($update_all_vendors as $key => $v) {
                $v = !empty($v[$product_id]) ? $v[$product_id] : $v;
                if (!is_array($v) && $v != YesNo::YES) {
                    continue;
                }

                if (in_array($key, array('youtube_link', 'show_youtube_video', 'replace_main_image'))) {
                    db_query('UPDATE ?:ult_product_descriptions SET `' . $key . '` = ?s WHERE product_id = ?i', $product_data[$key], $product_id);
                }
            }
        } else {
            $_data = array(
                'youtube_link' => isset($product_data['youtube_link']) ? $product_data['youtube_link'] : '',
                'show_youtube_video' => !empty($product_data['show_youtube_video']) ? $product_data['show_youtube_video'] : YesNo::NO,
                'replace_main_image' => !empty($product_data['replace_main_image']) ? $product_data['replace_main_image'] : YesNo::NO,
            );
            $condition = '';
            $company_id = $company_id ? $company_id : $product_data['company_id'];

            if ($company_id) {
                $condition .= db_quote(' AND company_id = ?i', $company_id);
            }

            db_query('UPDATE ?:ult_product_descriptions SET ?u WHERE product_id = ?i ?p', $_data, $product_id, $condition);
        }
    }
}

function fn_sd_youtube_get_product_data_post(&$product_data, $auth, $preview, $lang_code)
{
    $fields = array(
        'video_id',
        'product_id',
        'comment',
        'youtube_link',
        'position'
    );

    $company_id = Registry::get('runtime.company_id') ? Registry::get('runtime.company_id') : $product_data['company_id'];
    $table = db_quote(' ?:product_videos');

    if (fn_allowed_for('ULTIMATE') && fn_ult_is_shared_product($product_data['product_id']) == YesNo::YES) {
        $table = db_quote(' ?:ult_product_videos');
    }

    $product_data['youtube_videos'] = db_get_array(
        'SELECT ' . implode(', ', $fields)
        . ' FROM ?p'
        . ' WHERE product_id = ?i'
        . ' AND company_id = ?i'
        . ' ORDER BY position'
        , $table, $product_data['product_id'], $company_id
    );
}

function fn_sd_youtube_gather_additional_products_data_post($product_ids, $params, &$products, $auth)
{
    $controller = Registry::get('runtime.controller');
    $mode = Registry::get('runtime.mode');
    $tab_list = ProductTabs::instance()->getTemplates();
    $replace_main_img = Registry::get('addons.sd_youtube.replace_main_img');

    foreach ($tab_list as $tab) {
         if ($tab == 'collections') {
             $collection = 'true';
         } else {
             $collection = 'false';
         }
    }

    foreach ($products as &$product) {
        //add in array image_main
        if (!empty($product['youtube_link'])) {
            if ($controller == 'index' && $mode == 'index') {
                $product['image_pairs'] = !empty($product['image_pairs']) ? (array($product['main_pair']['pair_id'] => $product['main_pair']) + $product['image_pairs']) : array($product['main_pair']['pair_id'] => $product['main_pair']);
            }
            $youtube_video_id = fn_sd_youtube_get_product_video_id($product['product_id']);
            $youtube_position = fn_sd_youtube_get_product_position_youtube($product['product_id']);
            $youtube_position = ($youtube_position < 1) ? 0 : ($youtube_position - 1);
            $youtube_link_with_protocol = Registry::get('settings.Security.secure_storefront') == ('full' || YesNo::YES) ? SD_YOUTUBE_HTTPS_LINK : SD_YOUTUBE_HTTP_LINK;
            $youtube_image_params = array(
                'image_path' => $youtube_link_with_protocol . "$youtube_video_id" . '/0.jpg',
                'image_x' => '35',
                'image_y' => '35',
                'http_image_path' => SD_YOUTUBE_HTTP_LINK . "$youtube_video_id" . '/0.jpg',
                'https_image_path' => SD_YOUTUBE_HTTPS_LINK . "$youtube_video_id" . '/0.jpg',
                'absolute_path' => '',
                'relative_path' => '',
            );

            if ($controller == 'products'
                && ($mode == 'options' || $mode == 'quick_view'
                    ||($mode == 'view' && Registry::get('sd_youtube_is_main_block')))
            ) {
                if (isset($product['show_youtube_video']) && $product['show_youtube_video'] == YesNo::YES) {
                    if (isset($product['image_pairs'])) {
                        array_splice($product['image_pairs'], $youtube_position, 0, array($youtube_video_id =>array(
                            'pair_id' => $youtube_video_id,
                            'detailed_id' => $youtube_video_id,
                            'position' => $youtube_position,
                            'detailed' => $youtube_image_params
                        )));
                    }
                    if (isset($product['replace_main_image']) && $product['replace_main_image'] == YesNo::YES) {
                        if (isset($product['image_pairs'])) {
                            foreach ($product['image_pairs'] as $key => $value) {
                                if ($value['pair_id'] == $youtube_video_id) {
                                    list($product['image_pairs'][$key], $product['main_pair']) = array($product['main_pair'], $product['image_pairs'][$key]);
                                }
                            }
                        }
                        if ($collection == 'false') {
                            $product['main_pair']['detailed_id'] = $youtube_video_id;
                            $product['main_pair']['detailed'] = $youtube_image_params;
                        }
                    }
                }
            } elseif (((Registry::get('addons.sd_youtube.replace_main_img') == YesNo::YES || Registry::get('get_youtube_images')) && $mode != 'quick_view')
                || $controller == 'youtube_gallery'
            ) {
                if (empty($product['image_pairs'])) {
                    $product['image_pairs'] = array();
                }
                if (isset($product['main_pair']['pair_id']) && !empty($params['get_additional'])) {
                    $product['image_pairs'][$product['main_pair']['pair_id']] = $product['main_pair'];
                    ksort($product['image_pairs']);
                }

                $product['main_pair']['pair_id'] = $youtube_video_id;
                if ($controller != 'collection' && $controller != 'checkout') {
                    $product['main_pair']['detailed_id'] = $youtube_video_id;
                    $product['main_pair']['detailed'] = $youtube_image_params;
                }
            }
        }
    }
}

function fn_sd_youtube_render_block_pre($block, $block_schema, $params, $block_content)
{
    if (!empty($block['type']) && $block['type'] == 'main') {
        Registry::set('sd_youtube_is_main_block', true);
    } else {
        Registry::set('sd_youtube_is_main_block', false);
    }
}

function fn_sd_youtube_get_video_ico($youtube_video_id, $product_id = 0)
{
    $video_ico = array();

    if (!empty($youtube_video_id)) {
        $youtube_link_with_protocol = Registry::get('settings.Security.secure_storefront') == ('full' || YesNo::YES) ? SD_YOUTUBE_HTTPS_LINK : SD_YOUTUBE_HTTP_LINK;
        $video_ico = array();
        $video_ico['pair_id'] = "$youtube_video_id" . "$product_id";
        $video_ico['image_id'] = $youtube_video_id;
        $video_ico['detailed_id'] = 0;
        $video_ico['position'] = 0;
        $video_ico['icon'] = array(
            'image_path' => $youtube_link_with_protocol . "$youtube_video_id" . "/0.jpg",
            'http_image_path' => SD_YOUTUBE_HTTP_LINK . "$youtube_video_id" . "/0.jpg",
            'https_image_path' => SD_YOUTUBE_HTTPS_LINK . "$youtube_video_id" . "/0.jpg",
            'absolute_path' => '',
            'relative_path' => '',
        );
    }

    return $video_ico;
}

function fn_sd_youtube_get_products_videos($limit = 0, $company_id, $condition = '')
{
    $product_videos = array();
    $share_product_video = array();
    $condition .= db_quote(' AND youtube_link != ?s', '');

    if (!empty($company_id)) {
        $condition .= db_quote(' AND company_id = ?i', $company_id);
    }

    $product_videos = db_get_hash_single_array(
    'SELECT product_id, youtube_link'
    . ' FROM ?:products'
    . ' WHERE 1 ?p'
    , array('product_id', 'youtube_link'), $condition
    );

    if (fn_allowed_for('ULTIMATE') && $company_id) {
        foreach ($product_videos as $product_id => $youtube_link) {
            if (!in_array($company_id, fn_ult_get_shared_product_companies($product_id))) {
                unset($product_videos[$product_id]);
                continue;
            }
        }
        $share_product_video = db_get_hash_single_array(
            'SELECT product_id, youtube_link'
            . ' FROM ?:ult_product_descriptions'
            . ' WHERE youtube_link != ?s AND company_id = ?i'
            , array('product_id', 'youtube_link'), '', $company_id
        );
    }

    $product_videos = fn_array_merge($product_videos, $share_product_video);

    if ($limit != 0) {
        $product_videos = array_slice($product_videos, 0, $limit);
    }

    return $product_videos;
}

function fn_sd_youtube_get_product_videos($product_id, $company_id)
{
    $product_videos = array();
    $table = db_quote(' ?:product_videos');
    if ($product_id && $company_id) {
        if (fn_allowed_for('ULTIMATE') && fn_ult_is_shared_product($product_id) == YesNo::YES) {
            $table = db_quote(' ?:ult_product_videos');
        }
        $product_videos = db_get_hash_single_array(
            'SELECT video_id, product_id, youtube_link'
            . ' FROM ?p'
            . ' WHERE product_id = ?i AND company_id = ?i'
            , array('video_id', 'youtube_link'), $table, $product_id, $company_id
        );

    } elseif ($product_id) {
        $product_videos = db_get_hash_single_array(
        'SELECT video_id, product_id, youtube_link FROM ?p WHERE product_id = ?i', array('video_id', 'youtube_link'), $table, $product_id);
     }

    return $product_videos;
}

function fn_sd_youtube_get_products($params, &$fields, &$sortings, &$condition, &$join, $sorting, $group_by, $lang_code, $having)
{
    $runtime = Registry::get('runtime');
    $company_id = $runtime['company_id'];
    if (AREA == 'C') {
        $fields['replace_main_image'] = 'products.replace_main_image';
        $fields['youtube_link'] = 'products.youtube_link';
        $fields['show_youtube_video'] = 'products.show_youtube_video';
        if ($runtime['controller'] == 'youtube_gallery' && $runtime['mode'] == 'view') {
            $params['only_short_fields'] = true;
        }
    }

    if ($company_id && fn_allowed_for('ULTIMATE')) {

        $fields['youtube_link'] = db_quote('IF(shared_descr.product_id IS NOT NULL, shared_descr.youtube_link, products.youtube_link) AS youtube_link');
        $sortings['youtube_link'] = db_quote('IF(shared_descr.product_id IS NOT NULL, shared_descr.youtube_link, products.youtube_link)');

        if (!strripos($join, '?:ult_product_descriptions shared_descr')) {
            $join .= db_quote(' LEFT JOIN ?:ult_product_descriptions shared_descr ON products.product_id = shared_descr.product_id', $company_id);
        }

        if ((!empty($params['with_video']) && $params['with_video'] == true) || (!empty($params['youtube_link']) && $params['youtube_link'] == YesNo::YES)
        ) {
            $condition .= db_quote(' AND IF(shared_descr.product_id IS NOT NULL, shared_descr.youtube_link, products.youtube_link) != ?s', '');
            $condition .= db_quote(' AND IF(shared_descr.product_id IS NOT NULL, shared_descr.show_youtube_video, products.show_youtube_video) = ?s', YesNo::YES);
            $condition .= db_quote(' AND IF(shared_descr.product_id IS NOT NULL, shared_descr.company_id, products.company_id) = ?i', $company_id);
        }
    } else {
        $fields['youtube_link'] = 'products.youtube_link';
        $sortings['youtube_link'] = 'products.youtube_link';
        if ((!empty($params['with_video']) && $params['with_video'] == true) || (!empty($params['youtube_link']) && $params['youtube_link'] == YesNo::YES)
        ) {
            $condition .= db_quote(' AND products.youtube_link != ?s', '');
            $condition .= db_quote(' AND products.show_youtube_video = ?s', YesNo::YES);
        }
    }
}

function fn_sd_youtube_get_product_filter_fields(&$filters)
{
    $additional_filters = fn_sd_youtube_get_youtube_video_fields();
    $filters = array_merge($filters, $additional_filters);

    if (!isset($additional_filters[YesNo::YES])) {
        unset($filters[YesNo::YES]);
    }
}

function fn_sd_youtube_get_youtube_video_fields()
{
    $additional_fields = array();
    $additional_fields[YesNo::YES] = array(
        'db_field' => 'youtube_link',
        'table' => 'products',
        'description' => 'product_with_video',
        'condition_type' => 'C',
        'map' => array(
            'youtube_link' => YesNo::YES,
        ),
        'variant_name_field' => '\'' .  __('product_with_video') . '\'',
        'conditions' => function($db_field, $fields_join, $fields_where) {
            $fields_where = db_quote(" AND $db_field != ''");
            $db_field = db_quote("IF($db_field != '', ?s, ?s)", YesNo::YES, YesNo::YES);
            return array($db_field, $fields_join, $fields_where);
        }
    );

    return $additional_fields;
}

function fn_sd_youtube_get_products_before_select(&$params, $join, &$condition, $u_condition, $inventory_join_cond, &$sortings, $total, $items_per_page, $lang_code, $having)
{
    $company_id = Registry::get('runtime.company_id');

    if (!empty($params['filter_params']['youtube_link'])) {
        if ($company_id && fn_allowed_for('ULTIMATE')) {
            $condition .= db_quote(' AND (products.youtube_link != ?s AND products.company_id = ?i) OR (shared_descr.youtube_link != ?s AND shared_descr.company_id = ?i)', '', $company_id, '', $company_id);
        } else {
            $condition .= db_quote(' AND products.youtube_link != ?s', '', $company_id);
        }
        unset($params['filter_params']['youtube_link']);
    }
    $sortings['youtube_link'] = 'products.youtube_link';
}

function fn_sd_youtube_check_and_update_product_sharing($product_id, $shared, $existing_company_ids, $product_categories_company_ids)
{
    if ($product_id) {
        $count = count($product_categories_company_ids);
        $company_id = reset($product_categories_company_ids);
        $product_company_id = db_get_field('SELECT company_id FROM ?:products WHERE product_id = ?i', $product_id);

        if ($count == 1 && $company_id == $product_company_id) {
            db_query('DELETE FROM ?:ult_product_videos WHERE product_id = ?i', $product_id);
        } else {
            $added_company_ids = array_diff($product_categories_company_ids, $existing_company_ids);
            $deleted_company_ids = array_diff($existing_company_ids, $product_categories_company_ids);

            $product_videos = db_get_array('SELECT video_id, product_id, comment, youtube_link FROM ?:product_videos WHERE product_id = ?i', $product_id);
            $product_main_video_data = db_get_row('SELECT youtube_link, show_youtube_video, replace_main_image FROM ?:products WHERE product_id = ?i', $product_id);

            // Copying product data to sharing tables
            foreach ($added_company_ids as $new_cid) {
                foreach ($product_videos as $video) {
                    if ($new_cid) {
                        $video['company_id'] = $new_cid;
                        db_query('REPLACE INTO ?:ult_product_videos  ?e', $video);
                    }
                }
                db_query(
                    'UPDATE ?:ult_product_descriptions SET ?u WHERE product_id = ?i AND company_id = ?i', $product_main_video_data, $product_id, $new_cid
                );
            }

            // Deleting data from sharing tables
            if (!empty($deleted_company_ids)) {
                db_query(
                    'DELETE FROM ?:ult_product_videos WHERE product_id = ?i AND company_id IN (?n)', $product_id, $deleted_company_ids
                );
            }
        }
    }
}

function fn_sd_youtube_get_category_video($params)
{
    $video_ids = array();

    if (isset($params['category']) && !$params['from_all_store']) {
        $params['subcats'] = '';
        if (Registry::get('settings.General.show_products_from_subcategories') == YesNo::YES) {
            $params['subcats'] = YesNo::YES;
        }

        $params['cid'] = $params['category'];
        $params['with_video'] = true;

        list($products, $search) = fn_get_products($params, $params['video_limit']);

        if (!empty($products)) {
            foreach ($products as $product_id => $product_data) {
                $video_ids[] = $product_data['youtube_link'];
            }
        }
    } else {
        $video_ids = fn_sd_youtube_get_products_videos($params['video_limit'], Registry::get('runtime.company_id'));
    }

    return array($video_ids, $params);
}

function fn_sd_youtube_get_single_video($params = array())
{
    if (!empty($params['youtube_link'])) {
        $res = array(
            'youtube_link' => $params['youtube_link'],
            'video_width' => !empty($params['video_width']) ? $params['video_width'] : '',
            'visibility' => !empty($params['visibility']) ? $params['visibility'] : '',
            'show_suggested_videos_after_finish' => '0',
        );
    }
    return !empty($res) ? array($res, $params) : array();
}

function fn_sd_youtube_update_block_pre(&$block_data)
{
    if (!isset($block_data['content_data']['override_by_this']) ||
    (isset($block_data['content_data']['override_by_this']) &&
    $block_data['content_data']['override_by_this'] == YesNo::NO)) {
        if (isset($block_data['content']['items']['all_subcategories']) &&
        $block_data['content']['items']['all_subcategories'] == YesNo::YES &&
        $block_data['content_data']['object_type'] == 'categories' &&
        $block_data['content_data']['object_id'] &&
        !empty($block_data['block_id'])) {
            $block_id = intval($block_data['block_id']);
            $subcategories = fn_get_subcategories($block_data['content_data']['object_id']);
            $content_data = $block_data;

            if (!empty($subcategories)) {
                foreach ($subcategories as $subcategory_id => $subcategory_data) {
                    $content_data['content_data']['object_id'] = $subcategory_id;
                    if (!empty($content_data['apply_to_all_langs']) && $content_data['apply_to_all_langs'] == YesNo::YES) {
                        foreach (fn_get_translation_languages() as $content_data['content_data']['lang_code'] => $v) {
                            fn_sd_youtube_update_block_data($block_id, $content_data['type'], $content_data['content_data']);
                        }
                    } else {
                        fn_sd_youtube_update_block_data($block_id, $content_data['type'], $content_data['content_data']);
                    }
                }
            }
        }
    }

    if ($block_data['type'] == 'youtube_video') {
        if (isset($block_data['content']['items']['youtube_link']) && !empty($block_data['content']['items']['youtube_link'])) {
            $parser = new VideoUrlParser();
            $block_data['content_data']['content']['items']['youtube_link'] = $block_data['content']['items']['youtube_link'] = $parser->getUrlId($block_data['content']['items']['youtube_link']);
        }
    }
}

function fn_sd_youtube_update_block_data($block_id, $block_type, $content_data)
{
    if (!empty($block_type)) {
        if (isset($content_data['content']) && is_array($content_data['content'])) {
            $content_data['content'] = serialize($content_data['content']);
        } else {
            $content_data['content'] = '';
        }
        $content_data['block_id'] = $block_id;

        if (isset($content_data['snapping_id'])) {
            unset($content_data['snapping_id']);
        }

        db_replace_into('bm_blocks_content', $content_data);

        return true;
    }
    return false;
}







function fn_sd_youtube_can_disable_addon()
{
    $filter_ids = db_get_fields('SELECT filter_id FROM ?:product_filters WHERE field_type = ?s', YesNo::YES);

    return empty($filter_ids);
}
