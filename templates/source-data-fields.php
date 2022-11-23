<?php
declare( strict_types=1 );

use Automattic\WooCommerce\Grow\OrderAttributePrototype\Internal\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Variables used in this file.
 *
 * @see Plugin
 *
 * @var Plugin   $this
 * @var WC_Order $order
 */

$fields = $this->fields;
$meta   = array_filter(
	get_post_meta( $order->get_id() ),
	function ( $key ) {
		return str_starts_with( $key, '_grow_' );
	},
	ARRAY_FILTER_USE_KEY
);

?>

<div class="source_data form-field form-field-waide">
	<h3><?php _e( 'Source Info', 'grow-oap' ); ?></h3>

	<?php
	foreach ( $fields as $field ) {
		if ( ! array_key_exists( $field, $meta ) ) {
			continue;
		}

		switch ( $field ) {
			case 'url':
				$label = __( 'Referrer', 'grow-oap' );
				break;

			case 'type':
				$label = __( 'Source type', 'grow-oap' );
				break;

			default:
				$label = strtoupper( $field );
				break;
		}
		?>
		<p class="form-field form-field-wide">
			<label><?php echo esc_html( $label ); ?>:</label>
			<?php echo esc_html( $meta[ $field ] ); ?>
		</p>
		<?php
	}
	?>
</div>
