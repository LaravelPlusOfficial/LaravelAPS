<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MediaRequest;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        if ($request->wantsJson()) {

            if ($request->user()->can('manage.media')) {
                return Media::latest()->paginate(20);
            }

            return Media::where('uploaded_by', \Auth::id())->latest()->paginate(20);

        }

        return view('admin.media.index');
    }

    public function store(MediaRequest $request)
    {
        return $request->uploadMedia();
    }

    /**
     * @param Media $media
     * @return bool|null
     * @throws \Exception
     */
    public function destroy(Media $media)
    {
        $this->authorize('manage', $media);

        if ($media->delete()) {

            $disk = \Storage::disk(config('aps.media.disk'));

            foreach ($media->variations as $variation) {

                if ($disk->exists($variation['path'])) {

                    $disk->delete($variation['path']);

                }

            }

            return response("Media deleted", 200);
        }

        return response("Error", 500);
    }

}
