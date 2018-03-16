<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\SocialAutoPilot\SocialAutoPilot;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PublishPostToSocialMedia implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Post
     */
    protected $post;

    protected $publisher;
    /**
     * @var bool
     */
    private $rePublish;
    /**
     * @var string
     */
    private $providerOrProviders;

    /**
     * Create a new job instance.
     *
     * @param Post $post
     * @param bool $rePublish
     * @param null $providerOrProviders
     */
    public function __construct(Post $post, $rePublish = false, $providerOrProviders = null)
    {
        $this->post = $post;

        $this->publisher = (new SocialAutoPilot($this->post, $rePublish));

        $this->rePublish = $rePublish;

        $this->providerOrProviders = $providerOrProviders;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (app()->runningInConsole()) return;

        $this->publisher->publish(
            $this->getProviders()
        );
    }

    protected function getProviders()
    {
        $default = [
            'facebook',
            'twitter'
        ];

        return $this->providerOrProviders ?? $default;
    }

}
