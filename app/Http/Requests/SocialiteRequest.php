<?php

namespace App\Http\Requests;

use App\Models\User;
use Google_Client;
use Google_Service_People;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SocialiteRequest extends FormRequest
{
    /**
     * @var
     */
    protected $user;

    /**
     * @var
     */
    protected $data;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @param $user
     * @param $provider
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function handleCallback($user, $provider)
    {
        $this->user = $user;

        $method = "{$provider}Callback";

        if (method_exists($this, $method)) {

            $this->$method();

            if (isset($this->data['email']) && !empty($this->data['email'])) {

                if ($user = $this->userAlreadyInDatabase($this->data['email'])) {

                    Auth::login($user);

                    return redirect()->route('admin.dashboard');

                }

                return view('auth.passwords.chooser-password')->with([
                    'name'  => $this->data['name'],
                    'email' => $this->data['email'],
                    'data'  => $this->getDataForRegisteration()
                ]);

            }

        }

        abort(500, "No processing");
    }

    protected function googleCallback()
    {
        // Set token for the Google API PHP Client
        $google_client_token = [
            'access_token'  => $this->user->token,
            'refresh_token' => $this->user->refreshToken,
            'expires_in'    => $this->user->expiresIn
        ];

        $client = new Google_Client();
        $client->setApplicationName("Laravel");
        $client->setDeveloperKey(env('GOOGLE_SERVER_KEY'));
        $client->setAccessToken(json_encode($google_client_token));

        $service = new Google_Service_People($client);

        $optParams = ['requestMask.includeField' => 'person.phone_numbers,person.names,person.email_addresses,person.photos'];

        $result = $service->people->get('people/me', $optParams);

        $this->data = [
            'name'       => $result->getNames()[0]->givenName . ' ' . $result->getNames()[0]->familyName,
            'email'      => $result->getEmailAddresses()[0]->value,
            'avatar_url' => $result->getPhotos()[0]->url
        ];

        //dd($this->data);
    }

    /**
     * Get Fields from facebook
     */
    protected function facebookCallback()
    {
        $this->data = [
            'name'       => $this->user->getName(),
            'email'      => $this->user->getEmail(),
            'avatar_url' => removeQueryFromUrl($this->user->getAvatar(), '?type=large')
        ];
    }

    /**
     * Get Fields from Github
     */
    protected function githubCallback()
    {
        $this->data = [
            'name'       => $this->user->getName(),
            'email'      => $this->user->getEmail(),
            'avatar_url' => $this->user->getAvatar()
        ];
    }

    /**
     * Get Field from twitter
     */
    protected function twitterCallback()
    {
        $this->data = [
            'name'  => $this->user->getName(),
            'email' => $this->user->getEmail(),
        ];

        if (!$this->isTwitterDefaultAvatar()) {
            $this->data['avatar_url'] = str_replace('_normal.', '_400x400.', $this->user->getAvatar());
        } else {
            $this->data['avatar_url'] = $this->getDefaultImagePath();
        }

    }

    /**
     * @return string
     */
    protected function getDataForRegisteration()
    {
        $data = [
            'avatar_url'     => $this->data['avatar_url'],
            'email_verified' => true,
            'email'          => $this->data['email']
        ];

        return encrypt(base64_encode(json_encode($data)));
    }

    /**
     * @return bool
     */
    protected function isTwitterDefaultAvatar()
    {
        // http://abs.twimg.com/sticky/default_profile_images/default_profile_normal.png
        return str_contains($this->user->getAvatar(), ['/default_profile_images/default_profile_normal.']);
    }

    /**
     * @return string
     */
    protected function getDefaultImagePath()
    {
        return public_path('images/defaults/user.jpeg');
    }

    /**
     * @param $email
     * @return mixed
     */
    protected function userAlreadyInDatabase($email)
    {
        return User::where('email', trim($email))->first();
    }
}
