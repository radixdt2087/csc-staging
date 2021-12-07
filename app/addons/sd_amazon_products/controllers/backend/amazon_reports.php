<?php
 use Tygh\Registry; if (!defined('BOOTSTRAP')) { die('Access denied'); } if ($_SERVER['REQUEST_METHOD'] == 'POST') { if ($mode == 'delete') { if (!empty($_REQUEST['report_id'])) { fn_remove_report($_REQUEST['report_id']); } } elseif ($mode == 'm_delete') { if (!empty($_REQUEST['report_ids'])) { fn_remove_report($_REQUEST['report_ids']); } } return array(CONTROLLER_STATUS_OK, 'amazon_reports.manage'); } if ($mode == 'manage') { $params = $_REQUEST; list($reports, $search) = fn_get_reports($params, DESCR_SL, Registry::get('settings.Appearance.admin_elements_per_page')); $page = $search['page']; $valid_page = db_get_valid_page($page, $search['items_per_page'], $search['total_items']); if ($page > $valid_page) { $_REQUEST['page'] = $valid_page; return array(CONTROLLER_STATUS_REDIRECT, Registry::get('config.current_url')); } Tygh::$app['view']->assign('search', $search); Tygh::$app['view']->assign('reports', $reports); } elseif ($mode == 'view') { $params = $_REQUEST; $url = 'amazon_reports.manage'; if (defined('AJAX_REQUEST') && $params['sync_type'] == AMAZON_SYNC_TYPE_EXPORT) { $report = fn_get_processing_report($params); if ($report === false || (isset($report['Error']['Code']) && $report['Error']['Code'] == 'FeedProcessingResultNotReady') || !$report['Message']['ProcessingReport']['ProcessingSummary']['MessagesSuccessful']) { fn_set_notification('W', __('notice'), __('sd_amz_report_is_not_avail', array( '[feed]' => $params['feed_id'], ))); Tygh::$app['ajax']->assign('non_ajax_notifications', true); Tygh::$app['ajax']->assign('force_redirection', fn_url($url)); exit; } else { Tygh::$app['view']->assign('report', $report['Message']); Tygh::$app['view']->assign('feed_id', $params['feed_id']); } } elseif (defined('AJAX_REQUEST') && $params['sync_type'] == AMAZON_SYNC_TYPE_IMPORT) { list($report, $search) = fn_get_reports($params, DESCR_SL, Registry::get('settings.Appearance.admin_elements_per_page')); Tygh::$app['view']->assign('report', $report[0]); Tygh::$app['view']->assign('feed_id', $params['feed_id']); } else { return array(CONTROLLER_STATUS_REDIRECT, $url); } } 