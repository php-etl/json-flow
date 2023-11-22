<?php

declare(strict_types=1);

namespace Kiboko\Component\Flow\JSON;

use Kiboko\Component\Bucket\AcceptanceResultBucket;
use Kiboko\Component\Bucket\RejectionResultBucket;
use Kiboko\Contract\Pipeline\ExtractorInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

readonly class Extractor implements ExtractorInterface
{
    public function __construct(
        private \SplFileObject $file,
        private LoggerInterface $logger = new NullLogger(),
    ) {}

    public function extract(): iterable
    {
        $data = null;
        while (!$this->file->eof()) {
            try {
                $data = json_decode($this->file->fgets(), true, 512, \JSON_ERROR_NONE);

                yield new AcceptanceResultBucket($data);
            } catch (\Throwable $exception) {
                $this->logger->critical($exception->getMessage(), ['item' => $data, 'exception' => $exception]);
                yield new RejectionResultBucket(
                    'It seems that something failed when decoding the json file.',
                    $exception,
                    $data
                );
            }
        }
    }
}
