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

include_once(Registry::get('config.dir.addons') . 'sd_youtube/schemas/exim/products.functions.php');

$schema['export_fields']['YouTube Link'] = array (
    'db_field' => 'youtube_link',
    'process_get' => array('fn_sd_youtube_link_export', '#key', '#this'),
    'process_put' => array('fn_sd_youtube_link_import', '#key', '#this')
);

return $schema;
