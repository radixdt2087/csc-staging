<?php
use Tygh\Models\VendorPlan;
use Tygh\Registry;
use Tygh\Tygh;
if (!defined('BOOTSTRAP')) { die('Access denied'); }
if ($mode == 'update' || $mode == 'add') {
    $id=0;
    if ($mode == 'update') {
        /** @var \Tygh\SmartyEngine\Core $view */
        $view = Tygh::$app['view'];
        $plan = VendorPlan::model()->find($_REQUEST['plan_id'], ['get_companies_count' => true]);
        if (!$plan) {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }
        $view->assign('plan', $plan);
        $id = $plan->plan_id;
    }

    $tabs = Registry::get('navigation.tabs');
    $atabs = array(
        'plan_addons_' . $id => array(
            'title' => __('addons'),
            'js' => true,
        ),
    );
    Registry::set('navigation.tabs', array_merge($tabs,$atabs));

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_data=array();
        $_data['add_package'] = implode(",",$_POST['plan_data']['add_package']);
        db_query("UPDATE ?:vendor_plans SET ?u WHERE plan_id = ?i", $_data, $id);
    }
    
    $allow_addon_data = array();
    $res = db_query("SELECT * FROM ?:plan_addons_details where `status`=?s and allow_package=?s",'A','Yes');
    $allow_addon_data = array();
    while($row = mysqli_fetch_assoc($res)) {
        $allow_addon_data[] = $row;
    }

    Tygh::$app['view']->assign('allow_addon_data', $allow_addon_data);
}