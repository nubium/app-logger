<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Driver;

class VoidAppLoggerDriver implements IAppLoggerDriver
{
	/**
	 * @inheritDoc
	 */
	public function save(mixed $data): void {}
}
