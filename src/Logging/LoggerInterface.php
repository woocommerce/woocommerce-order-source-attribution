<?php

namespace Automattic\WooCommerce\OrderSourceAttribution\Logging;

use Exception;

interface LoggerInterface {
	/**
	 * Log an exception.
	 *
	 * @param Exception $exception
	 * @param string    $method
	 */
	public function log_exception( Exception $exception, string $method ): void;

	/**
	 * Log an error.
	 *
	 * @param string $message
	 * @param string $method
	 */
	public function log_error( string $message, string $method ): void;

	/**
	 * Log a generic note.
	 *
	 * @param string $message
	 * @param string $method
	 */
	public function log_message( string $message, string $method ): void;

	/**
	 * Log a JSON response.
	 *
	 * @param mixed  $response
	 * @param string $method
	 */
	public function log_response( $response, string $method ): void;

}