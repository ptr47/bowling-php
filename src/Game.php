<?php
namespace BowlingPhp;

use InvalidArgumentException;

class Game
{
    private int $currentFrameIdx = 0;
    /**
     * @var Frame[]
     */
    private array $frames = [];
    public const int FRAMES_AMOUNT = 10;
    public function __construct()
    {
        for ($i = 0; $i < $this::FRAMES_AMOUNT; $i++) {
            $this->frames[$i] = new Frame($i, $this::FRAMES_AMOUNT);
        }
    }

    /**
     * @param int $amount number of pins to roll
     * @return bool True if a frame was finished, false otherwise.
     */
    public function roll(int $amount): bool
    {
        if (!Roll::isValidRoll($amount)) {
            throw new InvalidArgumentException("Invalid pin count: $amount");
        }

        $currentFrame = $this->frames[$this->currentFrameIdx] ?? null;
        if ($currentFrame === null) {
            return false; // or throw exception?
        }
        $currentFrame->addRoll($amount);
        $this->addBonusPoints();

        if ($currentFrame->isFinished) {
            $this->currentFrameIdx++;
            return true;
        }
        return false;
    }
    public function isGameOver(): bool
    {
        return $this->currentFrameIdx === $this::FRAMES_AMOUNT;
    }
    private function addBonusPoints(): void
    {
        $currentFrame = $this->frames[$this->currentFrameIdx];
        $previousFrame = $this->getPreviousFrame($this->currentFrameIdx);
        if (!$currentFrame->isFinished) {
            if ($previousFrame and $previousFrame->isSpare() and !$previousFrame->isBonusAdded) {
                $previousFrame->addBonusPoints($currentFrame->rolls[0]);
            }
            return;
        }
        if ($previousFrame and $previousFrame->isStrike()) {
            $this->addBonusPointsToSecondPreviousFrame();

            $bonusPoints = $currentFrame->rolls[0] + ($currentFrame->rolls[1] ?? 0);
            $previousFrame->addBonusPoints($bonusPoints);
        }
    }
    private function addBonusPointsToSecondPreviousFrame(): void
    {
        if ($this->currentFrameIdx > 1 and $this->frames[$this->currentFrameIdx - 2]->isStrike()) {
            $currentFrame = $this->frames[$this->currentFrameIdx];
            $this->frames[$this->currentFrameIdx - 2]->addBonusPoints($currentFrame->rolls[0]);
        }
    }
    private function getPreviousFrame(int $currentFrameIdx): ?Frame
    {
        if ($currentFrameIdx > 0) {
            return $this->frames[$currentFrameIdx - 1];
        }
        return null;
    }
    public function getScore(): int
    {
        $score = 0;
        foreach ($this->frames as $frame) {
            $score += $frame->getScore();
        }

        return $score;
    }
    public function getScoreboard(): array
    {
        $scoreboard = [];
        foreach ($this->frames as $frame) {
            $scoreboard[$frame->number] = $frame->getScore();
        }
        $scoreboard['total'] = $this->getScore();
        return $scoreboard;
    }
    public function getCurrentFrameIndex(): int
    {
        return $this->currentFrameIdx;
    }
}