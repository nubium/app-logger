<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Sentry;

class VoidAppLoggerSentryContext implements IAppLoggerSentryContext
{
	/**
	 * @inheritDoc
	 */
	public function toArray(): array
	{
		return [];
	}
}
