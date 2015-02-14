<?php
//require_once __DIR__ . "/paymentlib.php";
//require_once __DIR__ . "/braintree_class.php";
//require_once __DIR__ . "/paypal_class.php";

// Include the composer Autoloader
// The location of your project's vendor autoloader.
$composerAutoload = __DIR__. '/autoload.php';

if (!file_exists($composerAutoload)) {
    //If the project is used as its own project, it would use rest-api-sdk-php composer autoloader.
    $composerAutoload = __DIR__ . '/vendor/autoload.php';

    if (!file_exists($composerAutoload)) {
        echo "The 'vendor' folder is missing. You must run 'composer update' to resolve application dependencies.\nPlease see the README for more information.\n";
        exit(1);
    }
}

/*
 * Paypal Credentials
 */
define('PAYPAL_ENVIRONMENT', 'sandbox'); // for sandbox mode
//define('PAYPAL_ENVIRONMENT', 'live'); // for live mode
define('PAYPAL_API_CLIENTID', 'AdrVuBA72swvrff941X3b49ymOanjTNgwYE6dQE0qyij5wm8o5wZtnxPWzbI');
define('PAYPAL_API_SECRET', 'EOOn-BDR3gp95ZA8tHiN-Am3A2XWcxw12SpmwCO6A1EX2FHXwyZgLbSJigu_');

/*
 * Brain Tree Credentaials
 */
define('BRAINTREE_ENVIRONMENT', 'sandbox'); // for sandbox mode
//define('BRAINTREE_ENVIRONMENT', 'production'); // for production mode
define('BRAINTREE_MERCHANTID', 'phg8vjpqtndv7vxt');
define('BRAINTREE_PUBLICKEY', 'fv8jmr75kkm7bcmd');
define('BRAINTREE_PRIVATEKEY', '548b5c53f1894d9090a1a05f63c1be53');

// Default Pament method for teh system
define('DEFAULT_PAYMENT_GATEWAY', 'braintree');

define('PP_CONFIG_PATH', __DIR__);

?>