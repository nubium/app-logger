<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Tests;

use JsonException;
use Nubium\AppLogger\AppLogger;
use Nubium\AppLogger\Driver\AppLoggerDriverException;
use Nubium\AppLogger\Driver\IAppLoggerDriver;
use Nubium\AppLogger\Formatter\AppLoggerFormatterException;
use Nubium\AppLogger\Formatter\IAppLoggerFormatter;
use Nubium\AppLogger\IAppLoggerField;
use Nubium\AppLogger\Identifier\IAppLoggerIdentifier;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AppLoggerTest extends TestCase
{
	public function testCollect(): void
	{
		$logger = new TestAppLogger(
			'app',
			$this->createPsrLoggerMock(),
			$this->createDriverMock(),
			$this->createFormatterMock(),
			$this->createIdentifierMock(),
		);

		$logger->collect('log1', 'data1');
		$logger->collect('log2', 1234);
		$logger->collect('log3', 1.2);
		$logger->collect($this->createFieldName('log4'), [1,2,3,4]);
		$logger->collect($this->createFieldName('log5'), ['a' => 4]);

		$logger->bulkCollect([
			'log6' => true,
			'log7' => false,
		]);

		$this->assertSame([
			'log1' => 'data1',
			'log2' => 1234,
			'log3' => 1.2,
			'log4' => [1,2,3,4],
			'log5' => ['a' => 4],
			'log6' => true,
			'log7' => false,
		], $logger->getData());
	}

	/**
	 * @throws JsonException
	 */
	public function testStore(): void
	{
		$key = 'log';
		$data = 'log_data';
		$json = json_encode([$key => $data], flags: JSON_THROW_ON_ERROR);

		$driver = $this->createDriverMock();
		$driver->expects($this->once())->method('save')->with($json);
		$formatter = $this->createFormatterMock();
		$formatter->expects($this->once())->method('format')->willReturn($json);

		(new AppLogger(
			'app',
			$this->createPsrLoggerMock(),
			$driver,
			$formatter,
			$this->createIdentifierMock(),
		))->store($key, $data);
	}

	/**
	 * @throws JsonException
	 */
	public function testBulkStore(): void
	{
		$data = [
			'log1' => 'data1',
			'log2' => 'data2',
		];
		$json = json_encode($data, flags: JSON_THROW_ON_ERROR);

		$driver = $this->createDriverMock();
		$driver->expects($this->once())->method('save')->with($json);
		$formatter = $this->createFormatterMock();
		$formatter->expects($this->once())->method('format')->willReturn($json);

		(new AppLogger(
			'app',
			$this->createPsrLoggerMock(),
			$driver,
			$formatter,
			$this->createIdentifierMock(),
		))->bulkStore($data);
	}

	/**
	 * @param class-string $exceptionClass
	 * @param callable(): IAppLoggerDriver $driverFactory
	 * @param callable(): IAppLoggerFormatter $formatterFactory
	 *
	 * @dataProvider dpStoreError
	 */
	public function testStoreError(string $exceptionClass, callable $driverFactory, callable $formatterFactory): void
	{
		$psrLogger = $this->createPsrLoggerMock();
		$psrLogger->expects($this->once())
			->method('error')
			->with(
				'error',
				$this->callback(fn(array $context)
					=> array_key_exists('exception', $context)
					&& $context['exception'] instanceof $exceptionClass)
			);

		(new AppLogger(
			'app',
			$psrLogger,
			$driverFactory(),
			$formatterFactory(),
			$this->createIdentifierMock(),
		))->store('a', 'b');
	}

	/**
	 * @return array<string, (class-string|(callable(): IAppLoggerDriver)|(callable(): IAppLoggerFormatter))[]>
	 */
	public function dpStoreError(): array
	{
		return [
			'driver' => [
				AppLoggerDriverException::class,
				function () {
					$mock = $this->createDriverMock();
					$mock->method('save')->willThrowException(new AppLoggerDriverException('error'));

					return $mock;
				},
				fn() => $this->createFormatterMock(),
			],
			'formatter' => [
				AppLoggerFormatterException::class,
				fn() => $this->createDriverMock(),
				function () {
					$mock = $this->createFormatterMock();
					$mock->method('format')->willThrowException(new AppLoggerFormatterException('error'));

					return $mock;
				},
			]
		];
	}


	private function createPsrLoggerMock(): MockObject&LoggerInterface
	{
		return $this->createMock(LoggerInterface::class);
	}

	private function createDriverMock(): MockObject&IAppLoggerDriver
	{
		return $this->createMock(IAppLoggerDriver::class);
	}

	private function createFormatterMock(): MockObject&IAppLoggerFormatter
	{
		return $this->createMock(IAppLoggerFormatter::class);
	}

	private function createIdentifierMock(): MockObject&IAppLoggerIdentifier
	{
		return $this->createMock(IAppLoggerIdentifier::class);
	}

	private function createFieldName(string $key): IAppLoggerField
	{
		return new class($key) implements IAppLoggerField {
			public function __construct(private string $key) {}


			public function toString(): string
			{
				return $this->key;
			}
		};
	}
}
