window.addEventListener( 'load' , function ( e ) {
	if ( window.wccom && window.wccom.canTrackUser('analytics') ) {
		if ( ! wccom || ! wccom.canTrackUser( 'analytics' ) ) {
			return;
		}
		window.woocommerce_order_source_attribution.setAllowTrackingConsent( true );
	}
} );


