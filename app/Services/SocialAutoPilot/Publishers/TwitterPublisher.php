<?php

namespace App\Services\SocialAutoPilot\Publishers;


use Abraham\TwitterOAuth\TwitterOAuth;
use App\Models\Post;

class TwitterPublisher implements Publisher
{
    protected $post;

    /**
     * Publish Post to provider
     *
     * @param Post $post
     * @return mixed
     */
    public function publish(Post $post)
    {
        $this->post = $post;

        try {

            $url = route('post.show.short-url', $this->post->short_url);

            $connection = $this->getConnection();

            $parameters = [
                'status'    => "{$this->post->title}\n{$url}",
                'media_ids' => implode(',', [$this->uploadImageToTwitter($connection)->media_id_string])
            ];

            $connection->post('statuses/update', $parameters);

        } catch (\Exception $e) {

            Log::error($e->getMessage());

        }
    }

    protected function getImagePath()
    {
        if ($imagePath = optional($this->post->featuredImage)->variations['large']['path']) {

            return storage_path() . '/app/public' . $imagePath;

        }

        return public_path('site/defaults/post-image-large.png');
    }

    protected function getConnection()
    {
        return new TwitterOAuth(
            config('services.twitter.client_id'),
            config('services.twitter.client_secret'),
            config('services.twitter.access_token'),
            config('services.twitter.access_secret')
        );

    }

    protected function uploadImageToTwitter($connection)
    {
        $imagePath = $this->getImagePath();

        return $connection->upload('media/upload', ['media' => $imagePath]);
    }
}