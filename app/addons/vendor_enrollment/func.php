<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2021 Radixweb*
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

function fn_vendor_enrollment_change_company_status_before_mail($company_id, $status_to, $reason, $status_from, $skip_query, $notify, $company_data, $user_data, $result) {
    $password = db_get_field("SELECT `password` FROM ?:companies WHERE company_id = ?i", $company_id);
    $user_id = db_get_field("SELECT `user_id` FROM ?:users WHERE company_id = ?i AND user_type = 'V'", $company_id); 
    db_query('UPDATE ?:users SET `password`=?s,password_change_timestamp=?i  WHERE `user_id` = ?i',$password,time(),$user_id);
}

function fn_vendor_enrollment_sucess_user_login() {    
    $dauth = Tygh::$app['session']['auth']; 
    if(isset($dauth['user_type']) && $dauth['user_type'] == 'V' && AREA == 'A') {
        $company_id = $dauth['company_id'];
        $last_login = db_get_field("SELECT `last_login` FROM ?:users WHERE company_id = ?i AND user_type = 'V'", $company_id); 
        if($last_login==0) { fn_redirect('companies.update&company_id='.$company_id.'&selected_section=plan'); };
    }   
}