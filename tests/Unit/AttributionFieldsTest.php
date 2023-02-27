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
}
