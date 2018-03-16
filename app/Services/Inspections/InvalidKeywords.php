<?php

namespace App\Services\Inspections;

use App\Services\Inspections\Contract\SpamContract;
use Exception;

class InvalidKeywords implements SpamContract
{
    /**
     * All registered invalid keywords.
     *
     * @var array
     */
    protected $keywords;

    public function __construct()
    {
        $this->keywords = explode(',', setting('security_spam_keywords', null, ''));

    }

    /**
     * Detect spam.
     *
     * @param  string $body
     * @throws \Exception
     */
    public function detect($body)
    {
        foreach ($this->keywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('Your reply contains spam.');
            }
        }
    }
}