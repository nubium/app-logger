<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Formatter;

use DateTimeInterface;
use Nubium\AppLogger\Identifier\IAppLoggerIdentifier;

class JsonAppLoggerFormatter implements IAppLoggerFormatter
{
	/**
	 * @inheritDoc
	 */
	public function format(string $appName, DateTimeInterface $dateTime, IAppLoggerIdentifier $identifier, array $data): mixed
	{
		$encodedData = json_encode($data + [
			'date_time' => $dateTime->format(DateTimeInterface::RFC3339),
			'request_id' => $identifier->toString(),
			'app_name' => $appName,
		]);

		if ($encodedData === false) {
			throw new AppLoggerFormatterException(json_last_error_msg());
		}

		return $encodedData;
	}
}
