<?php

namespace Differ\Differ;

use Exception;

use function Differ\Parser\parseFile;

/**
 * @throws Exception
 */
function genDiff(string $pathToFile1, string $pathToFile2)
{
    $text1 = parseFile($pathToFile1);
    $text2 = parseFile($pathToFile2);

    $result = compareTexts($text1, $text2);

    return beautifyAnswer($result);
}

function compareTexts(object $object1, object $object2): array
{
    $text1 = get_object_vars($object1);
    $text2 = get_object_vars($object2);

    $commonArray = array_merge($text1, $text2);
    ksort($commonArray);

    $result = [];
    foreach ($commonArray as $key => $value) {
        if (!isset($text1[$key])) {
            $result[] = makeRecord('added', $key, $text2[$key]);
            continue;
        }

        if (!isset($text2[$key])) {
            $result[] = makeRecord('deleted', $key, $text1[$key]);

            continue;
        }

        if ($text1[$key] != $text2[$key]) {
            $result[] = makeRecord('changed', $key, $text1[$key], $text2[$key]);

            continue;
        }

        $result[] = makeRecord('unchanged', $key, $value);
    }

    return $result;
}

function makeRecord(string $operation, string $key, $value1, $value2 = null): array
{
    return [
        'operation' => $operation,
        'key'       => $key,
        'value1'    => is_bool($value1) ? ($value1 ? 'true' : 'false') : $value1,
        'value2'    => is_bool($value2) ? ($value1 ? 'true' : 'false') : $value2,
    ];
}

function beautifyAnswer(array $records): string
{
    $answer = "{";
    $tab    = PHP_EOL . "    ";
    foreach ($records as $record) {
        switch ($record['operation']) {
            case 'unchanged':
                $answer .= "{$tab}  {$record['key']}: {$record['value1']}";
                break;
            case 'added':
                $answer .= "{$tab}+ {$record['key']}: {$record['value1']}";
                break;
            case 'deleted':
                $answer .= "{$tab}- {$record['key']}: {$record['value1']}";
                break;
            case 'changed':
                $answer .= "{$tab}- {$record['key']}: {$record['value1']}{$tab}+ {$record['key']}: {$record['value2']}";
                break;
            default:
                throw new \Exception("Unknown operation: {$record['operation']}");
        }
    }
    $answer .= PHP_EOL . "}";

    return $answer;
}
