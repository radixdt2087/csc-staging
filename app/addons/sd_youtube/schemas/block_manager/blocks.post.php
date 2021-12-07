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
use Tygh\Registry;

$schema['youtube_video'] = array(
    'templates' => array(
        'addons/sd_youtube/blocks/youtube_video.tpl' => array()
    ),
    'content' => array(
        'items' => array(
            'type' => 'enum',
            'object' => 'youtube_video',
            'hide_label' => true,
            'remove_indent' => true,
            'items_function' => 'fn_sd_youtube_get_single_video',
            'fillings' => array(
                'sd_youtube_single_video' => array(
                    'params' => array(
                        'sd_youtube_single_video' => true
                    ),
                ),
            ),
        ),
    ),
    'wrappers' => 'blocks/wrappers',
    'cache' => false
);

$schema['video_gallery'] = array(
    'content' => array(
        'items' => array(
            'remove_indent' => true,
            'hide_label' => true,
            'object' => 'videos',
            'type' => 'enum',
            'items_function' => 'fn_sd_youtube_get_category_video',
            'fillings' => array(
                'videos_from_all_store' => array(
                    'params' => array(
                        'from_all_store' => true,
                        'category_id' => 0,
                    ),
                ),
                'videos_from_category' => array(
                    'params' => array(
                        'plain' => false,
                        'simple' => false,
                        'group_by_level' => false,
                        'from_all_store' => false,
                    ),

                    'settings' => array(
                        'category' => array(
                            'type' => 'picker',
                            'default_value' => '0',
                            'picker' => 'pickers/categories/picker.tpl',
                            'picker_params' => array(
                                'multiple' => false,
                                'use_keys' => YesNo::NO,
                                'status' => 'A',
                                'default_name' => __('root_level'),
                            ),
                            'request' => array (
                                'cid' => '%CATEGORY_ID%'
                            )
                        ),
                    ),
                ),
            ),
        ),
    ),

    'settings' => array(
        'play_video_block' => array(
            'type' => 'checkbox',
            'default_value' => YesNo::YES
        ),

        'video_limit' => array(
            'type' => 'input',
            'default_value' => '6'
        ),
    ),

    'templates' => array(
        'addons/sd_youtube/blocks/video_gallery.tpl' => array(),
    ),
    'wrappers' => 'blocks/wrappers',
    'cache' => false,
    'multilanguage' => true
);

if (Registry::get('category_page')) {
    $schema['video_gallery']['content']['items']['fillings']['videos_from_all_store']['settings'] = array(
        'all_subcategories' => array(
            'type' => 'checkbox',
            'default_value' => YesNo::NO
        ),
    );

    $schema['video_gallery']['content']['items']['fillings']['videos_from_category']['settings']['all_subcategories'] = array(
        'type' => 'checkbox',
        'default_value' => YesNo::NO
    );
}

return $schema;
