<?php
namespace Automattic\WooCommerce\OrderSourceAttribution\Settings;

use Automattic\WooCommerce\OrderSourceAttribution\HelperTraits\Utilities;

defined( 'ABSPATH' ) || exit;

/**
 * Class SettingsTab
 *
 * @since 0.1.0
 */
class SettingsTab {

	use Utilities;

	const SETTINGS_ENABLE_ORDER_ATTRIBUTION_ID = 'wc_order_source_attribution_enable_order_source_data';
	const SETTINGS_DEBUG_MODE_ID               = 'wc_order_source_attribution_debug_mode';

	/**
	 * Bootstraps the class and hooks required actions & filters.
	 */
	public function register() {
		add_filter(
			"plugin_action_links_{$this->get_plugin_base_name()}",
			function ( $links ) {
				return $this->add_plugin_links( $links );
			}
		);

		add_filter(
			'woocommerce_get_settings_advanced',
			function( $settings, $current_section ) {
				if ( 'features' !== $current_section ) {
					return $settings;
				}

				return $this->add_experimental_settings( $settings );
			},
			100,
			2
		);
	}

	/**
	 * Filter the plugin action links.
	 *
	 * @param array $links Array of links.
	 *
	 * @return array
	 */
	private function add_plugin_links( array $links ) {
		$settings_url = add_query_arg(
			[
				'page'    => 'wc-settings',
				'tab'     => 'advanced',
				'section' => 'features',
			],
			admin_url( 'admin.php' )
		);
		$action_links = [
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				esc_url( $settings_url ),
				esc_html__( 'Settings', 'woocommerce-order-source-attribution' )
			),
		];

		return array_merge( $action_links, $links );
	}

	/**
	 * Add our setting to the end of the Experimental Features section.
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	private function add_experimental_settings( array $settings ) {
		/*
		 * The array of settings has numerically-indexed items as the settings, and key-indexed
		 * items that were used to generate the settings. This strips out the key-indexed
		 * items for our logic.
		 */
		$numeric_only_settings = array_filter(
			$settings,
			function( $key ) {
				return is_int( $key );
			},
			ARRAY_FILTER_USE_KEY
		);

		// Look for the beginning and end of the experimental_features_options section.
		$indices = array_keys( array_column( $numeric_only_settings, 'id' ), 'experimental_features_options', true );
		if ( 2 !== count( $indices ) ) {
			return $settings;
		}

		// Add our own settings to the end of the featured section.
		$order_attribution_settings = [
			[
				'title'   => __( 'Order Attribution (Beta)', 'woocommerce-order-source-attribution' ),
				'type'    => 'checkbox',
				'default' => 'yes',
				'desc'    => __( 'Enable this feature to track and credit channels and campaigns that contribute to orders on your site.', 'woocommerce-order-source-attribution' ),
				'id'      => self::SETTINGS_ENABLE_ORDER_ATTRIBUTION_ID,
			],
		];

		return array_merge(
			array_slice( $numeric_only_settings, 0, $indices[1] ),
			$order_attribution_settings,
			array_slice( $numeric_only_settings, $indices[1] )
		);
	}
}
