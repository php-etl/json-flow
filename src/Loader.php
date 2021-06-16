<?php

namespace Kiboko\Component\Flow\JSON;

use Kiboko\Component\Bucket\AcceptanceResultBucket;
use Kiboko\Contract\Pipeline\LoaderInterface;
use Psr\Log\LoggerInterface;

class Loader implements LoaderInterface
{
    public function __construct(
        private \SplFileObject $file,
        ?LoggerInterface $logger = null
    ) {
    }

    public function load(): \Generator
    {
        $line = yield;

        while (true) {
            $this->file->fwrite(json_encode($line) . "\n");

            $line = yield new AcceptanceResultBucket($line);
        }
    }
}
