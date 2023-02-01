<?php
declare( strict_types=1 );

namespace Automattic\WooCommerce\OrderSourceAttribution\Internal;

use Automattic\WooCommerce\OrderSourceAttribution\Logging\DebugLogger;

defined( 'ABSPATH' ) || exit;

/**
 * Class PluginFactory
 *
 * This is responsible for instantiating a Plugin object and returning the same object.
 *
 * @since x.x.x
 */
final class PluginFactory {

	/**
	 * Get the instance of the Plugin object.
	 *
	 * @return Plugin
	 */
	public static function instance(): Plugin {
		static $plugin = null;
		if ( null === $plugin ) {
			$logger = new DebugLogger( wc_get_logger() );
			$plugin = new Plugin( $logger );
		}

		return $plugin;
	}
}
