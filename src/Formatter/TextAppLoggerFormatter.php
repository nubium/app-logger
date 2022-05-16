<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Formatter;

use DateTimeInterface;
use Nubium\AppLogger\Identifier\IAppLoggerIdentifier;

class TextAppLoggerFormatter implements IAppLoggerFormatter
{
	/**
	 * @inheritDoc
	 */
	public function format(string $appName, DateTimeInterface $dateTime, IAppLoggerIdentifier $identifier, array $data): mixed
	{
		$encodedData = [];
		foreach ($data as $key => $val) {
			$encodedData[] = $key . ': ' . $this->formatValue($val);
		}

		return sprintf(
			'%s %s %s: %s',
			$appName,
			$dateTime->format('Y-d-m H:i:s'),
			$identifier->toString(),
			implode(' | ', $encodedData),
		);
	}


	/**
	 * @throws AppLoggerFormatterException
	 */
	private function formatValue(mixed $value): string
	{
		switch (true) {
			case is_bool($value): return (string)(int)$value;
			case is_scalar($value): return str_replace("\n", '\\n', (string)$value);
			default:
				$json = json_encode($value);
				if ($json === false) {
					throw new AppLoggerFormatterException(json_last_error_msg());
				}
				return $json;
		}
	}
}
