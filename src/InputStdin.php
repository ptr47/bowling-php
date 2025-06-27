<?php
namespace BowlingPhp;

class InputStdin extends Input
{
    public function getPinAmount(): int|null
    {
        echo "Enter pin amount: ";
        $input = fgets(STDIN);
        
        return intval($input);
    }
}