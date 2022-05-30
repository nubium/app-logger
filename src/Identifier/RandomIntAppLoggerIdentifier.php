<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Identifier;

use Exception;

class RandomIntAppLoggerIdentifier implements IAppLoggerIdentifier
{
	private string $identifier;


	/**
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->identifier = (string) random_int(100000000, 999999999);
	}


	public function toString(): string
	{
		return $this->identifier;
	}
}
