<?php

namespace ArchLinux\DiscussionFeed\Entity;

class Author
{
    public function __construct(
        public string $name,
        public string $uri,
    ) {
    }
}
