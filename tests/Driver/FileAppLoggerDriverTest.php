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
		$file = $this->createTempFile();

		try {
			$driver = new FileAppLoggerDriver($file, 0);
			$driver->save('log1');
			$driver->save('log2');

			$data = file_get_contents($file);
		} finally {
			unlink($file);
		}

		$this->assertSame('log1' . PHP_EOL . 'log2' . PHP_EOL, $data);
	}

	public function testPermissionDeniedError(): void
	{
		$fileName = $this->createTempFile();
		// disallow writing
		chmod($fileName, 0444);

		$this->expectException(AppLoggerDriverException::class);
		$this->expectExceptionMessage('Failed to open stream: Permission denied');

		$driver = $this->getMockBuilder(FileAppLoggerDriver::class)
			->setConstructorArgs([
				'filePath' => $fileName,
				'logLengthLimit' => 0
			])
			->onlyMethods(['usleep'])
			->getMock();

		$driver->expects($this->exactly(5))
			->method('usleep');

		try {
			$driver->save('log1');
		} finally {
			unlink($fileName);
		}
	}

	public function testSaveWithLogRowLimit(): void
	{
		$file = $this->createTempFile();

		try {
			$driver = new FileAppLoggerDriver($file, 1);
			$driver->save('abcd');

			$this->fail('Log row length limit does not took effect.');
		} catch (AppLoggerDriverException $e) {
			$this->assertSame('File driver log row length "4" is bigger that limit "1"', $e->getMessage());
		} finally {
			unlink($file);
		}
	}


	private function createTempFile(): string
	{
		/** @var non-empty-string|false $file */
		$file = tempnam(sys_get_temp_dir(), 'app-logger');
		if ($file === false) {
			$this->fail(error_get_last()['message'] ?? 'can not create temp file');
		}

		return $file;
	}
}
