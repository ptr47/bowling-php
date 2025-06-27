<?php
require_once 'Frame.php';
require_once 'Roll.php';
require_once 'Player.php';

class Game
{
    private int $currentFrameIdx = 0;
    public const int FRAMES_AMOUNT = 10;
    /**
     * @var Player[]
     */
    private array $players = [];
    private int $currentPlayerIdx = 0;

    public function __construct(int $playerCount)
    {
        for ($i = 0; $i < $playerCount; $i++) {
            $this->players[] = new Player($i);
        }
    }


    public function isGameOver(): bool
    {
        return $this->currentFrameIdx === Game::FRAMES_AMOUNT;
    }

    /**
     * @return bool True if roll was successful
     */
    public function roll($pins): bool
    {
        $pins ??= 0;
        
        if (!Roll::isValidRoll($pins)) {
            Output::showError("Invalid roll.");
            return false;
        }

        $currentPlayer = $this->players[$this->currentPlayerIdx];
        $isPlayerFinished = $currentPlayer->roll($pins, $this->currentFrameIdx);
        if ($isPlayerFinished) {
            $this->currentPlayerIdx++;

            if ($this->currentPlayerIdx === count($this->players)) {
                $this->currentPlayerIdx = 0;
                $this->currentFrameIdx++;
            }
        }

        return true;
    }

    public function getScoreString(): string
    {
        $result = "";
        foreach ($this->players as $iter => $player) {
            $playerNumber = $iter+1;
            $result .= "| Player {$playerNumber}: {$player->getScore()} |";
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

    
    public function getCurrentFrameString(): string
    {
        $player = $this->currentPlayerIdx + 1;
        $frame = $this->currentFrameIdx + 1;
        return "Player {$player} - Frame {$frame}";
    }
}