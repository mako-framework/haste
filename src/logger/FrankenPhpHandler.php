<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\haste\logger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Override;

use function frankenphp_log;

/**
 * FrankenPHP log handler for Monolog.
 */
class FrankenPhpHandler extends AbstractProcessingHandler
{
	/**
	 * Maps monolog levels to FrankenPHP levels.
	 */
	protected function mapLevel(Level $level): int
	{
		return match ($level) {
			Level::Debug => FRANKENPHP_LOG_LEVEL_DEBUG,
			Level::Info, Level::Notice => FRANKENPHP_LOG_LEVEL_INFO,
			Level::Warning => FRANKENPHP_LOG_LEVEL_WARN,
			Level::Error, Level::Critical, Level::Alert, Level::Emergency => FRANKENPHP_LOG_LEVEL_ERROR,
		};
	}

	/**
	 * {@inheritDoc}
	 */
	#[Override]
	protected function write(LogRecord $record): void
	{
		frankenphp_log(
			(string) $record->formatted,
			$this->mapLevel($record->level),
			$record->context
		);
	}
}
