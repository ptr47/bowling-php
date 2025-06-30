<?php
namespace App\Infrastructure\Input;

use RuntimeException;

class InputFile extends AbstractInput
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
            throw new RuntimeException("File $this->filePath didn't open correctly.");
        }

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