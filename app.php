<?php
require_once 'Game.php';
require_once 'ConsoleArgs.php';
require_once 'OutputFile.php';
require_once 'OutputStdout.php';
require_once 'InputFile.php';
require_once 'InputStdin.php';


$args = new ConsoleArgs($argv)->getArgs();

$input = isset($args['input']) ? new InputFile($args['input']) : new InputStdin();
$output = isset($args['output']) ? new OutputFile($args['output']) : new OutputStdout();
$playerCount = $args['players'] ?? 1;

$game = new Game($playerCount);

while (!$game->isGameOver()) {
    $output->write($game->getCurrentFrameString());
    $pins = $input->getPinAmount();
    if ($game->roll($pins)) {
        $output->write($game->getScoreString());
    }
}

$output->write($game->generateAsciiScoreboard());