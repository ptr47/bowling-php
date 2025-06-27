<?php
require_once 'Output.php';

class OutputStdout extends Output
{
    public function write(string $text): void
    {
        echo $text . PHP_EOL;
    }
    public function generateScoreboard(array $scoreboard): void
    {
        $asciiScoreboard = $this->generateAsciiScoreboard($scoreboard);
        echo $asciiScoreboard . PHP_EOL;
    }
    private function generateAsciiScoreboard(array $scoreboard): string
    {
        $output = '';

        $framesAmount = Game::FRAMES_AMOUNT;

        $playerColWidth = 12;
        $frameColWidths = array_fill(0, $framesAmount, 5);
        $totalColWidth = 7;

        // --- Construct the Separator Line ---
        $separator = '+' . str_repeat('-', $playerColWidth);
        for ($i = 0; $i < $framesAmount; $i++) {
            $separator .= '+' . str_repeat('-', $frameColWidths[$i]);
        }
        $separator .= '+' . str_repeat('-', $totalColWidth) . '+';
        $output .= $separator . PHP_EOL;

        // --- Construct the Header Row ---
        $header = '|' . str_pad('Player', $playerColWidth, ' ', STR_PAD_BOTH);
        for ($i = 0; $i < $framesAmount; $i++) {
            $header .= '|' . str_pad('F' . ($i + 1), $frameColWidths[$i], ' ', STR_PAD_BOTH);
        }
        $header .= '|' . str_pad('Total', $totalColWidth, ' ', STR_PAD_BOTH) . '|';
        $output .= $header . PHP_EOL;

        $output .= $separator . PHP_EOL;

        // --- Construct Data Rows ---
        foreach ($scoreboard as $playerName => $data) {
            $row = '| ' . str_pad($playerName, $playerColWidth - 1, ' ', STR_PAD_RIGHT);
            for ($i = 0; $i < $framesAmount; $i++) {
                $score = $data[$i] ?? '';
                $row .= '|' . str_pad((string) $score, $frameColWidths[$i], ' ', STR_PAD_BOTH);
            }
            $totalScore = $data['total'] ?? '';
            $row .= '|' . str_pad((string) $totalScore, $totalColWidth, ' ', STR_PAD_BOTH) . '|';
            $output .= $row . PHP_EOL;
        }

        // --- Add the bottom separator line ---
        $output .= $separator . PHP_EOL;

        return $output;
    }
}