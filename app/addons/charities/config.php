<?php

/*****************************
* Copyright 2015 1st Source IT, LLC
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

// Config params for this addon
$x = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
$config_addon_name = $x[count($x)-1];
$addon_min_ver = '4';			// Force version 4 updates only
$addon_cur_ver = '4.11.13';		// This version
$addon_depends = 'ez_common';
$addon_has_changelog = false;
/* 4.3.1 - 12/7/15 -	Initial build
4.3.2 - 12/13/15 	-	Several bug fixes.  Added hooks for commission
					calculation.  Added sortable headers in tracking
					view.  Modified seo_name to have 'charity-' prefix
					rather than '-charity' suffix.
4.3.3 - 12/18/15 	-	Bugfix - process_order when no prior tracking exists
4.3.4 - TBD			-	Bugfixes:
					- typo for notification of disabled charity on login
					- charities list page images now link to charity
					- column sorting on charities/manage page in admin
4.3.5 - 10/28/16 	- Fixed several typos and DB schema definition 
						for tracking commission
4.3.6 - 10/31/16 	- Fix commission_from setting to prevent NULL
					- Modify change_order status to ignore 'place_order'
4.7.7 - 5/13/18		- Made 'name' required in detail.tpl
					- Add copy of descriptions to all languages when creating affiliate
4.7.8	- 6/6/18	- Fix defect related to copying descriptions to all languages on new chartity
4.9.9	- 12/12/18	- Added support for charity selection on checkout.
4.9.10	- 12/13/18	- Fixed css issue in admin for top of sidebar
					- Removed background color for checkout charity selection area
4.11.11	- 02/06/20	- Fixed typo in init.post.php preventing system commission rate from being seen
					- Changed empty test to include cart[total].
4.11.12 - 03/26/20	- Add floatval(commission) to get numeric portion only in controllers/frontend/init.pre.php
4.13/20 - 11/20/20	- Force load of sample imagery directly from the themes_repository
*/
$addon_depends = 'ez_common';
$addon_has_changelog = false;
?>