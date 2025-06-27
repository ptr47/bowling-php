<?php
require_once "Game.php";

const TEXT_RED = "\033[0;31m";
const TEXT_RESET = "\033[0m";
const TEXT_BOLD = "\033[1m";
abstract class Output
{
    abstract public function write(string $text): void;
    abstract public function generateScoreboard(array $scoreboardData): void;
    public static function showError(string $message): void
    {
        fwrite(STDERR, TEXT_RED . $message . TEXT_RESET . PHP_EOL);
    }
}