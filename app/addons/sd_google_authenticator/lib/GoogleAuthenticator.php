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

// interfaces
require_once(dirname(__FILE__) . '/Providers/Rng/IRNGProvider.php');

require_once(dirname(__FILE__) . '/Providers/Qr/IQRCodeProvider.php');
require_once(dirname(__FILE__) . '/Providers/Qr/BaseHTTPQRCodeProvider.php');

// classes
require_once(dirname(__FILE__) . '/Providers/Rng/CSRNGProvider.php');
require_once(dirname(__FILE__) . '/Providers/Rng/HashRNGProvider.php');
require_once(dirname(__FILE__) . '/Providers/Rng/MCryptRNGProvider.php');
require_once(dirname(__FILE__) . '/Providers/Rng/OpenSSLRNGProvider.php');
require_once(dirname(__FILE__) . '/Providers/Rng/RNGException.php');

require_once(dirname(__FILE__) . '/Providers/Qr/GoogleQRCodeProvider.php');
require_once(dirname(__FILE__) . '/Providers/Qr/QRException.php');
require_once(dirname(__FILE__) . '/Providers/Qr/QRicketProvider.php');
require_once(dirname(__FILE__) . '/Providers/Qr/QRServerProvider.php');

// Main classes
require_once(dirname(__FILE__) . '/TwoFactorAuth.php');
require_once(dirname(__FILE__) . '/TwoFactorAuthException.php');

