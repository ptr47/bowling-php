<?php
require_once 'Output.php';

class OutputStdout extends Output
{
    public function showCurrentFrame(int $frameIdx): void
    {
        echo "Frame ", $frameIdx, " ";
    }

    public function showCurrentScore(int $score): void
    {
        echo TEXT_BOLD . "Current score: " . $score . TEXT_RESET . PHP_EOL;
    }
    public function drawScoreboard(array $scoreboard): void
    {
        echo $this->formatScoreboard($scoreboard);
    }
}