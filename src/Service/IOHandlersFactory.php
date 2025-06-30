<?php
namespace App\Service;

use App\Infrastructure\Input as Input;
use App\Infrastructure\Output as Output;

class IOHandlersFactory
{
    private array $args;
    public function __construct($args)
    {
        $this->args = $args;
    }
    public function getInputHandler(): Input\AbstractInput
    {
        $inputValue = $this->args['input'] ?? $this->args['i'] ?? null;

        if ($inputValue) {
            return new Input\InputFile($inputValue);
        }

        return new Input\InputStdin();
    }
    public function getOutputHandler(): Output\AbstractOutput
    {
        $outputValue = $this->args['output'] ?? $this->args['o'] ?? null;

        if ($outputValue) {
            return new Output\OutputFile($outputValue);
        }

        return new Output\OutputStdout();
    }
    public function getPlayerCount(): int
    {
        $playerCount = $this->args['players'] ?? $this->args['p'] ?? 1;
        return max(1, $playerCount);
    }
}