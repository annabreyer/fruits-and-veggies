<?php

declare(strict_types=1);

namespace App\Service\Helper;

class QuantityConversionHelper
{
    public static function convertKilogrammToGram(int $quantity): int
    {
        return $quantity * 1000;
    }

    public static function convertGramToKilogramm(int $quantity): float
    {
        return round($quantity / 1000, 2);
    }
}
