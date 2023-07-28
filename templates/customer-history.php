<?php
declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/**
 * Variables used in this file.
 *
 * @var int $customer_id
 */

/** @var WC_Order[] $orders */
$orders = wc_get_orders(
	[
		'customer_id' => $customer_id,
		'status' => array_map(
			function( $value ) {
				return "wc-{$value}";
			},
			wc_get_is_paid_statuses()
		),
	]
);

$order_count   = count( $orders );
$total_spent   = array_reduce(
	$orders,
	function( $total, WC_Order $order ) {
		return $total + $order->get_total();
	},
	0
);
$average_spent = $order_count ? $total_spent / $order_count : 0;
?>

<div class="customer-history order-source-attribution-metabox">
	<h4>
		<?php
		esc_html_e( 'Total orders', 'woocommerce-order-source-attribution' );
		echo wc_help_tip(
			__( 'Total of completed orders by this customer, including the current one. Excludes cancelled or refunded orders.', 'woocommerce-order-source-attribution' )
		); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		?>
	</h4>

	<span class="order-source-attribution-total-orders">
		<?php echo esc_html( $order_count ); ?>
	</span>

	<h4><?php esc_html_e( 'Total spend', 'woocommerce-order-source-attribution' ); ?></h4>
	<span class="order-source-attribution-total-spend">
		<?php echo wc_price( $total_spent ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
	</span>

	<h4><?php esc_html_e( 'Average order value', 'woocommerce-order-source-attribution' ); ?></h4>
	<span class="order-source-attribution-average-order-value">
		<?php echo wc_price( $average_spent ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
	</span>
</div>
