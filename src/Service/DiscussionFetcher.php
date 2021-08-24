<?php

namespace ArchLinux\DiscussionFeed\Service;

use ArchLinux\DiscussionFeed\Entity\Author;
use ArchLinux\DiscussionFeed\Entity\Discussion;
use Flarum\Discussion\DiscussionRepository;
use Flarum\Post\CommentPost;
use Flarum\User\User;
use Illuminate\Support\Stringable;
use s9e\TextFormatter\Utils;

class DiscussionFetcher
{
    public function __construct(private DiscussionRepository $discussionRepository)
    {
    }

    /**
     * @return \Traversable<Discussion>
     */
    public function fetchRecentDiscussions(): \Traversable
    {
        $discussions = $this->discussionRepository
            ->query()
            ->orderByDesc('created_at')
            ->whereVisibleTo(new User())
            ->limit(50);

        /** @var \Flarum\Discussion\Discussion $discussion */
        foreach ($discussions->get() as $discussion) {
            yield new Discussion(
                $discussion->id,
                $discussion->slug,
                $discussion->title,
                $discussion->created_at,
                $discussion->last_posted_at,
                $discussion->firstPost instanceof CommentPost ? $this->createSummary($discussion->firstPost) : '...',
                $discussion->user ? new Author($discussion->user->display_name, $discussion->user->username) : null
            );
        }
    }

    private function createSummary(CommentPost $post): string
    {
        return (new Stringable(Utils::removeFormatting($post->getParsedContentAttribute())))
            ->limit(280)
            ->replaceMatches('/\s+/', ' ');
    }
}
