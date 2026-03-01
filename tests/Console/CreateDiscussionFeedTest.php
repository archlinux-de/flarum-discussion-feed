<?php

namespace ArchLinux\DiscussionFeed\Test\Console;

use ArchLinux\DiscussionFeed\Console\CreateDiscussionFeed;
use ArchLinux\DiscussionFeed\Service\DiscussionFeedGenerator;
use Flarum\Foundation\Paths;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateDiscussionFeedTest extends TestCase
{
    public function testCreatesFeedAndReturnsSuccess(): void
    {
        $directory = sys_get_temp_dir() . '/discussion-feed-' . bin2hex(random_bytes(8));
        mkdir($directory);
        $generator = $this->createMock(DiscussionFeedGenerator::class);
        $generator->expects($this->once())->method('generateFeed')->willReturn('<feed/>');
        $paths = new Paths(['base' => $directory, 'public' => $directory, 'storage' => $directory]);

        try {
            $tester = new CommandTester(new CreateDiscussionFeed($generator, $paths));
            $this->assertSame(0, $tester->execute([]));
            $this->assertSame('<feed/>', file_get_contents($directory . '/feed.xml'));
        } finally {
            if (file_exists($directory . '/feed.xml')) {
                unlink($directory . '/feed.xml');
            }
            rmdir($directory);
        }
    }
}
