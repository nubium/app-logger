<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Sentry;

class VoidAppLoggerSentryTags implements IAppLoggerSentryTags
{
	/**
	 * @inheritDoc
	 */
	public function toArray(): array
	{
		return [];
	}
}
