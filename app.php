<?php
require __DIR__ . '/vendor/autoload.php';

use BowlingPhp\IOHandlersFactory;
use BowlingPhp\Lane;
use BowlingPhp\PlayersFactory;

$shortOptions = "i:o:p:";
$longOptions = [
    "input:",
    "output:",
    "players:",
];

$args = getopt($shortOptions, $longOptions);
$ioHandlersFactory = new IOHandlersFactory($args);
$input = $ioHandlersFactory->getInputHandler();
$output = $ioHandlersFactory->getOutputHandler();
$playerCount = $ioHandlersFactory->getPlayerCount();

$players = new PlayersFactory()->createPlayers($playerCount);
$lane = new Lane($players);

while (!$lane->isGameOver()) {
    $output->writeCurrentFrame($lane->getCurrentFrame());
    $pins = $input->getPinAmount();
    try {
        $lane->roll($pins);
        $output->write($lane->getScoreString());
    } catch (Exception $e) {
        $output->writeError($e->getMessage());
    }
}

$output->generateScoreboard($lane->getScoreboard());