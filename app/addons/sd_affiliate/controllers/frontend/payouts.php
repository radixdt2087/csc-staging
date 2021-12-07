<?php
 use Tygh\Registry; defined('BOOTSTRAP') or die('Access denied'); if ($mode == 'list') { fn_add_breadcrumb(__('affiliates_partnership'), 'affiliate_plans.list'); list($payouts, $search, $payout_search_data) = fn_get_customer_payouts( $_REQUEST, Registry::get('settings.Appearance.elements_per_page'), $auth ); Tygh::$app['view']->assign('payouts', $payouts); Tygh::$app['view']->assign('search', $search); Tygh::$app['view']->assign('payout_search', $payout_search_data); } elseif ($mode == 'update') { if (empty($_REQUEST['payout_id'])) { return [CONTROLLER_STATUS_NO_PAGE]; } else { $payout_data = db_get_row('SELECT * FROM ?:affiliate_payouts WHERE payout_id = ?i', $_REQUEST['payout_id']); if (empty($payout_data)) { return [CONTROLLER_STATUS_NO_PAGE]; } else { if (!empty($payout_data['partner_id'])) { $payout_data['partner'] = sd_YzMyYjYwOWYwODE2NWUyZGM5NjE2Zjk5($payout_data['partner_id']); } if (!empty($payout_data['partner']['plan_id'])) { $payout_data['plan'] = sd_Y2M5OWY4ZDllM2MyODUxM2Q1N2VjYjA3($payout_data['partner']['plan_id']); } $_REQUEST['user_type'] = Tygh::$app['session']['auth']['user_type']; list($payout_data['actions'], $search) = sd_MmMxMDg1NjBmYjhlY2NhZjA0M2ZmY2Q4( $_REQUEST, Registry::get('settings.Appearance.elements_per_page') ); $payout_data['date_range']['min'] = db_get_field( 'SELECT MIN(date)' . ' FROM ?:aff_partner_actions' . ' WHERE payout_id = ?i', $_REQUEST['payout_id'] ); $payout_data['date_range']['max'] = db_get_field( 'SELECT MAX(date)' . ' FROM ?:aff_partner_actions' . ' WHERE payout_id = ?i', $_REQUEST['payout_id'] ); fn_add_breadcrumb(__('affiliates_partnership'), 'affiliate_plans.list'); fn_add_breadcrumb(__('payouts'), 'payouts.list'); Tygh::$app['view']->assign('affiliate_plan', sd_OTljYTYwM2ExM2Q5MjM3MzAwNzA0NDYw($auth['user_id'])); Tygh::$app['view']->assign('payouts', [$payout_data['partner_id'] => $payout_data]); } Tygh::$app['view']->assign('search', $search); } } function fn_get_customer_payouts($params, $items_per_page = 0, $auth, $lang_code = CART_LANGUAGE) { $default_params = [ 'page' => 1, 'items_per_page' => $items_per_page ]; $params = array_merge($default_params, $params); $sortings = [ 'amount' => '?:affiliate_payouts.amount', 'date' => '?:affiliate_payouts.date', 'status' => '?:affiliate_payouts.status', ]; $sorting = db_sort($params, $sortings, 'date', 'desc'); if (!empty($params['payout_search'])) { $payout_search = $params['payout_search']; $payout_search_data = $payout_search; $payout_search_condition = '1'; if (!empty($params['period']) && $params['period'] != 'A') { list($time_from, $time_to) = fn_create_periods($params); $payout_search_data['period'] = $params['period']; $payout_search_data['time_from'] = $time_from; $payout_search_data['time_to'] = $time_to; $payout_search_condition .= db_quote( ' AND (?:affiliate_payouts.date >= ?i AND ?:affiliate_payouts.date <= ?i)', $time_from, $time_to ); } else { $payout_search_data['period'] = 'A'; if (!empty($params['time_from'])) { $payout_search_data['period'] = 'C'; $payout_search_data['time_from'] = strtotime($params['time_from']); $payout_search_condition .= db_quote( ' AND ?:affiliate_payouts.date >= ?i', strtotime($params['time_from']) ); } if (!empty($params['time_to'])) { $payout_search_data['period'] = 'C'; $payout_search_data['time_to'] = strtotime($params['time_to']); $payout_search_condition .= db_quote( ' AND ?:affiliate_payouts.date <= ?i', strtotime($params['time_to']) ); } } if (!empty($payout_search['status'])) { $payout_search_condition .= db_quote(' AND ?:affiliate_payouts.status = ?s ', $payout_search['status']); } $payout_search_data['amount']['from'] = floatval(@$payout_search['amount']['from']); if (!empty($payout_search_data['amount']['from'])) { $payout_search_condition .= db_quote( ' AND ?:affiliate_payouts.amount >= ?d ', fn_convert_price($payout_search_data['amount']['from']) ); } else { $payout_search_data['amount']['from'] = ''; } $payout_search_data['amount']['to'] = floatval(@$payout_search['amount']['to']); if (!empty($payout_search_data['amount']['to'])) { $payout_search_condition .= db_quote( ' AND ?:affiliate_payouts.amount <= ?d ', fn_convert_price($payout_search_data['amount']['to']) ); } else { $payout_search_data['amount']['to'] = ''; } } if (empty($payout_search_data)) { $payout_search_data = []; } if (empty($payout_search_condition)) { $payout_search_condition = ' 1 '; } $limit = ''; if (!empty($params['items_per_page'])) { $params['total_items'] = db_get_field( 'SELECT COUNT(*)' . ' FROM ?:affiliate_payouts' . ' LEFT JOIN ?:users ON ?:affiliate_payouts.partner_id = ?:users.user_id' . ' WHERE ?p AND user_id = ?i', $payout_search_condition, $auth['user_id'] ); $limit = db_paginate($params['page'], $params['items_per_page']); } $payouts = db_get_hash_array( 'SELECT ?:affiliate_payouts.*, ?:users.firstname, ?:users.lastname' . ' FROM ?:affiliate_payouts' . ' LEFT JOIN ?:users ON ?:affiliate_payouts.partner_id=?:users.user_id' . ' WHERE ?p AND ?:users.user_id = ?i ?p ?p', 'payout_id', $payout_search_condition, $auth['user_id'], $sorting, $limit ); return [$payouts, $params, $payout_search_data]; } 