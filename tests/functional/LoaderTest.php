<?php

namespace functional\Kiboko\Component\Flow\JSON;

use Kiboko\Component\Flow\JSON\Loader;
use Kiboko\Component\PHPUnitExtension\Assert\LoaderAssertTrait;
use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    use LoaderAssertTrait;

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

    public function testLoad()
    {
        $file = new vfsStreamFile('output.jsonld');
        $loader = new Loader(new \SplFileObject($file->getName(), 'w'));

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

        $this->assertFileEquals(__DIR__.'/data/users.jsonld', $file->getName());
    }

    public function pipelineRunner(): PipelineRunnerInterface
    {
        return new PipelineRunner();
    }
}
