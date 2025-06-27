<?php
require_once 'Output.php';

class OutputFile extends Output
{
    private string $filePath;
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
    public function write(string $text): void
    {
        $file = fopen($this->filePath,'w');
        fwrite($file, $text);
        fclose($file);
    }
}