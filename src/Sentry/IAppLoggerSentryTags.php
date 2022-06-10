<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Sentry;

interface IAppLoggerSentryTags
{
	/**
	 * @return array<string, string>
	 */
	public function toArray(): array;
}
