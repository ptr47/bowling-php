<?php
namespace BowlingPhp;

class IOHandlersFactory
{
    private array $args;
    public function __construct($args)
    {
        $this->args = $args;
    }
    public function getInputHandler(): Input
    {
        $inputValue = $this->args['input'] ?? $this->args['i'] ?? null;

        if ($inputValue) {
            return new InputFile($inputValue);
        }

        return new InputStdin();
    }
    public function getOutputHandler(): Output
    {
        $outputValue = $this->args['output'] ?? $this->args['o'] ?? null;

        if ($outputValue) {
            return new OutputFile($outputValue);
        }

        return new OutputStdout();
    }
    public function getPlayerCount(): int
    {
        $playerCount = $this->args['players'] ?? $this->args['p'] ?? 1;
        return max(1, $playerCount);
    }
}