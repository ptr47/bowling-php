<?php
require_once 'Game.php';
require_once 'ConsoleArgs.php';
require_once 'OutputFile.php';
require_once 'OutputStdout.php';
require_once 'InputFile.php';
require_once 'InputStdin.php';

$shortOptions = "i:o:p:";
$longOptions = [
    "input:",
    "output:",
    "players:",
];

$args = getopt($shortOptions, $longOptions);
$consoleArgs = new ConsoleArgs($args);
$input = $consoleArgs->getInput();
$output = $consoleArgs->getOutput();
$playerCount = $consoleArgs->getPlayerCount();

$game = new Game($playerCount);

while (!$game->isGameOver()) {
    $output->write($game->getCurrentFrameString());
    $pins = $input->getPinAmount();
    if ($game->roll($pins)) {
        $output->write($game->getScoreString());
    }
}

$output->generateScoreboard($game->getScoreboard());