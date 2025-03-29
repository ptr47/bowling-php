<?php
require_once 'Frame.php';

class Game {

    const int MAX_PINS = 10;

    private int $score = 0; # maybe change to frame based score
    /**
     * @var array<Frame>
     */
    private array $frames = [];
    private int $currentFrameIdx = 0;
    private bool $isGameOver = false;

    public function __construct(int $framesAmount) {
        for ($i = 0; $i < $framesAmount; $i++) {
            $this->frames[] = new Frame();
        }
    }

    public function isGameOver(): bool {
        return $this->isGameOver;
    }

    static public function isValidRoll($pins): bool {
        if (is_numeric($pins)) {
            return $pins >= 0 and $pins <= self::MAX_PINS;
        }

        return false;
    }


    public function roll($pins): void {

        if (!self::isValidRoll($pins)) {
            echo 'Incorrect pin amount: '.$pins;

            return;
        }
        $this->getCurrentFrame()->addRoll($pins);
    }

    public function getScore(): int {

        return $this->score;
    }

    public function getCurrentFrame(): ?Frame {
        if (count($this->frames) > $this->currentFrameIdx) {
            return $this->frames[$this->currentFrameIdx];
        }

        return null;
    }

    public function drawScoreboard(): void {
        foreach ($this->frames as $frame) {
            echo $frame->getRolls();
        }
    }

}