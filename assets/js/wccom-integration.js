( function ( $ ) {
	'use strict';

	function activateOrderTrackingCookies() {
		if (window.wccom && window.wccom.canTrackUser('analytics')) {
			if ( ! wccom || ! wccom.canTrackUser( 'analytics' ) ) {
				return;
			}
			window.woocommerce_order_source_attribution.setAllowTrackingConsent( true );
		}
	}

	activateOrderTrackingCookies();

} )( jQuery );
