<?php

namespace Differ\Differ;

use Exception;

use function Differ\Parser\parseFile;
use function Differ\Format\format;
use function Functional\sort;


/**
 * @throws Exception
 */
function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $text1 = parseFile($pathToFile1);
    $text2 = parseFile($pathToFile2);

    $result = compareTexts($text1, $text2);

    return format($result, $format);
}

function compareTexts(object $data1, object $data2): array
{
    $text1 = get_object_vars($data1);
    $text2 = get_object_vars($data2);
    $uniqueKeys = array_unique(array_merge(array_keys($text1), array_keys($text2)));
    $sortedKeys = sort($uniqueKeys, fn($a, $b) => $a <=> $b);

    return array_map(function ($key) use ($text1, $text2) {
        if (!array_key_exists($key, $text1)) {
            return ['operation' => 'added', 'key' => $key, 'value2' => $text2[$key]];
        }
        if (!array_key_exists($key, $text2)) {
            return ['operation' => 'deleted', 'key' => $key, 'value1' => $text1[$key]];
        }
        if (is_object($text1[$key]) && is_object($text2[$key])) {
            $child = compareTexts($text1[$key], $text2[$key]);
            return ['operation' => 'parent', 'key' => $key, 'child' => $child];
        }
        if ($text1[$key] === $text2[$key]) {
            return ['operation' => 'unchanged', 'key' => $key, 'value1' => $text1[$key]];
        }
        return ['operation' => 'changed', 'key' => $key, 'value1' => $text1[$key], 'value2' => $text2[$key]];
    }, $sortedKeys);
}
