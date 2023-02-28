<?php

namespace Automattic\WooCommerce\OrderSourceAttribution\Test\Integration;

use Automattic\WooCommerce\OrderSourceAttribution\Integration\WCCOMTracking;
use WP_UnitTestCase;

class WCCOMTrackingTest extends WP_UnitTestCase {
	/**
	 * @var $WCCOMTrackingIntegration
	 */
	private $WCCOMTrackingIntegration;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void {
		parent::setUp();
		$this->WCCOMTrackingIntegration = $this->getMockBuilder( WCCOMTracking::class )
			->onlyMethods( [ 'is_WCCom_Cookie_Terms_available', 'is_wccom_tracking_allowed' ] )
			->getMock();
	}

	public function test_wccom_tracking_not_allowed(): void {
		$this->WCCOMTrackingIntegration->method( 'is_wccom_cookie_terms_available' )
			->willReturn( true );
		$this->WCCOMTrackingIntegration->method( 'is_wccom_tracking_allowed' )
			->willReturn( false );

		$this->WCCOMTrackingIntegration->register();

		$is_order_tracking_allowed = apply_filters( 'wc_order_source_attribution_allow_tracking', true );

		$this->assertFalse( $is_order_tracking_allowed );

	}

	public function test_wccom_tracking_allowed(): void {

		$this->WCCOMTrackingIntegration->method( 'is_wccom_cookie_terms_available' )
			->willReturn( true );
		$this->WCCOMTrackingIntegration->method( 'is_wccom_tracking_allowed' )
			->willReturn( true );
		$this->WCCOMTrackingIntegration->register();

		$is_order_tracking_allowed = apply_filters( 'wc_order_source_attribution_allow_tracking', true );

		$this->assertTrue( $is_order_tracking_allowed );

	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void {
		parent::tearDown();
		unset( $this->WCCOMTrackingIntegration );
	}
}
