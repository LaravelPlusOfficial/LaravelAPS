<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Seo\Contract\SeoContract;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
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
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showLinkRequestForm()
    {
        $view = view('auth.passwords.email');

        return $this->seo->setMetaTags($view, [
            'type'  => 'page',
            'metas' => [
                'seo_title'       => 'Reset password',
                'seo_description' => 'Request a password reset link'
            ]
        ]);
    }
}
