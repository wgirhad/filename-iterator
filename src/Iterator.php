<?php

namespace Wgirhad\FilenameIterator;

use CallbackFilterIterator;
use Generator;
use IteratorAggregate;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use GlobIterator;

use function basename;
use function fnmatch;
use function realpath;

/**
 * @implements IteratorAggregate<string>
 */
class Iterator implements IteratorAggregate
{
    private const FS_ITERATOR_FLAGS = GlobIterator::CURRENT_AS_PATHNAME | GlobIterator::SKIP_DOTS;

    public function __construct(
        private string $path,
        private string $pattern,
    ) {
    }

    public function getIterator(): Generator
    {
        $entrypoints = $this->entrypoints();

        foreach ($this->entrypoints() as $entrypoint) {
            $dir = realpath(strval($entrypoint));

            if (!is_string($dir)) {
                continue;
            }

            $files = $this->files($dir);
            foreach ($this->filterFiles($files) as $file) {
                $path = realpath(strval($file));

                if (!is_string($path)) {
                    continue;
                }

                yield $path;
            }
        }
    }

    private function entrypoints(): \Iterator
    {
        return new GlobIterator($this->path, self::FS_ITERATOR_FLAGS);
    }

    private function files(string $dir): \Iterator
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, self::FS_ITERATOR_FLAGS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
    }

    private function filterFiles(\Iterator $files): \Iterator
    {
        return new CallbackFilterIterator(
            $files,
            fn($path) => fnmatch($this->pattern, basename($path))
        );
    }
}
