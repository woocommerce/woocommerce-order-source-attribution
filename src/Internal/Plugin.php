<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Grow\OrderAttributePrototype\Internal;

use WC_Order;

defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin
 *
 * @since x.x.x
 */
final class Plugin {

	/** @var string[] */
	private $default_fields = [
		// main
		'type',
		'url',

		// utm
		'utm_campaign',
		'utm_source',
		'utm_medium',
		'utm_content',
		'utm_id',
		'utm_term',

		// additional
		'session_entry',
		'session_start_time',
		'session_pages',
		'session_count',
		'user_agent',
	];

	/** @var array */
	private $fields = [];

	/** @var string */
	private $field_prefix = '';

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		$this->fields       = (array) apply_filters( 'grow_oat_tracking_fields', $this->default_fields );
		$this->field_prefix = (string) apply_filters( 'grow_oat_tracking_field_prefix', 'grow_oap_' );
	}

	/**
	 * Register our hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action(
			'wp_enqueue_scripts',
			function () {
				$this->enqueue_scripts_and_styles();
			}
		);

		// Include our hidden fields on order notes and registration form.
		$source_form_fields = function () {
			$this->source_form_fields();
		};

		add_action( 'woocommerce_after_order_notes', $source_form_fields );
		add_action( 'woocommerce_register_form', $source_form_fields );

		// Update data based on submitted fields.
		add_action(
			'woocommerce_checkout_update_order_meta',
			function ( $order_id ) {
				$this->set_source_data( $order_id, 'order' );
			}
		);
		add_action(
			'user_register',
			function ( $customer_id ) {
				$this->set_source_data( $customer_id, 'customer' );
			}
		);

		// Display the source data.
		add_action(
			'woocommerce_admin_order_data_after_order_details',
			function ( $order ) {
				$this->display_source_data( $order );
			}
		);
	}

	/**
	 * Scripts & styles for custom source tracking and cart tracking.
	 */
	private function enqueue_scripts_and_styles() {
		wp_enqueue_script(
			'sourcebuster-js',
			plugins_url( 'assets/js/sourcebuster.min.js', WC_GROW_ORDER_ATTRIBUTE_PROTOTYPE_FILE ),
			[ 'jquery' ],
			WC_GROW_ORDER_ATTRIBUTE_PROTOTYPE_VERSION,
			true
		);

		wp_enqueue_script(
			'grow-js',
			plugins_url( 'assets/js/grow.js', WC_GROW_ORDER_ATTRIBUTE_PROTOTYPE_FILE ),
			[ 'jquery' ],
			WC_GROW_ORDER_ATTRIBUTE_PROTOTYPE_VERSION,
			true
		);

		/**
		 * Pass parameters to Grow JS.
		 */
		$params = [
			'lifetime' => (int) apply_filters( 'grow_oat_cookie_lifetime_months', 6 ),
			'session'  => (int) apply_filters( 'grow_oat_session_length_minutes', 30 ),
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
			'prefix'   => $this->field_prefix,
		];

		wp_localize_script( 'grow-js', 'grow_params', $params );
	}

	/**
	 * Add grow hidden input fields for checkout & customer register froms.
	 */
	private function source_form_fields() {
		foreach ( $this->fields as $field ) {
			printf( '<input type="hidden" name="%s" value="" />', esc_attr( $this->prefix_field( $field ) ) );
		}
	}

	/**
	 * Set source data.
	 */
	private function set_source_data( $id, $resource ) {
		$values = [];

		// Look through each field in POST data.
		foreach ( $this->fields as $field ) {
			$values[ $field ] = sanitize_text_field( $_POST[ $this->prefix_field( $field ) ] ?? '' );
		}

		// update function based on order or customer
		$update_function = $resource == 'order' ? 'update_post_meta' : 'update_user_meta';

		// Handle storing each field.
		foreach ( $values as $key => $value ) {
			if ( '(none)' === $value ) {
				continue;
			}

			switch ( $key ) {
				case 'type':
					call_user_func_array( $update_function, [ $id, '_grow_source_type', $value ] );
					break;

				case 'url':
					call_user_func_array( $update_function, [ $id, '_grow_referrer', $value ] );
					break;

				default:
					call_user_func_array( $update_function, [ $id, "_grow_{$key}", $value ] );
					break;
			}
		}
	}

	/**
	 * @param $field
	 *
	 * @return string
	 */
	private function prefix_field( $field ): string {
		return "{$this->field_prefix}{$field}";
	}

	/**
	 * Display the source data template for the order.
	 *
	 * @param WC_Order $order
	 *
	 * @return void
	 */
	private function display_source_data( $order ) {
		include dirname( WC_GROW_ORDER_ATTRIBUTE_PROTOTYPE_FILE ) . '/templates/source-data-fields.php';
	}
}
