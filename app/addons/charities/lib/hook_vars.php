<?php
/*****************************
* Copyright 2010, 2011, 2012, 2013 1st Source IT, LLC
* All rights reserved.
* Permission granted for use as
* long as this copyright notice, associated text and
* links remain in tact.
* Licensed for a single domain and a single instance of EZ-cart.
* Additional licenses can be purchased for additonal sites.
*
* http://www.ez-ms.com
* http://www.ez-om.com
* http://www.1sit.com*
*
* End copyright notification
*/

if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

$hooks = array();
	
/* Example hook option
 */
 /*
// Don't mess with this unless you really know what you're doing!
$hooks = array('customer' => array( 'views/checkout/summary.tpl' => array(
								   				'name'=>'custom_summary',	// hook name I.e. name="section.name"
												'section' => 'checkout',	// section for the hook name I.e. name="section:name"
												'pattern' => ';<p.*text_customer_notes.*</textarea>/uS',	// pattern for the source file to be "hooked"
												'type' => 'override',		// one of override, pre, post
												'match_idx' => 0			// match index of the pattern to match
																		)
			   					)
					);
$hooks = array('php' => array( Registry::get('config.dir.addons')."fn.common.php" => array(
								   				'name'=>'send_mail',	// hook name I.e. name="send_mail"
												'args' => '$mail',		// arguments for the set_hook() function
												'pattern' => ';foreach \(__to.*};U',	// pattern for the source file to be "hooked"
												'type' => 'pre',		// one of override, pre, post
												'match_idx' => 0			// match index of the pattern to match
								)
						)
					);
	$hooks = array('customer' => array( "views/checkout/summary.tpl" => array(
								   				'name'=>'custom_summary',	// hook name I.e. name="send_mail"
												'section' => 'checkout',	// arguments for the set_hook() function
												'pattern' => ';<p.*text_customer_notes.*</textarea>;sU',	// pattern for the source file to be "hooked"
												'type' => 'override',		// one of override, pre, post
												'match_idx' => 0			// match index of the pattern to match
								)
						)
					);
*/

?>