<?php
require_once 'Game.php';

function getPinAmount(): int
{
    echo "Enter pin amount: ";
    $input = fgets(STDIN);

    return intval($input);
}

$game = new Game(10);


while (!$game->isGameOver()) {
    echo "Frame ", $game->getCurrentFrameIdx() + 1, " ";
    $game->roll(getPinAmount());
    echo TEXT_BOLD."Current score: ".$game->getScore().TEXT_RESET.PHP_EOL;
}

$game->drawScoreboard();