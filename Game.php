<?php
require_once 'Frame.php';

class Game {

    const int MAX_PINS = 10;

    /**
     * @var array<Frame>
     */
    private array $frames;
    private int $currentFrameIdx = 0;

    public function __construct(int $framesAmount)
    {
        $this->frames = array_fill(0, $framesAmount, new Frame());
    }

    static public function isValidRoll(int $pins): bool
    {
        return ($pins >= 0) and ($pins <= self::MAX_PINS);
    }

    public function isGameOver(): bool
    {
        $currentFrame = $this->getCurrentFrame();
        $isLastFrame = $this->currentFrameIdx === count($this->frames) - 1;

        return $isLastFrame and count($currentFrame->rolls) === 3;
    }

    public function roll(int $pins): void
    {

        if (!self::isValidRoll($pins)) {
            echo "Incorrect pin amount: ".$pins;

            return;
        }
        $currentFrame = $this->getCurrentFrame();
        $currentFrame->addRoll($pins);

        if (count($currentFrame->rolls) === 2) {
            $this->currentFrameIdx++;
        }
    }

    public function getScore(): int
    {
        $score = 0;
        foreach ($this->frames as $frame) {
            $score += $frame->getScore();
        }

        return $score;
    }

    public function drawScoreboard(): void
    {
        foreach ($this->frames as $frame) {
            echo $frame->getRolls();
        }
    }

    private function getCurrentFrame(): ?Frame
    {
        if (count($this->frames) > $this->currentFrameIdx) {
            return $this->frames[$this->currentFrameIdx];
        }

        return null;
    }
}