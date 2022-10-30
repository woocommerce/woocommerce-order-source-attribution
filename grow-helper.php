<?php
/**
 * Plugin Name: Grow Helper
 * Plugin URI: https://woogrowp2.wordpress.com/
 * Description: Test
 * Version: 0.1.0
 * Author: Grow
 * Author URI: https://woogrowp2.wordpress.com/
 * Text Domain: grow-helper
 * WC requires at least: 2.6.0
 * WC tested up to: 5.5.0.
 */
class Grow_Helper
{
    /**
     * Current version of Grow Helper.
     */
    public $version = '0.1.0';

    /**
     * URL dir for plugin.
     */
    public $url;

    /**
     * The single instance of the class.
     */
    protected static $_instance = null;

    /**
     * Main Helper Instance.
     *
     * Ensures only one instance of the Helper is loaded or can be loaded.
     *
     * @return Helper - Main instance.
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('plugins_loaded', array($this, 'init'));

        // Set URL
        $this->url = plugin_dir_url(__FILE__);
    }

    /**
     * Start plugin.
     */
    public function init()
    {
        if (class_exists('WooCommerce')) {

            // Require files for the plugin
            require_once 'inc/custom.php';
        }

        // Plugin textdomain
        load_plugin_textdomain('grow-helper', false, basename(dirname(__FILE__)).'/languages/');
    }

}

/**
 * For plugin-wide access to initial instance.
 */
function Grow_Helper()
{
    return Grow_Helper::instance();
}

Grow_Helper();
