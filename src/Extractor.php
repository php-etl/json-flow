<?php

declare(strict_types=1);

namespace Kiboko\Component\Flow\JSON;

use Kiboko\Component\Bucket\AcceptanceResultBucket;
use Kiboko\Contract\Pipeline\ExtractorInterface;

class Extractor implements ExtractorInterface
{
    public function __construct(
        private readonly \SplFileObject $file
    ) {
    }

    public function extract(): iterable
    {
        while (!$this->file->eof()) {
            yield new AcceptanceResultBucket(json_decode($this->file->fgets(), true, 512, JSON_THROW_ON_ERROR));
        }
    }
}
