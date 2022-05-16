<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Identifier;

interface IAppLoggerIdentifier
{
	public function toString(): string;
}
