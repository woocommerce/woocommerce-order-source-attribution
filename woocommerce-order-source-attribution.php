<?php
/**
 * Plugin Name: Grow Order Attribute Prototype
 * Plugin URI: https://woomultichannel.wordpress.com/2022/11/07/project-thread-order-attribution-source-prototype/
 * Description: Prototyping Order Attribution tracking.
 * Version: 0.1.0
 * Author: Grow
 * Author URI: https://woogrowp2.wordpress.com/
 * Text Domain: woocommerce-order-source-attribution
 * Requires at least: 5.8
 * Tested up to: 6.1
 * Requires PHP: 7.4
 *
 * WC requires at least: 7.0
 * WC tested up to: 7.1
 * Woo:
 *
 */

use Automattic\WooCommerce\OrderSourceAttribution\Autoloader;
use Automattic\WooCommerce\OrderSourceAttribution\Internal\PluginFactory;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

define( 'WC_GROW_ORDER_ATTRIBUTE_PROTOTYPE_VERSION', '0.1.0' ); // WRCS: DEFINED_VERSION.
define( 'WC_GROW_ORDER_ATTRIBUTE_PROTOTYPE_FILE', __FILE__ );

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
