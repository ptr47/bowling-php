<?php

class Player
{
    public private(set) int $id;
    /**
     * @var Frame[]
     */
    private array $frames = [];
    public function __construct($id)
    {
        $this->id = $id;
    }
    /**
     * @param int $amount amount of pins to roll
     * @param int $frameIndex current frame index
     * @return bool True if this player finished a frame
     */
    public function roll(int $amount, int $frameIndex)
    {
        if (!isset($this->frames[$frameIndex])) {
            $this->frames[$frameIndex] = new Frame($frameIndex);
        }

        $currentFrame = $this->frames[$frameIndex];
        $currentFrame->addRoll($amount);

        $lastFrame = $this->getLastFrame($frameIndex);
        if (!$currentFrame->isFinished) {
            if ($lastFrame and $lastFrame->isSpare()) {
                $lastFrame->addBonusPoints($currentFrame->rolls[0]);
            }
            return false;
        }
        
        if ($lastFrame and $lastFrame->isStrike()) {
            $this->addBonusPointsToSecondLastFrame($frameIndex);
            $lastFrame->addBonusPoints($currentFrame->rolls[0]);
            # if current frame is strike then rolls[1] is null
            $lastFrame->addBonusPoints($currentFrame->rolls[1] ?? 0);
        }

        return true;

    }
    private function addBonusPointsToSecondLastFrame(int $currentFrameIdx): void
    {
        if ($currentFrameIdx > 1 and $this->frames[$currentFrameIdx - 2]->isStrike()) {
            $currentFrame = $this->frames[$currentFrameIdx];
            $this->frames[$currentFrameIdx - 2]->addBonusPoints($currentFrame->rolls[0]);
        }
    }
    private function getLastFrame(int $currentFrameIdx): ?Frame
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
}