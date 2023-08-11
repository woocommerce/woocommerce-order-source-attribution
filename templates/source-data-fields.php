<?php
declare( strict_types=1 );

use Automattic\WooCommerce\OrderSourceAttribution\Internal\AttributionFields;

defined( 'ABSPATH' ) || exit;

/**
 * Variables used in this file.
 *
 * @var array $meta
 * @var AttributionFields $this
 */
?>

<div class="source_data form-field form-field-wide order-source-attribution-metabox">

	<?php if ( array_key_exists( 'origin', $meta ) ) : ?>
		<h4><?php esc_html_e( 'Origin', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-origin">
			<?php echo esc_html( $meta['origin'] ); ?>
		</span>
	<?php endif; ?>

	<div class="woocommerce-order-source-attribution-details">

		<a href="" class="woocommerce-order-source-attribution-details-toggle" aria-expanded="false">
			<?php esc_html_e( 'Details', 'woocommerce-order-source-attribution' ); ?>
			<span class="toggle-indicator" aria-hidden="true"></span>
		</a>

		<div class="clear"></div>

		<div class="woocommerce-order-source-attribution-details-container closed">
			<?php if ( array_key_exists( 'type', $meta ) ) : ?>
				<h4><?php esc_html_e( 'Source type', 'woocommerce-order-source-attribution' ); ?></h4>
				<span class="order-source-attribution-source_type">
					<?php echo esc_html( $meta['type'] ); ?>
				</span>
			<?php endif; ?>

			<?php if ( array_key_exists( 'utm_campaign', $meta ) ) : ?>
				<h4><?php esc_html_e( 'UTM campaign', 'woocommerce-order-source-attribution' ); ?></h4>
				<span class="order-source-attribution-utm-campaign">
					<?php echo esc_html( $meta['utm_campaign'] ); ?>
				</span>
			<?php endif; ?>

			<?php if ( array_key_exists( 'utm_source', $meta ) ) : ?>
				<h4><?php esc_html_e( 'UTM source', 'woocommerce-order-source-attribution' ); ?></h4>
				<span class="order-source-attribution-utm-source">
					<?php echo esc_html( $meta['utm_source'] ); ?>
				</span>
			<?php endif; ?>

			<?php if ( array_key_exists( 'utm_medium', $meta ) ) : ?>
				<h4><?php esc_html_e( 'UTM medium', 'woocommerce-order-source-attribution' ); ?></h4>
				<span class="order-source-attribution-utm-medium">
					<?php echo esc_html( $meta['utm_medium'] ); ?>
				</span>
			<?php endif; ?>

		</div>
	</div>

	<?php if ( array_key_exists( 'device_type', $meta ) ) : ?>
		<h4><?php esc_html_e( 'Device type', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-device_type">
			<?php echo esc_html( $meta['device_type'] ); ?>
		</span>
	<?php endif; ?>

	<?php if ( array_key_exists( 'session_pages', $meta ) ) : ?>
		<h4>
			<?php
			esc_html_e( 'Session page views', 'woocommerce-order-source-attribution' );
			echo wc_help_tip(
				__( 'The number of unique pages viewed by the customer prior to this order.', 'woocommerce-order-source-attribution' )
			); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			?>
		</h4>
		<span class="order-source-attribution-utm-session-pages">
			<?php echo esc_html( $meta['session_pages'] ); ?>
		</span>
	<?php endif; ?>
</div>
