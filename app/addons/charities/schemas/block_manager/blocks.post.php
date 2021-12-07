<?php

/*
 * Copyright 2016, 1st Source IT, LLC, EZ Merchant Solutions
 * All rights reserved.
 * Resale prohibited.
 */
if( !defined('BOOTSTRAP') ) die('Access denied');
$schema['charities_banner'] = array(
									 'templates' => 'addons/charities/blocks/charities_banner.tpl',
									 'wrappers' => 'blocks/wrappers',
									 'cache' => false
									 );
return $schema;
