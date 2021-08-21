<?php

namespace ArchLinux\DiscussionFeed\Console;

use ArchLinux\DiscussionFeed\Service\DiscussionFeedGenerator;
use ArchLinux\DiscussionFeed\Service\DiscussionFetcher;
use Flarum\Console\AbstractCommand;
use Flarum\Foundation\Paths;

class CreateDiscussionFeed extends AbstractCommand
{
    private DiscussionFetcher $discussionFetcher;
    private DiscussionFeedGenerator $discussionFeedGenerator;
    private Paths $paths;

    public function __construct(
        DiscussionFetcher $discussionFetcher,
        DiscussionFeedGenerator $discussionFeedGenerator,
        Paths $paths
    ) {
        $this->discussionFetcher = $discussionFetcher;
        $this->discussionFeedGenerator = $discussionFeedGenerator;
        $this->paths = $paths;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('dioscussion-feed:create')->setDescription('Create discussion atom feed');
    }

    protected function fire(): void
    {
        $discussions = $this->discussionFetcher->fetchRecentDiscussions();
        $feed = $this->discussionFeedGenerator->generateFeed(iterator_to_array($discussions));
        file_put_contents($this->paths->public . DIRECTORY_SEPARATOR . 'feed.xml', $feed);
    }
}
