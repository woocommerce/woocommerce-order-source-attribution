<?php

namespace Automattic\WooCommerce\OrderSourceAttribution\Test\Integration;

use Automattic\WooCommerce\OrderSourceAttribution\HelperTraits\Utilities;
use Automattic\WooCommerce\OrderSourceAttribution\Integration\WPConsentAPI;
use WP_UnitTestCase;

class WPConsentAPITest extends WP_UnitTestCase {

	use Utilities;

	/**
	 * @var WPConsentAPI
	 */
	private WPConsentAPI $WPConsentAPIIntegration;

	/**
	 * @var string
	 */
	private string $plugin;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void {
		parent::setUp();
		$this->WPConsentAPIIntegration = $this->getMockBuilder( WPConsentAPI::class )
			->onlyMethods( [ 'is_wp_consent_api_active' ] )
			->getMock();
		$this->plugin = $this->get_plugin_base_name();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void {
		parent::tearDown();
		unset( $this->WPConsentAPIIntegration );
		unset( $this->plugin );
	}

	public function test_wp_consent_api_not_available(): void {
		$this->WPConsentAPIIntegration->method( 'is_wp_consent_api_active' )
			->willReturn( false );
		$this->WPConsentAPIIntegration->register();
		$this->assertFalse( has_filter( "wp_consent_api_registered_{$this->plugin}" ) );

	}

	public function test_wp_consent_api_available(): void {
		$this->WPConsentAPIIntegration->method( 'is_wp_consent_api_active' )
			->willReturn( true );
		$this->WPConsentAPIIntegration->register();
		$this->assertTrue( has_filter( "wp_consent_api_registered_{$this->plugin}" ) );
	}

}
