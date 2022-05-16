<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Driver;

use InvalidArgumentException;

class FileAppLoggerDriver implements IAppLoggerDriver
{
	/**
	 * @param non-empty-string $filePath
	 * @throws InvalidArgumentException
	 */
	public function __construct(private string $filePath)
	{
		if (empty($this->filePath)) {
			throw new InvalidArgumentException(self::class . '::filePath is empty');
		}
	}


	/**
	 * @inheritDoc
	 */
	public function save(mixed $data): void
	{
		if (!is_scalar($data)) {
			throw new AppLoggerDriverException('File driver can save only scalar values');
		}

		for ($i = 0; $i < 5; $i++) {
			if (@file_put_contents($this->filePath, $data . PHP_EOL, FILE_APPEND | LOCK_EX)) {
				return;
			}

			$error = error_get_last()['message'] ?? '';
			if ($error !== 'Failed to open stream: Permission denied') {
				throw new AppLoggerDriverException($error);
			}

			usleep(50 * 1000); // 50ms
		}

		throw new AppLoggerDriverException($error);
	}
}
