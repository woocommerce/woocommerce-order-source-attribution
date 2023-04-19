<?php

namespace Automattic\WooCommerce\OrderSourceAttribution\Test;

use Automattic\WooCommerce\OrderSourceAttribution\Internal\AttributionFields;
use Automattic\WooCommerce\OrderSourceAttribution\Logging\LoggerInterface;
use Exception;
use WP_UnitTestCase;

class AttributionFieldsTest extends WP_UnitTestCase {

	protected static LoggerInterface $dummy_logger;

	protected AttributionFields $attribution_fields_class;

	/**
	 * This method is called before the first test of this test class is run.
	 *
	 * @codeCoverageIgnore
	 *
	 * @return void
	 */
	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		self::$dummy_logger = new class() implements LoggerInterface {
			public function log_exception( Exception $exception, string $method ): void {}
			public function log_error( string $message, string $method ): void {}
			public function log_message( string $message, string $method ): void {}
			public function log_response( $response, string $method ): void {}
		};
	}

	/**
	 * Sets up the fixture, for example, open a network connection.
	 *
	 * This method is called before each test.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();
		$this->attribution_fields_class = $this->getMockBuilder( AttributionFields::class )
			->setConstructorArgs( [ self::$dummy_logger ] )
			->getMock();

	}

	/**
	 * Tests the output_origin_column method.
	 *
	 * @return void
	 */
	public function test_output_origin_column() {

		$attribution_field_class = new AttributionFields( self::$dummy_logger );

		// Define the expected output for each test case.
		$test_cases = array(
			array(
				'source_type'     => 'utm',
				'source'          => 'example',
				'expected_output' => 'Source: Example',
			),
			array(
				'source_type'     => 'organic',
				'source'          => 'example',
				'expected_output' => 'Organic: Example',
			),
			array(
				'source_type'     => 'referral',
				'source'          => 'example',
				'expected_output' => 'Referral: Example',
			),
			array(
				'source_type'     => 'typein',
				'source'          => '(direct)',
				'expected_output' => 'Direct',
			),
			array(
				'source_type'     => '',
				'source'          => '',
				'expected_output' => 'None',
			),
		);

		foreach ( $test_cases as $test_case ) {
			// Create a mock WC_Order object.
			$order = $this->getMockBuilder( \WC_Order::class )
				->onlyMethods( array( 'get_meta' ) )
				->getMock();
			$order->method( 'get_meta' )
				->willReturnOnConsecutiveCalls( $test_case['source_type'], $test_case['source'] );

			// Capture the output.
			ob_start();

			$attribution_field_class->output_origin_column( $order );

			$output = ob_get_clean();

			$this->assertEquals( $test_case['expected_output'], $output );
		}
	}
}
