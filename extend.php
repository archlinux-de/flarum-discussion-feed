<?php

namespace ArchLinux\DiscussionFeed;

use ArchLinux\DiscussionFeed\Console\CreateDiscussionFeed;
use ArchLinux\DiscussionFeed\Controller\FeedController;
use Flarum\Extend;
use Flarum\Extension\Extension;
use Flarum\Foundation\Paths;
use Flarum\Frontend\Document;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Contracts\Container\Container;

return [
    (new Extend\Console())
        ->command(CreateDiscussionFeed::class)
        ->schedule(CreateDiscussionFeed::class, function (Event $event) {
            $event->withoutOverlapping();
            $event->everyFifteenMinutes();
        }),
    (new Extend\View())->namespace('discussion-feed', __DIR__ . '/views'),
    (new Extend\Routes('forum'))->get('/feed.xml', 'discussion-feed.feed', FeedController::class),
    (new Extend\Frontend('forum'))
        ->content(function (Document $document) {
            $document->head[] =
                '<link href="/feed.xml" type="application/atom+xml" rel="alternate" title="Discussion Atom feed" />';
        }),
    (new class implements Extend\ExtenderInterface, Extend\LifecycleInterface {
        public function extend(Container $container, Extension $extension = null): void
        {
        }

        public function onEnable(Container $container, Extension $extension): void
        {
        }

        public function onDisable(Container $container, Extension $extension): void
        {
            $feedFile = $container->get(Paths::class)->public . DIRECTORY_SEPARATOR . 'feed.xml';
            if (file_exists($feedFile)) {
                unlink($feedFile);
            }
        }
    })
];
