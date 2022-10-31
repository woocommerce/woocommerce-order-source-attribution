<?php

/**
 * Custom changes like tracking referrer.
 */
class Grow_Custom
{
    /**
     * Current version of Grow Helper.
     */
    public $version = '0.1.0';

    /**
     * Possible fields.
     */
    public $fields = array(
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
    );

    /**
     * Field prefix (for the input field names).
     */
    public $fieldPrefix = 'grow_source_';

    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'scripts_styles'));

        // fields
        add_action('woocommerce_after_order_notes', array($this, 'source_form_fields'));
        add_action('woocommerce_register_form', array($this, 'source_form_fields'));

        // update
        add_action('woocommerce_checkout_update_order_meta', array($this, 'set_order_source'));
        add_action('user_register', array($this, 'set_customer_source'));
	    
	// display
	add_action('woocommerce_admin_order_data_after_order_details', array($this, 'display_order_source'));
    }

    /**
     * Scripts & styles for custom source tracking and cart tracking.
     */
    public function scripts_styles()
    {
        /*
         * Enqueue scripts.
         */
	wp_enqueue_script('sourcebuster-js', plugins_url('assets/js/sourcebuster.min.js', dirname(__FILE__)), array('jquery'), $this->version, true);
        wp_enqueue_script('grow-js', plugins_url('assets/js/grow.js', dirname(__FILE__)), array('jquery'), $this->version, true);


        /**
         * Pass parameters to Grow JS.
         */
        $params = array(
            'lifetime'                 => (int) apply_filters('grow_cookie_lifetime', 6), // 6 months
            'session'                  => (int) apply_filters('grow_session_length', 30), // 30 minutes
            'ajaxurl'                  => admin_url('admin-ajax.php'),
        );

        wp_localize_script('grow-js', 'grow_params', $params);
    }

    /**
     * Add grow hidden input fields for checkout & customer register froms.
     */
    public function source_form_fields()
    {
        /*
         * Hidden field for each possible field.
         */
        foreach ($this->fields as $field) {
            echo '<input type="hidden" name="'.$this->fieldPrefix.$field.'" value="" />';
        }
    }

    /**
     * Set the source data in the order post meta.
     */
    public function set_order_source($order_id)
    {
        $this->set_source_data($order_id, 'order');
    }

    /**
     * Set the source data in the customer user meta.
     */
    public function set_customer_source($customer_id)
    {
        $this->set_source_data($customer_id, 'customer');
    }

    /**
     * Set source data.
     */
    public function set_source_data($id, $resource)
    {
        /**
         * Values.
         */
        $values = array();

        /*
         * Get each field if POSTed.
         */
        foreach ($this->fields as $field) {
            // default empty
            $values[$field] = '';

            // set if have
            if (isset($_POST[$this->fieldPrefix.$field]) && $_POST[$this->fieldPrefix.$field]) {
                $values[$field] = sanitize_text_field($_POST[$this->fieldPrefix.$field]);
            }
        }

        /**
         * Now parse values to set in meta.
         */

        // update function based on order or customer
        $update_function = $resource == 'order' ? 'update_post_meta' : 'update_user_meta';

        // type
        if ($values['type'] && $values['type'] !== '(none)') {
            $update_function($id, '_grow_source_type', $values['type']);
        }
        unset($values['type']);

        // referer url
        if ($values['url'] && $values['url'] !== '(none)') {
            $update_function($id, '_grow_referer', $values['url']);
        }
        unset($values['url']);

        // rest of fields - UTMs & sessions (if not '(none)')
        foreach ($values as $key => $value) {
            if ($value && $value !== '(none)') {
                $update_function($id, '_grow_'.$key, $value);
            }
        }
    }

// display the extra data in the order admin panel
public function display_order_source( $order ){  ?>
    <div class="order_data_column">
	<h3><?php _e( 'Source Info' ); ?></h3>
	<?php				       				       						  
        foreach ($this->fields as $field) {
		
		$value = get_post_meta( $order->id, '_grow_'.$field, true ) ;

		// referer url
       		if ( 'url' == $field && '(none)' !== $value ) {
           		echo '<p class="form-field form-field-wide"><label>referer:</label>' . get_post_meta( $order->id, '_grow_referer', true ) . '</p>';
       		}

		// source type
       		if ( 'type' == $field && '(none)' !== $value ) {
           		echo '<p class="form-field form-field-wide"><label>source_type:</label>' . get_post_meta( $order->id, '_grow_source_type', true ) . '</p>';
       		}
		
		if ( ! empty( $value ) ) {
			echo '<p class="form-field form-field-wide"><label>' . $field . ':</label>' . get_post_meta( $order->id, '_grow_'.$field, true ) . '</p>';
		}
        }
	?>				       
    </div>
<?php }

}

new Grow_Custom();
