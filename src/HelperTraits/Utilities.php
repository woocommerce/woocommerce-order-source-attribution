<?php
declare( strict_types=1 );

namespace Automattic\WooCommerce\OrderSourceAttribution\HelperTraits;

use Automattic\WooCommerce\OrderSourceAttribution\Settings\SettingsTab;

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

	/**
	 * Check if debug mode is enabled.
	 *
	 * @return bool
	 */
	protected function is_debug_mode_enabled() {
		$debug_mode = get_option( SettingsTab::SETTINGS_DEBUG_MODE_ID, 'no' );
		return 'yes' === $debug_mode;
	}

}

