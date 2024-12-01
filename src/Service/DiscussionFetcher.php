<?php

namespace ArchLinux\DiscussionFeed\Service;

use ArchLinux\DiscussionFeed\Entity\Author;
use ArchLinux\DiscussionFeed\Entity\Discussion;
use Flarum\Discussion\DiscussionRepository;
use Flarum\Http\SlugManager;
use Flarum\Http\UrlGenerator;
use Flarum\Post\CommentPost;
use Flarum\Post\Post;
use Flarum\User\Guest;
use Flarum\User\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Stringable;
use s9e\TextFormatter\Utils;
use Flarum\Discussion\Discussion as FlarumDiscussion;

class DiscussionFetcher
{
    public function __construct(
        private readonly DiscussionRepository $discussionRepository,
        private readonly UrlGenerator $urlGenerator,
        private readonly SlugManager $slugManager,
    ) {
    }

    /**
     * @return \Traversable<Discussion>
     */
    public function fetchRecentDiscussions(): \Traversable
    {
        /** @var Builder $discussions */
        $discussions = $this->discussionRepository // @phpstan-ignore method.nonObject
            ->query()
            ->orderByDesc('created_at')
            ->whereVisibleTo(new Guest())
            ->limit(50);

        /** @var FlarumDiscussion $discussion */
        foreach ($discussions->get() as $discussion) {
            yield new Discussion(
                $this->createPermalink($discussion),
                $discussion->title,
                $this->createLink($discussion),
                $discussion->created_at,
                $discussion->last_posted_at,
                $this->createSummary($discussion->firstPost),
                $this->createAuthor($discussion)
            );
        }
    }

    private function createSummary(?Post $post): string
    {
        if (!$post instanceof CommentPost) {
            return '...';
        }

        return (new Stringable(Utils::removeFormatting($post->getParsedContentAttribute())))
            ->limit(280)
            ->replaceMatches('/\s+/', ' ');
    }

    private function createPermalink(FlarumDiscussion $discussion): string
    {
        return $this->urlGenerator
            ->to('forum')
            ->route(
                'discussion',
                ['id' => $discussion->id]
            );
    }

    private function createLink(FlarumDiscussion $discussion): string
    {
        return $this->urlGenerator
            ->to('forum')
            ->route(
                'discussion',
                ['id' => $this->slugManager->forResource(FlarumDiscussion::class)->toSlug($discussion)]
            );
    }

    private function createUserLink(User $user): string
    {
        return $this->urlGenerator
            ->to('forum')
            ->route(
                'user',
                ['username' => $this->slugManager->forResource(User::class)->toSlug($user)]
            );
    }

    private function createAuthor(FlarumDiscussion $discussion): ?Author
    {
        return $discussion->user
            ? new Author($discussion->user->display_name, $this->createUserLink($discussion->user))
            : null;
    }
}
