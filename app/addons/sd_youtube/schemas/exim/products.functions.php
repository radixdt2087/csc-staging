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
use Tygh\VideoUrlParser;

function fn_sd_youtube_link_export($product_id, $youtube_link)
{
    if (fn_allowed_for('ULTIMATE')) {
        $company_id = Registry::get('runtime.company_id');
        if (!empty($company_id) && !empty($product_id)) {
            $ult_youtube_link = db_get_field(
                'SELECT youtube_link'
                . ' FROM ?:ult_product_descriptions'
                . ' WHERE product_id = ?i'
                . ' AND company_id = ?i',
                $product_id, $company_id
            );
        }
    }

    return !empty($ult_youtube_link) ? $ult_youtube_link : $youtube_link;
}

function fn_sd_youtube_link_import($product_id, $youtube_link)
{
    if (!empty($product_id)) {

        if (!empty($youtube_link)) {
            $youtube_link = VideoUrlParser::getUrlId($youtube_link);
        }

        $company_id = Registry::get('runtime.company_id');

        db_query(
            'UPDATE ?:products'
            . ' SET youtube_link = ?s'
            . ' WHERE product_id = ?i'
            , $youtube_link, $product_id
        );

        if (fn_allowed_for('ULTIMATE')) {

            $prod_company_id = db_get_field(
                'SELECT company_id'
                . ' FROM ?:products'
                . ' WHERE product_id = ?i'
                , $product_id
            );

            if (empty($company_id)) {
                $company_id = $prod_company_id;
            }

            db_query(
                'UPDATE ?:ult_product_descriptions'
                . ' SET youtube_link = ?s'
                . ' WHERE product_id = ?i'
                . ' AND company_id = ?i'
                , $youtube_link, $product_id, $company_id
            );
        }
    }
}
