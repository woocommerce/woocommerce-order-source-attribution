( function ( $, data ) {
    'use strict';

    // check if window.consent_api is available
    window.allowTracking = false;
    document.addEventListener("wp_listen_for_consent_change", function (e) {
        var changedConsentCategory = e.detail;
        for (var key in changedConsentCategory) {
            if (changedConsentCategory.hasOwnProperty(key)) {
                if (key === 'marketing' && changedConsentCategory[key] === 'allow') {
                    allowTracking = true;
                    woocommerce_order_source_attribution.initOrderTracking();
                }
            }
        }
    });

} )( jQuery );