<?php
namespace Automattic\WooCommerce\OrderSourceAttribution\Logging;

use Automattic\WooCommerce\OrderSourceAttribution\HelperTraits\Utilities;
use Exception;
use WC_Log_Levels;
use WC_Logger_Interface;

/**
 * Class DebugLogger
 *
 * @since x.x.x
 */
class DebugLogger implements LoggerInterface {

	use Utilities;

	/**
	 * WooCommerce logger class instance.
	 *
	 * @var WC_Logger_Interface
	 */
	private $logger;

	/**
	 * Constructor.
	 *
	 * @param WC_Logger_Interface $wc_logger The WooCommerce logger.
	 */
	public function __construct( WC_Logger_Interface $wc_logger ) {
		$this->logger = $wc_logger;
	}

	/**
	 * Log an exception.
	 *
	 * @param Exception $exception
	 * @param string    $method
	 */
	public function log_exception( Exception $exception, string $method ): void {
		$this->log( $exception->getMessage(), $method, WC_Log_Levels::ERROR );
	}

	/**
	 * Log an error.
	 *
	 * @param string $message
	 * @param string $method
	 */
	public function log_error( string $message, string $method ): void {
		$this->log( $message, $method, WC_Log_Levels::ERROR );
	}

	/**
	 * Log a generic note.
	 *
	 * @param string $message
	 * @param string $method
	 */
	public function log_message( string $message, string $method ): void {
		$this->log( $message, $method );
	}

	/**
	 * Log a JSON response.
	 *
	 * @param mixed  $response
	 * @param string $method
	 */
	public function log_response( $response, string $method ): void {
		$message = wp_json_encode( $response, JSON_PRETTY_PRINT );
		$this->log( $message, $method );
	}

	/**
	 * Log a message as a debug log entry.
	 *
	 * @param string $message
	 * @param string $method
	 * @param string $level
	 */
	protected function log( string $message, string $method, string $level = WC_Log_Levels::DEBUG ) {
		if ( ! $this->is_debug_mode_enabled() ) {
			return;
		}

		$this->logger->log(
			$level,
			sprintf( '%s %s', $method, $message ),
			[
				'source' => 'woocommerce-order-source-attribution',
			]
		);
	}

}
