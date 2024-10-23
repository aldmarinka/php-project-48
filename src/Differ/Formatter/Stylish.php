<?php

namespace Differ\Formatter\Stylish;

function renderStylish(array $records, int $level = 0): string
{
    $indent = spacesAmount($level);
    $resultArray = array_map(function ($record) use ($level, $indent) {
        $currentLevel = $level + 1;
        switch ($record['operation']) {
            case 'parent':
                return "{$indent}    {$record['key']}: " . renderStylish($record['child'], $currentLevel) . "\n";
            case 'unchanged':
                $value1 = toString($record['value1'], $currentLevel);
                return "{$indent}    {$record['key']}:{$value1}\n";
            case 'added':
                $value2 = toString($record['value2'], $currentLevel);
                return "{$indent}  + {$record['key']}:{$value2}\n";
            case 'deleted':
                $value1 = toString($record['value1'], $currentLevel);
                return "{$indent}  - {$record['key']}:{$value1}\n";
            case 'changed':
                $value1 = toString($record['value1'], $currentLevel);
                $value2 = toString($record['value2'], $currentLevel);
                return "{$indent}  - {$record['key']}:{$value1}\n{$indent}  + {$record['key']}:{$value2}\n";
            default:
                throw new \Exception("Unknown operation: {$record['operation']}");
        }
    }, $records);

    return "{\n" . implode("", $resultArray) . "{$indent}}";
}

function toString(mixed $value, int $level): string
{
    if (is_bool($value)) {
        return $value === true ? ' true' : ' false';
    }
    if (is_null($value)) {
        return ' null';
    }
    if (!is_object($value)) {
        return (string)$value === '' ? '' : ' ' . $value;
    }

    $indent = spacesAmount($level);
    $formattedArray = array_map(function ($key, $item) use ($level, $indent) {
        $dataType = (is_object($item)) ? toString($item, $level + 1) : ' ' . $item;
        return $indent . spacesAmount(1) . "{$key}:" . $dataType . "\n";
    }, array_keys(get_object_vars($value)), get_object_vars($value));

    return " {" . "\n" . implode("", $formattedArray) . $indent . "}";
}

function spacesAmount(int $level, int $spaces = 4): string
{
    return str_repeat(" ", $level * $spaces);
}
