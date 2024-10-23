<?php

namespace Differ\Formatters\Json;

function render(array $records): string
{
    return json_encode($records);
}
