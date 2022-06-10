<?php
declare(strict_types=1);

namespace Nubium\AppLogger;

interface IAppLogger
{
	/**
	 * @param IAppLoggerField|string $key key is field name, IAppLoggerField is transformed to string
	 */
	public function collect(IAppLoggerField|string $key, mixed $value): void;

	/**
	 * @param IAppLoggerField|string $key key is field name, IAppLoggerField is transformed to string
	 */
	public function store(IAppLoggerField|string $key, mixed $value): void;


	/**
	 * @param array<string, mixed> $data array key is field name
	 */
	public function bulkCollect(array $data): void;

	/**
	 * @param array<string, mixed> $data array key is field name
	 */
	public function bulkStore(array $data): void;
}
