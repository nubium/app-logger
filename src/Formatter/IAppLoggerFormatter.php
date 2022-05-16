<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Formatter;

use Nubium\AppLogger\Identifier\IAppLoggerIdentifier;

use DateTimeInterface;

interface IAppLoggerFormatter
{
	/**
	 * @param array<string, mixed> $data
	 * @throws AppLoggerFormatterException
	 */
	public function format(string $appName, DateTimeInterface $dateTime, IAppLoggerIdentifier $identifier, array $data): mixed;
}
