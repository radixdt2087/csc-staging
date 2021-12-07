<?php

/*
 * Copyright 2015, 1st Source IT, LLC, EZ Merchant Solutions
 */
if( !defined('BOOTSTRAP') ) die('Access denied');
$schema['top']['addons']['items']['charities'] = array(
	'attrs' => array('class' => 'is-addon'),
	'href' => 'charities.manage',
	'position' => 1100,
	);
return $schema;
?>