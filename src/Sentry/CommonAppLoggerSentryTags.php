<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Sentry;

use Nubium\AppLogger\Identifier\IAppLoggerIdentifier;

class CommonAppLoggerSentryTags implements IAppLoggerSentryTags
{
	public function __construct(private IAppLoggerIdentifier $identifier) {}


	/**
	 * @inheritDoc
	 */
	public function toArray(): array
	{
		return [
			'requestId' => $this->identifier->toString(),
		];
	}
}
