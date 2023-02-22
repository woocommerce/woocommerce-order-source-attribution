<?php
namespace Automattic\WooCommerce\OrderSourceAttribution\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Class SettingsTab
 *
 * @since x.x.x
 */
class SettingsTab {
	const SETTINGS_ENABLE_ORDER_ATTRIBUTION_ID = 'wc_order_source_attribution_enable_order_source_data';
	const SETTINGS_DEBUG_MODE_ID               = 'wc_order_source_attribution_debug_mode';

	/**
	 * Bootstraps the class and hooks required actions & filters.
	 */
	public function register() {
		add_filter(
			'woocommerce_settings_tabs_array',
			function( $settings_tabs ) {
				return $this->add_settings_tab( $settings_tabs );
			},
			50
		);

		add_action(
			'woocommerce_settings_wc_order_source_attribution',
			function() {
				$this->settings_tab();
			}
		);

		add_action(
			'woocommerce_update_options_wc_order_source_attribution',
			function() {
				$this->update_settings();
			},
			90
		);
	}


	/**
	 * Add a new settings tab to the WooCommerce settings tabs array.
	 *
	 * @param array $settings_tabs Array of WooCommerce setting tabs.
	 * @return array $settings_tabs Array of WooCommerce setting tabs.
	 */
	private function add_settings_tab( $settings_tabs ) {
		$settings_tabs['wc_order_source_attribution'] = __( 'Order Attribution', 'woocommerce-order-source-attribution' );
		return $settings_tabs;
	}


	/**
	 * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	 */
	private function settings_tab() {
		woocommerce_admin_fields( $this->get_settings() );
	}


	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 */
	private function update_settings() {
		woocommerce_update_options( $this->get_settings() );
	}


	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 *
	 * @return array Array of settings for @see woocommerce_admin_fields() function.
	 */
	private function get_settings() {
		$is_enabled = get_option( self::SETTINGS_ENABLE_ORDER_ATTRIBUTION_ID, 'yes' );
		$debug_mode = get_option( self::SETTINGS_DEBUG_MODE_ID, 'no' );

		return array(
			'section_title' => array(
				'name' => __( 'WooCommerce Order Source Attribution Settings', 'woocommerce-order-source-attribution' ),
				'type' => 'title',
				'desc' => '',
				'id'   => 'wc_order_source_attribution_section_title',
			),
			'enabled'       => array(
				'title'   => __( 'Order Attribution', 'woocommerce-order-source-attribution' ),
				'type'    => 'checkbox',
				'default' => 'yes',
				'desc'    => __( 'Enable WooCommerce Order Source Attribution.', 'woocommerce-order-source-attribution' ),
				'id'      => self::SETTINGS_ENABLE_ORDER_ATTRIBUTION_ID,
				'value'   => $is_enabled,
			),
			'debug_mode'    => array(
				'title' => __( 'Debug Mode', 'woocommerce-order-source-attribution' ),
				'type'  => 'checkbox',
				'desc'  => __( 'Log plugin events.', 'woocommerce-order-source-attribution' ),
				'id'    => self::SETTINGS_DEBUG_MODE_ID,
				'value' => $debug_mode,
			),
			'section_end'   => array(
				'type' => 'sectionend',
				'id'   => 'wc_order_source_attribution_section_end',
			),
		);
	}
}
