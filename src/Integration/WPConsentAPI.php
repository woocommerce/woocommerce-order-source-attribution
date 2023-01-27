<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\OrderSourceAttribution\Integration;

use Automattic\WooCommerce\OrderSourceAttribution\HelperTraits\Utilities;

/**
 * Class WPConsentAPI
 *
 * @since x.x.x
 */
class WPConsentAPI {

	use Utilities;

	/**
	 * Register the consent API.
	 *
	 * @return void
	 * @since x.x.x
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

	}

	/**
	 * Check if WP Cookie Consent API is active
	 *
	 * @return bool
	 * @since x.x.x
	 */
	public function is_wp_consent_api_active() {
		return class_exists( WP_CONSENT_API::class );
	}

	/**
	 * Enqueue JS for integration with WP Consent Level API
	 *
	 * @return void
	 * @since   x.x.x
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

}
