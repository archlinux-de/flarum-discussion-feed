## Simple and efficient Atom feed for Flarum

This simple extensions generates a feed of recent created discussions. A scheduled task will regulary update the feed on
disk so it can be served by your web server without invoking Flarum or PHP. This is benefitial if you have a lot of
subscribers pulling your feed in short intervals.

### Installation

#### Install and enable the extension

```sh
composer require archlinux-de/flarum-discussion-feed
```

Now enable the extension in your admin backend.

#### Web server setup

You might want to add the following rule to your Nginx config

```
location = /feed.xml {
    types { } default_type "application/atom+xml; charset=utf-8";
    expires 15m;
}
```

#### Enable Flarum's scheduler

Refer to [schedule:run](https://docs.flarum.org/console.html#schedule-run) to setup your Flarum scheduler.

#### Permissions

Make sure the user which runs the scheduler has permissions to create the feed.xml file within Flarum's public
directory.
