<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Tests\Driver;

use Nubium\AppLogger\Driver\AppLoggerDriverException;
use Nubium\AppLogger\Driver\FileAppLoggerDriver;
use PHPUnit\Framework\TestCase;

class FileAppLoggerDriverTest extends TestCase
{
	/**
	 * @throws AppLoggerDriverException
	 */
	public function testSave(): void
	{
		/** @var non-empty-string|false $file */
		$file = tempnam(sys_get_temp_dir(), 'app-logger');
		if ($file === false) {
			$this->fail(error_get_last()['message'] ?? 'can not create temp file');
		}

		try {
			$driver = new FileAppLoggerDriver($file);
			$driver->save('log1');
			$driver->save('log2');

			$data = file_get_contents($file);
		} finally {
			unlink($file);
		}

		$this->assertSame('log1' . PHP_EOL . 'log2' . PHP_EOL, $data);
	}
}
