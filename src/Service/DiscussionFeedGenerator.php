<?php

namespace ArchLinux\DiscussionFeed\Service;

use Flarum\Foundation\Application;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\View\Factory as ViewFactory;

class DiscussionFeedGenerator
{
    private ViewFactory $factory;
    private SettingsRepositoryInterface $settingsRepository;
    private Application $application;
    private DiscussionFetcher $discussionFetcher;

    public function __construct(
        ViewFactory $factory,
        SettingsRepositoryInterface $settingsRepository,
        Application $application,
        DiscussionFetcher $discussionFetcher
    ) {
        $this->factory = $factory;
        $this->settingsRepository = $settingsRepository;
        $this->application = $application;
        $this->discussionFetcher = $discussionFetcher;
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
