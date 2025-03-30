<?php
require_once 'Game.php';

class Frame
{
    /**
     * If isSpare then the third element is bonus points.
     * If isStrike then the second and third element are bonus points
     * @var array<int>
     */
    public array $rolls = [];
    private bool $isLast;

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
            echo "Invalid roll.".PHP_EOL;

            return null;
        }

        if (!$this->isLast or !$this->isStrike()) {
            if (isset($this->rolls[0]) and ($this->rolls[0] + $pins > Game::MAX_PINS)) {
                echo "Invalid roll.".PHP_EOL;

                return null;
            }
        }
        $this->rolls[] = $pins;
        if ($this->isLast and ($this->isStrike() or $this->isSpare())) {
            return isset($this->rolls[2]); # if the there were 3 rolls, returns true
        } elseif (count($this->rolls) > 1 or $this->isStrike()) return true;

        return false;
    }

    public function addBonusPoints(int $points): void
    {
        if (!Game::isValidRoll($points)) {
            return;
        }
        $this->rolls[] = $points;
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
        return count($this->rolls) > 2 and array_sum($this->rolls) >= Game::MAX_PINS;
    }

    public function getScore(): int
    {
        return array_sum($this->rolls);
    }
}