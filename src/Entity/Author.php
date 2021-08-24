<?php

namespace ArchLinux\DiscussionFeed\Entity;

class Author
{
    public function __construct(
        public string $displayName,
        public string $username,
    ) {
    }
}
