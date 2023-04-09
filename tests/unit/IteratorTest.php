<?php

declare(strict_types=1);

namespace Wgirhad\FilenameIterator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

use function array_map;
use function dirname;
use function iterator_to_array;
use function realpath;

#[CoversClass(Iterator::class)]
#[Small]
final class IteratorTest extends TestCase
{
    public static function provider(): array
    {
        return [
            'lists all files' => [
                [
                    'a/c/.hidden/foobar.test',
                    'a/c/bar.test',
                    'a/c/d/bar.test',
                    'a/c/d/foo.test',
                    'a/c/d/foobar.test',
                    'a/c/foo.test',
                    'a/c/foobar.test',
                    'a/foobar.test',
                    'b/e/foobar.test',
                    'b/e/g/foobar.test',
                    'b/f/foobar.test',
                    'b/f/h/foobar.test',
                    'b/foobar.test',
                ],
                'fixture',
                '*.test'
            ],

            'filter by start of name' => [
                [
                    'a/c/.hidden/foobar.test',
                    'a/c/d/foo.test',
                    'a/c/d/foobar.test',
                    'a/c/foo.test',
                    'a/c/foobar.test',
                    'a/foobar.test',
                    'b/e/foobar.test',
                    'b/e/g/foobar.test',
                    'b/f/foobar.test',
                    'b/f/h/foobar.test',
                    'b/foobar.test',
                ],
                'fixture',
                'foo*.test',
            ],

            'filter by end of name' => [
                [
                    'a/c/.hidden/foobar.test',
                    'a/c/bar.test',
                    'a/c/d/bar.test',
                    'a/c/d/foobar.test',
                    'a/c/foobar.test',
                    'a/foobar.test',
                    'b/e/foobar.test',
                    'b/e/g/foobar.test',
                    'b/f/foobar.test',
                    'b/f/h/foobar.test',
                    'b/foobar.test',
                ],
                'fixture',
                '*bar.test',
            ],

            'applies glob to directories' => [
                [
                    'a/c/.hidden/foobar.test',
                    'a/c/bar.test',
                    'a/c/d/bar.test',
                    'a/c/d/foo.test',
                    'a/c/d/foobar.test',
                    'a/c/foo.test',
                    'a/c/foobar.test',
                ],
                'fixture/*/c',
                '*.test'
            ],
        ];
    }

    #[DataProvider('provider')]
    public function testSomething(array $expected, string $path, string $pattern): void
    {
        $root = dirname(__DIR__);
        $path = "{$root}/{$path}";

        $expected = array_map(fn($file) => realpath("{$root}/fixture/{$file}"), $expected);
        $result = iterator_to_array(new Iterator($path, $pattern));

        sort($expected);
        sort($result);

        $this->assertSame($expected, $result);
    }

    public function testExampleA(): void
    {
        $root = dirname(__DIR__);
        $path = "{$root}/fixture/example";

        $expected = [
            "{$path}/public/static/foo.png",
            "{$path}/public/static/images/bar.png",
            "{$path}/public/static/images/baz.png",
            "{$path}/public/static/images/.peek/aboo.png",
        ];

        $result = iterator_to_array(new Iterator("{$path}/public/static", '*.png'));

        sort($expected);
        sort($result);

        $this->assertSame($expected, $result);
    }

    public function testExampleB(): void
    {
        $root = dirname(__DIR__);
        $path = "{$root}/fixture/example";

        $expected = [
            "{$path}/src/CoolestClass.php",
            "{$path}/src/SecondCoolest.php",
            "{$path}/tests/CoolestClassJest.php",
            "{$path}/tests/SecondCoolestJest.php",
        ];

        $result = iterator_to_array(new Iterator("{$path}/*", '*Coolest*.php'));

        sort($expected);
        sort($result);

        $this->assertSame($expected, $result);
    }
}
