<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Tests;

use Nubium\AppLogger\AppLogger;

class TestAppLogger extends AppLogger
{
	/**
	 * @return array<string, mixed>
	 */
	public function getData(): array
	{
		return $this->data;
	}
}
