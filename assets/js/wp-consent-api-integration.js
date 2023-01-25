( function ( $, data ) {
	'use strict';

	window.woocommerce_order_source_attribution = {};

	// check init order source attribution on consent change
	window.woocommerce_order_source_attribution.allowTracking = false;
	const consentCategory = 'marketing';
	document.addEventListener("wp_listen_for_consent_change", function (e) {
		var changedConsentCategory = e.detail;
		for (var key in changedConsentCategory) {
			if (changedConsentCategory.hasOwnProperty(key)) {
				if (key === consentCategory && changedConsentCategory[key] === 'allow') {
					window.woocommerce_order_source_attribution.allowTracking = true;
					window.woocommerce_order_source_attribution.initOrderTracking();
				}
			}
		}
	});

	// init order source attribution as soon as consent type defined
	$(document).on("wp_consent_type_defined", activateOrderTrackingCookies);

	function activateOrderTrackingCookies(consentData) {
		if (wp_has_consent(consentCategory)) {
			window.woocommerce_order_source_attribution.allowTracking = true;
			window.woocommerce_order_source_attribution.initOrderTracking();
		}
	}

} )( jQuery );

