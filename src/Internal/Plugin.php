<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\OrderSourceAttribution\Internal;

use Automattic\WooCommerce\OrderSourceAttribution\HelperTraits\LoggerTrait;
use Automattic\WooCommerce\OrderSourceAttribution\Integration\WPConsentAPI;
use Automattic\WooCommerce\OrderSourceAttribution\Logging\LoggerInterface;
use Automattic\WooCommerce\OrderSourceAttribution\Settings\SettingsTab;

defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin
 *
 * @since x.x.x
 */
final class Plugin {

	use LoggerTrait;

	/**
	 * Plugin constructor.
	 *
	 * @param LoggerInterface $logger The logger.
	 */
	public function __construct( LoggerInterface $logger ) {
		$this->set_logger( $logger );
	}

	/**
	 * Register our hooks.
	 *
	 * @return void
	 */
	public function register() {
		// Register our settings tab.
		( new SettingsTab() )->register();

		if ( ! $this->is_order_source_data_enabled() ) {
			return;
		}

		// Register WPConsentAPI
		( new WPConsentAPI() )->register();
		( new AttributionFields( $this->get_logger() ) )->register();
	}

	/**
	 * Check to see if order source data is enabled.
	 *
	 * @return bool
	 */
	private function is_order_source_data_enabled(): bool {
		return 'yes' === get_option( SettingsTab::SETTINGS_ENABLE_ORDER_ATTRIBUTION_ID, 'yes' );
	}
}
