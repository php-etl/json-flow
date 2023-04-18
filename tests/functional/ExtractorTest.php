<?php

namespace functional\Kiboko\Component\Flow\JSON;

use Kiboko\Component\Flow\JSON\Extractor;
use Kiboko\Component\PHPUnitExtension\Assert\ExtractorAssertTrait;
use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;

class ExtractorTest extends TestCase
{
    use ExtractorAssertTrait;

    private ?vfsStreamDirectory $fs = null;

    protected function setUp(): void
    {
        $this->fs = vfsStream::setup();
    }

    protected function tearDown(): void
    {
        $this->fs = null;
        vfsStreamWrapper::unregister();
    }

    public function testExtract()
    {
        $extractor = new Extractor(new \SplFileObject(__DIR__.'/data/users.jsonld'));

        $this->assertExtractorExtractsLike(
            [
                [
                    [
                        'firstname' => 'john',
                        'lastname' => 'doe'
                    ],
                    [
                        'firstname' => 'alexandre',
                        'lastname' => 'gagne'
                    ]
                ],
                [
                    'firstname' => 'jean',
                    'lastname' => 'dupont'
                ]
            ],
            $extractor,
        );
    }

    public function pipelineRunner(): PipelineRunnerInterface
    {
        return new PipelineRunner();
    }
}
