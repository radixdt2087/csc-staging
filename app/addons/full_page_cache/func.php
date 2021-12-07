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

use Tygh\Addons\FullPageCache\Addon;
use Tygh\Providers\FullPageCacheProvider;
use Tygh\Registry;

Tygh::$app['class_loader']->add('', __DIR__);
Tygh::$app->register(new FullPageCacheProvider());

/**
 * Checks session is active
 *
 * @return bool
 */
function fn_full_page_cache_is_session_active()
{
    static $active;

    if ($active !== null) {
        return $active;
    }

    /** @var \Tygh\Web\Session $session */
    $session = Tygh::$app['session'];

    return $active = $session->isStarted();
}

/**
 * Checks current dispatch is cachable
 *
 * @return bool
 */
function fn_full_page_cache_is_current_dispatch_cacheable()
{
    static $cachable;

    if ($cachable !== null) {
        return $cachable;
    }

    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];
    $auth = Tygh::$app['session']['auth'];

    return $cachable = (
        AREA === 'C'
        && $_SERVER['REQUEST_METHOD'] === 'GET'
        && empty($auth['user_id'])
        && !$addon->isEsiRequest()
        && $addon->isDispatchCacheable(
            Registry::get('runtime.controller'),
            Registry::get('runtime.mode'),
            Registry::get('runtime.action')
        )
    );
}

/**
 * Sets cookie that prevent use caching for once time.
 */
function fn_full_page_cache_disable_cache_once_by_cookie()
{
    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];
    $addon->setCookie('disable_cache', 'O', time() + COOKIE_ALIVE_TIME);
}

/**
 * Sets cookie that prevent use caching for all time.
 */
function fn_full_page_cache_disable_cache_by_cookie()
{
    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];
    $addon->setCookie('disable_cache', 'Y', time() + COOKIE_ALIVE_TIME);
}

/**
 * Removes cookie that prevent use caching for all time.
 */
function fn_full_page_cache_enable_cache_by_cookie()
{
    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];
    $addon->removeCookie('disable_cache');
}

/**
 * Hook is used to install the unmanaged addon together with main addon.
 */
function fn_full_page_cache_install()
{
    fn_install_addon('full_page_cache_unmanaged', false, false, true);
}

/**
 * Hook is used to install the unmanaged addon together with main addon.
 */
function fn_full_page_cache_uninstall()
{
    fn_uninstall_addon('full_page_cache_unmanaged', false, true);
}

/**
 * The "render_block_pre" hook handler.
 *
 * Actions performed:
 *  - This hook handler determines whether ESI-rendering should be enabled for the block being currently rendered.
 *
 * @see \Tygh\BlockManager\RenderManager::renderBlockContent
 */
function fn_full_page_cache_render_block_pre($block, $block_schema, &$params, &$block_content)
{
    if (AREA === 'A' || defined('API')) {
        $params['esi_enabled'] = false;
    } elseif (!isset($params['esi_enabled'])) {
        $params['esi_enabled'] = $block_schema['session_dependent']
            && fn_full_page_cache_is_session_active()
            && fn_full_page_cache_is_current_dispatch_cacheable();
    }

    if ($params['esi_enabled']) {
        $params['use_cache'] = false;
    }
}

/**
 * The "render_block_post" hook handler.
 *
 * Actions performed:
 *  - If ESI is enabled, this hook handle wrap the block content to esi XML tags.
 *
 * @see \Tygh\BlockManager\RenderManager::renderBlockContent
 */
function fn_full_page_cache_render_block_post(
    $block,
    $block_schema,
    &$block_content,
    $load_block_from_cache,
    $display_this_block,
    $params
) {
    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];

    if ($params['esi_enabled'] && $display_this_block) {
        $block_content = $addon->renderESIForBlock(
            $block, $block_content, CART_LANGUAGE,
            Registry::get('config.origin_http_location'),
            $_SERVER['REQUEST_URI'],
            (defined('DEVELOPMENT') && DEVELOPMENT)
        );
    }
}

/**
 * The "dispatch_before_send_response" hook handler.
 *
 * Actions performed:
 *  - If dispatch is cacheable, this hook handler sets HTTP headers for caching by cache server.
 *
 * @see \Tygh\Registry::save
 */
function fn_full_page_cache_dispatch_before_send_response($status, $area, $controller, $mode, $action)
{
    if ($status !== CONTROLLER_STATUS_OK
        || $area !== 'C'
        || !fn_full_page_cache_is_current_dispatch_cacheable()
        || Registry::get('runtime.full_page_cache.notification_exists')
    ) {
        return;
    }

    /** @var \Tygh\Addons\FullPageCache\Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];

    if ($addon->isCookieSend()) {
        return;
    }

    $addon->registerPageCacheTags([Addon::GLOBAL_TAG_CACHE]);
    $addon->registerPageCacheTags($addon->getGlobalCacheTags());

    foreach (Registry::getCachedKeys() as $cached_key) {
        if (isset($cached_key['condition']) && is_array($cached_key['condition'])) {
            $addon->registerPageCacheTags($cached_key['condition']);
        }
    }

    $addon->registerPageCahceTTL($controller, $mode, $action);

    if (fn_full_page_cache_is_session_active()) {
        $addon->setIsAllowEsi(true);
    }

    foreach ($addon->getPageHeaders() as $header) {
        header($header);
    }
}

/**
 * The "registry_save_pre" hook handler.
 *
 * Actions performed:
 *  - Invalidates page cache records.
 *
 * @see \Tygh\Registry::save
 */
function fn_full_page_cache_registry_save_pre($changed_tables)
{
    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];
    $addon->invalidateByTags(array_keys($changed_tables));
}

/**
 * The "get_route" hook handler.
 *
 * IMPORTANT! This handler must run before the handler of the SEO add-on,
 * because it might initialize `sl` params.
 *
 * Actions performed:
 *  - If the cookie contains the `sl` parameter and url is not allowed, this hook handler sets `sl` to request.
 *
 * @see fn_get_route
 */
function fn_full_page_cache_get_route(&$req, $result, $area, $is_allowed_url)
{
    if ($area !== 'C' || isset($req['sl']) || $is_allowed_url) {
        return;
    }

    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];

    if ($addon->hasCookie('sl')) {
        $req['sl'] = $addon->getCookie('sl');
    }
}

/**
 * The "get_route_runtime" hook handler.
 *
 * Actions performed:
 *  - If the cookie contains the `sl` parameter, this hook handler sets `sl` to request.
 *
 * @see fn_get_route
 */
function fn_full_page_cache_get_route_runtime(&$req, $area, $result)
{
    if ($area !== 'C' || isset($req['sl'])) {
        return;
    }

    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];

    if ($addon->hasCookie('sl')) {
        $req['sl'] = $addon->getCookie('sl');
    }
}

/**
 * The "init_currency_pre" hook handler.
 *
 * Actions performed:
 *  - If the cookie contains the `currency` parameter, this hook handler sets `currency` to request.
 *
 * @see fn_init_currency
 */
function fn_full_page_cache_init_currency_pre(&$params, $area)
{
    if ($area !== 'C' || isset($params['currency'])) {
        return;
    }

    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];

    if ($addon->hasCookie('currency')) {
        $params['currency'] = $addon->getCookie('currency');
    }
}

/**
 * The "init_user" hook handler.
 *
 * Actions performed:
 *  - If selected language is different than default language, this hook handler sets `sl` to cookie.
 *  - If selected currency is different than default currency, this hook handler sets `currency` to cookie.
 *
 * @see fn_init_user
 */
function fn_full_page_cache_user_init($auth)
{
    if (AREA !== 'C') {
        return;
    }

    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];
    $default_language = Registry::get('settings.Appearance.frontend_default_language');

    if (!empty($auth['user_id'])) {
        fn_full_page_cache_disable_cache_by_cookie();
    } elseif ($addon->getCookie('disable_cache') === 'O' && !defined('AJAX_REQUEST')) {
        fn_full_page_cache_enable_cache_by_cookie();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !defined('AJAX_REQUEST')) {
        fn_full_page_cache_disable_cache_once_by_cookie();
    }

    if ($addon->hasCookie('sl') && $addon->getCookie('sl') === CART_LANGUAGE) {
        unset($_REQUEST['sl']);
    }

    if (CART_LANGUAGE !== $default_language
        && CART_LANGUAGE !== $addon->getCookie('sl')
    ) {
        $addon->setCookie('sl', CART_LANGUAGE);
    } elseif (CART_LANGUAGE === $default_language && $addon->hasCookie('sl')) {
        $addon->removeCookie('sl');
    }

    if (CART_SECONDARY_CURRENCY !== CART_PRIMARY_CURRENCY
        && CART_SECONDARY_CURRENCY !== $addon->getCookie('currency')
    ) {
        $addon->setCookie('currency', CART_SECONDARY_CURRENCY);
    } elseif (CART_SECONDARY_CURRENCY === CART_PRIMARY_CURRENCY && $addon->hasCookie('currency')) {
        $addon->removeCookie('currency');
    }
}

/**
 * The "clear_cache_post" hook handler.
 *
 * Actions performed:
 *  - Invalidates all cache records
 *
 * @see fn_clear_cache
 */
function fn_full_page_cache_clear_cache_post($type, $extra)
{
    if (!in_array($type, ['registry', 'all', 'assets'])) {
        return;
    }

    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];

    $addon->invalidateByTags([Addon::GLOBAL_TAG_CACHE]);
}

/**
 * The "db_query_executed" hook handler.
 *
 * Actions performed:
 *  - Retrieves table names from SELECT query for usage as cach tags
 *
 * @see \Tygh\Database\Connection::query
 */
function fn_full_page_cache_db_query_executed($query, $result)
{
    if (AREA !== 'C'
        || !is_object($result)
        || !Registry::get('runtime.full_page_cache.inited')
        || stripos($query, 'SELECT') === false
        || !fn_full_page_cache_is_current_dispatch_cacheable()
    ) {
        return;
    }

    static $table_prefix;

    if ($table_prefix === null) {
        $table_prefix = Registry::get('config.table_prefix');
    }

    preg_match_all('/(?:FROM|JOIN)\s+(?<tables>[\-_\d\w]+)/i', $query, $matches);

    if (empty($matches['tables'])) {
        return;
    }

    $matches['tables'] = array_map(function ($table) use ($table_prefix) {
        return str_replace($table_prefix, '', $table);
    }, $matches['tables']);

    /** @var Addon $addon */
    $addon = Tygh::$app['addons.full_page_cache'];
    $addon->registerPageCacheTags($matches['tables']);
}

/**
 * The "sucess_user_login" hook handler.
 *
 * Actions performed:
 *  - Prevents usage full page cache for authorized user by set cookie.
 *
 * @see fn_login_user
 */
function fn_full_page_cache_sucess_user_login()
{
    if (AREA == 'C') {
        fn_full_page_cache_disable_cache_by_cookie();
    }
}

/**
 * The "user_logout_after" hook handler.
 *
 * Actions performed:
 *  - Enables usage full page cache after user is logout by remove cookie
 *
 * @see fn_user_logout
 */
function fn_full_page_cache_user_logout_after()
{
    if (AREA == 'C') {
        fn_full_page_cache_enable_cache_by_cookie();
    }
}

/**
 * The "update_customization_mode" hook handler.
 *
 * Actions performed:
 *  - Prevents usage full page cache for customization mode
 *
 * @see fn_update_customization_mode
 */
function fn_full_page_cache_update_customization_mode($modes, $enabled_modes)
{
    if (empty($enabled_modes)) {
        fn_full_page_cache_enable_cache_by_cookie();
    } else {
        fn_full_page_cache_disable_cache_by_cookie();
    }
}

/**
 * The "dispatch_before_display" hook handler.
 *
 * Actions performed:
 *  - If notifications exists then marks the current request for disable caching.
 *
 * @see fn_dispatch
 */
function fn_full_page_cache_dispatch_before_display()
{
    if (AREA !== 'C') {
        return;
    }

    Registry::set('runtime.full_page_cache.notification_exists', !empty(Tygh::$app['session']['notifications']));
}