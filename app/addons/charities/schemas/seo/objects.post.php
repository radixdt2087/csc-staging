<?php

/*
 * Copyright 2015, 1st Source IT, LLC, EZ Merchant Solutions
 */
if( !defined('BOOTSTRAP') ) die('Access denied');

$schema['h'] = array(
				'table' => '?:ez_charities_affiliate_descriptions',
				'description' => 'short_name',
				'dispatch' => 'charities.profile',
				'item' => 'affiliate_id',
				'condition' => '',
				'name' => 'charity',
				'html_options' => array('file'),
				'option'=> 'seo_other_type',
				'skip_lang_condition' => true
				);
return $schema;
?>