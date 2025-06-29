<?php
namespace BowlingPhp\Tests;

use BowlingPhp\Game;
use BowlingPhp\Player;
use BowlingPhp\Lane;
use BowlingPhp\PlayersFactory;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class LaneTest extends TestCase
{
    #[Test]
    public function changePlayerAfterFrameIsFinished(): void
    {
        $players = new PlayersFactory()->createPlayers(2);
        $lane = new Lane($players);

        $initialPlayerIdx = $lane->currentPlayerIdx;
        $lane->roll(1);
        $lane->roll(1);

        $this->assertNotEquals($initialPlayerIdx, $lane->currentPlayerIdx);
    }
    #[Test]
    public function gameCanBeStartedWithAtLeastOnePlayer(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Lane([]);
    }
    #[Test]
    public function allPlayersMustBeOfTypePlayer(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Lane(["player"]);
    }
    #[Test]
    #[DataProvider("gameOverValueProvider")]
    public function matchIsOverWhenAllPlayersFinished(bool $isGameOver): void
    {
        // Setup game
        $game = $this->createStub(Game::class);
        $game->method("isGameOver")->willReturn($isGameOver);
        // Setup game factory
        $playerStub = $this->createStub(Player::class);
        $playerStub->method("getGame")->willReturn($game);

        $lane = new Lane([$playerStub, $playerStub]);

        $this->assertEquals($isGameOver, $lane->isGameOver());
    }
    public static function gameOverValueProvider(): array
    {
        return [
            [true],
            [false],
        ];
    }
}