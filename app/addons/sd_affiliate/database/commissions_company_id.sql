ALTER TABLE `cscart_aff_partner_actions` ADD `company_id` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `cscart_affiliate_payouts` ADD `company_id` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `cscart_aff_customer_payouts` ADD `company_id` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `cscart_aff_customer_actions` ADD `company_id` mediumint(8) unsigned NOT NULL DEFAULT '0';