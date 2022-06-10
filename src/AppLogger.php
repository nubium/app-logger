<?php
declare(strict_types=1);

namespace Nubium\AppLogger;

use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Nubium\AppLogger\Driver\AppLoggerDriverException;
use Nubium\AppLogger\Driver\IAppLoggerDriver;
use Nubium\AppLogger\Formatter\AppLoggerFormatterException;
use Nubium\AppLogger\Formatter\IAppLoggerFormatter;
use Nubium\AppLogger\Identifier\IAppLoggerIdentifier;

class AppLogger implements IAppLogger
{
	/** @var array<string, mixed> */
	protected array $data = [];


	public function __construct(
		private string $appName,
		private LoggerInterface $errorLogger,
		private IAppLoggerDriver $driver,
		private IAppLoggerFormatter $formatter,
		private IAppLoggerIdentifier $identifier
	) {
		register_shutdown_function(fn() => $this->save($this->data));
	}


	/**
	 * @inheritDoc
	 */
	public function collect(IAppLoggerField|string $key, mixed $value): void
	{
		$this->data[is_string($key) ? $key : $key->toString()] = $value;
	}

	/**
	 * @inheritDoc
	 */
	public function store(IAppLoggerField|string $key, mixed $value): void
	{
		$this->save([is_string($key) ? $key : $key->toString() => $value]);
	}


	/**
	 * @inheritDoc
	 */
	public function bulkCollect(array $data): void
	{
		$this->data += $data;
	}

	/**
	 * @inheritDoc
	 */
	public function bulkStore(array $data): void
	{
		$this->save($data);
	}


	/**
	 * @param array<string, mixed> $data
	 */
	private function save(array $data): void
	{
		if (!$data) {
			return;
		}

		$now = new DateTimeImmutable();

		try {
			$log = $this->formatter->format($this->appName, $now, $this->identifier, $data);
			$this->driver->save($log);
		} catch (AppLoggerDriverException|AppLoggerFormatterException $e) {
			$this->errorLogger->error($e->getMessage(), [
				'exception' => $e,
				'appName' => $this->appName,
				'dateTime' => $now,
				'identifier' => $this->identifier->toString(),
				'data' => $data,
			]);
		}
	}
}
