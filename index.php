<?php
require __DIR__ . '/vendor/autoload.php';

session_start();

if (isset($_GET['reset']) and $_GET['reset'] == true) {
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
    </main>
</body>

</html>