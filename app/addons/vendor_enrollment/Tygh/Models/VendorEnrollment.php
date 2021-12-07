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

namespace Tygh\Models;

use Tygh\Enum\ObjectStatuses;
use Tygh\Models\Components\AModel;
use Tygh\Models\Components\Relation;
use Tygh\Registry;

/**
 * Class VendorEnrollment
 *
 * @property string $id
 * @property string $products_limit
 * @property string $revenue_limit
 * @property string $is_default
 * @property null|string $companies_count
 *
 * @package Tygh\Models
 */
class VendorEnrollment extends AModel
{
    public function getTableName()
    {
        return '?:plan_payment_details';
    }

    public function getPrimaryField()
    {
        return 'id';
    }
    
    public function getFields($params)
    {
        $fields = array(
            '?:plan_payment_details.*',
        );

        /**
         * Change fields list for main SQL query
         *
         * @param object $instance Current model instance
         * @param array  $fields   Fields list
         * @param array  $params   Params array
         */
        fn_set_hook('vendor_enrollment_get_fields', $this, $fields, $params);

        return $fields;
    }

    public function getSearchFields()
    {
        $search_fields = array(
            'number' => array(
                'is_default',
            ),
            'string' => array(
                'status',
            ),
            'text' => array(
                'plan',
            ),
            'range' => array(
                'price',
            ),
            'in' => array(
                'periodicity'
            ),
        );

        /**
         * Setting search fields schema
         *
         * @param object $instance      Current model instance
         * @param array  $search_fields Fields list
         */
        fn_set_hook('vendor_enrollment_get_search_fields', $this, $search_fields);

        return $search_fields;
    }

    public function getSortFields()
    {
        $sort_fields = array(
            'id' => 'id',            
        );

        /**
         * Setting sorting fields schema
         *
         * @param object $instance    Current model instance
         * @param array  $sort_fields Sorting fields schema
         */
        fn_set_hook('vendor_enrollment_get_sort_fields', $this, $sort_fields);

        return $sort_fields;
    }

    public function getRelations()
    {
        $relations = array(
            'companies' => array(Relation::HAS_MANY, 'Tygh\Models\Company', 'company_id'),
            'companiesCount' => array(Relation::HAS_MANY, 'Tygh\Models\Company', 'company_id', null, array(
                'get_count' => true,
            )),
        );

        /**
         * Setting relations schema
         *
         * @param object $instance  Current model instance
         * @param array  $relations Relations schema
         */
        //fn_set_hook('vendor_plan_get_relations', $this, $relations);

       // return $relations;
    }

    public function getExtraCondition($params)
    {
        $condition = array();
        if (isset($params['company_id'])) {          
            $condition['company_id'] = db_quote("?:plan_payment_details.company_id = ?i", $params['company_id']);
        }
        
       $condition['status'] = db_quote("?:plan_payment_details.status = ?s", 'Success');
        return $condition;
    }

    public function prepareQuery(&$params, &$fields, &$sorting, &$joins, &$condition)
    {
        /**
         * Change SQL parameters for vendor plans select
         *
         * @param object $instance   Current model instance
         * @param array  $params     Params array
         * @param array  $fields     Fields list
         * @param array  $sortings   Sortings list
         * @param array  $joins      Joins list
         * @param array  $condition  Conditions list
         */

        
        if(empty($_REQUEST['sort_by'])) {
            $sorting = 'id desc';
        }
        fn_set_hook('vendor_enrollment_get_list', $this, $params, $fields, $sorting, $joins, $condition);
    }
}
