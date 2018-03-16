<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MustBeSocialLink implements Rule
{

    protected $socials = [];

    protected $message = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->socials = config('aps.users.allowed_social_links', []);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  array $socialLinks
     * @return bool
     */
    public function passes($attribute, $socialLinks)
    {
        if (!is_array($socialLinks)) {
            $this->message = 'Social Links are provided in wrong format';
            return false;
        }

        if (!$this->checkValidKeys($socialLinks)) {
            return false;
        }

        if (!$this->checkIfValuesAreValid($socialLinks)) {
            return false;
        }

        return true;

    }


    protected function checkValidKeys(array $socialLinks)
    {
        foreach ($socialLinks as $name => $value) {

            if (!array_key_exists($name, $this->socials)) {

                $this->message = "Social link {$name} is not the valid link";

                return false;

            }

        }
        return true;
    }

    protected function checkIfValuesAreValid(array $socialLinksProvided)
    {
        foreach ($socialLinksProvided as $name => $userValue) {

            if (empty($userValue)) {
                continue;
            }

            $toCheckFromConfig = trim(strtolower($this->socials[$name]));

            $name = title_case(str_replace('_', ' ', $name));

            // Check if performing twitter username
            if ($toCheckFromConfig == '@') {

                if ( ! starts_with($userValue, $toCheckFromConfig) || ! $this->validTwitterName($userValue)) {

                    $this->message = "Social link for '{$name}' is not valid";

                    return false;

                }

            }

            $userValue = trim(str_replace(' ', '', strtolower($userValue)));

            if (!starts_with($userValue, $toCheckFromConfig)) {

                $this->message = "Social link for '{$name}' is not valid";

                return false;
            }
        }

        return true;
    }

    protected function validTwitterName($username)
    {
        return (bool)preg_match('/^[@A-Za-z0-9_]+$/', $username);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
