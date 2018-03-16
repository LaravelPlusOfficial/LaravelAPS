<?php

namespace App\Services\SocialAutoPilot\Provider;


use Facebook\PersistentData\PersistentDataInterface;

class FacebookPersistentDataHandler implements PersistentDataInterface
{

    protected $sessionPrefix = 'facebook_social_auto_post_';

    /**
     * Get a value from a persistent data store.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return \Session::get($this->sessionPrefix . $key);
    }

    /**
     * Set a value in the persistent data store.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        \Session::put($this->sessionPrefix . $key, $value);
    }
}