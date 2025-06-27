<?php
namespace BowlingPhp;

class InputHtml extends Input
{
    /**
     * @inheritDoc
     */
    public function getPinAmount(): int|null
    {
        return intval($_POST['pins']);
    }
}