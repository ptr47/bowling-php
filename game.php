<?php
require __DIR__ . '/vendor/autoload.php';
require_once 'scoreboard.php';
require_once 'helpers.php';

use BowlingPhp\InputHtml;
use BowlingPhp\Lane;
use BowlingPhp\PlayersFactory;

session_start();



$laneIdFromGet = $_GET['lane_id'] ?? "";
$playerCount = $_POST['player_count'] ?? 1;

$lane = loadLaneById($laneIdFromGet) ?? $_SESSION['bowling_lane'] ?? null;

if (!$lane) {
    $players = new PlayersFactory()->createPlayers($playerCount);
    $lane = new Lane($players);
    try {
        saveLaneToFile($lane);
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}

$laneId = $lane->getId();
$_SESSION['bowling_lane'] = $lane;

$input = new InputHtml();
$errorMessage = null;

if (isset($_POST['pins'])) {
    $pins = $input->getPinAmount();
    try {
        $lane->roll($pins);
        saveLaneToFile($lane);
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}

$currentFrame = $lane->getCurrentFrame();
$isGameOver = $lane->isGameOver();
$scoreboardTable = generateHtmlTable($lane->getScoreboard());
$winners = getWinnersString($lane->getWinners());

render(
    scoreboardTable: $scoreboardTable,
    playerIdx: $currentFrame['playerIdx'],
    frameIdx: $currentFrame['frameIdx'],
    isGameOver: $isGameOver,
    laneId: $laneId,
    winners: $winners,
    errorMessage: $errorMessage
);
