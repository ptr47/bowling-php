<?php
namespace BowlingPhp;

use InvalidArgumentException;

class Lane
{
    /**
     * @var Player[]
     */
    private array $players = [];
    private string $id;
    private(set) int $currentPlayerIdx = 0;

    public function __construct(array $players = [new Player(1)])
    {
        if (count($players) < 1) {
            throw new InvalidArgumentException("Lane must have at least 1 player.");
        }
        if (array_any($players, fn($element) => !$element instanceof Player)) {
            throw new InvalidArgumentException("All players should be of type Player.");
        }
        $this->players = $players;
        $this->id = uniqid();
    }

    public function isGameOver(): bool
    {
        return array_all($this->players, fn($player) => $player->getGame()->isGameOver());
    }

    public function roll(?int $pins): void
    {
        $pins ??= 0;

        $currentPlayer = $this->players[$this->currentPlayerIdx];
        $isFrameFinished = $currentPlayer->getGame()->roll($pins);
        if ($isFrameFinished) {
            $this->incrementPlayers();
        }
    }

    private function incrementPlayers(): void
    {
        $this->currentPlayerIdx++;
        if ($this->currentPlayerIdx === count($this->players)) {
            $this->currentPlayerIdx = 0;
        }
    }
    public function getCurrentScores(): array
    {
        $currentScores = [];
        foreach ($this->players as $iter => $player) {
            $playerNumber = $iter + 1;
            $currentScores[$playerNumber] = $player->getScore();
        }
        return $currentScores;
    }
    public function getScoreString(): string
    {
        $result = "";
        foreach ($this->players as $iter => $player) {
            $playerNumber = $iter + 1;
            $result .= "| Player $playerNumber: {$player->getScore()} |";
        }
        return $result;
    }
    public function getScoreboard(): array
    {
        $scoreboard = [];
        foreach ($this->players as $iter => $player) {
            $scoreboard['player ' . $iter + 1] = $player->getScoreboard();
        }
        return $scoreboard;
    }
    /**
     * @return array{frameIdx: int, playerIdx: int}
     */
    public function getCurrentFrame(): array
    {
        $playerIdx = $this->currentPlayerIdx + 1;
        $frameIdx = $this->players[$this->currentPlayerIdx]->getGame()->getCurrentFrameIndex() + 1;
        return [
            'playerIdx' => $playerIdx,
            'frameIdx' => $frameIdx,
        ];
    }
    public function getWinners(): array
    {
        $winners = [];
        $winningScore = -1;
        $scoreboard = $this->getScoreboard();
        foreach ($scoreboard as $player => $scores) {
            $currentScore = $scores['total'] ?? 0;
            if ($currentScore > $winningScore) {
                $winningScore = $currentScore;
                $winners = [$player];
            } elseif ($currentScore == $winningScore) {
                $winners[] = $player;
            }
        }
        return $winners;
    }

    public function getId(): string
    {
        return $this->id;
    }
}