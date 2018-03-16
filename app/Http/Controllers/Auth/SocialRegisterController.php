<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Seo\Contract\SeoContract;
use App\Services\Avatar\Contract\AvatarContract;
use App\Http\Controllers\Auth\Traits\Registerable;

class SocialRegisterController extends Controller
{
    /*
     * Socialite User Register Controller
     *
     * This controller handles the registration of new users from
     * socialite providers as well as their validation and creation.
     */

    use Registerable;

    /**
     * @var SeoContract
     */
    protected $seo;


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
    }

    /**
     * @return \Illuminate\Contracts\View\View|mixed|null
     */
    public function register()
    {
        if ($response = $this->requestNotValid()) {
            return $response;
        }

        $user = $this->decryptSocialiteData()->create();

        return $this->registered($user);
    }

    /**
     * Check Request is valid, die silently if $data not provided
     * as $data we need to perform decryption and check if email is tempered or not
     *
     * @return \Illuminate\Contracts\View\View|null
     */
    protected function requestNotValid()
    {
        $validator = $this->validator();

        if (!$this->request->data) {
            abort(500);
        }

        return $validator->fails() ? $this->choosePasswordView($validator, $this->request) : null;
    }


    /**
     * Decrypt data came from socialite
     * will return avatar, email, name
     *
     * @return $this
     */
    protected function decryptSocialiteData()
    {
        $decryptedData = json_decode(base64_decode(decrypt($this->request->data)));

        if ($this->emailTempered($decryptedData->email, $this->request->email)) {
            abort(500);
        }

        $this->emailVerified = $decryptedData->email_verified;

        $this->avatarUrl = $decryptedData->avatar_url;

        return $this;
    }

    /**
     * Check if email from $provider is same as email from input
     *
     * @param $decryptedEmail
     * @param $requestEmail
     * @return bool
     */
    protected function emailTempered($decryptedEmail, $requestEmail)
    {
        return (bool)trim($decryptedEmail) != trim($requestEmail);
    }

    /**
     * Store user avatar
     *
     * @param $user
     */
    protected function storeAvatar($user)
    {
        try {
            if (filter_var($this->avatarUrl, FILTER_VALIDATE_URL) !== FALSE) {
                resolve(AvatarContract::class)->storeFromUrl($user, $this->avatarUrl);
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * @param $validator
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    protected function choosePasswordView($validator, Request $request)
    {
        $view = view('auth . passwords . chooser - password')
            ->withErrors($validator)
            ->with([
                'name'  => $request->name,
                'email' => $request->email,
                'data'  => $request->data
            ]);

        return $this->seo->setMetaTags($view, [
            'type'  => 'page',
            'metas' => [
                'seo_title'       => 'Choose password',
                'seo_description' => 'Choose strong password'
            ]
        ]);
    }

}