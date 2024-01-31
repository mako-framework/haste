<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\afterburner;

use Closure;
use mako\application\web\Application;

/**
 * Afterburner interface.
 */
interface AfterburnerInterface
{
	/**
	 * Runs the application.
	 */
	public static function run(Application $application, ?Closure $beforeRequest = null, ?Closure $afterRequest = null, mixed ...$options): void;
}
