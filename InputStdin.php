<?php
require_once 'Input.php';

class InputStdin extends Input
{
    public function getPinAmount(): int
    {
        echo "Enter pin amount: ";
        $input = fgets(STDIN);

        return intval($input);
    }
}