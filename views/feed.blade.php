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
            <id>{{ $url }}/d/{{ $discussion->id }}</id>
            <title>{{ $discussion->title }}</title>
            <published>{{ $discussion->createdAt->format(DateTimeInterface::ATOM) }}</published>
            @if ($discussion->lastPostedAt)
                <updated>{{ $discussion->lastPostedAt->format(DateTimeInterface::ATOM) }}</updated>
            @endif
            <link rel="alternate" href="{{ $url }}/d/{{ $discussion->id}}-{{ $discussion->slug }}"/>
            @if ($discussion->author)
                <author>
                    <name>{{ $discussion->author->displayName }}</name>
                    <uri>{{ $url }}/u/{{ $discussion->author->username }}</uri>
                </author>
            @else
                <author>
                    <?php /** @var \Flarum\Locale\Translator $translator */ ?>
                    <name>{{ $translator->trans('core.lib.username.deleted_text') }}</name>
                </author>
            @endif
            <summary type="text">{{ $discussion->content }}</summary>
        </entry>
    @endforeach
</feed>
