<?php

function getWinnersString()
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

function render($scoreboardTable, $playerIdx, $frameIdx, $isGameOver, $winners = "", $errorMessage = null)
{
    $mainBlock = "";
    $errorBlock = $errorMessage ? "<div class=\"error-message\">$errorMessage</div>" : "";

    if (!$isGameOver) {
        $mainBlock = '<div class="game-info">

                <p>Now playing: Player ' . $playerIdx . ' - Frame ' . $frameIdx . '</p>
                ' . $scoreboardTable . '
            </div>

            <form action="game.php" method="post">
                <label for="pins">Pins knocked down:</label>
                <input type="number" id="pins" name="pins" min="0" max="10" required>
                <button type="submit">Roll!</button>
            </form>
            ' . $errorBlock;
    } else {
        $mainBlock = '<div class="game-over-message">
                <h2>Game Over!</h2>
                <h3>' . $winners . '</h3>
                <p>Scoreboard:</p>
                ' . $scoreboardTable . '
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
        <h1>Bowling Game</h1>

        ' . $mainBlock . '
    </main>
</body>

</html>';
}
