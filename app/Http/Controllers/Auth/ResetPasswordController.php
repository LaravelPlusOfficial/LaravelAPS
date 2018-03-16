<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Seo\Contract\SeoContract;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    /**
     * @var SeoContract
     */
    private $seo;

    /**
     * Create a new controller instance.
     *
     * @param SeoContract $seo
     */
    public function __construct(SeoContract $seo)
    {
        $this->middleware('guest');

        $this->seo = $seo;
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string|null $token
     * @return \Illuminate\Contracts\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        $view = view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );

        return $this->seo->setMetaTags($view, [
            'type'  => 'page',
            'metas' => [
                'seo_title'       => 'Change password',
                'seo_description' => 'Reset old password'
            ]
        ]);

    }
}
