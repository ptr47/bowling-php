<?php
namespace BowlingPhp;

abstract class Input
{
    abstract public function getPinAmount(): int|null;
}