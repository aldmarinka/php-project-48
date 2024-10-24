<?php

namespace Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public static function provideTestFileNames(): array
    {
        return [
            ['nested1.json', 'nested2.json', 'expectedNestedStylish.txt', 'stylish'],
            ['nested1.yaml', 'nested2.yaml', 'expectedNestedStylish.txt', 'stylish'],
            ['nested1.json', 'nested2.json', 'expectedNestedPlain.txt', 'plain'],
            ['nested1.yaml', 'nested2.yaml', 'expectedNestedPlain.txt', 'plain'],
            ['nested1.json', 'nested2.json', 'expectedNestedJson.txt', 'json'],
            ['nested1.yaml', 'nested2.yaml', 'expectedNestedJson.txt', 'json'],
        ];
    }

    private function makeFilePath(string $fileName): string
    {
        return __DIR__ . "/../fixtures/{$fileName}";
    }

    #[DataProvider('provideTestFileNames')]
    public function testGenDiff(string $fileName1, string $fileName2, string $expected, string $format): void
    {
        $file1 = $this->makeFilePath($fileName1);
        $file2 = $this->makeFilePath($fileName2);
        $expected = $this->makeFilePath($expected);

        $this->assertStringEqualsFile($expected, genDiff($file1, $file2, $format));
    }
}
