<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Driver;

use InvalidArgumentException;

class ResourceAppLoggerDriver implements IAppLoggerDriver
{
	/**
	 * @param resource $resource
	 * @throws InvalidArgumentException
	 */
	public function __construct(private $resource)
	{
		if (!is_resource($this->resource)) {
			throw new InvalidArgumentException(self::class . '::resource is not resource');
		}
	}


	/**
	 * @inheritDoc
	 */
	public function save(mixed $data): void
	{
		if (!is_scalar($data)) {
			throw new AppLoggerDriverException('Resource driver can save only scalar values');
		}

		if (@fwrite($this->resource, $data . PHP_EOL) === false) {
			throw new AppLoggerDriverException(error_get_last()['message'] ?? 'unknown error');
		}
	}
}
