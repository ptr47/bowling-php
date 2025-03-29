<?php
require_once 'Game.php';


class Frame
{
    /**
     * @var array<int>
     */
    public array $rolls = [];
    private int $bonusPoints = 0;
    private bool $isLast;

    public function __construct(bool $isLast = false)
    {
        $this->isLast = $isLast;
    }

    /**
     * @return bool True, if the frame has max rolls and the game can advance
     */
    public function addRoll(int $pins): bool
    {
        if (!Game::isValidRoll($pins)) {
            return false;
        }
        $this->rolls[] = $pins;

        if ($this->isStrike() and $this->isLast) return false;
        elseif ($this->isStrike()) return true;
        elseif ($this->isLast and $this->isSpare()) return false;
        elseif (count($this->rolls) > 1) return true;
        else return false;
    }

    public function isLastFrame(): bool
    {
        return $this->isLast;
    }

    public function isStrike(): bool
    {
        return (count($this->rolls) === 1) and $this->isSpare();
    }

    public function isSpare(): bool
    {
        return array_sum($this->rolls) === Game::MAX_PINS;
    }

    public function getScore(): int
    {
        return array_sum($this->rolls) + $this->bonusPoints;
    }
}