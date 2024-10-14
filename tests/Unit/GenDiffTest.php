<?php
declare(strict_types=1);

namespace Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public static function provideTestFileNames(): array
    {
        return [
            ['file1.json', 'file2.json', 'expectedJson.txt'],
            ['file1.yaml', 'file2.yaml', 'expectedYaml.txt'],
        ];
    }

    private function makeFilePath(string $fileName): string
    {
        return __DIR__ . "/../fixtures/{$fileName}";
    }

    #[DataProvider('provideTestFileNames')]
    public function testGenDiff(string $fileName1, string $fileName2, string $expected): void
    {
        $file1 = $this->makeFilePath($fileName1);
        $file2 = $this->makeFilePath($fileName2);
        $expected = $this->makeFilePath($expected);

        $this->assertStringEqualsFile($expected, genDiff($file1, $file2));
    }
}