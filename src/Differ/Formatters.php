<?php

namespace Differ\Format;

use Exception;

use function Differ\Formatter\Stylish\renderStylish;

/**
 * @throws Exception
 */
function format(array $data, string $format): string
{
    return match ($format) {
        'stylish' => renderStylish($data),
        default => throw new Exception(sprintf("Invalid format - %s", $format))
    };
}
