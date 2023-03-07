<?php
/**
 * Plugin Name: WooCommerce Order Source Attribution
 * Plugin URI: https://github.com/woocommerce/woocommerce-order-source-attribution#readme
 * Description: WooCommerce Order Source Attribution helps merchants understand which marketing activities, channels or campaigns are leading to orders in their stores.
 * Version: 0.1.0
 * Author: WooCommerce
 * Author URI: https://woocommerce.com/
 * Text Domain: woocommerce-order-source-attribution
 * Requires at least: 5.9
 * Tested up to: 6.1
 * Requires PHP: 7.4
 *
 * WC requires at least: 7.0
 * WC tested up to: 7.1
 * Woo:
 */

use Automattic\WooCommerce\OrderSourceAttribution\Autoloader;
use Automattic\WooCommerce\OrderSourceAttribution\Internal\PluginFactory;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

define( 'WC_ORDER_ATTRIBUTE_SOURCE_VERSION', '0.1.0' ); // WRCS: DEFINED_VERSION.
define( 'WC_ORDER_ATTRIBUTE_SOURCE_FILE', __FILE__ );

// Load and initialize the autoloader.
require_once __DIR__ . '/src/Autoloader.php';
if ( ! Autoloader::init() ) {
	return;
}

// Declare compatibility with HPOS.
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( FeaturesUtil::class ) ) {
			FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__ );
		}
	}
);

// Hook much of our plugin after WooCommerce is loaded.
add_action(
	'woocommerce_loaded',
	function() {
		PluginFactory::instance()->register();
	}
);
