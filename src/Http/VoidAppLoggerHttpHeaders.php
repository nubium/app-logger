<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Http;

class VoidAppLoggerHttpHeaders implements IAppLoggerHttpHeaders
{
	/**
	 * @inheritDoc
	 */
	public function toArray(): array
	{
		return [];
	}
}
