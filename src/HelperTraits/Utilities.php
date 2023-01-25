<?php
declare( strict_types=1 );

namespace Automattic\WooCommerce\OrderSourceAttribution\HelperTraits;

defined( 'ABSPATH' ) || exit;

/**
 * Trait Utilities.
 *
 * @since x.x.x
 */
trait Utilities {

	/**
	 * Get plugin base name.
	 *
	 * @return string
	 * @since x.x.x
	 */
	protected function get_plugin_base_name() {
		return plugin_basename( WC_ORDER_ATTRIBUTE_SOURCE_FILE );
	}

}

