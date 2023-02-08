<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\OrderSourceAttribution\HelperTraits;

use Automattic\WooCommerce\OrderSourceAttribution\Logging\LoggerInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Trait LoggerTrait
 *
 * @since x.x.x
 */
trait LoggerTrait {

	/** @var LoggerInterface */
	private $logger;

	/**
	 * Set the logger object.
	 *
	 * @param LoggerInterface $logger
	 *
	 * @return void
	 */
	private function set_logger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Get the logger object.
	 *
	 * @return LoggerInterface
	 */
	private function get_logger(): LoggerInterface {
		return $this->logger;
	}
}
