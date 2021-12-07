<?php
 use Tygh\Registry; use Tygh\Enum\AvailableAmazonMarkets; if (!defined('BOOTSTRAP')) { die('Access denied'); } $params = $_REQUEST; if ($_SERVER['REQUEST_METHOD'] == 'POST') { if ($mode == 'import') { if (!empty($params['amazon_order_ids'])) { $count_orders = $count_statuses = 0; $params['amazon_order_ids'] = is_array($params['amazon_order_ids']) ? $params['amazon_order_ids'] : array($params['amazon_order_ids']); foreach ($params['amazon_order_ids'] as $amazon_order_id) { list($order_imported, $status_updated) = fn_get_amazon_order_data($amazon_order_id, array(), array()); $count_orders = $order_imported ? ++$count_orders : $count_orders; $count_statuses = $status_updated ? ++$count_statuses : $count_statuses; } fn_set_notification('N', __('notice'), __('sd_amz_import_finished', array('[count_orders]' => $count_orders, '[count_statuses]' => $count_statuses))); list($orders, $params) = Tygh::$app['session']['amazon_order_list']; $return_url = 'amazon_orders.manage?marketplace=' . $params['marketplace'] . '&period=' . $params['period'] . '&time_from=' . $params['time_from'] . '&time_to=' . $params['time_to']; return array(CONTROLLER_STATUS_OK, $return_url); } } return array(CONTROLLER_STATUS_OK); } if ($mode == 'manage') { $orders = $search = array(); $addon_settings = Registry::get('addons.sd_amazon_products'); unset(Tygh::$app['session']['amazon_order_list']); if ($addon_settings['sync_orders'] != 'Y') { return array(CONTROLLER_STATUS_DENIED); } if (!empty($params['marketplace'])) { if (!empty($addon_settings['default_shipping'])) { $params['period'] = !empty($params['period']) ? $params['period'] : 'D'; list($params['time_from'], $params['time_to']) = fn_create_periods($params); if (in_array($params['period'], array('D', 'W', 'M', 'Y'))) { $params['time_to'] = ''; } elseif ($params['period'] == 'A') { $params['time_to'] = ''; $params['time_from'] = 1; } list($orders, $search) = fn_get_amazon_orders($params); } else { fn_set_notification('E', __('error'), __('sd_amz_default_shipping_not_found')); } } if (!empty($params['error'])) { fn_set_notification('E', __('error'), $params['error']); } Tygh::$app['view']->assign(array( 'marketplace_list' => AvailableAmazonMarkets::getWithDescriptions(), 'search' => $search, 'orders' => $orders )); Tygh::$app['session']['amazon_order_list'] = array( $orders, $search ); } elseif ($mode == 'import_cron') { $addon_settings = Registry::get('addons.sd_amazon_products'); if (!empty($params['key']) && $params['key'] == Registry::get('addons.sd_amazon_products.cron_key') && $addon_settings['sync_orders'] == 'Y') { $marketplace_list = AvailableAmazonMarkets::getAll(); $params['period'] = $addon_settings['sync_period']; list($params['time_from'], $params['time_to']) = fn_create_periods($params); $params['time_to'] = ''; $count_orders = $count_statuses = 0; foreach ($marketplace_list as $marketplace) { $amazon_config = fn_get_amazon_config($marketplace); if (!empty($amazon_config)) { $params['marketplace'] = $marketplace; list($orders, $params) = fn_get_amazon_orders($params); if (!empty($orders)) { foreach ($orders as $order) { list($order_imported, $status_updated) = fn_get_amazon_order_data($order['AmazonOrderId'], $orders, $params); $count_orders = $order_imported ? ++$count_orders : $count_orders; $count_statuses = $status_updated ? ++$count_statuses : $count_statuses; } if (!empty($params['from_order_manage'])) { fn_set_notification('N', __('notice'), __('sd_amz_import_finished', array('[count_orders]' => $count_orders, '[count_statuses]' => $count_statuses))); } else { fn_echo(__('sd_amz_import_finished', array('[count_orders]' => $count_orders, '[count_statuses]' => $count_statuses))); } } } } } if (!empty($params['from_order_manage'])) { return array(CONTROLLER_STATUS_OK, 'orders.manage'); } else { exit; } } 