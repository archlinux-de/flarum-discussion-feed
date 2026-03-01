<?php

namespace ArchLinux\DiscussionFeed;

use Flarum\Extend;
use Flarum\Extension\Extension;
use Flarum\Foundation\Paths;
use Illuminate\Contracts\Container\Container;

class FeedLifecycle implements Extend\ExtenderInterface, Extend\LifecycleInterface
{
    public function extend(Container $container, ?Extension $extension = null): void
    {
    }

    public function onEnable(Container $container, Extension $extension): void
    {
    }

    public function onDisable(Container $container, Extension $extension): void
    {
        /** @var Paths $paths */
        $paths = $container->get(Paths::class);
        $feedFile = $paths->public . DIRECTORY_SEPARATOR . 'feed.xml';
        if (file_exists($feedFile)) {
            unlink($feedFile);
        }
    }
}
