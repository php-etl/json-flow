<?php

namespace functional\Kiboko\Component\Flow\JSON;

use Kiboko\Component\Flow\JSON\Extractor;
use Kiboko\Component\PHPUnitExtension\Assert\ExtractorAssertTrait;
use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use PHPUnit\Framework\TestCase;
use Vfs\FileSystem;

class ExtractorTest extends TestCase
{
    use ExtractorAssertTrait;

    private ?FileSystem $fs = null;

    protected function setUp(): void
    {
        $this->fs = FileSystem::factory('vfs://');
        $this->fs->mount();
    }

    protected function tearDown(): void
    {
        $this->fs->unmount();
        $this->fs = null;
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
