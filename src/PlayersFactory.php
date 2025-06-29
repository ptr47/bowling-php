<?php
namespace BowlingPhp;

use InvalidArgumentException;

class PlayersFactory
{
    public function createPlayers(int $count): array
    {
        $players = [];
        for ($i = 0; $i < $count; $i++) {
            $players[] = new Player($i, new Game());
        }
        return $players;
    }
    public function createPlayersWithNames(array $names, Game $game = new Game()): array
    {
        $players = [];
        foreach ($names as $name) {
            if (!is_string($name)) {
                throw new InvalidArgumentException("Player names should be of type string.");
            }
            $players[] = new Player($name, $game);
        }
        return $players;
    }
}