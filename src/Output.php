<?php
namespace BowlingPhp;

const TEXT_RED = "\033[0;31m";
const TEXT_RESET = "\033[0m";
const TEXT_BOLD = "\033[1m";
abstract class Output
{
    abstract public function write(string $text): void;
    abstract public function writeError(string $text): void;
    /**
     * @param array $currentFrameData Must have keys 'playerIdx' and 'frameIdx'
     * @return void
     */
    abstract public function writeCurrentFrame(array $currentFrameData): void;
    abstract public function generateScoreboard(array $scoreboardData): void;
}