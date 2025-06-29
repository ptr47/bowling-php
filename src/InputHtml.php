<?php
namespace BowlingPhp;

class InputHtml extends Input
{
    public function getPinAmount(): int|null
    {
        return intval($_POST['pins']);
    }
}