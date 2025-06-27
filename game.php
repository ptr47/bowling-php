<?php
require __DIR__ . '/vendor/autoload.php';
require_once 'scoreboard.php';

use BowlingPhp\InputHtml;
use BowlingPhp\Lane;
use BowlingPhp\PlayersFactory;

session_start();

$playerCount = $_POST['player_count'] ?? 1;

if (!isset($_SESSION['bowling_lane'])) {
    $players = new PlayersFactory()->createPlayers($playerCount);
    $_SESSION['bowling_lane'] = new Lane($players);
}

$lane = $_SESSION['bowling_lane'];
$input = new InputHtml();
$errorMessage = null;
if (isset($_POST['pins'])) {
    $pins = $input->getPinAmount();
    try {
        $lane->roll($pins);
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}

$currentFrame = $lane->getCurrentFrame();
$playerIdx = $currentFrame['playerIdx'];
$frameIdx = $currentFrame['frameIdx'];
$isGameOver = $lane->isGameOver();
$scoreboard = $lane->getScoreboard();
$scoreboardTable = generateHtmlTable($scoreboard);
$winners = getWinnersString();

render($scoreboardTable, $playerIdx, $frameIdx, $isGameOver, $winners, $errorMessage);
