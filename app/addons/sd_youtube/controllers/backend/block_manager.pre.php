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

defined('BOOTSTRAP') or die('Access denied');

$request = $_REQUEST;

if ($mode == 'update_block') {
    if (isset($request['dynamic_object']['object_type']) && $request['dynamic_object']['object_type'] == 'categories' && isset($request['dynamic_object']['object_id']) && $request['dynamic_object']['object_id']) {
        Registry::set('category_page', true);
    }
}
