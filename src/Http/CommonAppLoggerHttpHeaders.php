<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Http;

use Nubium\AppLogger\Identifier\IAppLoggerIdentifier;

class CommonAppLoggerHttpHeaders implements IAppLoggerHttpHeaders
{
	public function __construct(private string $appName, private IAppLoggerIdentifier $identifier) {}


	/**
	 * @inheritDoc
	 */
	public function toArray(): array
	{
		return [
			'X-Request-Id' => $this->identifier->toString(),
			'X-App-Name' => $this->appName,
		];
	}
}
