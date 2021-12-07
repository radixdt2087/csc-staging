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

use Tygh\BlockManager\Block;
use Tygh\BlockManager\Grid;
use Tygh\BlockManager\RenderManager;
use Tygh\Registry;

define('AREA', 'C');

// In case of this file is symlinked to the CS-Cart installation directory,
// there will be no 'init.php' file at the __DIR__ directory.
$root_dir = __DIR__;
if (isset($_SERVER['SCRIPT_FILENAME']) && dirname($_SERVER['SCRIPT_FILENAME']) != $root_dir) {
    $root_dir = dirname($_SERVER['SCRIPT_FILENAME']);
}


if (isset($_REQUEST['block_id'],
    $_REQUEST['snapping_id'],
    $_REQUEST['lang_code'],
    $_REQUEST['requested_uri']
)) {

    $block_id = (int) $_REQUEST['block_id'];
    $snapping_id = (int) $_REQUEST['snapping_id'];
    $lang_code = $_REQUEST['lang_code'];

    $requested_uri = rawurldecode($_REQUEST['requested_uri']);

    $_SERVER['QUERY_STRING'] = parse_url($requested_uri, PHP_URL_QUERY);
    $_SERVER['REQUEST_URI'] = $requested_uri;
    $_SERVER['SCRIPT_NAME'] = 'index.php';

    unset($_REQUEST['block_id'], $_REQUEST['snapping_id'], $_REQUEST['lang_code'], $_REQUEST['requested_uri']);
    unset($_GET['block_id'], $_GET['snapping_id'], $_GET['lang_code'], $_GET['requested_uri']);

    if ($_SERVER['QUERY_STRING']) {
        parse_str($_SERVER['QUERY_STRING'], $vars);
        $_REQUEST = $_GET = $vars;
    }

    require_once $root_dir . '/init.php';
    
    $block = Block::instance()->getById($block_id, $snapping_id, array(), $lang_code);
    $parent_grid = Grid::getById($block['grid_id'], $lang_code);

    $content = RenderManager::renderBlock($block, $parent_grid, 'C', array(
        'esi_enabled' => false,
        'use_cache' => false,
        'parse_js' => false,
    ));

    header('Content-Type: text/html');
    header('X-ESI-Response: true');

    echo $content;

    exit;
}