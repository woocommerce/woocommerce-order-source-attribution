<?php

namespace Automattic\WooCommerce\OrderSourceAttribution\Utilities;

defined( 'ABSPATH' ) || exit;

/**
 * Helper class.
 *
 * @since x.x.x
 */
class Helper {

    /**
     * Get plugin base name.
     *
     * @return string
     * @since x.x.x
     */
    public static function get_plugin_base_name(){
        return plugin_basename(WC_ORDER_ATTRIBUTE_SOURCE_FILE);
    }

}

