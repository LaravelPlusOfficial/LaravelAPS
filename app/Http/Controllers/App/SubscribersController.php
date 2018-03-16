<?php

namespace App\Http\Controllers\App;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscribersController extends Controller
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $attributes = $request->validate(['email' => 'required|email|unique:subscribers,email']);

        return Subscriber::create($attributes);
    }

    /**
     * @param $email
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($email)
    {
        Subscriber::whereEmail($email)->firstOrFail()->delete();

        return response('Email unsbscribed', 200);
    }

}
