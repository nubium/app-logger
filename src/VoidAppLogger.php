<?php
declare(strict_types=1);

namespace Nubium\AppLogger;

class VoidAppLogger implements IAppLogger
{
	/**
	 * @inheritDoc
	 */
	public function collect(IAppLoggerField|string $key, mixed $value): void {}

	/**
	 * @inheritDoc
	 */
	public function store(IAppLoggerField|string $key, mixed $value): void {}


	/**
	 * @inheritDoc
	 */
	public function bulkCollect(array $data): void {}

	/**
	 * @inheritDoc
	 */
	public function bulkStore(array $data): void {}
}
