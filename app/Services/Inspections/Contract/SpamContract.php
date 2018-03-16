<?php

namespace App\Services\Inspections\Contract;


interface SpamContract
{

    public function detect($body);

}