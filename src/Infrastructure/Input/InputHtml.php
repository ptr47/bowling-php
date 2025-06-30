<?php
namespace App\Infrastructure\Input;

class InputHtml extends AbstractInput
{
    public function getPinAmount(): int|null
    {
        return intval($_POST['pins']);
    }
}