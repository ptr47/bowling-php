<?php
namespace App\Domain;


class Player
{
    public string $name;
    private Game $game;
    public function __construct($name, $game = new Game())
    {
        $this->name = $name;
        $this->game = $game;
    }
    public function getScore(): int
    {
        return $this->game->getScore();
    }
    public function getScoreboard(): array
    {
        return $this->game->getScoreboard();
    }
    public function getGame(): Game
    {
        return $this->game;
    }
}