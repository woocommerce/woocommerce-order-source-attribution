<?php

namespace Automattic\WooCommerce\OrderSourceAttribution\Integration;

use WCCom_Cookie_Terms;

/**
 * Class WCCOMTracking
 */
class WCCOMTracking {

	/**
	 * Register the WCCOM integration.
	 *
	 * @return void
	 */
	public function register() {
		// Include integration to WCCOM if available.
		if ( ! class_exists( WCCom_Cookie_Terms::class ) ) {
			return;
		}

		add_filter(
			'wc_order_source_attribution_allow_tracking',
			function () {
				return $this->is_wccom_tracking_allowed();
			}
		);

		add_action(
			'wp_enqueue_scripts',
			function () {
				$this->enqueue_scripts();
			}
		);

	}

	/**
	 * Check if WCCOM tracking is allowed.
	 *
	 * @return bool
	 */
	private function is_wccom_tracking_allowed() {
		return WCCom_Cookie_Terms::instance()->can_track_user( 'analytics' );
	}

	/**
	 * Enqueue JS for integration with WCCOM Consent Management API
	 *
	 * @return void
	 */
	private function enqueue_scripts() {
		wp_register_script(
			'wccom-integration-js',
			plugins_url( 'assets/js/wccom-integration.js', WC_ORDER_ATTRIBUTE_SOURCE_FILE ),
			[ 'woocommerce-order-attribute-source-js' ],
			WC_ORDER_ATTRIBUTE_SOURCE_VERSION,
			true
		);
		wp_enqueue_script( 'wccom-integration-js' );
	}


}
