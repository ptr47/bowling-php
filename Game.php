<?php
require_once 'Frame.php';
const TEXT_RED = "\033[0;31m";
const TEXT_RESET = "\033[0m";
const TEXT_BOLD = "\033[1m";
class Game
{

    const int MAX_PINS = 10;

    /**
     * @var array<Frame>
     */
    private array $frames;
    private int $currentFrameIdx = 0;

    public function __construct(int $framesAmount)
    {
        for ($i = 0; $i < $framesAmount - 1; $i++) {
            $this->frames[] = new Frame();
        }
        $this->frames[] = new Frame(true);
    }

    static public function isValidRoll(int $pins): bool
    {
        return ($pins >= 0) and ($pins <= self::MAX_PINS);
    }

    public function isGameOver(): bool
    {
        return $this->currentFrameIdx === count($this->frames);
    }

    /**
     * @return bool True if roll was successful
     */
    public function roll(int $pins): bool
    {

        if (!self::isValidRoll($pins)) {
            echo TEXT_RED."Incorrect pin amount: ".$pins.TEXT_RESET.PHP_EOL;

            return false;
        }

        $currentFrame = $this->frames[$this->currentFrameIdx];
        $rollOutput = $currentFrame->addRoll($pins);
        if (is_null($rollOutput)) {
            return false;
        }

        $lastFrame = $this->getLastFrame();
        if ($rollOutput) {
            if (!is_null($lastFrame) and $lastFrame->isStrike()) {
                $this->addBonusPointsToSecondLastFrame($currentFrame);
                $lastFrame->addBonusPoints($currentFrame->rolls[0]);
                # if current frame is strike then rolls[1] is null
                $lastFrame->addBonusPoints($currentFrame->rolls[1] ?? 0);
            } elseif (!is_null($lastFrame) and $lastFrame->isSpare()) {
                $lastFrame->addBonusPoints($currentFrame->rolls[0]);
            }
            $this->currentFrameIdx++;
        }

        return true;
    }

    private function addBonusPointsToSecondLastFrame(Frame $currentFrame): void
    {
        if ($this->currentFrameIdx > 1 and $this->frames[$this->currentFrameIdx - 2]->isStrike()) {
            $this->frames[$this->currentFrameIdx - 2]->addBonusPoints($currentFrame->rolls[0]);
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
        echo str_repeat("+---", count($this->frames))."+".PHP_EOL;
        foreach ($this->frames as $frame) {
            echo "|".str_pad($frame->getScore(), 3);
        }
        echo "|".PHP_EOL;
        echo str_repeat("+---", count($this->frames))."+".PHP_EOL;
        echo TEXT_BOLD."Final score: ".$this->getScore().TEXT_RESET.PHP_EOL;
    }

    public function getCurrentFrameIdx(): int
    {
        return $this->currentFrameIdx;
    }

    private function getLastFrame(): ?Frame
    {
        if ($this->currentFrameIdx > 0) {
            return $this->frames[$this->currentFrameIdx - 1];
        }

        return null;
    }
}