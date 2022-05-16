<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Sentry;

interface IAppLoggerSentryContext
{
	/**
	 * @return array<string, string>
	 */
	public function toArray(): array;
}
