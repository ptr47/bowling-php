<?php
namespace App\Infrastructure\Input;

class InputStdin extends AbstractInput
{
    public function getPinAmount(): int|null
    {
        echo "Enter pin amount: ";
        $input = fgets(STDIN);

        return intval($input);
    }
}