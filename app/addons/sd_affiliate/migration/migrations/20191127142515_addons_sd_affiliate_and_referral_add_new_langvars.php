<?php
 use Phinx\Migration\AbstractMigration; class AddonsSdAffiliateAndReferralAddNewLangvars extends AbstractMigration { public function up() { $options = $this->adapter->getOptions(); $this->prefix = $options['prefix']; $langvars = [ 'sd_affiliate.set_as_default' => [ 'en' => 'Set as default', 'ru' => 'Установить по умолчанию' ], ]; $this->addOrUpdateLangvars($langvars); } public function down() { } protected function addOrUpdateLangvars($langvars) { foreach ($langvars as $langvar_name => $langvar_values) { foreach ($langvar_values as $lang_code => $langvar_value) { $data = [ 'lang_code' => $lang_code, 'name' => $langvar_name, 'value' => $langvar_value ]; $value = $this->fetchRow( "SELECT value FROM {$this->prefix}language_values" . " WHERE name = '{$data['name']}' AND lang_code = '{$data['lang_code']}'" ); if (empty($value)) { list($fields, $values) = $this->prepareForInsert($data); $this->execute("INSERT INTO {$this->prefix}language_values ({$fields}) VALUES ({$values})"); } else { $this->execute("UPDATE {$this->prefix}language_values" . " SET value = '{$data['value']}'" . " WHERE name = '{$data['name']}' AND lang_code = '{$data['lang_code']}'"); } } } } protected function prepareForInsert($data) { $data = array_map('addslashes', $data); $fields = implode(', ', array_keys($data)); $values = sprintf("'%s'", implode("', '", $data)); return [$fields, $values]; } } 