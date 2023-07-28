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

$keyed_meta = array_combine(
	wp_list_pluck( $meta, 'key' ),
	array_values( $meta )
);

$prefix = function( $name ) {
	if ( 'type' === $name ) {
		$name = 'source_type';
	} elseif ( 'url' === $name ) {
		$name = 'referrer';
	}

	return "_{$this->prefix_field( $name )}";
};
?>

<div class="source_data form-field form-field-wide order-source-attribution-metabox">

	<?php if ( array_key_exists( $prefix( 'type' ), $keyed_meta ) ) : ?>
		<h4>
			<?php
			esc_html_e( 'Origin', 'woocommerce-order-source-attribution' );
			echo wc_help_tip(
				__( 'The origin of the order', 'woocommerce-order-source-attribution' )
			); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			?>
		</h4>
		<span class="order-source-attribution-origin">
			<?php echo esc_html( $keyed_meta[ $prefix( 'type' ) ]->value ); ?>
		</span>
	<?php endif; ?>

	<?php if ( array_key_exists( $prefix( 'source_type' ), $keyed_meta ) ) : ?>
		<h4><?php esc_html_e( 'Source type', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-source_type">
			<?php echo esc_html( $keyed_meta[ $prefix( 'source_type' ) ]->value ); ?>
		</span>
	<?php endif; ?>

	<?php if ( array_key_exists( $prefix( 'utm_campaign' ), $keyed_meta ) ) : ?>
		<h4><?php esc_html_e( 'UTM campaign', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-utm-campaign">
			<?php echo esc_html( $keyed_meta[ $prefix( 'utm_campaign' ) ]->value ); ?>
		</span>
	<?php endif; ?>

	<?php if ( array_key_exists( $prefix( 'utm_source' ), $keyed_meta ) ) : ?>
		<h4><?php esc_html_e( 'UTM source', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-utm-source">
			<?php echo esc_html( $keyed_meta[ $prefix( 'utm_source' ) ]->value ); ?>
		</span>
	<?php endif; ?>

	<?php if ( array_key_exists( $prefix( 'utm_medium' ), $keyed_meta ) ) : ?>
		<h4><?php esc_html_e( 'UTM medium', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-utm-medium">
			<?php echo esc_html( $keyed_meta[ $prefix( 'utm_medium' ) ]->value ); ?>
		</span>
	<?php endif; ?>

	<!-- todo: Device type -->

	<?php if ( array_key_exists( $prefix( 'session_pages' ), $keyed_meta ) ) : ?>
		<h4><?php esc_html_e( 'Session page views', 'woocommerce-order-source-attribution' ); ?></h4>
		<span class="order-source-attribution-utm-session-pages">
			<?php echo esc_html( $keyed_meta[ $prefix( 'session_pages' ) ]->value ); ?>
		</span>
	<?php endif; ?>
</div>
