<?php
require_once 'Input.php';

class InputFile extends Input
{
    private string $filePath;
    private $file;
    private bool $eofReached = false;
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
    public function getPinAmount(): int|null
    {

        if ($this->eofReached) {
            return null;
        }
        if ($this->file === null) {
            $this->file = fopen($this->filePath, "r");
        }
        if ($this->file === false) {
            Output::showError("Error: File {$this->filePath} didn't open correctly");
            return null;
        }
        $pins = 0;
        if (!feof($this->file)) {
            $line = fgets($this->file);
            $pins = intval($line);
        } else {
            fclose($this->file);
            $this->eofReached = true;
            return null;
        }

        return $pins;
    }
    public function resetEof(): void
    {
        $this->eofReached = false;
    }
}