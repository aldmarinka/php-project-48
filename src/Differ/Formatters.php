<?php

namespace Differ\Format;

use Exception;

use function Differ\Formatters\Stylish\render as renderStylish;
use function Differ\Formatters\Plain\render as renderPlain;

/**
 * @throws Exception
 */
function format(array $data, string $format): string
{
    return match ($format) {
        'stylish' => renderStylish($data),
        'plain' => renderPlain($data),
        default => throw new Exception(sprintf("Invalid format - %s. You can use stylish or plain format", $format))
    };
}
