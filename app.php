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
    $game->roll(getPinAmount());
    echo "Current score: ", $game->getScore(), PHP_EOL;
}

$game->drawScoreboard();