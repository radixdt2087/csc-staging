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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    return true;
}

$params = $_REQUEST;

if ($mode == 'play') {

    $company_id = (empty($params['company_id'])) ? 0 : $params['company_id'];

    if (defined('AJAX_REQUEST') && $params['youtube_link']) {

        Tygh::$app['view']->assign(array(
            'id' => $company_id,
            'youtube_link' => $params['youtube_link']
        ));
        Tygh::$app['view']->display('addons/sd_youtube/views/youtube/play.tpl');
        exit();

    } else {
        return array(CONTROLLER_STATUS_REDIRECT, (isset($params['return_url'])) ? $params['return_url'] : '');
    }

}
