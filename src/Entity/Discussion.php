<?php

namespace ArchLinux\DiscussionFeed\Entity;

class Discussion
{
    private int $id;
    private string $slug;
    private string $title;
    private \DateTime $createdAt;
    private \DateTime $lastPostedAt;

    public function __construct(int $id, string $slug, string $title, \DateTime $createdAt, \DateTime $lastPostedAt)
    {
        $this->id = $id;
        $this->slug = $slug;
        $this->title = $title;
        $this->createdAt = $createdAt;
        $this->lastPostedAt = $lastPostedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getLastPostedAt(): \DateTime
    {
        return $this->lastPostedAt;
    }
}
