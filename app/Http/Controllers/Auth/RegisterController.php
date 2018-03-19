<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Seo\Contract\SeoContract;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Auth\Traits\Registerable;

class RegisterController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, Registerable {
        Registerable::registered insteadof RegistersUsers;
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = null;

    /**
     * Create a new controller instance.
     *
     * @param SeoContract $seo
     * @param Request $request
     */
    public function __construct(SeoContract $seo, Request $request)
    {
        $this->middleware('guest');

        $this->seo = $seo;

        $this->request = $request;

        $this->setDefaultRole();

        $this->redirectTo = route('admin.dashboard');
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showRegistrationForm()
    {
        return $this->seo->setMetaTags(view('auth.register'), [
            'type'  => 'page',
            'metas' => [
                'seo_title'       => 'Register',
                'seo_description' => 'Register to laravelplus.com'
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register()
    {
        if (!$this->registerationOpen()) {
            abort(404);
        }

        $this->validator()->validate();

        $user = $this->create();

        return $this->registered($user) ?: redirect($this->redirectPath());
    }

}