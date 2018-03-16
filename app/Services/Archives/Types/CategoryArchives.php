<?php

namespace App\Services\Archives\Types;


class CategoryArchives extends ArchiveType
{

    public function getResultSet()
    {
        $resultSet = $this->getPosts()->paginate();

        $this->view = $this->getView()->with([
            'posts' => $resultSet
        ]);

        return $this->metanateView([
            'type'      => 'archive',
            'paginator' => $resultSet,
            'main_type' => 'category',
            'sub_type'  => $this->subType
        ]);

    }

}