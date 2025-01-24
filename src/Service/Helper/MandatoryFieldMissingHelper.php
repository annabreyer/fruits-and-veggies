<?php

declare(strict_types=1);

namespace App\Service\Helper;

class MandatoryFieldMissingHelper
{
    public static function message(string $object): string
    {
        return \sprintf('Mandatory fields are missing in %s.', $object);
    }
}
