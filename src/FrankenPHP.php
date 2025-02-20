<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\haste;

use mako\application\Application as BaseApplication;
use mako\application\CurrentApplication;
use mako\application\web\Application;
use mako\error\ErrorHandler;
use mako\http\exceptions\HttpException;
use Throwable;

use function array_diff;
use function array_merge;
use function frankenphp_handle_request;
use function gc_collect_cycles;
use function ignore_user_abort;

/**
 * FrankenPHP.
 */
class FrankenPHP implements HasteInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function run(Application $application, ?callable $beforeRequest = null, ?callable $afterRequest = null, mixed ...$options): void
	{
		ignore_user_abort(true);

		$classesToKeep = $application->getContainer()->getInstanceClassNames();

		// Configure things.

		if (!empty($options['classesToKeep'])) {
			$classesToKeep = array_merge($classesToKeep, $options['classesToKeep']);
		}

		$maxRequests = $options['maxRequests'] ?? 1000;

		// Handle requests.

		$requests = 0;
		$shutDownEarly = false;

		do {
			// Clone the application so that we have a clean slate for each request.

			$currentApplication = clone $application;

			$currentApplication->getContainer()->replaceInstance(BaseApplication::class, $currentApplication);

			CurrentApplication::set($currentApplication);

			// Run the before request callable and stop processing requests if it returns FALSE.

			if ($beforeRequest !== null && $currentApplication->getContainer()->call($beforeRequest) === false) {
				break;
			}

			// Handle the request.

			$keepGoing = frankenphp_handle_request(static function () use ($currentApplication, &$shutDownEarly): void {
				try {
					$currentApplication->run();
				}
				catch (Throwable $e) {
					if ($hasHandler = $currentApplication->getContainer()->has(ErrorHandler::class)) {
						$currentApplication->getContainer()->get(ErrorHandler::class)->handle($e, shouldExit: false);
					}

					if (($e instanceof HttpException) === false) {
						$shutDownEarly = true;

						if (!$hasHandler) {
							throw $e;
						}
					}
				}
			});

			// Clean up if the request was handled successfully.

			if (!$shutDownEarly) {
				// Run the after request callable and stop processing requests if it returns FALSE.

				if ($afterRequest !== null && $currentApplication->getContainer()->call($afterRequest) === false) {
					break;
				}

				// Reset the container to the default state and collect garbage.

				$classesToRemove = array_diff($application->getContainer()->getInstanceClassNames(), $classesToKeep);

				foreach ($classesToRemove as $class) {
					$application->getContainer()->removeInstance($class);
				}

				gc_collect_cycles();
			}

			$continue = !$shutDownEarly && $keepGoing;

		}
		while ($continue && ++$requests < $maxRequests);
	}
}
