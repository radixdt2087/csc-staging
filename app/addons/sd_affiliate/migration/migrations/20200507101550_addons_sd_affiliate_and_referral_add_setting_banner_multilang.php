<?php
 use Phinx\Migration\AbstractMigration; class AddonsSdAffiliateAndReferralAddSettingBannerMultilang extends AbstractMigration { protected $addon_id = 'sd_affiliate'; public function up() { $options = $this->adapter->getOptions(); $this->prefix = $options['prefix']; $addon = $this->fetchRow( "SELECT addon FROM {$this->prefix}addons" . " WHERE addon = '{$this->addon_id}'" ); if (empty($addon)) { return; } $setting_params = [ 'section_tab' => 'general', 'langs' => [ 'value' => [ 'en' => 'Enable multi-language images/URLs', 'ru' => 'Разрешить разные изображения и URL для разных языков' ], 'tooltip' => [ 'en' => 'Allows uploading different images for different languages (for graphic banners)', 'ru' => 'Позволяет загружать разные изображения для разных языков (для графических баннеров)' ] ] ]; $setting_object = [ 'edition_type' => 'ROOT', 'name' => 'aff_banner_multilang', 'section_id' => null, 'section_tab_id' => null, 'type' => 'C', 'value' => 'N', 'position' => '510', 'is_global' => 'N', 'handler' => '', 'parent_id' => '0' ]; $this->createSetting($setting_params, $setting_object); } public function down() { } protected function createSetting($setting_params, $setting_object) { $addon_section = $this->fetchRow( "SELECT section_id FROM {$this->prefix}settings_sections" . " WHERE name = '{$this->addon_id}' AND type = 'ADDON'" ); if (!empty($addon_section['section_id'])) { $section_id = $addon_section['section_id']; $setting = $this->getSettingObject( $section_id, $setting_object['name'] ); if (empty($setting)) { $tab_name = $setting_params['section_tab']; $tab = $this->fetchRow( "SELECT * FROM {$this->prefix}settings_sections" . " WHERE parent_id = {$section_id} AND name = '{$tab_name}' AND type = 'TAB'" ); $setting_object['section_id'] = $section_id; $setting_object['section_tab_id'] = $tab['section_id']; $this->createSettingObject($setting_params, $setting_object); } } } protected function getSettingObject($section_id, $section_name) { return $this->fetchRow( "SELECT * FROM {$this->prefix}settings_objects" . " WHERE section_id = {$section_id} AND name = '{$section_name}'" ); } protected function createSettingObject($setting_params, $setting_object) { list($fields, $values) = $this->prepareForInsert($setting_object); $this->execute("INSERT INTO {$this->prefix}settings_objects ({$fields}) VALUES ({$values})"); $setting_object = $this->getSettingObject( $setting_object['section_id'], $setting_object['name'] ); if (!empty($setting_params['langs'])) { $languages = $this->fetchAll( "SELECT lang_code FROM {$this->prefix}languages" ); foreach ($languages as $lang) { $lang_code = $lang['lang_code']; if (!empty($setting_params['langs']['value'][$lang_code])) { $name = $setting_params['langs']['value'][$lang_code]; } else { $name = reset($setting_params['langs']['value']); } $data = [ 'object_id' => $setting_object['object_id'], 'object_type' => 'O', 'lang_code' => $lang_code, 'value' => $name, ]; list($fields, $values) = $this->prepareForInsert($data); $this->execute("INSERT INTO {$this->prefix}settings_descriptions ({$fields}) VALUES ({$values})"); } } } protected function prepareForInsert($data) { $data = array_map('addslashes', $data); $fields = implode(', ', array_keys($data)); $values = sprintf("'%s'", implode("', '", $data)); return [$fields, $values]; } } 