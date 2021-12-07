<?php
/***************************************************************************
*                                                                          *
*   © Simtech Development Ltd.                                             *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
***************************************************************************/
use Tygh\Enum\VisibilityValues;

$schema['sd_youtube_single_video'] = array(
    'youtube_link' => array(
        'type' => 'input'
    ),
    'video_width' => array(
        'type' => 'input',
        'tooltip' => __('video_width_tooltip'),
    ),
    'visibility' => array (
        'type' => 'selectbox',
        'values' => array (
            VisibilityValues::UNAUTHORIZED => 'only_unauthorized_users',
            VisibilityValues::AUTHORIZED => 'only_authorized_users',
            VisibilityValues::ALL => 'all_users'
        ),
        'default_value' => 'P'
    ),
);

return $schema;
