<?php
require_once 'Game.php';

enum FrameType {
    case Normal;
    case Strike;
    case Spare;
}

class Frame {
    /**
     * @var array<int>
     */
    private array $rolls = [];

    private FrameType $type = FrameType::Normal;

    public function addRoll(int $score): void {
        if (Game::isValidRoll($score)) {
            $this->rolls[] = $score;
            var_export($this->rolls);
        }
    }

    public function getRolls(): string {
        return implode(',', $this->rolls);
    }
}