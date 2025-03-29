<?php
require_once 'Game.php';


class Frame {
    /**
     * @var array<int>
     */
    public array $rolls = [];
    private int $bonusPoints = 0;

    public function addRoll(int $pins): void
    {
        if (Game::isValidRoll($pins)) {
            $this->rolls[] = $pins;
        }
    }

    public function isStrike(): bool
    {
        return (count($this->rolls) === 1) and $this->isSpare();
    }

    public function isSpare(): bool
    {
        return array_sum($this->rolls) === 10;
    }

    public function getScore(): int
    {
        return array_sum($this->rolls) + $this->bonusPoints;
    }
}