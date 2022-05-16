<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Tests\Sentry;

use Nubium\AppLogger\Identifier\RandomIntAppLoggerIdentifier;
use Nubium\AppLogger\Sentry\CommonAppLoggerSentryContext;
use PHPUnit\Framework\TestCase;

class CommonAppLoggerSentryContextTest extends TestCase
{
	public function testToArray(): void
	{
		$identifier = new RandomIntAppLoggerIdentifier();

		$context = (new CommonAppLoggerSentryContext($identifier))->toArray();

		$this->assertSame(['requestId' => $identifier->toString()], $context);
	}
}

