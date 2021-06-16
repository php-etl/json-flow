<?php

namespace Kiboko\Component\Flow\JSON;

use Kiboko\Contract\Pipeline\ExtractorInterface;

class Extractor implements ExtractorInterface
{
    public function __construct(
        private \SplFileObject $file
    ) {
    }

    public function extract(): iterable
    {
        while (!$this->file->eof()) {
            yield json_decode($this->file->fgets(), true);
        }
    }
}
