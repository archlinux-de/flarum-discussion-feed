<?php

namespace ArchLinux\DiscussionFeed\Test;

use ArchLinux\DiscussionFeed\FeedLifecycle;
use Flarum\Extension\Extension;
use Flarum\Foundation\Paths;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

class FeedLifecycleTest extends TestCase
{
    public function testDisablingRemovesGeneratedFeed(): void
    {
        $directory = sys_get_temp_dir() . '/discussion-feed-' . bin2hex(random_bytes(8));
        mkdir($directory);
        $feedFile = $directory . '/feed.xml';
        file_put_contents($feedFile, '<feed/>');
        $container = new Container();
        $container->instance(Paths::class, new Paths([
            'base' => $directory,
            'public' => $directory,
            'storage' => $directory,
        ]));
        $extension = new Extension(dirname(__DIR__), ['name' => 'archlinux-de/flarum-discussion-feed']);
        $lifecycle = new FeedLifecycle();

        try {
            $lifecycle->extend($container);
            $lifecycle->onEnable($container, $extension);
            $this->assertFileExists($feedFile);

            $lifecycle->onDisable($container, $extension);
            $this->assertFileDoesNotExist($feedFile);

            $lifecycle->onDisable($container, $extension);
            $this->assertFileDoesNotExist($feedFile);
        } finally {
            if (file_exists($feedFile)) {
                unlink($feedFile);
            }
            rmdir($directory);
        }
    }
}
