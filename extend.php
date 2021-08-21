<?php

namespace ArchLinux\DiscussionFeed;

use ArchLinux\DiscussionFeed\Console\CreateDiscussionFeed;
use Flarum\Extend;
use Illuminate\Console\Scheduling\Event;

return [
    (new Extend\Console())
        ->command(CreateDiscussionFeed::class)
        ->schedule(CreateDiscussionFeed::class, function (Event $event) {
            $event->withoutOverlapping();
            $event->everyFifteenMinutes();
        }),
    (new Extend\View())->namespace('discussion-feed', __DIR__ . '/views'),
];
