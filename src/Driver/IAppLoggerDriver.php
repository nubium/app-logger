<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Driver;

interface IAppLoggerDriver
{
	/**
	 * @throws AppLoggerDriverException
	 */
	public function save(mixed $data): void;
}
