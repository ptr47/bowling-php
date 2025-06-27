<?php
require_once 'Game.php';
require_once 'Roll.php';

class Frame
{
    /**
     * @var array<int>
     */
    public private(set) array $rolls = [];
    public private(set) int $number;
    private bool $isLast;
    private int $bonusPoints = 0;
    public private(set) bool $isFinished = false;
    public function __construct(int $number)
    {
        $this->number = $number;
        $this->isLast = $number === Game::FRAMES_AMOUNT - 1;
    }

    public function addRoll(int $pins): void
    {
        if (!Roll::isValidRoll($pins)) {
            Output::showError("Invalid roll.");
            return;
        }

        $isLastFrameWithBonus = $this->isLast and ($this->isStrike() or $this->isSpare());
        if (!$isLastFrameWithBonus and isset($this->rolls[0]) and ($this->rolls[0] + $pins > Roll::MAX_PINS)) {
            Output::showError("Too many pins.");
            return;
        }

        $this->rolls[] = $pins;
        if ($this->isLast and ($this->isStrike() or $this->isSpare())) {
            $this->isFinished = isset($this->rolls[2]); # if there were 3 rolls, returns true
        } elseif (count($this->rolls) > 1 or $this->isStrike()) {
            $this->isFinished = true;
        }
        return;
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