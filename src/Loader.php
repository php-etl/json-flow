<?php

declare(strict_types=1);

namespace Kiboko\Component\Flow\JSON;

use Kiboko\Component\Bucket\AcceptanceResultBucket;
use Kiboko\Component\Bucket\EmptyResultBucket;
use Kiboko\Component\Bucket\RejectionResultBucket;
use Kiboko\Contract\Pipeline\LoaderInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

readonly class Loader implements LoaderInterface
{
    public function __construct(
        private \SplFileObject $file,
        private LoggerInterface $logger = new NullLogger(),
    ) {}

    public function load(): \Generator
    {
        $line = yield new EmptyResultBucket();

        while ($line) {
            try {
                $this->file->fwrite(json_encode($line, \JSON_THROW_ON_ERROR)."\n");

                $line = yield new AcceptanceResultBucket($line);
            } catch (\Throwable $exception) {
                $this->logger->critical($exception->getMessage(), ['item' => $line, 'exception' => $exception]);
                $line = yield new RejectionResultBucket(
                    'It seems that something went wrong when writing to the json file',
                    $exception,
                    $line
                );
            }
        }
    }
}
