<?php

namespace App\Services\Archives\Contract;


interface ArchiveContract
{

    public function getResult($type, $slug = null);
}