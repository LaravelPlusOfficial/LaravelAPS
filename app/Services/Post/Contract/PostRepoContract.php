<?php

namespace App\Services\Post\Contract;


interface PostRepoContract
{

    public function getPostsByType($type);

    public function getPostsByCategory($category);

}