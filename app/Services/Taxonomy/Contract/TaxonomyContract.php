<?php
namespace App\Services\Taxonomy\Contract;


interface TaxonomyContract
{
    public function getCategories();

    public function getThreadedCategories();

    public function getTags();

}