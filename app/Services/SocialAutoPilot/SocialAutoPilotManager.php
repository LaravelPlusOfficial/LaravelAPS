<?php

namespace App\Services\SocialAutoPilot;


use App\Services\SocialAutoPilot\Provider\FacebookProvider;
use Illuminate\Http\Request;

class SocialAutoPilotManager
{

    /**
     * @var string
     */
    protected $provider;

    /**
     * @var Request
     */
    private $request;
    /**
     * @var null
     */
    private $action;

    /**
     * SocialAutoPilotManager constructor.
     * @param string|null  $provider
     * @param Request|null $request
     * @param null         $action
     */
    public function __construct(string $provider = null, Request $request = null, $action = null)
    {
        $this->provider = ucfirst(camel_case(str_replace('-', ' ', $provider)));

        $this->request = $request;

        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function enable()
    {
        $methodName = "enable{$this->provider}";

        if (method_exists($this, $methodName)) {
            return $this->{$methodName}();
        }

        abort(404);
    }

    /**
     * @return mixed
     */
    public function disable()
    {
        $methodName = "disable{$this->provider}";

        if (method_exists($this, $methodName)) {
            return $this->{$methodName}();
        }

        abort(404);
    }

    /**
     * @return mixed
     */
    public function processProvider()
    {
        $methodName = "process{$this->provider}";

        if (method_exists($this, $methodName)) {
            return $this->{$methodName}();
        }

        abort(404);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function enableFacebook()
    {
        return (new FacebookProvider($this->request))->enable();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function processFacebook()
    {
        return (new FacebookProvider($this->request))->fetchPageToken();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function disableFacebook()
    {
        return (new FacebookProvider($this->request))->disable();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function enableTwitter()
    {
        setting('social_auto_post_twitter', null, 'enable');

        return redirect()->route('admin.setting.index')->with('success', 'Twitter Enabled');
    }

    protected function disableTwitter()
    {
        setting('social_auto_post_twitter', null, 'disable');

        return redirect()->route('admin.setting.index')->with('success', 'Twitter Disabled');
    }

}