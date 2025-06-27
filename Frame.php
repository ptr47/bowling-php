<?php
require_once 'Game.php';
require_once 'Roll.php';

class Frame
{
    /**
     * @var array<int>
     */
    public private(set) array $rolls = [];
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
        if (!Roll::isValidRoll($pins)) {
            Output::showError("Invalid roll.");
            return null;
        }

        $isLastFrameWithBonus = $this->isLast and ($this->isStrike() or $this->isSpare());
        if (!$isLastFrameWithBonus and isset($this->rolls[0]) and ($this->rolls[0] + $pins > Roll::MAX_PINS)) {
            Output::showError("Too many pins.");
            return null;
        }

        $this->rolls[] = $pins;
        if ($this->isLast and ($this->isStrike() or $this->isSpare())) {
            return isset($this->rolls[2]); # if there were 3 rolls, returns true
        } elseif (count($this->rolls) > 1 or $this->isStrike()) {
            return true;
        }

        return false;
    }

    public function addBonusPoints(int $points): void
    {
        if (!Roll::isValidRoll($points)) {
            return;
        }
        $this->bonusPoints += $points;
    }

    public function isStrike(): bool
    {
        if (isset($this->rolls[0])) {
            return $this->rolls[0] === Roll::MAX_PINS;
        }

        return false;
    }

    public function isSpare(): bool
    {
        return count($this->rolls) >= 2 and array_sum($this->rolls) >= Roll::MAX_PINS;
    }

    public function getScore(): int
    {
        return array_sum($this->rolls) + $this->bonusPoints;
    }

}