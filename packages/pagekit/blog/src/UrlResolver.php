<?php

namespace Pagekit\Blog;

use Pagekit\Application as App;
use Pagekit\Blog\Model\Post;
use Pagekit\Routing\ParamsResolverInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class UrlResolver implements ParamsResolverInterface
{
    const CACHE_KEY = 'blog.routing';

    protected bool $cacheDirty = false;

    protected array $cacheEntries;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->cacheEntries = App::cache()->fetch(self::CACHE_KEY) ?: [];
    }

    /**
     * {@inheritdoc}
     */
    public function match(array $parameters = []): array
    {
        if (isset($parameters['id'])) {
            return $parameters;
        }

        if (!isset($parameters['slug'])) {
            App::abort(404, 'Post not found.');
        }

        $slug = $parameters['slug'];

        $id = false;
        foreach ($this->cacheEntries as $entry) {
            if ($entry['slug'] === $slug) {
                $id = $entry['id'];
            }
        }

        if (!$id) {

            if (!$post = Post::where(compact('slug'))->first()) {
                App::abort(404, 'Post not found.');
            }

            $this->addCache($post);
            $id = $post->id;
        }

        $parameters['id'] = $id;

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(array $parameters = []): array
    {
        $id = $parameters['id'];

        if (!isset($this->cacheEntries[$id])) {

            if (!$post = Post::where(compact('id'))->first()) {
                throw new RouteNotFoundException('Post not found!');
            }

            $this->addCache($post);
        }

        $meta = $this->cacheEntries[$id];

        preg_match_all('#{([a-z]+)}#i', self::getPermalink(), $matches);

        if ($matches) {
            foreach($matches[1] as $attribute) {
                if (isset($meta[$attribute])) {
                    $parameters[$attribute] = $meta[$attribute];
                }
            }
        }

        unset($parameters['id']);
        return $parameters;
    }

    public function __destruct()
    {
        if ($this->cacheDirty) {
            App::cache()->save(self::CACHE_KEY, $this->cacheEntries);
        }
    }

    /**
     * Gets the blog's permalink setting.
     */
    public static function getPermalink(): string
    {
        static $permalink;

        if (null === $permalink) {

            $blog = App::module('blog');
            $permalink = $blog->config('permalink.type');

            if ($permalink == 'custom') {
                $permalink = $blog->config('permalink.custom');
            }

        }

        return $permalink;
    }

    protected function addCache($post): void
    {
        $this->cacheEntries[$post->id] = [
            'id'     => $post->id,
            'slug'   => $post->slug,
            'year'   => $post->date->format('Y'),
            'month'  => $post->date->format('m'),
            'day'    => $post->date->format('d'),
            'hour'   => $post->date->format('H'),
            'minute' => $post->date->format('i'),
            'second' => $post->date->format('s'),
        ];

        $this->cacheDirty = true;
    }
}
