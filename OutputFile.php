<?php
require_once 'Output.php';

class OutputFile extends Output
{
    private string $filePath;
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
    public function showCurrentFrame(int $frameIdx): void
    {
    }

    public function showCurrentScore(int $score): void
    {
    }
    public function drawScoreboard(array $scoreboard): void
    {
        $file = fopen($this->filePath, 'w');
        fwrite($file, $this->formatScoreboard($scoreboard));
        fclose($file);
    }
}