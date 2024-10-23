<?php

namespace Differ\Format;

use Exception;

use function Differ\Formatters\Stylish\render as renderStylish;
use function Differ\Formatters\Plain\render as renderPlain;
use function Differ\Formatters\Json\render as renderJson;

/**
 * @throws Exception
 */
function format(array $data, string $format): string
{
    return match ($format) {
        'stylish' => renderStylish($data),
        'plain' => renderPlain($data),
        'json' => renderJson($data),
        default => throw new Exception(sprintf("Invalid format %s. You can use stylish, plain or json format", $format))
    };
}
