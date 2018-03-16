<?php

namespace App\Services\SocialAutoPilot\Provider;


use Illuminate\Http\Request;

class FacebookProvider
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $permissions = ['email,publish_pages,publish_actions'];

    /**
     * @var \Facebook\Facebook|string
     */
    protected $client;


    /**
     * @var \App\Models\Setting|\App\Services\Settings\Contract\SettingContract|string
     */
    protected $pageId;

    /**
     * @var FacebookClientManager
     */
    protected $clientManager;

    /**
     * FacebookProvider constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request;

        $this->clientManager = new FacebookClientManager();

        $this->client = $this->clientManager->getClient();

        $this->pageId = setting('facebook_page_id');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable()
    {
        if (!$this->pageId) {

            $this->clearFacebookToken();

            return $this->redirectToSettings('Facebook <u>PAGE ID</u> is not specified in <u>facebook section</u>');
        }

        // If is string its an error message came frm FacebookClient Class
        if (is_string($this->client)) {

            return $this->redirectToSettings($this->client);

        }

        if ($this->client) {

            return redirect($this->getCallbackUrl());
        }


        return $this->redirectToSettings('Unknown error encountered, while processing this request');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable()
    {
        $this->clearFacebookToken();

        return $this->redirectToSettings(null, 'Facebook auto post disabled');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fetchPageToken()
    {
        $helper = $this->client->getRedirectLoginHelper();

        // Get user access token
        try {
            $userAccessToken = $helper->getAccessToken();
        } catch (\Exception $e) {
            $this->clearFacebookToken();
            return $this->redirectToSettings($e->getCode() . ' - ' . $e->getMessage());
        }

        // Get user accounts
        if (isset($userAccessToken)) {
            try {
                $accounts = $this->client->get('me/accounts', $userAccessToken->getValue());
            } catch (\Exception $e) {
                $this->clearFacebookToken();

                return $this->redirectToSettings($e->getCode() . ' - ' . $e->getMessage());
            }
        }

        // Get page detail
        if (isset($accounts)) {

            try {

                $pageData = collect($accounts->getDecodedBody()['data'])->where('id', $this->pageId)->first();

                unset($pageData['perms']);

                setting('social_auto_post_facebook_token', null, $this->encodedToken($pageData));

                setting('social_auto_post_facebook', null, 'enable');

                return $this->redirectToSettings(null, 'Facebook Enabled');

            } catch (\Exception $e) {

                $this->clearFacebookToken();

                return $this->redirectToSettings($e->getMessage());

            }

        }

        $this->clearFacebookToken();

        return $this->redirectToSettings('Something went wrong while fetching token');
    }

    /**
     * @param null $errors
     * @param null $success
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToSettings($errors = null, $success = null)
    {
        $redirect = redirect()->route('admin.setting.index');

        if ($errors) {
            $redirect->withErrors($errors);
        }

        if ($success) {
            $redirect->with('success', $success);
        }

        return $redirect;
    }

    /**
     * @return string
     */
    protected function getCallbackUrl()
    {
        $callback = route('social.auto.publish.from.provider', 'facebook');

        return $this->client->getRedirectLoginHelper()->getLoginUrl($callback, $this->permissions);
    }

    /**
     * Clear facebook token from database
     */
    public function clearFacebookToken()
    {
        setting('social_auto_post_facebook_token', null, null, true);

        setting('social_auto_post_facebook', null, 'disable');
    }

    /**
     *
     */
    public function getStoredToken()
    {
        setting('social_auto_post_facebook_token', null);
    }


    /**
     * @param $token
     * @return string
     */
    protected function encodedToken($token)
    {
        return base64_encode(json_encode($token));
    }

    /**
     * @return mixed|null
     */
    public function decodedToken()
    {
        if ($token = setting('social_auto_post_facebook_token', null)) {

            return $this->decodeToken($token);
        }

        return null;
    }

    /**
     * @param $token
     * @return mixed
     */
    protected function decodeToken($token)
    {
        return json_decode(base64_decode($token));
    }

    /**
     * @return \App\Models\Setting|\App\Services\Settings\Contract\SettingContract|string
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * @return bool
     */
    public function hasValidToken()
    {
        if ($token = $this->decodedToken()) {

            if ($validToken = $this->clientManager->hasValidToken($token, $this->pageId)) {
                return $validToken;
            }

        }

        $this->clearFacebookToken();

        return false;
    }

}