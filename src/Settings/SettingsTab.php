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
				$settings_url = add_query_arg(
					[
						'page' => 'wc-settings',
						'tab'  => 'wc_order_source_attribution',
					],
					admin_url( 'admin.php' )
				);
				$action_links = [
					'settings' => '<a href="' . $settings_url . '">' . esc_html__( 'Settings', 'woocommerce-order-source-attribution' ) . '</a>',
				];

				return array_merge( $action_links, $links );
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
	 * Add our setting to the Experimental Features section.
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	private function add_experimental_settings( array $settings ) {
		$numeric_only_settings = array_filter(
			$settings,
			function( $key ) {
				return is_int( $key );
			},
			ARRAY_FILTER_USE_KEY
		);

		// Add our own settings in the featured section.
		$ids = array_column( $numeric_only_settings, 'id' );
		$feature_begin_index = array_search( 'experimental_features_options', $ids, true );
		if ( false === $feature_begin_index ) {
			return $settings;
		}

		$order_attribution_settings = [
			[
				'title'   => __( 'Order Attribution', 'woocommerce-order-source-attribution' ),
				'type'    => 'checkbox',
				'default' => 'yes',
				'desc'    => __( 'Enable WooCommerce Order Source Attribution.', 'woocommerce-order-source-attribution' ),
				'id'      => self::SETTINGS_ENABLE_ORDER_ATTRIBUTION_ID,
			],
		];

		$first_section = array_slice( $numeric_only_settings, 0, $feature_begin_index + 1 );
		$second_section = array_slice( $numeric_only_settings, $feature_begin_index + 1 );

		return array_merge(
			$first_section,
			$order_attribution_settings,
			$second_section
		);
	}
}
