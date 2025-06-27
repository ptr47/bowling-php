<?php
require_once 'Frame.php';
require_once 'Roll.php';
require_once 'Player.php';

class Game
{
    private int $currentFrameIdx = 0;
    public const int FRAMES_AMOUNT = 3;
    /**
     * @var Player[]
     */
    private array $players = [];
    private int $currentPlayerIdx = 0;

    public function __construct(int $playerCount)
    {
        for ($i = 0; $i < $playerCount; $i++) {
            $this->players[] = new Player($i);
        }
    }


    public function isGameOver(): bool
    {
        return $this->currentFrameIdx === Game::FRAMES_AMOUNT;
    }

    /**
     * @return bool True if roll was successful
     */
    public function roll($pins): bool
    {

        if (!Roll::isValidRoll($pins)) {
            Output::showError("Invalid roll.");
            return false;
        }

        $currentPlayer = $this->players[$this->currentPlayerIdx];
        $isPlayerFinished = $currentPlayer->roll($pins, $this->currentFrameIdx);
        if ($isPlayerFinished) {
            $this->currentPlayerIdx++;

            if ($this->currentPlayerIdx === count($this->players)) {
                $this->currentPlayerIdx = 0;
                $this->currentFrameIdx++;
            }
        }

        return true;
    }

    public function getScoreString(): string
    {
        $result = "";
        foreach ($this->players as $iter => $player) {
            $playerNumber = $iter+1;
            $result .= "| Player {$playerNumber}: {$player->getScore()} |";
        }
        return $result;
    }
    private function getScoreboard(): array
    {
        $scoreboard = [];
        foreach ($this->players as $iter => $player) {
            $scoreboard['player ' . $iter + 1] = $player->getScoreboard();
        }
        return $scoreboard;
    }

    public function generateAsciiScoreboard(): string
    {
        $output = '';

        $framesAmount = self::FRAMES_AMOUNT;
        $scoreboard = $this->getScoreboard();

        $playerColWidth = 12;
        $frameColWidths = array_fill(0, $framesAmount, 5);
        $totalColWidth = 7;

        // --- Construct the Separator Line ---
        $separator = '+' . str_repeat('-', $playerColWidth);
        for ($i = 0; $i < $framesAmount; $i++) {
            $separator .= '+' . str_repeat('-', $frameColWidths[$i]);
        }
        $separator .= '+' . str_repeat('-', $totalColWidth) . '+';
        $output .= $separator . PHP_EOL;

        // --- Construct the Header Row ---
        $header = '|' . str_pad('Player', $playerColWidth, ' ', STR_PAD_BOTH);
        for ($i = 0; $i < $framesAmount; $i++) {
            $header .= '|' . str_pad('F' . ($i + 1), $frameColWidths[$i], ' ', STR_PAD_BOTH);
        }
        $header .= '|' . str_pad('Total', $totalColWidth, ' ', STR_PAD_BOTH) . '|';
        $output .= $header . PHP_EOL;

        $output .= $separator . PHP_EOL;

        // --- Construct Data Rows ---
        foreach ($scoreboard as $playerName => $data) {
            $row = '| ' . str_pad($playerName, $playerColWidth - 1, ' ', STR_PAD_RIGHT);
            for ($i = 0; $i < $framesAmount; $i++) {
                $score = $data[$i] ?? '';
                $row .= '|' . str_pad((string) $score, $frameColWidths[$i], ' ', STR_PAD_BOTH);
            }
            $totalScore = $data['total'] ?? '';
            $row .= '|' . str_pad((string) $totalScore, $totalColWidth, ' ', STR_PAD_BOTH) . '|';
            $output .= $row . PHP_EOL;
        }

        // --- Add the bottom separator line ---
        $output .= $separator . PHP_EOL;

        return $output;
    }
    public function getCurrentFrameString(): string
    {
        $player = $this->currentPlayerIdx + 1;
        $frame = $this->currentFrameIdx + 1;
        return "Player {$player} - Frame {$frame}";
    }
}