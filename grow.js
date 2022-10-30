(function($) {
    /**
     * Initialize sourcebuster.js.
     */
    sbjs.init({
        lifetime: grow_params.life,
        session_length: grow_params.session,
        timezone_offset: '0', // utc
    });

    /**
     * Set values.
     */
    var setFields = function() {

        if (sbjs.get) {
            $('input[name="grow_source_type"]').val(sbjs.get.current.typ);
            $('input[name="grow_source_url"]').val(sbjs.get.current_add.rf);
            $('input[name="grow_source_mtke"]').val(sbjs.get.current.mtke);

            $('input[name="grow_source_utm_campaign"]').val(sbjs.get.current.cmp);
            $('input[name="grow_source_utm_source"]').val(sbjs.get.current.src);
            $('input[name="grow_source_utm_medium"]').val(sbjs.get.current.mdm);
            $('input[name="grow_source_utm_content"]').val(sbjs.get.current.cnt);
            $('input[name="grow_source_utm_id"]').val(sbjs.get.current.id);
            $('input[name="grow_source_utm_term"]').val(sbjs.get.current.trm);

            $('input[name="grow_source_session_entry"]').val(sbjs.get.current_add.ep);
            $('input[name="grow_source_session_start_time"]').val(sbjs.get.current_add.fd);
            $('input[name="grow_source_session_pages"]').val(sbjs.get.session.pgs);
            $('input[name="grow_source_session_count"]').val(sbjs.get.udata.vst);
        }
    };

    /**
     * Add source values to checkout.
     */
    $(document.body).on('init_checkout', function(event) {
        setFields();
    });

    /**
     * Add source values to register.
     */
    if ($('.woocommerce form.register').length) {
        setFields();
    }

})(jQuery);
