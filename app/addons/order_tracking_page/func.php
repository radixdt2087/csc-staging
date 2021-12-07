<?php
/**
 * CS-Cart Order Status Tracker - order_tracking_page
 * 
 * PHP version 7.0
 * 
 * @category  Add-on
 * @package   CS_Cart
 * @author    WebKul Software Private Limited <support@webkul.com>
 * @copyright 2010 WebKul Software Private Limited
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version   GIT: 1.0
 * @link      Technical Support: Forum - http://webkul.com/ticket
 */

if (!defined('AREA')) {
    die('Access denied');
}

use Tygh\Registry;

/**
 * Function to send the user guide and support link notification on add-on install.
 *
 * @return void
 */
function Fn_Order_Tracking_Page_install()
{
    Tygh::$app['view']->assign('mode', 'notification');

    fn_set_notification(
        'S', __('well_done'), __(
            'wk_webkul_user_guide_content', array(
                '[support_link]'    => 'https://webkul.uvdesk.com/en/customer/create-ticket/',
                '[user_guide]'      => 'https://webkul.com/blog/cs-cart-order-status-tracker/',
                '[addon_name]'      => 'Order Status Tracker'
            )
        )
    );
}

/**
 * Function to get the language translation for the given translation variable.
 *
 * @param string $label_data Translation string to translate.
 * @param string $lang_code  language code-2.
 * 
 * @return string
 */
function Fn_Order_Tracking_Page_Default_Lang_data(&$label_data, $lang_code)
{
    $id = 0;
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
    }
    if (isset($label_data['id'])) {
        $id = $label_data['id'];
    }
    
    $backend_deafult_lang   = Registry::get('settings.Appearance.backend_default_language');
    $is_id_title_exist      = db_get_field('SELECT id FROM ?:wk_order_labels_description WHERE id=?i AND lang_code=?s', $id, $lang_code);
    
    if (empty($is_id_title_exist)) {
        $default_lang_data = db_get_row('SELECT title, description FROM ?:wk_order_labels_description WHERE id=?i AND lang_code=?s', $id, $backend_deafult_lang);
        
        if (!empty($default_lang_data)) {
            $label_data['title']        = $default_lang_data['title'];
            $label_data['description']  = $default_lang_data['description'];
        }
    }
}