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
            <id>{{ $discussion->permalink }}</id>
            <title>{{ $discussion->title }}</title>
            <published>{{ $discussion->published->format(DateTimeInterface::ATOM) }}</published>
            @if ($discussion->updated)
                <updated>{{ $discussion->updated->format(DateTimeInterface::ATOM) }}</updated>
            @endif
            <link rel="alternate" href="{{ $discussion->link }}"/>
            @if ($discussion->author)
                <author>
                    <name>{{ $discussion->author->name }}</name>
                    <uri>{{ $discussion->author->uri }}</uri>
                </author>
            @else
                <author>
                    <?php /** @var \Flarum\Locale\Translator $translator */ ?>
                    <name>{{ $translator->trans('core.lib.username.deleted_text') }}</name>
                </author>
            @endif
            <summary type="text">{{ $discussion->summary }}</summary>
        </entry>
    @endforeach
</feed>
