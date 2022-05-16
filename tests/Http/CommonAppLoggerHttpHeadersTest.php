<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Tests\Http;

use Nubium\AppLogger\Http\CommonAppLoggerHttpHeaders;
use Nubium\AppLogger\Identifier\RandomIntAppLoggerIdentifier;
use PHPUnit\Framework\TestCase;

class CommonAppLoggerHttpHeadersTest extends TestCase
{
	public function testToArray(): void
	{
		$identifier = new RandomIntAppLoggerIdentifier();

		$context = (new CommonAppLoggerHttpHeaders('app', $identifier))->toArray();

		$this->assertSame([
			'X-Request-Id' => $identifier->toString(),
			'X-App-Name' => 'app',
		], $context);
	}
}

