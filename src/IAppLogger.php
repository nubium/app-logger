<?php
declare(strict_types=1);

namespace Nubium\AppLogger;

interface IAppLogger
{
	/**
	 * @param array<string, mixed> $data
	 */
	public function store(array $data): void;

	public function collect(IAppLoggerField|string $key, mixed $value): void;
}
