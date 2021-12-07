<?php
use Tygh\Tygh;
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }
/**
 * @var string $mode
 * @var string $action
 */
if ($mode == 'view') { 
  $companyData = db_get_array("SELECT * FROM ?:companies WHERE status  = ?s ORDER BY  company ASC", 'A');
  Tygh::$app['view']->assign('companies', $companyData);
}
?>