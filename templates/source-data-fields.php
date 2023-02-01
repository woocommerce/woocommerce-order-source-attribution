<?php
declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/**
 * Variables used in this file.
 *
 * @var WC_Meta_Data[] $meta
 */
?>

<div class="source_data form-field form-field-wide">
	<h3><?php esc_html_e( 'Source Info', 'woocommerce-order-source-attribution' ); ?></h3>

	<?php
	foreach ( $meta as $item ) {
		switch ( $item->key ) {
			case '_wc_order_source_attribution_referrer':
				$label = __( 'Referrer', 'woocommerce-order-source-attribution' );
				break;

			case '_wc_order_source_attribution_source_type':
				$label = __( 'Source type', 'woocommerce-order-source-attribution' );
				break;

			default:
				$label = str_replace( [ '_wc_order_source_attribution_', '_' ], [ '', ' ' ], $item->key );
				$label = ucwords( $label );
				break;
		}
		?>
		<p class="form-field form-field-wide">
			<label><?php echo esc_html( $label ); ?>:</label>
			<?php echo esc_html( $item->value ); ?>
		</p>
		<?php
	}
	?>
</div>
