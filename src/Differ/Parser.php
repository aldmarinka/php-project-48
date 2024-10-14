<?php

namespace Differ\Parser;

use Exception;
use Symfony\Component\Yaml\Yaml;

function parseFile(string $path): object
{
    if (!file_exists($path)) {
        throw new \Exception("File {$path} not found");
    }

    $fileData = (string) file_get_contents($path, true);
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    return match ($extension) {
        'json' => json_decode($fileData),
        'yml', 'yaml' => Yaml::parse($fileData, Yaml::PARSE_OBJECT_FOR_MAP),
        default => throw new Exception("Unknown extension {$extension}"),
    };
}