<?php

namespace App\Services\SocialAutoPilot\Provider;


use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Support\Facades\Log;

class FacebookClientManager
{

    /**
     * @return Facebook|string
     */
    public function getClient()
    {
        try {

            return new Facebook([
                'app_id'                  => config('services.facebook.app_id'),
                'app_secret'              => config('services.facebook.client_secret'),
                'default_graph_version'   => 'v2.11',
                'persistent_data_handler' => new FacebookPersistentDataHandler()
            ]);

        } catch (FacebookSDKException $e) {

            Log::error("Facebook sdk errors in class " . class_basename(FacebookClientManager::class) . " check credentials");

            return $this->getCredentialsError();

        }
    }

    /**
     * @return string
     */
    protected function getCredentialsError()
    {
        $msg = 'Facebook SDK has credential error <br />';
        $msg .= 'Check your credentials in .env file <br />';
        $msg .= 'Make sure FACEBOOK_APP_ID & FACEBOOK_APP_SECRET are defined';

        return $msg;
    }

    /**
     * @param $token
     * @return string
     */
    public function graphUrl($token)
    {
        return "https://graph.facebook.com/me?access_token=" . $token;
    }

    /**
     * @param $URL
     * @return bool|mixed
     */
    public function curlGetFileContents($URL)
    {
        $c = curl_init();

        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($c, CURLOPT_URL, $URL);

        $contents = curl_exec($c);

        curl_getinfo($c, CURLINFO_HTTP_CODE);

        curl_close($c);

        return $contents ?? false;
    }

    /**
     * @param $token
     * @param $pageId
     * @return bool
     */
    public function hasValidToken($token, $pageId)
    {
        $queryUrl = $this->graphUrl($token->access_token);

        $check = $this->curlGetFileContents($queryUrl);

        if (isset($check['error'])) {

            return false;
        }

        if ($token->id != $pageId) {

            return false;

        }

        return $token;
    }


}