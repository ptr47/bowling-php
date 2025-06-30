<?php
namespace App\Domain;

use InvalidArgumentException;

class Frame
{
    /**
     * @var array<int>
     */
    private(set) array $rolls = [];
    private(set) int $number;
    private bool $isLast;
    private int $bonusPoints = 0;
    private(set) bool $isFinished = false;
    public bool $isBonusAdded = false;
    public function __construct(int $number, int $framesAmount)
    {
        $this->number = $number;
        $this->isLast = $number === $framesAmount - 1;
    }

    public function addRoll(int $pins): void
    {
        $isLastFrameWithBonus = $this->isLast && ($this->isStrike() or $this->isSpare());
        if (
            !$isLastFrameWithBonus and
            isset($this->rolls[0]) and
            ($this->rolls[0] + $pins > Roll::MAX_PINS)
        ) {
            throw new InvalidArgumentException("Too many pins.");
        }

        $this->rolls[] = $pins;
        if ($this->isLast and ($this->isStrike() or $this->isSpare())) {
            $this->isFinished = isset($this->rolls[2]); # if there were 3 rolls, returns true
        } elseif (count($this->rolls) > 1 or $this->isStrike()) {
            $this->isFinished = true;
        }
    }

    public function addBonusPoints(int $points): void
    {
        $this->bonusPoints += $points;
        $this->isBonusAdded = true;
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