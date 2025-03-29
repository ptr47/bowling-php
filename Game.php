<?php
require_once 'Frame.php';

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

    public function roll(int $pins): void
    {

        if (!self::isValidRoll($pins)) {
            echo "Incorrect pin amount: ".$pins;

            return;
        }
        $currentFrame = $this->frames[$this->currentFrameIdx];

        if ($currentFrame->addRoll($pins)) {
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
        echo str_repeat("+---", count($this->frames))."+".PHP_EOL;
        foreach ($this->frames as $frame) {
            echo "|".str_pad($frame->getScore(), 3);
        }
        echo "|".PHP_EOL;
        echo str_repeat("+---", count($this->frames))."+".PHP_EOL;
        echo "Final score: ".$this->getScore().PHP_EOL;
    }

    public function getCurrentFrameIdx(): int
    {
        return $this->currentFrameIdx;
    }
}