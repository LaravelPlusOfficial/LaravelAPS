<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Google\GoogleClient;
use Google_Service_People;
use App\Http\Controllers\Controller;
use App\Http\Requests\SocialiteRequest;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{

    public function __construct()
    {
        $this->middleware(['guest']);
    }

    /**
     * @param $provider
     * @return mixed
     */
    public function toProvider($provider)
    {
        if (!$this->registerationOpen()) {
            abort(404);
        }

        $driver = Socialite::driver($provider);


        return $provider == 'google' ?
            $driver->scopes(['openid', 'profile', 'email', Google_Service_People::CONTACTS_READONLY])->redirect() :
            $driver->redirect();

    }

    /**
     * @param SocialiteRequest $request
     * @param                  $provider
     * @return mixed
     */
    public function fromProvider(SocialiteRequest $request, $provider)
    {
        if (!$this->registerationOpen()) {
            abort(404);
        }

        $user = Socialite::driver($provider)->user();

        return $request->handleCallback($user, $provider);
    }

    protected function registerationOpen()
    {
        return (bool)setting('users_registeration_enabled', 'disable') == 'enable';
    }
}
