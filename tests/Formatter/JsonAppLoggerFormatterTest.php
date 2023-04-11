<?php
declare(strict_types=1);

namespace Nubium\AppLogger\Tests\Formatter;

use JsonException;
use DateTimeImmutable;
use DateTimeInterface;
use Nubium\AppLogger\Formatter\AppLoggerFormatterException;
use Nubium\AppLogger\Formatter\JsonAppLoggerFormatter;
use Nubium\AppLogger\Identifier\RandomIntAppLoggerIdentifier;
use PHPUnit\Framework\TestCase;

class JsonAppLoggerFormatterTest extends TestCase
{
	/**
	 * @throws AppLoggerFormatterException
	 * @throws JsonException
	 */
	public function testFormat(): void
	{
		$identifier = new RandomIntAppLoggerIdentifier();
		$formatter = new JsonAppLoggerFormatter();
		$now = new DateTimeImmutable();

		$data = [
			'a' => false,
			'b' => 1,
			'c' => 1.1,
			'd' => 'string',
			'e' => [1, 2, 3, 4],
			'f' => ['a' => 1]
		];

		$json = json_decode($formatter->format('app', $now, $identifier, $data), true, flags: JSON_THROW_ON_ERROR);

		$this->assertSame([
			'a' => false,
			'b' => 1,
			'c' => 1.1,
			'd' => 'string',
			'e' => [1, 2, 3, 4],
			'f' => ['a' => 1],
			'date_time' => $now->format(DateTimeInterface::RFC3339),
			'request_id' => $identifier->toString(),
			'app_name' => 'app',
		], $json);
	}

    /**
     * @throws AppLoggerFormatterException
     * @throws JsonException
     */
    public function testInvalidUtf8Character(): void
    {
        $identifier = new RandomIntAppLoggerIdentifier();
        $formatter = new JsonAppLoggerFormatter();
        $now = new DateTimeImmutable();

        $data = [
            // invalid utf-8 character
            'x' => chr(193)
        ];

        $json = json_decode($formatter->format('app', $now, $identifier, $data), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame([
            'x' => "\u{FFFD}",
            'date_time' => $now->format(DateTimeInterface::RFC3339),
            'request_id' => $identifier->toString(),
            'app_name' => 'app',
        ], $json);
    }
}
