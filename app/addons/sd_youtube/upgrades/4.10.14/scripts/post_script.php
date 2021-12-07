<?php
/***************************************************************************
*                                                                          *
*   В© Simtech Development Ltd.                                             *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
***************************************************************************/

use Tygh\Registry;
use Tygh\Addons\SchemesManager;

$addon_id = 'sd_youtube';
SchemesManager::clearInternalCache($addon_id);

$addon_scheme = SchemesManager::getScheme($addon_id);

if (!empty($addon_scheme)) {
    if ($addon_scheme instanceof \Tygh\Addons\XmlScheme3) {
        if ($marketplace_id = $addon_scheme->getMarketplaceProductID()) {
            db_query(
                'UPDATE ?:addons SET marketplace_id = ?i WHERE addon = ?s',
                $marketplace_id,
                $addon_id
            );
        }

        if ($license_key = Registry::get('addons.' . $addon_id . '.lkey')) {
            db_query(
                'UPDATE ?:addons SET marketplace_license_key = ?s WHERE addon = ?s',
                $license_key,
                $addon_id
            );
        }

        db_query(
            'UPDATE ?:addons SET version = ?s WHERE addon = ?s',
            $addon_scheme->getVersion(),
            $addon_id
        );

        $section_id = db_get_field(
            'SELECT section_id FROM ?:settings_sections WHERE name = ?s AND type = ?s',
            $addon_id,
            'ADDON'
        );

        if (!empty($section_id)) {
            $object_id = db_get_field(
                'SELECT object_id FROM ?:settings_objects WHERE section_id = ?i AND name = ?s',
                $section_id,
                'lkey'
            );

            db_query(
                'DELETE FROM ?:settings_objects WHERE section_id = ?i AND name = ?s',
                $section_id,
                'lkey'
            );

            db_query(
                'DELETE FROM ?:settings_descriptions WHERE object_id = ?i',
                $object_id
            );
        }
    }
}