<?php

namespace functional\Kiboko\Component\Flow\JSON;

use Kiboko\Component\Flow\JSON\Loader;
use Kiboko\Component\PHPUnitExtension\Assert\LoaderAssertTrait;
use Kiboko\Component\Pipeline\PipelineRunner;
use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Vfs\FileSystem;

class LoaderTest extends TestCase
{
    use LoaderAssertTrait;

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

    public function testLoad()
    {
        $loader = new Loader(new \SplFileObject('vfs://output.jsonld', 'w'));

        $this->assertLoaderLoadsLike(
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
            $loader
        );

        $this->assertFileEquals(__DIR__.'/data/users.jsonld', 'vfs://output.jsonld');
    }

    public function pipelineRunner(): PipelineRunnerInterface
    {
        return new \Kiboko\Component\Pipeline\PipelineRunner(
            new NullLogger()
        );
    }
}
