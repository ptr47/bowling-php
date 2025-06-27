<?php
require_once "Game.php";

const TEXT_RED = "\033[0;31m";
const TEXT_RESET = "\033[0m";
const TEXT_BOLD = "\033[1m";
abstract class Output
{
    abstract public function drawScoreboard(array $scoreboard): void;
    abstract public function showCurrentFrame(int $frameIdx): void;

    abstract public function showCurrentScore(int $score): void;
    protected function formatScoreboard(array $scoreboard): string
    {
        $formattedScoreboard = "";
        $formattedScoreboard .= str_repeat("+---", count($scoreboard) - 1) . "+" . PHP_EOL;
        foreach ($scoreboard as $key => $score) {
            if (is_int($key)) {
                $formattedScoreboard .= "|" . str_pad($score, 3);
            }
        }
        $formattedScoreboard .= "|" . PHP_EOL;
        $formattedScoreboard .= str_repeat("+---", count($scoreboard) - 1) . "+" . PHP_EOL;
        $formattedScoreboard .= "Final score: " . $scoreboard['total'] . PHP_EOL;
        return $formattedScoreboard;
    }

    public static function showError(string $message): void
    {
        fwrite(STDERR, TEXT_RED . $message . TEXT_RESET . PHP_EOL);
    }
}