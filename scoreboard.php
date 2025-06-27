<?php
use BowlingPhp\Game;
function generateHtmlTable(array $scoreboardData): string
{
    if (empty($scoreboardData)) {
        return "<p>No score data available to display.</p>";
    }

    $html = '<table class="bowling-score-table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Player</th>';
    for ($i = 0; $i < Game::FRAMES_AMOUNT; $i++) {
        $html .= '<th>Frame ' . ($i + 1) . '</th>';
    }
    $html .= '<th>Total Score</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    foreach ($scoreboardData as $playerName => $playerScores) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($playerName) . '</td>';
        for ($i = 0; $i < Game::FRAMES_AMOUNT; $i++) {
            $score = $playerScores[strval($i)] ?? '-';
            $html .= '<td>' . htmlspecialchars($score) . '</td>';
        }
        $total = $playerScores['total'] ?? '-';
        $html .= '<td><strong>' . htmlspecialchars($total) . '</strong></td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';

    return $html;
}