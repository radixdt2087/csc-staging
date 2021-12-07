<?php
/***************************************************************************
*                                                                          *
*   © Simtech Development Ltd.                                             *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
***************************************************************************/

defined('BOOTSTRAP') or die('Access denied');

fn_register_hooks(
    'update_product_pre',
    'update_product_post',
    'gather_additional_products_data_post',
    'get_products',
    'get_product_data_post',
    'get_product_filter_fields',
    'get_products_before_select',
    'check_and_update_product_sharing',
    'update_block_pre',
    'render_block_pre'
);
