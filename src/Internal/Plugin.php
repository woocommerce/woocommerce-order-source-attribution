<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\OrderSourceAttribution\Internal;

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
use Automattic\WooCommerce\OrderSourceAttribution\Integration\WPConsentAPI;
use Automattic\WooCommerce\OrderSourceAttribution\Logging\DebugLogger;
use Automattic\WooCommerce\Utilities\OrderUtil;
use Exception;
use WC_Customer;
use WC_Meta_Data;
use WC_Order;
use WP_User;

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

	/** @var DebugLogger */
	private $logger;

	/**
	 * Plugin constructor.
	 *
	 * @param DebugLogger $logger The logger.
	 */
	public function __construct( DebugLogger $logger ) {

		$this->fields       = (array) apply_filters( 'wc_order_source_attribution_tracking_fields', $this->default_fields );
		$this->field_prefix = (string) apply_filters( 'wc_order_source_attribution_tracking_field_prefix', 'wc_order_source_attribution_' );
		$this->logger       = $logger;

	}

	/**
	 * Register our hooks.
	 *
	 * @return void
	 */
	public function register() {

		// Register WPConsentAPI
		( new WPConsentAPI() )->register();

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
			'woocommerce_checkout_order_created',
			function ( $order ) {
				$this->set_order_source_data( $order );
			}
		);
		add_action(
			'user_register',
			function ( $customer_id ) {
				try {
					$customer = new WC_Customer( $customer_id );
					$this->set_customer_source_data( $customer );
				} catch ( Exception $e ) {
					$this->logger->log_exception( $e, __METHOD__ );
				}
			}
		);

		// Add our source data to the order display.
		add_action(
			'add_meta_boxes',
			function() {
				$this->add_meta_box();
			}
		);

		// Add output to the User display page.
		$customer_meta_boxes = function( WP_User $user ) {
			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				return;
			}

			try {
				$customer = new WC_Customer( $user->ID );
				$this->display_customer_source_data( $customer );
			} catch ( Exception $e ) {
				$this->logger->log_exception( $e, __METHOD__ );
			}
		};

		add_action( 'show_user_profile', $customer_meta_boxes );
		add_action( 'edit_user_profile', $customer_meta_boxes );

	}

	/**
	 * Scripts & styles for custom source tracking and cart tracking.
	 */
	private function enqueue_scripts_and_styles() {
		wp_enqueue_script(
			'sourcebuster-js',
			plugins_url( 'assets/js/sourcebuster.min.js', WC_ORDER_ATTRIBUTE_SOURCE_FILE ),
			[ 'jquery' ],
			WC_ORDER_ATTRIBUTE_SOURCE_VERSION,
			true
		);

		wp_enqueue_script(
			'woocommerce-order-attribute-source-js',
			plugins_url( 'assets/js/woocommerce-order-attribute-source.js', WC_ORDER_ATTRIBUTE_SOURCE_FILE ),
			[ 'jquery' ],
			WC_ORDER_ATTRIBUTE_SOURCE_VERSION,
			true
		);

		/**
		 * Pass parameters to Grow JS.
		 */
		$params = [
			'lifetime'      => (int) apply_filters( 'wc_order_source_attribution_cookie_lifetime_months', 6 ),
			'session'       => (int) apply_filters( 'wc_order_source_attribution_session_length_minutes', 30 ),
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'prefix'        => $this->field_prefix,
			'allowTracking' => wc_bool_to_string( WPConsentAPI::is_wp_consent_api_active() ? wp_has_consent( 'marketing' ) : true ),
		];

		wp_localize_script( 'woocommerce-order-attribute-source-js', 'wc_order_attribute_source_params', $params );
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
	 * @param WC_Customer $customer
	 *
	 * @return void
	 */
	private function set_customer_source_data( WC_Customer $customer ) {
		foreach ( $this->get_source_values() as $key => $value ) {
			$customer->add_meta_data( $key, $value );
		}

		$customer->save_meta_data();
	}

	/**
	 * @param WC_Order $order
	 *
	 * @return void
	 */
	private function set_order_source_data( WC_Order $order ) {
		foreach ( $this->get_source_values() as $key => $value ) {
			$order->add_meta_data( $key, $value );
		}

		$order->save_meta_data();
	}

	/**
	 * Map posted values to meta values.
	 *
	 * @return array
	 */
	private function get_source_values(): array {
		$values = [];

		// Look through each field in POST data.
		foreach ( $this->fields as $field ) {
			// phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$value = sanitize_text_field( wp_unslash( $_POST[ $this->prefix_field( $field ) ] ?? '' ) );
			if ( '(none)' === $value ) {
				continue;
			}

			switch ( $field ) {
				case 'type':
					$meta_key = '_wc_order_source_attribution_source_type';
					break;

				case 'url':
					$meta_key = '_wc_order_source_attribution_referrer';
					break;

				default:
					$meta_key = "_wc_order_source_attribution_{$field}";
					break;
			}

			$values[ $meta_key ] = $value;
		}

		return $values;
	}

	/**
	 * Adds prefix to field name.
	 *
	 * @param string $field Field name.
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
	private function display_order_source_data( WC_Order $order ) {
		$meta = $this->filter_meta_data( $order->get_meta_data() );

		// If we don't have any meta to show, return.
		if ( empty( $meta ) ) {
			return;
		}

		include dirname( WC_ORDER_ATTRIBUTE_SOURCE_FILE ) . '/templates/source-data-fields.php';
	}

	/**
	 * Display the source data template for the customer.
	 *
	 * @param WC_Customer $customer
	 *
	 * @return void
	 */
	private function display_customer_source_data( WC_Customer $customer ) {
		$meta = $this->filter_meta_data( $customer->get_meta_data() );

		// If we don't have any meta to show, return.
		if ( empty( $meta ) ) {
			return;
		}

		include dirname( WC_ORDER_ATTRIBUTE_SOURCE_FILE ) . '/templates/source-data-fields.php';
	}

	/**
	 * Add our own meta box to the order display screen.
	 *
	 * @return void
	 */
	private function add_meta_box() {
		add_meta_box(
			'woocommerce-order-source-data',
			__( 'Order Source Data', 'woocommerce-order-source-attribution' ),
			function ( $post ) {
				global $theorder;

				OrderUtil::init_theorder_object( $post );
				$order = $theorder;

				$this->display_order_source_data( $order );
			},
			$this->is_hpos_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order',
			'normal'
		);
	}

	/**
	 * Filter the meta data to only the keys that we care about.
	 *
	 * @param WC_Meta_Data[] $meta
	 *
	 * @return array
	 */
	private function filter_meta_data( array $meta ): array {
		return array_filter(
			$meta,
			function ( WC_Meta_Data $meta ) {
				return str_starts_with( $meta->key, '_wc_order_source_attribution_' );
			}
		);
	}

	/**
	 * Check to see if HPOS is enabled.
	 *
	 * @return bool
	 */
	private function is_hpos_enabled(): bool {
		try {
			/** @var CustomOrdersTableController $cot_controller */
			$cot_controller = wc_get_container()->get( CustomOrdersTableController::class );

			return $cot_controller->custom_orders_table_usage_is_enabled();
		} catch ( Exception $e ) {
			$this->logger->log_exception( $e, __METHOD__ );
			return false;
		}
	}
}
