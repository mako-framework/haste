<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\haste;

use mako\application\web\Application;

/**
 * Haste interface.
 */
interface HasteInterface
{
	/**
	 * Runs the application.
	 */
	public static function run(Application $application, ?callable $beforeRequest = null, ?callable $afterRequest = null, mixed ...$options): void;
}
