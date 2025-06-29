<?php
require __DIR__ . '/vendor/autoload.php';

session_start();

if (isset($_GET['reset']) and $_GET['reset']) {
    session_destroy();
    session_start();
}

?>
<!DOCTYPE html>
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

        <h3>Choose the number of players</h3>

        <form action="game.php" method="post">
            <label for="player_count">Player amount:</label>
            <input type="number" id="player_count" name="player_count" min="1" max="5" required>
            <button type="submit">Play!</button>
        </form>

        <h3>Or load an existing game</h3>
        <form action="game.php" method="get">
            <label for="lane_id">Lane ID:</label>
            <input type="text" id="lane_id" name="lane_id" required>
            <button type="submit">Load game</button>
        </form>

    </main>
</body>

</html>