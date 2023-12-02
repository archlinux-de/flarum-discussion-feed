<?php

namespace ArchLinux\DiscussionFeed\Service;

use Flarum\Foundation\Application;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\View\Factory as ViewFactory;

class DiscussionFeedGenerator
{
    public function __construct(
        private readonly ViewFactory $factory,
        private readonly SettingsRepositoryInterface $settingsRepository,
        private readonly Application $application,
        private readonly DiscussionFetcher $discussionFetcher
    ) {
    }

    public function generateFeed(): string
    {
        return $this->factory->make(
            'discussion-feed::feed',
            [
                'discussions' => $this->discussionFetcher->fetchRecentDiscussions(),
                'url' => $this->application->url(),
                'title' => $this->settingsRepository->get('forum_title')
            ]
        )->render();
    }
}
