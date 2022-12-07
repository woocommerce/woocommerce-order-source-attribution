( function ( $, params ) {
	'use strict';

	const prefix = params.prefix;
	const cookieLifetime = Number(params.lifetime);
	const sessionLength = Number(params.session);

	/**
	 * Initialize sourcebuster.js.
	 */
	sbjs.init( {
		lifetime: cookieLifetime,
		session_length: sessionLength,
		timezone_offset: '0', // utc
	} );

	/**
	 * Set values.
	 */
	var setFields = function () {

		if ( sbjs.get ) {
			$( `input[name="${prefix}type"]` ).val( sbjs.get.current.typ );
			$( `input[name="${prefix}url"]` ).val( sbjs.get.current_add.rf );

			$( `input[name="${prefix}utm_campaign"]` ).val( sbjs.get.current.cmp );
			$( `input[name="${prefix}utm_source"]` ).val( sbjs.get.current.src );
			$( `input[name="${prefix}utm_medium"]` ).val( sbjs.get.current.mdm );
			$( `input[name="${prefix}utm_content"]` ).val( sbjs.get.current.cnt );
			$( `input[name="${prefix}utm_id"]` ).val( sbjs.get.current.id );
			$( `input[name="${prefix}utm_term"]` ).val( sbjs.get.current.trm );

			$( `input[name="${prefix}session_entry"]` ).val( sbjs.get.current_add.ep );
			$( `input[name="${prefix}session_start_time"]` ).val( sbjs.get.current_add.fd );
			$( `input[name="${prefix}session_pages"]` ).val( sbjs.get.session.pgs );
			$( `input[name="${prefix}session_count"]` ).val( sbjs.get.udata.vst );

			$( `input[name="${prefix}user_agent"]` ).val( sbjs.get.udata.uag );
		}
	};

	/**
	 * Add source values to checkout.
	 */
	$( document.body ).on( 'init_checkout', function () {
		setFields();
	} );

	/**
	 * Add source values to register.
	 */
	if ( $( '.woocommerce form.register' ).length ) {
		setFields();
	}

} )( jQuery, window.wc_order_attribute_source_params );
