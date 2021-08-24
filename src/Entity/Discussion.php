<?php

namespace ArchLinux\DiscussionFeed\Entity;

class Discussion
{
    public function __construct(
        public int $id,
        public string $slug,
        public string $title,
        public \DateTime $createdAt,
        public ?\DateTime $lastPostedAt,
        public string $content,
        public ?Author $author,
    ) {
    }
}
