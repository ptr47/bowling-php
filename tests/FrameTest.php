<?php
namespace BowlingPhp\Tests;

use BowlingPhp\Frame;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class FrameTest extends TestCase
{
    #[Test]
    #[DataProvider("frameRollsProvider")]
    public function addRollThrowsExceptionWhenGivenTooManyPins(array $rolls): void
    {
        $this->expectException(InvalidArgumentException::class);

        $frame = new Frame(9, 10);
        $frame->addRoll(9);
        $frame->addRoll(9);
        foreach ($rolls as $roll) {
            $frame->addRoll($roll);
        }
    }
    public static function frameRollsProvider(): array
    {
        return [
            [[1, 10]],
            [[1, 1, 10]]
        ];
    }
}