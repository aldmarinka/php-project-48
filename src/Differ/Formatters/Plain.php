<?php

namespace Differ\Formatters\Plain;

function render(array $records, string $valuePath = ''): string
{
    return implode("", array_map(function ($record) use ($valuePath) {
        switch ($record['operation']) {
            case 'parent':
                $path = "$valuePath{$record['key']}.";
                return render($record['child'], $path);
            case 'unchanged':
                return "";
            case 'added':
                $value2 = toString($record['value2']);
                return "Property '$valuePath{$record['key']}' was added with value: {$value2}\n";
            case 'deleted':
                return "Property '$valuePath{$record['key']}' was removed\n";
            case 'changed':
                $value1 = toString($record['value1']);
                $value2 = toString($record['value2']);
                return "Property '$valuePath{$record['key']}' was updated. From {$value1} to {$value2}\n";
            default:
                throw new \Exception("Unknown operation: {$record['operation']}");
        }
    }, $records));
}

function toString(mixed $value): string
{
    if (is_object($value)) {
        return "[complex value]";
    }
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }

    return strtolower(var_export($value, true));
}
