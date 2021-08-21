<?php

namespace ArchLinux\DiscussionFeed\Console;

use ArchLinux\DiscussionFeed\Service\DiscussionFeedGenerator;
use Flarum\Console\AbstractCommand;
use Flarum\Foundation\Paths;

class CreateDiscussionFeed extends AbstractCommand
{
    private DiscussionFeedGenerator $discussionFeedGenerator;
    private Paths $paths;

    public function __construct(DiscussionFeedGenerator $discussionFeedGenerator, Paths $paths)
    {
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
        file_put_contents(
            $this->paths->public . DIRECTORY_SEPARATOR . 'feed.xml',
            $this->discussionFeedGenerator->generateFeed()
        );
    }
}
