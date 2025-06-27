<?php
namespace BowlingPhp\Tests;

use BowlingPhp\Game;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class GameTest extends TestCase
{
    #[Test]
    #[DataProvider("rollValuesWithScoreProvider")]
    public function getScoreReturnsValueForRolls(string $testName, array $rolls, int $expected): void
    {
        $game = new Game();

        foreach ($rolls as $rollValue) {
            $game->roll($rollValue);
        }
        $score = $game->getScore();

        $this->assertEquals($expected, $score, "$testName should return $expected");
    }
    public static function rollValuesWithScoreProvider(): array
    {
        return [
            ['all zeros', [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], 0],
            ['all ones', [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1], 20],
            ['one spare', [5, 5, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], 16],
            ['one strike', [10, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], 16],
            ['all strikes', [10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10], 300],
            ['all spares', [5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5], 150],
            ['random gameplay', [7, 1, 6, 3, 7, 0, 10, 5, 1, 5, 4, 7, 2, 2, 4, 2, 5, 3, 7, 5], 92],
        ];
    }
    #[Test]
    #[DataProvider("rollInvalidValuesProvider")]
    public function rollThrowsExceptionWhenGivenInvalidPinAmount(int $invalidRoll): void
    {
        $this->expectException(InvalidArgumentException::class);

        $game = new Game();
        $game->roll($invalidRoll);

    }
    public static function rollInvalidValuesProvider(): array
    {
        return [
            [-1],
            [11],
        ];
    }
}