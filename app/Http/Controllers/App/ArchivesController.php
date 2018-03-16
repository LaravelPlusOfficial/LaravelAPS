<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\Archives\Contract\ArchiveContract;

class ArchivesController extends Controller
{

    public function index(ArchiveContract $archive)
    {
        return $archive
            ->getResult(
                request()->route()->parameters()['archiveType'],
                request()->route()->parameters()['archiveSlug']
            );
    }

}
