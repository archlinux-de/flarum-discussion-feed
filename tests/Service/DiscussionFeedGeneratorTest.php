<?php

namespace ArchLinux\DiscussionFeed\Test\Service;

use ArchLinux\DiscussionFeed\Entity\Discussion;
use ArchLinux\DiscussionFeed\Service\DiscussionFeedGenerator;
use ArchLinux\DiscussionFeed\Service\DiscussionFetcher;
use Flarum\Foundation\Application;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory as ViewFactory;
use PHPUnit\Framework\TestCase;

class DiscussionFeedGeneratorTest extends TestCase
{
    public function testGenerateFeed(): void
    {
        $renderer = $this->createMock(Renderable::class);
        $renderer
            ->expects($this->atLeastOnce())
            ->method('render')
            ->willReturn('foo');

        $viewFactory = $this->createMock(ViewFactory::class);
        $viewFactory
            ->expects($this->atLeastOnce())
            ->method('make')
            ->willReturn($renderer);

        $discussionFetcher = $this->createMock(DiscussionFetcher::class);
        $discussionFetcher
            ->expects($this->atLeastOnce())
            ->method('fetchRecentDiscussions')
            ->willReturn(
                new \ArrayIterator([
                                       new Discussion(1, 'test', 'TEST', new \DateTime(), new \DateTime())
                                   ])
            );

        $discussionFeedGenerator = new DiscussionFeedGenerator(
            $viewFactory,
            $this->createMock(SettingsRepositoryInterface::class),
            $this->createMock(Application::class),
            $discussionFetcher
        );

        $this->assertEquals('foo', $discussionFeedGenerator->generateFeed());
    }
}
