<?php

namespace ArchLinux\DiscussionFeed\Console;

use ArchLinux\DiscussionFeed\Service\DiscussionFeedGenerator;
use Flarum\Console\AbstractCommand;
use Flarum\Foundation\Paths;

class CreateDiscussionFeed extends AbstractCommand
{
    public function __construct(
        private readonly DiscussionFeedGenerator $discussionFeedGenerator,
        private readonly Paths $paths
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('discussion-feed:create')->setDescription('Create discussion atom feed');
    }

    protected function fire(): void
    {
        file_put_contents(
            $this->paths->public . DIRECTORY_SEPARATOR . 'feed.xml',
            $this->discussionFeedGenerator->generateFeed()
        );
    }
}
