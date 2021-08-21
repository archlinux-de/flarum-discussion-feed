## Simple and efficient Atom feed for Flarum

### Installation

```sh
composer require archlinux-de/flarum-discussion-feed
```
### Web server setup
You might want to add the following rule to your Nginx config
```
location = /feed.xml {
    expires 15m;
}
```
