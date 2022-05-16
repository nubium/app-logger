<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Tests\Formatter;

use DateTimeImmutable;
use Nubium\AppLogger\Formatter\TextAppLoggerFormatter;
use Nubium\AppLogger\Identifier\RandomIntAppLoggerIdentifier;
use PHPUnit\Framework\TestCase;

class TextAppLoggerFormatterTest extends TestCase
{
	public function testFormat(): void
	{
		$identifier = new RandomIntAppLoggerIdentifier();
		$formatter = new TextAppLoggerFormatter();
		$now = new DateTimeImmutable();

		$data = [
			'a' => false,
			'b' => 1,
			'c' => 1.1,
			'd' => 'string',
			'e' => [1, 2, 3, 4],
			'f' => ['a' => 1]
		];

		$log = $formatter->format('app', $now, $identifier, $data);

		$this->assertSame(
			'app ' .
			$now->format('Y-d-m H:i:s') .
			' ' .
			$identifier->toString() .
			': a: 0 | b: 1 | c: 1.1 | d: string | e: [1,2,3,4] | f: {"a":1}',
			$log
		);
	}
}
