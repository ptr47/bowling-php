<?php
require __DIR__.'/vendor/autoload.php';
require_once 'scoreboard.php';
require_once 'helpers.php';

use BowlingPhp\InputHtml;
use BowlingPhp\Lane;
use BowlingPhp\PlayersFactory;

session_start();

$laneId = $_GET['lane_id'] ?? null;
$playerCount = $_POST['player_count'] ?? 1;

$lane = $laneId ? loadLaneById($laneId) : null;

if (!$lane) {
    $players = new PlayersFactory()->createPlayers($playerCount);
    $lane = new Lane($players);
    $laneId = $lane->getId();
    try {
        saveLaneToFile($lane);
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }

    header("Location: game.php?lane_id={$laneId}");
    exit;
}

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
$winners = getWinnersString();

render(
    scoreboardTable: $scoreboardTable,
    playerIdx:       $currentFrame['playerIdx'],
    frameIdx:        $currentFrame['frameIdx'],
    isGameOver:      $isGameOver,
    laneId:          $laneId,
    winners:         $winners,
    errorMessage:    $errorMessage
);
