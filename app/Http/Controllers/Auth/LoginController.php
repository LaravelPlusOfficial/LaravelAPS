<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Seo\Contract\SeoContract;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';


    /**
     * @var SeoContract
     */
    protected $seo;

    /**
     * Create a new controller instance.
     *
     * @param SeoContract $seo
     */
    public function __construct(SeoContract $seo)
    {
        $this->middleware('guest')->except('logout');

        $this->seo = $seo;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showLoginForm()
    {
        return $this->seo->setMetaTags(view('auth.login'), [
            'type'  => 'page',
            'metas' => [
                'seo_title'       => 'Login',
                'seo_description' => 'Login to laravelplus.com'
            ]
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function redirectTo()
    {
        if (request()->exists('backUrl')) {
            return url(request()->get('backUrl'));
        }

        return route('admin.dashboard');
    }
}