<?php
require_once 'Frame.php';
require_once 'Roll.php';
class Game
{

    /**
     * @var array<Frame>
     */
    private array $frames;
    private int $currentFrameIdx = 0;

    private const int FRAMES_AMOUNT = 10;

    public function __construct()
    {
        for ($i = 0; $i < Game::FRAMES_AMOUNT - 1; $i++) {
            $this->frames[] = new Frame();
        }
        $this->frames[] = new Frame(true);
    }


    public function isGameOver(): bool
    {
        return $this->currentFrameIdx === count($this->frames);
    }

    /**
     * @return bool True if roll was successful
     */
    public function roll($pins): bool
    {
        
        if (!Roll::isValidRoll($pins)) {
            Output::showError("Invalid roll.");
            return false;
        }

        $currentFrame = $this->frames[$this->currentFrameIdx];
        if ($currentFrame === null) {
            Output::showError("");
        }
        $rollOutput = $currentFrame->addRoll($pins);
        if ($rollOutput === null) {
            return false;
        }

        $lastFrame = $this->getLastFrame();
        if ($rollOutput) {
            if ($lastFrame and $lastFrame->isStrike()) {
                $this->addBonusPointsToSecondLastFrame($currentFrame);
                $lastFrame->addBonusPoints($currentFrame->rolls[0]);
                # if current frame is strike then rolls[1] is null
                $lastFrame->addBonusPoints($currentFrame->rolls[1] ?? 0);
            } elseif ($lastFrame and $lastFrame->isSpare()) {
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

    public function getScoreboard(): array
    {
        $scoreboard = [];
        foreach($this->frames as $iter => $frame)
        {
            $scoreboard[$iter+1] = $frame->getScore();
        }
        $scoreboard['total'] = $this->getScore();
        return $scoreboard;
    }

    public function getFrameCount(): int
    {
        return count($this->frames);
    }

    public function getCurrentFrameIdx(): int
    {
        return $this->currentFrameIdx + 1;
    }

    private function getCurrentFrame(): ?Frame
    {
        return $this->frames[$this->currentFrameIdx - 1] ?? null;
    }

    private function getLastFrame(): ?Frame
    {
        if ($this->currentFrameIdx > 0) {
            return $this->frames[$this->currentFrameIdx - 1];
        }

        return null;
    }
}