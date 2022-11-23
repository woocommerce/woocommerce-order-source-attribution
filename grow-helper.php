<?php
/**
 * Plugin Name: Grow Order Attribute Prototype
 * Plugin URI: https://woogrowp2.wordpress.com/
 * Description: Test
 * Version: 0.1.0
 * Author: Grow
 * Author URI: https://woogrowp2.wordpress.com/
 * Text Domain: grow-oap
 * WC requires at least: 2.6.0
 * WC tested up to: 5.5.0.
 */

use Automattic\WooCommerce\Grow\OrderAttributePrototype\Autoloader;
use Automattic\WooCommerce\Grow\OrderAttributePrototype\Internal\PluginFactory;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

define( 'WC_GROW_ORDER_ATTRIBUTE_PROTOTYPE_VERSION', '0.1.0' ); // WRCS: DEFINED_VERSION.
define( 'WC_GROW_ORDER_ATTRIBUTE_PROTOTYPE_FILE', __FILE__ );

// Load and initialize the autoloader.
require_once __DIR__ . '/src/Autoloader.php';
if ( ! Autoloader::init() ) {
	return;
}

// Declare incompatibility with HPOS for now.
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( FeaturesUtil::class ) ) {
			FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, false );
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
