<?php

namespace ArchLinux\DiscussionFeed\Service;

use ArchLinux\DiscussionFeed\Entity\Discussion;
use Flarum\Discussion\DiscussionRepository;
use Flarum\Foundation\Application;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Database\Query\Builder;

class DiscussionFeedGenerator
{
    private ViewFactory $factory;
    private SettingsRepositoryInterface $settingsRepository;
    private Application $application;

    public function __construct(
        ViewFactory $factory,
        SettingsRepositoryInterface $settingsRepository,
        Application $application
    ) {
        $this->factory = $factory;
        $this->settingsRepository = $settingsRepository;
        $this->application = $application;
    }

    /**
     * @param Discussion[] $discussions
     * @return string
     */
    public function generateFeed(array $discussions): string
    {
        return $this->factory->make(
            'discussion-feed::feed',
            [
                'discussions' => $discussions,
                'url' => $this->application->url(),
                'title' => $this->settingsRepository->get('forum_title')
            ]
        )->render();
    }
}
