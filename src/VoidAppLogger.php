<?php
declare(strict_types=1);

namespace Nubium\AppLogger;

class VoidAppLogger implements IAppLogger
{
	public function store(array $data): void {}

	public function collect(IAppLoggerField|string $key, mixed $value): void {}
}
