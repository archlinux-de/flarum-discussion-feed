## Simple and efficient Atom feed for Flarum

### Installation

```sh
composer require archlinux-de/flarum-discussion-feed
```
### Web server setup
You might want to add the following rule to your Nginx config
```
location = /feed.xml {
    types { } default_type "application/atom+xml; charset=utf-8";
    expires 15m;
}
```
