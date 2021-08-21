<?php

namespace ArchLinux\DiscussionFeed;

use ArchLinux\DiscussionFeed\Console\CreateDiscussionFeed;
use ArchLinux\DiscussionFeed\Controller\FeedController;
use Flarum\Extend;
use Flarum\Frontend\Document;
use Illuminate\Console\Scheduling\Event;

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
        })
];
