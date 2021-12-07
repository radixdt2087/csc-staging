<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

use Tygh\Registry;
use Tygh\Mailer;
use Tygh\BlockManager\Block;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_vendor_store_banner_update_company(&$company_data, &$company_id, &$lang_code, &$action)
{
	fn_attach_image_pairs('vendor_store_banner_main', 'vendor_store_banner', $company_id, $lang_code);
}

function fn_vendor_store_banner_get_company_data_post(&$company_id, &$lang_code = DESCR_SL, &$extra = array(), &$company_data)
{
	$company_data['vendor_store_banner'] 	= fn_get_image_pairs($company_id, 'vendor_store_banner', 'M', true, true);
}
