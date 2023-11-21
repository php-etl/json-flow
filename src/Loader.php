<?php

declare(strict_types=1);

namespace Kiboko\Component\Flow\JSON;

use Kiboko\Component\Bucket\AcceptanceResultBucket;
use Kiboko\Component\Bucket\EmptyResultBucket;
use Kiboko\Contract\Pipeline\LoaderInterface;

class Loader implements LoaderInterface
{
    public function __construct(
        private readonly \SplFileObject $file,
    ) {
    }

    public function load(): \Generator
    {
        $line = yield;

        while (true) {
            if ($line === null) {
                $line = yield new EmptyResultBucket();
                continue;
            }
            $this->file->fwrite(json_encode($line, \JSON_THROW_ON_ERROR)."\n");

            $line = yield new AcceptanceResultBucket($line);
        }
    }
}
