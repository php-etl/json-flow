<?php

declare(strict_types=1);

namespace Kiboko\Component\Flow\JSON;

use Kiboko\Component\Bucket\AcceptanceResultBucket;
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
        while (!$this->file->eof()) {
            try {
                $data = json_decode($this->file->fgets(), true, 512, \JSON_ERROR_NONE);

                if (\JSON_ERROR_NONE !== json_last_error()) {
                    $this->logger->error(json_last_error_msg(), ['item' => $data]);
                    continue;
                }

                yield new AcceptanceResultBucket($data);
            } catch (\Throwable $exception) {
                $this->logger->critical($exception->getMessage(), ['item' => $data, 'exception' => $exception]);
            }
        }
    }
}
