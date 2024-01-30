<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\afterburner;

use mako\application\web\Application;

/**
 * Afterburner interface.
 */
interface AfterburnerInterface
{
	/**
	 * Runs the application.
	 */
	public static function run(Application $application, mixed ...$options): void;
}
