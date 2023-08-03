<?php
declare( strict_types=1 );

use Automattic\WooCommerce\OrderSourceAttribution\Internal\AttributionFields;

defined( 'ABSPATH' ) || exit;

/**
 * Variables used in this file.
 *
 * @var WC_Meta_Data[]    $meta
 * @var AttributionFields $this
 */
?>

<div class="source_data form-field form-field-wide order-source-attribution-metabox">

	<?php if ( array_key_exists( $this->get_meta_prefixed_field( 'type' ), $meta ) ) : ?>
		<h4><?php esc_html_e( 'Origin', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-origin">
			<?php echo esc_html( $meta[ $this->get_meta_prefixed_field( 'type' ) ]->value ); ?>
		</span>
	<?php endif; ?>

	<?php if ( array_key_exists( $this->get_meta_prefixed_field( 'source_type' ), $meta ) ) : ?>
		<h4><?php esc_html_e( 'Source type', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-source_type">
			<?php echo esc_html( $meta[ $this->get_meta_prefixed_field( 'source_type' ) ]->value ); ?>
		</span>
	<?php endif; ?>

	<?php if ( array_key_exists( $this->get_meta_prefixed_field( 'utm_campaign' ), $meta ) ) : ?>
		<h4><?php esc_html_e( 'UTM campaign', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-utm-campaign">
			<?php echo esc_html( $meta[ $this->get_meta_prefixed_field( 'utm_campaign' ) ]->value ); ?>
		</span>
	<?php endif; ?>

	<?php if ( array_key_exists( $this->get_meta_prefixed_field( 'utm_source' ), $meta ) ) : ?>
		<h4><?php esc_html_e( 'UTM source', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-utm-source">
			<?php echo esc_html( $meta[ $this->get_meta_prefixed_field( 'utm_source' ) ]->value ); ?>
		</span>
	<?php endif; ?>

	<?php if ( array_key_exists( $this->get_meta_prefixed_field( 'utm_medium' ), $meta ) ) : ?>
		<h4><?php esc_html_e( 'UTM medium', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-utm-medium">
			<?php echo esc_html( $meta[ $this->get_meta_prefixed_field( 'utm_medium' ) ]->value ); ?>
		</span>
	<?php endif; ?>

	<!-- todo: Device type -->

	<?php if ( array_key_exists( $this->get_meta_prefixed_field( 'session_pages' ), $meta ) ) : ?>
		<h4>
			<?php
			esc_html_e( 'Session page views', 'woocommerce-order-source-attribution' );
			echo wc_help_tip(
				__( 'The number of unique pages viewed by the customer prior to this order.', 'woocommerce-order-source-attribution' )
			); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			?>
		</h4>
		<span class="order-source-attribution-utm-session-pages">
			<?php echo esc_html( $meta[ $this->get_meta_prefixed_field( 'session_pages' ) ]->value ); ?>
		</span>
	<?php endif; ?>
</div>
