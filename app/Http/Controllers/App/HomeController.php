<?php

namespace App\Http\Controllers\App;

use App\Models\Post;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        return $this->getPageResult();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome()
    {
        return $this->getPageResult();
    }


    /**
     * Get result from page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function getPageResult()
    {
        $posts = Post::fetchAll()->latest()->publish()->simplePaginate(perPage());

        $view = view('app.pages.welcome', compact('posts'));

        return $view;
    }

}
