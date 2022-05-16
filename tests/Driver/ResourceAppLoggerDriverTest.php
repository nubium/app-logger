<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Tests\Driver;

use Nubium\AppLogger\Driver\AppLoggerDriverException;
use Nubium\AppLogger\Driver\ResourceAppLoggerDriver;
use PHPUnit\Framework\TestCase;

class ResourceAppLoggerDriverTest extends TestCase
{
	/**
	 * @throws AppLoggerDriverException
	 */
	public function testSave(): void
	{
		$resource = tmpfile();
		if ($resource === false) {
			$this->fail(error_get_last()['message'] ?? 'can not create temp file');
		}

		try {
			$driver = new ResourceAppLoggerDriver($resource);
			$driver->save('log1');
			$driver->save('log2');

			fseek($resource, 0);
			$data = fread($resource, 1024);
		} finally {
			fclose($resource);
		}

		$this->assertSame('log1' . PHP_EOL . 'log2' . PHP_EOL, $data);
	}
}
