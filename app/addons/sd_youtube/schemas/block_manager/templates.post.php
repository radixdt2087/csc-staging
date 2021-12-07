<?php
/***************************************************************************
*                                                                          *
*   © Simtech Development Ltd.                                             *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
***************************************************************************/

use Tygh\Enum\YesNo;

$schema['blocks/products/products_small_items.tpl']['settings']['sd_size_force'] = array(
    'type' => 'checkbox',
    'default_value' => YesNo::YES
);
$schema['blocks/products/products.tpl']['settings']['sd_size_force'] = array(
    'type' => 'checkbox',
    'default_value' => YesNo::YES
);

return $schema;
