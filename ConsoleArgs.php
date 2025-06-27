<?php

class ConsoleArgs
{
    private array $argv;
    public function __construct($argv)
    {
        $this->argv = $argv;
    }
    public function getArgs(): array
    {
        $args = [];
        for ($i = 1; $i < count($this->argv); $i++) {
            switch ($this->argv[$i]) {
                case '-i':
                    if (isset($this->argv[$i + 1])) {
                        $args['input'] = $this->argv[$i + 1];
                    } else {
                        Output::showError("Error: -i requires a filename.");
                        exit(1);
                    }
                    break;
                case '-o':
                    if (isset($this->argv[$i + 1])) {
                        $args['output'] = $this->argv[$i + 1];
                    } else {
                        Output::showError("Error: -o requires a filename.");
                        exit(1);
                    }
                    break;
                case '-p':
                    if (isset($this->argv[$i + 1])) {
                        $args['players'] = intval($this->argv[$i + 1]);
                        if ($args['players'] < 1) {
                            Output::showError('Error: -p must be an integer larger than 0');
                            exit(1);
                        }
                    } else {
                        Output::showError('Error: -p requires a value.');
                        exit(1);
                    }
                default:
                    break;
            }
        }
        return $args;
    }
}