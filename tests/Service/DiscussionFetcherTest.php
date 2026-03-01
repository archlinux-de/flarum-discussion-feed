<?php

namespace ArchLinux\DiscussionFeed\Test\Service;

use ArchLinux\DiscussionFeed\Service\DiscussionFetcher;
use Carbon\Carbon;
use Flarum\Discussion\Discussion;
use Flarum\Discussion\DiscussionRepository;
use Flarum\Http\RouteCollectionUrlGenerator;
use Flarum\Http\SlugManager;
use Flarum\Http\UrlGenerator;
use Flarum\Post\CommentPost;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DiscussionFetcherTest extends TestCase
{
    /** @return array<string, array{?string, string}> */
    public static function summaries(): array
    {
        return [
            'formatted content' => ['<foo>bar</foo>', 'bar'],
            'missing content' => [null, '...'],
            'empty content' => ['', '...'],
        ];
    }

    #[DataProvider('summaries')]
    public function testFetchRecentDiscussions(?string $content, string $summary): void
    {
        $user = $this->createMock(User::class);
        $user
            ->expects($this->atLeastOnce())
            ->method('__get')
            ->willReturnCallback(function (string $property): mixed {
                return match ($property) {
                    'display_name' => 'Bob Smith',
                    'username' => 'Bob',
                    default => null,
                };
            });

        $post = $this->createMock(CommentPost::class);
        $post
            ->expects($this->atLeastOnce())
            ->method('getParsedContentAttribute')
            ->willReturn($content);

        $discussion = $this->createMock(Discussion::class);
        $discussion
            ->expects($this->atLeastOnce())
            ->method('__get')
            ->willReturnCallback(function (string $property) use ($post, $user): mixed {
                return match ($property) {
                    'id' => 123,
                    'title' => 'Title',
                    'created_at' => new Carbon('yesterday'),
                    'last_posted_at' => new Carbon('today'),
                    'firstPost' => $post,
                    'user' => $user,
                    default => null,
                };
            });

        $builder = $this->createMock(Builder::class);
        $builder
            ->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn(new Collection([$discussion]));
        $builder->method('__call')->willReturnSelf();

        $discussionRepository = $this->createMock(DiscussionRepository::class);
        $discussionRepository
            ->expects($this->atLeastOnce())
            ->method('query')
            ->willReturn($builder);

        $routeCollectionUrlGenerator = $this->createMock(RouteCollectionUrlGenerator::class);
        $routeCollectionUrlGenerator
            ->expects($this->atLeastOnce())
            ->method('route')
            ->willReturn('http://localhost/');
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->expects($this->atLeastOnce())
            ->method('to')
            ->willReturn($routeCollectionUrlGenerator);

        $slugManager = $this->createStub(SlugManager::class);

        $discussionFetcher = new DiscussionFetcher($discussionRepository, $urlGenerator, $slugManager);

        /** @var \ArchLinux\DiscussionFeed\Entity\Discussion[] $recentDiscussions */
        $recentDiscussions = iterator_to_array($discussionFetcher->fetchRecentDiscussions());

        $this->assertNotEmpty($recentDiscussions);
        $this->assertEquals('Title', $recentDiscussions[0]->title);
        $this->assertEquals($summary, $recentDiscussions[0]->summary);
        $this->assertNotNull($recentDiscussions[0]->author);
        $this->assertEquals('Bob Smith', $recentDiscussions[0]->author->name);
    }
}
