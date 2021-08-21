<?php

namespace ArchLinux\DiscussionFeed\Service;

use ArchLinux\DiscussionFeed\Entity\Discussion;
use Flarum\Discussion\DiscussionRepository;
use Flarum\User\User;
use Illuminate\Database\Query\Builder;

class DiscussionFetcher
{
    private DiscussionRepository $discussionRepository;

    public function __construct(DiscussionRepository $discussionRepository)
    {
        $this->discussionRepository = $discussionRepository;
    }

    /**
     * @return \Traversable<Discussion>
     */
    public function fetchRecentDiscussions(): \Traversable
    {
        $discussions = $this->discussionRepository->query()
            ->select('id', 'title', 'slug', 'created_at', 'last_posted_at')->orderByDesc('created_at')
            ->whereVisibleTo(new User())
            ->limit(50);

        foreach ($discussions->get() as $discussion) {
            yield new Discussion(
                $discussion->id,
                $discussion->slug,
                $discussion->title,
                $discussion->created_at,
                $discussion->last_posted_at
            );
        }
    }
}
