<?php
namespace App\Infrastructure\Input;

abstract class AbstractInput
{
    abstract public function getPinAmount(): int|null;
}