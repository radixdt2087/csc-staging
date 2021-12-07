<?php
/*****************************************************************************
*                                                        © 2013 Cart-Power   *
*           __   ______           __        ____                             *
*          / /  / ____/___ ______/ /_      / __ \____ _      _____  _____    *
*      __ / /  / /   / __ `/ ___/ __/_____/ /_/ / __ \ | /| / / _ \/ ___/    *
*     / // /  / /___/ /_/ / /  / /_/_____/ ____/ /_/ / |/ |/ /  __/ /        *
*    /_//_/   \____/\__,_/_/   \__/     /_/    \____/|__/|__/\___/_/         *
*                                                                            *
*                                                                            *
* -------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license *
* and  accept to the terms of the License Agreement can install and use this *
* program.                                                                   *
* -------------------------------------------------------------------------- *
* website: https://store.cart-power.com                                      *
* email:   sales@cart-power.com                                              *
******************************************************************************/

use Tygh\Registry;

if (function_exists('___cp')) {
    $cpv1 = ___cp('Y29udHJvbGxlcnM');
    $cpv2 = ___cp('cGVybWlzc2lvbnM');
    $company_id = Registry::get('runtime.company_id');
    if (!empty($company_id)) {
        $allow_payments = db_get_field('SELECT allow_create_payment FROM ?:companies WHERE company_id = ?i', $company_id);
        if ($allow_payments == 'Y') {
            $schema[$cpv1]['payments'][$cpv2] = true;
            $schema[$cpv1]['tools']['modes']['update_status']['param_permissions']['table']['payments'] = true;
        }
    }
}
return $schema;