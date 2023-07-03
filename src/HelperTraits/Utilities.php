<?php
declare( strict_types=1 );

namespace Automattic\WooCommerce\OrderSourceAttribution\HelperTraits;

use Automattic\WooCommerce\OrderSourceAttribution\Settings\SettingsTab;

defined( 'ABSPATH' ) || exit;

/**
 * Trait Utilities.
 *
 * @since 0.1.0
 */
trait Utilities {

	/**
	 * Get plugin base name.
	 *
	 * @return string
	 * @since 0.1.0
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
		$option = 'yes' === get_option( SettingsTab::SETTINGS_DEBUG_MODE_ID, 'no' );

		return (bool) apply_filters( 'wc_order_source_attribution_debug_mode_enabled', $option );
	}
}
