<?php

namespace App\Services\SocialAutoPilot\Publishers;


use App\Models\Post;
use App\Services\SocialAutoPilot\Provider\FacebookClientManager;
use App\Services\SocialAutoPilot\Provider\FacebookProvider;
use Illuminate\Support\Facades\Log;

class FacebookPublisher implements Publisher
{

    /**
     * @var
     */
    protected $post;

    /**
     * @var \Facebook\Facebook|string
     */
    protected $client;

    /**
     * @var FacebookProvider
     */
    protected $provider;

    /**
     * @var
     */
    protected $token;

    /**
     * @var \App\Models\Setting|\App\Services\Settings\Contract\SettingContract|string
     */
    protected $pageId;

    /**
     * FacebookPublisher constructor.
     */
    public function __construct()
    {
        $this->client = (new FacebookClientManager())->getClient();

        $this->provider = new FacebookProvider();

        $this->pageId = $this->provider->getPageId();
    }

    /**
     * @param Post $post
     * @return bool|mixed
     */
    public function publish(Post $post)
    {
        $this->post = $post;

        if ($this->clientIsInvalid()) {
            return Log::debug("Facebook SDK Credentials are not valid. Not able auto publish post on page");
        }

        if ($this->hasInvalidToken()) {
            return Log::debug("Facebook token is invalid. Unable to publish post to page.");
        }

        try {

            $data = $this->formatPost();

            $this->client->post("/{$this->pageId}/feed", $data, $this->token->access_token);

        } catch (\Exception $e) {

            Log::error($e->getMessage());

        }

    }


    /**
     * @return array
     */
    protected function formatPost()
    {
        if (app()->isLocal()) {
            return [
                'link'    => $this->convertUrl(route('post.show.short-url', $this->post->short_url)),
                'name'    => $this->post->title,
                'message' => $this->post->excerpt ?? $this->post->title,
                'caption' => $this->convertUrl(url('/')),
                'picture' => $this->convertUrl($this->getImage())
            ];
        }

        return [
            'link'    => route('post.show.short-url', $this->post->short_url),
            'name'    => $this->post->title,
            'message' => $this->post->excerpt ?? $this->post->title,
            'caption' => url('/'),
            'picture' => $this->getImage()
        ];
    }

    protected function convertUrl($url)
    {
        return str_replace('http://agam.test', 'http://laravelplus.com', $url);
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    protected function getImage()
    {
        if ($imagePath = optional($this->post->featuredImage)->variations['large']['path']) {

            return url($imagePath);

        }

        return url('site/defaults/post-image-large.png');
    }

    /**
     * @return bool
     */
    protected function clientIsInvalid()
    {
        return is_string($this->client);
    }

    /**
     * @return bool
     */
    protected function hasInvalidToken()
    {
        if ($token = $this->provider->hasValidToken()) {

            $this->token = $token;

            return false;
        }

        return true;
    }

}