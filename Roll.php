<?php

class Roll
{
    public const int MAX_PINS = 10;
    public static function isValidRoll(int $pins): bool
    {
        return ($pins >= 0) and ($pins <= self::MAX_PINS);
    }
}