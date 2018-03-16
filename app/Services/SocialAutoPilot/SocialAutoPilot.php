<?php

namespace App\Services\SocialAutoPilot;


use App\Models\Post;

class SocialAutoPilot
{

    /**
     * @var Post
     */
    protected $post;

    /**
     * @var bool
     */
    protected $rePublish;

    /**
     * SocialAutoPilot constructor.
     *
     * @param Post $post
     * @param bool $rePublish
     */
    public function __construct(Post $post, $rePublish = false)
    {
        $this->post = $post;

        $this->rePublish = $rePublish;
    }

    /**
     * @param $providerOrProviders
     */
    public function publish($providerOrProviders)
    {

        if (is_array($providerOrProviders)) {

            foreach ($providerOrProviders as $provider) {

                if ($this->publishingEnabled($provider)) {

                    $this->publishProvider($provider);
                }

            }
        }

        if (is_string($providerOrProviders)) {

            if ($this->publishingEnabled($providerOrProviders)) {

                $this->publishProvider($providerOrProviders);
            }

        }

    }

    protected function publishingEnabled($provider)
    {
        if ($this->rePublish) {
            return true;
        }

        $metaOfProvider = $this->post->metas->where('key', "auto_post_{$provider}")->first();

        return optional($metaOfProvider)->value == 'enable';
    }

    protected function publishProvider($provider)
    {
        // App\Services\SocialAutoPilot\Publishers\FacebookPublisher
        $method = 'App\Services\SocialAutoPilot\Publishers\\' . ucfirst("{$provider}Publisher");

        try {

            app()->make($method)->publish($this->post);

        } catch (\Exception $e) {

        }
    }

}