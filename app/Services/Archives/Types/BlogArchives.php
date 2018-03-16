<?php

namespace App\Services\Archives\Types;


class BlogArchives extends ArchiveType
{

    public function getResultSet()
    {
        $resultSet = $this->getPosts()->paginate();

        $this->view = $this->getView('blog')->with([
            'posts' => $resultSet
        ]);

        return $this->metanateView([
            'type'      => 'blog',
            'paginator' => $resultSet,
            'metas'     => [
                'seo_title'       => setting('blog_title', 'Blog'),
                'seo_description' => setting('blog_description', 'Welcome to our blog')
            ]
        ]);
    }
}