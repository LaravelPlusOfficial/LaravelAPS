<?php

namespace App\Services\Archives;


use App\Services\Archives\Types\BlogArchives;
use App\Services\Archives\Types\TagArchives;
use App\Services\Archives\Types\CategoryArchives;
use App\Services\Archives\Contract\ArchiveContract;

class Archive implements ArchiveContract
{

    public function getResult($type, $slug = null)
    {
        $type = camel_case($type);

        $method = "{$type}Archive";

        if (method_exists($this, $method)) {
            return $this->$method($slug);
        }

        abort(404);

    }

    protected function categoryArchive($category)
    {
        return (new CategoryArchives())->setSubType($category)->getResultSet();
    }

    protected function tagArchive($tag)
    {
        return (new TagArchives())->setSubType($tag)->getResultSet();
    }

    protected function blogArchive()
    {
         return (new BlogArchives())->getResultSet();
    }

}