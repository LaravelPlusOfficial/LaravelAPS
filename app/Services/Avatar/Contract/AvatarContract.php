<?php
namespace App\Services\Avatar\Contract;


use App\Models\User;
use Illuminate\Http\UploadedFile;

interface AvatarContract
{

    /**
     * @param User $user
     * @param UploadedFile $file
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function store(User $user, UploadedFile $file);

    /**
     * @param User $user
     * @param string $url
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function storeFromUrl(User $user, string $url);

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete(User $user);
}