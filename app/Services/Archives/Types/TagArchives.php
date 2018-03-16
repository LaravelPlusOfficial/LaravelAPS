<?php

namespace App\Services\Archives\Types;


class TagArchives extends ArchiveType
{

    public function getResultSet()
    {
        $resultSet = $this->getPosts('tags')->paginate();

        $this->view = $this->getView()->with([
            'posts' => $resultSet
        ]);

        return $this->metanateView([
            'type'      => 'archive',
            'paginator' => $resultSet,
            'main_type' => 'tag',
            'sub_type'  => $this->subType
        ]);

    }

}