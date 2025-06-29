<?php

use BowlingPhp\Lane;

const DATA_DIR = __DIR__.'/data/';

function getWinnersString(): string
{
    $lane = $_SESSION['bowling_lane'];
    $res = "";
    $winners = $lane->getWinners();
    foreach ($winners as $winner) {
        $res .= "$winner ";
    }
    $res .= "wins!";

    return $res;
}

/**
 * @throws Exception
 */
function saveLaneToFile(Lane $lane): void
{
    $data = serialize($lane->getScoreboard());
    $filename = getLaneFilename($lane->getId());
    file_put_contents($filename, $data);
}

function getLaneFilename(string $id): string
{
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }

    return DATA_DIR.$id.'.json';
}

function loadLaneById(string $id): ?Lane
{
    $file = getLaneFilename($id);
    if (!file_exists($file)) {
        return null;
    }
    return unserialize(file_get_contents($file));
}

function getActiveGames(): array
{
    $games = [];
    if (!is_dir(DATA_DIR)) {
        return $games;
    }

    foreach (glob(DATA_DIR."*.json") as $file) {
        $id = basename($file, '.json');
        $lane = loadLaneById($id);
        if ($lane && !$lane->isGameOver()) {
            $games[] = $id;
        }
    }

    return $games;
}

function render($scoreboardTable, $playerIdx, $frameIdx, $isGameOver, $laneId, $winners = "", $errorMessage = null): void
{
    $errorBlock = $errorMessage ? "<div class=\"error-message\">$errorMessage</div>" : "";

    if (!$isGameOver) {
        $mainBlock = '<div class="game-info">

                <p>Now playing: Player '.$playerIdx.' - Frame '.$frameIdx.'</p>
                '.$scoreboardTable.'
            </div>

            <form action="game.php" method="post">
                <label for="pins">Pins knocked down:</label>
                <input type="number" id="pins" name="pins" min="0" max="10" required>
                <button type="submit">Roll!</button>
            </form>
            '.$errorBlock;
    } else {
        $mainBlock = '<div class="game-over-message">
                <h2>Game Over!</h2>
                <h3>'.$winners.'</h3>
                <p>Scoreboard:</p>
                '.$scoreboardTable.'
                <button onclick="window.location.href=\'index.php?reset=true\'">Play
                    Again</button>
            </div>';
    }
    echo '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bowling Game</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main>
        <h1>Bowling Game - Lane '.$laneId.'</h1>

        '.$mainBlock.'
    </main>
</body>

</html>';
}
