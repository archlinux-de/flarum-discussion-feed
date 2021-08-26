<?php

namespace ArchLinux\DiscussionFeed\Entity;

class Discussion
{
    public function __construct(
        public string $permalink,
        public string $title,
        public string $link,
        public \DateTime $published,
        public ?\DateTime $updated,
        public string $summary,
        public ?Author $author,
    ) {
    }
}
