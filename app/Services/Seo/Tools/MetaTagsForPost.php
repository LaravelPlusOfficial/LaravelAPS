<?php

namespace App\Services\Seo\Tools;


use App\Models\Post;
use App\Services\Seo\Contract\MetaTagsContract;

class MetaTagsForPost extends MetaTags implements MetaTagsContract
{

    /**
     * @var Post
     */
    protected $post;

    protected $author;

    protected $postMetas;

    /**
     * Get post from options
     */
    public function before()
    {
        $this->post = $this->options['post'];

        $this->post->metas->each(function ($meta) {
            $this->postMetas[$meta->key] = $meta->value;
        });

        $this->author = $this->post->author;
    }

    /**
     * @param Post $post
     * @return MetaTags
     */
    public function setPost(Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @param null $value
     */
    protected function seoTitle($value = null)
    {
        $this->addTitle($value ?: $this->post->title);
    }

    /**
     * @param null $value
     */
    protected function seoDescription($value = null)
    {
        $value = $this->getMeta('description') ?: $value;

        $this->addDescription($value ?: $this->post->excerpt);
    }

    /**
     * @param null $value
     */
    protected function seoUrl($value = null)
    {
        $this->addUrl($value ?: $this->post->path);
    }

    protected function ogType($value = null)
    {
        $this->addOgType($value ?: 'article');
    }

    /**
     * @param null $value
     * @throws \Exception
     */
    protected function seoImage($value = null)
    {
        try {
            $image = $this->post->featuredImage->variations['large']['path'];
        } catch (\Exception $e) {
            $image = url(setting('blog_image', mix('/site/defaults/site-share.png')));
        }
        $this->addImage($value ?: $image);
    }

    /**
     * @param null $value
     */
    protected function articleSection($value = null)
    {
        $this->addArticleSection($value ?: ucwords(str_replace('.', ' ', $this->post->post_type)));
    }

    /**
     * @param null $value
     */
    protected function articleAuthor($value = null)
    {
        try {
            $fbAuthor = $this->author->social_links['facebook_url'];
        } catch (\Exception $e) {
            $fbAuthor = null;
        }

        $this->addArticleAuthor($value ?: $fbAuthor);

    }

    /**
     * @param null $value
     */
    protected function publishedAt($value = null)
    {
        $this->addPublishDate($value ?: $this->post->publish_at);
    }

    /**
     * @param null $value
     */
    protected function updatedAt($value = null)
    {
        $this->addUpdateDate($value ?: $this->post->updated_at);
    }

    /**
     * @param null $value
     */
    protected function canonical($value = null)
    {
        $this->addCanonical($value ?: request()->url());
    }

    /**
     * @param null $value
     */
    protected function authorName($value = null)
    {
        $this->addAuthorName($value ?: $this->post->author->full_name);
    }

    public function twitterCreator($value = null)
    {
        $name = isset($this->author->social_links['twitter_username']) ? $this->author->social_links['twitter_username'] : null;
        $this->addTwitterCreator($value ?: $name);
    }

    public function articleAuthorGooglePlus($value = null)
    {
        $name = isset($this->author->social_links['google_plus_url']) ? $this->author->social_links['google_plus_url'] : null;
        $this->addArticleAuthorGooglePlus($value ?: $name);
    }

    public function robots($value = null)
    {
        $value = $value ?? $this->getMeta('robots');

        $this->addRobots($value ?? setting('seo_robots_meta', "index, follow"));
    }

}