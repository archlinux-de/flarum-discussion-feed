<?php

namespace ArchLinux\DiscussionFeed\Controller;

use ArchLinux\DiscussionFeed\Service\DiscussionFeedGenerator;
use ArchLinux\DiscussionFeed\Service\DiscussionFetcher;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\XmlResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class FeedController implements RequestHandlerInterface
{
    private DiscussionFetcher $discussionFetcher;
    private DiscussionFeedGenerator $discussionFeedGenerator;

    public function __construct(DiscussionFetcher $discussionFetcher, DiscussionFeedGenerator $discussionFeedGenerator)
    {
        $this->discussionFetcher = $discussionFetcher;
        $this->discussionFeedGenerator = $discussionFeedGenerator;
    }

    public function handle(Request $request): Response
    {
        $discussions = $this->discussionFetcher->fetchRecentDiscussions();
        $feed = $this->discussionFeedGenerator->generateFeed(iterator_to_array($discussions));
        return new XmlResponse($feed);
    }
}
