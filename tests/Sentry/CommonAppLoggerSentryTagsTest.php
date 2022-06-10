<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Tests\Sentry;

use Nubium\AppLogger\Identifier\RandomIntAppLoggerIdentifier;
use Nubium\AppLogger\Sentry\CommonAppLoggerSentryTags;
use PHPUnit\Framework\TestCase;

class CommonAppLoggerSentryTagsTest extends TestCase
{
	public function testToArray(): void
	{
		$identifier = new RandomIntAppLoggerIdentifier();

		$tags = (new CommonAppLoggerSentryTags($identifier))->toArray();

		$this->assertSame(['requestId' => $identifier->toString()], $tags);
	}
}

