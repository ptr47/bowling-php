<?php
require_once 'Input.php';

class InputFile extends Input
{
    private string $filePath;
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
    public function getPinAmount(): int
    {
        static $file = null;
        static $eofReached = false;

        if ($eofReached) {
            return 0;
        }
        if ($file === null) {
            $file = fopen($this->filePath, "r");
        }
        $pins = 0;
        if (!feof($file)) {
            $line = fgets($file);
            $pins = intval($line);
        } else {
            fclose($file);
            $eofReached = true;
            return 0;
        }

        return $pins;
    }
}