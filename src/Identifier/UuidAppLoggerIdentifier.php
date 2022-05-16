<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Identifier;

class UuidAppLoggerIdentifier implements IAppLoggerIdentifier
{
	private string $identifier;


	public function __construct()
	{
		$this->identifier = uuid_create();
	}


	public function toString(): string
	{
		return $this->identifier;
	}
}
