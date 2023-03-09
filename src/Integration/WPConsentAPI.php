<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\OrderSourceAttribution\Integration;

use Automattic\WooCommerce\OrderSourceAttribution\HelperTraits\Utilities;
use WP_CONSENT_API;

/**
 * Class WPConsentAPI
 *
 * @since 0.1.0
 */
class WPConsentAPI {

	use Utilities;

	/**
	 * Register the consent API.
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public function register() {
		// Include integration to WP Consent Level API if available
		if ( ! $this->is_wp_consent_api_active() ) {
			return;
		}

		$plugin = $this->get_plugin_base_name();
		add_filter( "wp_consent_api_registered_{$plugin}", '__return_true' );
		add_action(
			'wp_enqueue_scripts',
			function () {
				$this->enqueue_consent_api_scripts();
			}
		);

		/**
		 * Modify the "allowTracking" flag consent if the user has consented to marketing.
		 *
		 * Wp-consent-api will initialize the modules on "plugins_loaded" with priority 9,
		 * So this code needs to be run after that.
		 */
		add_action(
			'plugins_loaded',
			function () {
				$this->add_wc_order_source_attribution_allow_tracking_filter();
			},
			10
		);

	}

	/**
	 * Check if WP Cookie Consent API is active
	 *
	 * @return bool
	 * @since 0.1.0
	 */
	protected function is_wp_consent_api_active() {
		return class_exists( WP_CONSENT_API::class );
	}

	/**
	 * Enqueue JS for integration with WP Consent Level API
	 *
	 * @return void
	 * @since   0.1.0
	 */
	private function enqueue_consent_api_scripts() {
		wp_register_script(
			'wp-consent-api-integration-js',
			plugins_url( 'assets/js/wp-consent-api-integration.js', WC_ORDER_ATTRIBUTE_SOURCE_FILE ),
			[ 'jquery', 'wp-consent-api' ],
			WC_ORDER_ATTRIBUTE_SOURCE_VERSION,
			true
		);
		wp_enqueue_script( 'wp-consent-api-integration-js' );
	}

	/**
	 * Add wc_order_source_attribution_allow_tracking filter.
	 *
	 * @return void
	 */
	private function add_wc_order_source_attribution_allow_tracking_filter() {
		add_filter(
			'wc_order_source_attribution_allow_tracking',
			function () {
				return function_exists( 'wp_has_consent' ) && wp_has_consent( 'marketing' );
			}
		);
	}

}
