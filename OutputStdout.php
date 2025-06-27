<?php
require_once 'Output.php';

class OutputStdout extends Output
{
    public function write(string $text): void
    {
        echo $text . PHP_EOL;
    }
}