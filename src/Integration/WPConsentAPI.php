<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\OrderSourceAttribution\Integration;

use Automattic\WooCommerce\OrderSourceAttribution\Utilities\Helper;
use InvalidArgumentException;

class WPConsentAPI {


    /**
     * Register the consent API.
     *
     * @return void
     * @since x.x.x
     */
    public function register() {
        //Include integration to WP Consent Level API if available
        if ( $this->is_wp_consent_api_active() ) {
            add_filter("wp_consent_api_registered_" . Helper::get_plugin_base_name(), function(){return true;});
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_consent_api_scripts' ) );
        }
    }

    /**
     * Check if WP Cookie Consent API is active
     *
     * @return bool
     * @since x.x.x
     */
    public function is_wp_consent_api_active() {
        return class_exists( 'WP_CONSENT_API' );
    }

    /**
     * Enqueue JS for integration with WP Consent Level API
     *
     * @return void
     * @throws InvalidArgumentException
     * @since   x.x.x
     */
    public function enqueue_consent_api_scripts() {
        wp_register_script(
            'wp-consent-api-integration-js',
            plugins_url( 'assets/js/wp-consent-api-integration.js', WC_ORDER_ATTRIBUTE_SOURCE_FILE ),
            null,
            [ 'jquery' ],
            WC_ORDER_ATTRIBUTE_SOURCE_VERSION,
            true
        );
        wp_enqueue_script( 'wp-consent-api-integration-js' );
    }

    /**
     * Instance of WPConsentAPI
     *
     * @return WPConsentAPI
     * @since x.x.x
     */
    public static function instance() {
        static $instance = null;
        if ( is_null( $instance ) ) {
            $instance = new self();
        }
        return $instance;
    }

}