<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Http;

interface IAppLoggerHttpHeaders
{
	/**
	 * @return array<string, string>
	 */
	public function toArray(): array;
}
