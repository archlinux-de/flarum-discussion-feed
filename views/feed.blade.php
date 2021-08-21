<?= '<?xml version="1.0" encoding="utf-8"?>'; ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <id>{{ $url }}/</id>
    <title>{{ $title }}</title>
    <updated>{{ date(DateTimeInterface::ATOM) }}</updated>
    <link rel="alternate" href="{{ $url }}/"/>
    <link rel="self" href="{{ $url }}/feed.xml"/>
    <?php /** @var \ArchLinux\DiscussionFeed\Entity\Discussion[] $discussions */ ?>
    @foreach ($discussions as $discussion)
        <entry>
            <id>{{ $url }}/d/{{ $discussion->getId() }}</id>
            <title>{{ $discussion->getTitle() }}</title>
            <published>{{ $discussion->getCreatedAt()->format(DateTimeInterface::ATOM) }}</published>
            <updated>{{ $discussion->getLastPostedAt()->format(DateTimeInterface::ATOM) }}</updated>
            <link rel="alternate" href="{{ $url }}/d/{{ $discussion->getId() }}-{{ $discussion->getSlug() }}"/>
        </entry>
    @endforeach
</feed>
