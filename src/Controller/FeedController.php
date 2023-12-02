<?php

namespace ArchLinux\DiscussionFeed\Controller;

use ArchLinux\DiscussionFeed\Service\DiscussionFeedGenerator;
use Laminas\Diactoros\Response\XmlResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class FeedController implements RequestHandlerInterface
{
    public function __construct(private readonly DiscussionFeedGenerator $discussionFeedGenerator)
    {
    }

    public function handle(Request $request): Response
    {
        return new XmlResponse(
            xml: $this->discussionFeedGenerator->generateFeed(),
            headers: ['content-type' => 'application/atom+xml; charset=utf-8']
        );
    }
}
