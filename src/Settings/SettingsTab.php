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
			'woocommerce_settings_features',
			function( $features ) {
				return $this->add_settings( $features );
			}
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
	 * Add our setting to the Features section.
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	private function add_settings( array $settings ) {
		$order_attribution_settings = [
			[
				'title'   => __( 'Order Attribution (Beta)', 'woocommerce-order-source-attribution' ),
				'type'    => 'checkbox',
				'default' => 'yes',
				'desc'    => __( 'Enable this feature to track and credit channels and campaigns that contribute to orders on your site.', 'woocommerce-order-source-attribution' ),
				'id'      => self::SETTINGS_ENABLE_ORDER_ATTRIBUTION_ID,
			],
		];

		return array_merge( $settings, $order_attribution_settings );
	}
}
