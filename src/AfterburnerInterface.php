<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\haste;

use Closure;
use mako\application\web\Application;

/**
 * Haste interface.
 */
interface HasteInterface
{
	/**
	 * Runs the application.
	 */
	public static function run(Application $application, ?Closure $beforeRequest = null, ?Closure $afterRequest = null, mixed ...$options): void;
}
