<?php

namespace App\Http\Controllers\Social;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SocialAutoPilot\SocialAutoPilotManager;

class AutoPublishController extends Controller
{

    /**
     * @param         $provider
     * @param Request $request
     * @return mixed
     */
    public function enableProvider($provider, Request $request)
    {
        return (new SocialAutoPilotManager($provider, $request))->enable();
    }

    /**
     * @param         $provider
     * @param Request $request
     * @return mixed
     */
    public function fromProvider($provider, Request $request)
    {
        return (new SocialAutoPilotManager($provider, $request))->processProvider();
    }

    /**
     * @param         $provider
     * @param Request $request
     * @return mixed
     */
    public function disableProvider($provider, Request $request)
    {
        return (new SocialAutoPilotManager($provider, $request))->disable();
    }
}
