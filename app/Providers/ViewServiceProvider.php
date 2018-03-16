<?php

namespace App\Providers;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \View::composer('*', function ($view) {
            $view->with('javascripVars', "window.App = {$this->getJavascriptVars()}");
            $view->with('user', \Auth::user());
        });

        if (\App::environment('development', 'local')) {
            \View::share('faker', Factory::create());
            \View::share('carbon', new Carbon());
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getJavascriptVars()
    {
        $vars = [
            'csrfToken'        => csrf_token(),
            'user'             => $this->getUser(),
            'signedIn'         => \Auth::check(),
            'currentUri'       => request()->getRequestUri(),
            'recaptcha'        => [
                'sitekey' => config('services.google.recaptcha_site_key'),
                'theme'   => 'light'
            ],
            'fb_app_id'        => config('services.facebook.app_id'),
            'default_avatar'   => setting('site_default_user_avatar'),
            'social_auto_post' => $this->getSocialAutoPostCredentials()
        ];

        return json_encode($vars);

    }

    /**
     * @return array|null
     */
    protected function getUser()
    {
        $user = null;

        if (\Auth::check()) {
            $user = [
                'id'       => \Auth::id(),
                'name'     => \Auth::user()->full_name,
                'verified' => \Auth::user()->email_verified ? true : false
            ];
        }

        return $user;
    }

    protected function getSocialAutoPostCredentials()
    {
        if (optional(\Auth::user())->can('manage.settings')) {
            return [
                // 'google_client_id'     => config('services.google.social_auto_publish_client_id'),
                // 'google_api_key'       => config('services.google.social_auto_publish_client_api_key'),
                // 'google_client_secret' => config('services.google.social_auto_publish_client_secret'),
                'google_redirect'      => config('services.google.social_auto_publish_redirect'),
            ];
        }

        return [];
    }
}
