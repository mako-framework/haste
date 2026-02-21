<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\haste\logger\traits;

use mako\haste\logger\FrankenPhpHandler;

/**
 * Haste logger service trait.
 */
trait HasteLoggerServiceTrait
{
	/**
	 * Returns a FrankenPHP log handler.
	 */
	protected function getFrankenPhpHandler(): FrankenPhpHandler
	{
		return new FrankenPhpHandler;
	}
}
