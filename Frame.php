<?php
require_once 'Game.php';

class Frame
{
    /**
     * @var array<int>
     */
    public array $rolls = [];
    private bool $isLast;
    private int $bonusPoints = 0;

    public function __construct(bool $isLast = false)
    {
        $this->isLast = $isLast;
    }

    /**
     * @return bool|null True, if the frame has max rolls and the game can advance
     */
    public function addRoll(int $pins): ?bool
    {
        if (!Game::isValidRoll($pins)) {
            echo TEXT_RED."Invalid roll.".TEXT_RESET.PHP_EOL;

            return null;
        }

        $isLastFrameWithBonus = ($this->isLast and ($this->isStrike() or $this->isSpare()));
        if (!$isLastFrameWithBonus and isset($this->rolls[0]) and ($this->rolls[0] + $pins > Game::MAX_PINS)) {
            echo TEXT_RED."Too many pins.".TEXT_RESET.PHP_EOL;

            return null;
        }

        $this->rolls[] = $pins;
        if ($this->isLast and ($this->isStrike() or $this->isSpare())) {
            return isset($this->rolls[2]); # if the there were 3 rolls, returns true
        } elseif (count($this->rolls) > 1 or $this->isStrike()) {
            return true;
        }

        return false;
    }

    public function addBonusPoints(int $points): void
    {
        if (!Game::isValidRoll($points)) {
            return;
        }
        $this->bonusPoints += $points;
    }

    public function isStrike(): bool
    {
        if (isset($this->rolls[0])) {
            return $this->rolls[0] === Game::MAX_PINS;
        }

        return false;
    }

    public function isSpare(): bool
    {
        return count($this->rolls) >= 2 and array_sum($this->rolls) >= Game::MAX_PINS;
    }

    public function getScore(): int
    {
        return array_sum($this->rolls) + $this->bonusPoints;
    }

}