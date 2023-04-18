<?php

declare(strict_types=1);

namespace Kiboko\Component\Flow\JSON;

use Kiboko\Component\Bucket\AcceptanceResultBucket;
use Kiboko\Contract\Pipeline\LoaderInterface;

class Loader implements LoaderInterface
{
    public function __construct(
        private \SplFileObject $file,
    ) {
    }

    public function load(): \Generator
    {
        $line = yield;

        while (true) {
            $this->file->fwrite(json_encode($line)."\n");

            $line = yield new AcceptanceResultBucket($line);
        }
    }
}
