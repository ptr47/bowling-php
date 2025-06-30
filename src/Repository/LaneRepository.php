<?php
namespace App\Repository;

use App\Domain\Lane;
use App\Kernel;
use App\Service\PlayersFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LaneRepository
{
    private string $dataDir;
    public function __construct(string $dataDir)
    {
        $this->dataDir = "$dataDir/var/saves/";

        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }
    }

    public function createNew(int $playerCount): Lane
    {
        $players = new PlayersFactory()->createPlayers($playerCount);
        $lane = new Lane($players);
        $this->save($lane);
        return $lane;
    }
    public function getById(string $id): Lane
    {
        $file = $this->getLaneFilename($id);
        if (!file_exists($file)) {
            throw new NotFoundHttpException("The lane with ID $id was not found.");
        }
        $lane = unserialize(file_get_contents($file));
        return $lane;
    }
    public function save(Lane $lane): void
    {
        $data = serialize($lane);
        $filename = $this->getLaneFilename($lane->getId());
        echo $filename;
        file_put_contents($filename, $data);
    }
    private function getLaneFilename(string $id): string
    {
        return "{$this->dataDir}$id.lane";
    }
}