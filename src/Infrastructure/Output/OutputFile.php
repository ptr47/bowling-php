<?php
namespace App\Infrastructure\Output;

class OutputFile extends AbstractOutput
{
    private string $filePath;
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
    public function write(string $text): void
    {
        $file = fopen($this->filePath, 'w');
        fwrite($file, $text);
        fclose($file);
    }
    public function writeError(string $text): void
    {
        $file = fopen($this->filePath, 'w');
        fwrite($file, "ERROR: $text");
        fclose($file);
    }
    public function writeCurrentFrame(array $currentFrameData): void
    {
        $playerIdx = $currentFrameData['playerIdx'];
        $frameIdx = $currentFrameData['frameIdx'];
        $text = "Player $playerIdx - Frame $frameIdx" . PHP_EOL;
        $file = fopen($this->filePath, 'w');
        fwrite($file, $text);
        fclose($file);
    }
    public function generateScoreboard(array $scoreboardData): void
    {
        $jsonScoreboard = json_encode($scoreboardData, JSON_PRETTY_PRINT);
        $file = fopen($this->filePath, 'w');
        fwrite($file, $jsonScoreboard);
        fclose($file);
    }
}
