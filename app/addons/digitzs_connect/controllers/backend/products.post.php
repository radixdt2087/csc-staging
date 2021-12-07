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

defined('BOOTSTRAP') or die('Access denied');

use Tygh\Registry;

/** @var string $mode */

if ($mode == 'update' || $mode == 'add') {
    $product_id = $_REQUEST['product_id'];
    $product_data = fn_get_product_data($product_id,$auth);
    $company_id = '';
    if($mode == 'update'){
        $company_id = $product_data['company_id'];
    }else{
        $company_id = Registry::get('runtime.company_id');  
    }
    $vendor_commission = getVendorCommision($company_id); 
    if(!empty($_POST['product_data']['product_commission']) && $_POST['product_data']['product_commission_fee']) {
        $data = $_POST['product_data'];
        $company_id = $_POST['company_id'];
        $is_checked = $data['override_commission'] == 'yes' ? $data['override_commission'] : 'no';
        $_data['override_commission'] = $is_checked;
        db_query("UPDATE ?:products SET ?u WHERE product_id = ?i", $_data, $product_id);
    }
    Tygh::$app['view']->assign('company_id',$company_id);
    Tygh::$app['view']->assign('product_data',$product_data);
    Tygh::$app['view']->assign('vendor_commission',$vendor_commission);     
}
function getVendorCommision($company_id){
    $commission = db_get_row(
    'SELECT commission, fixed_commission'
    . ' FROM ?:vendor_plans AS p'
    . ' INNER JOIN ?:companies AS c ON c.plan_id = p.plan_id'
    . ' WHERE company_id = ?i', $company_id
    );
    return $commission;
}