<?php

use Illuminate\Database\Seeder;

class TaxonomiesTableSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $categories;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @var \Faker\Factory
     */
    protected $faker;

    /**
     * @var \App\Models\Category
     */
    protected $category;
    /**
     * @var \App\Models\Tag
     */
    protected $tag;

    /**
     * TaxonomiesTableSeeder constructor.
     *
     * @param \App\Models\Category $category
     * @param \App\Models\Tag $tag
     */
    public function __construct(\App\Models\Category $category, \App\Models\Tag $tag)
    {
        $this->faker = \Faker\Factory::create();

        $this->tags = [
            'Laravel 5.6',
            'Laravel 5.5',
            'vue2'
        ];

        $this->tag = $tag;

        $this->categories = [
            'Tutorials',
            'Packages',
            'Laravel',
            'Vue',
            'React Native',
            'Electron'
        ];

        $this->category = $category;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createTags();

        $this->createCategories();
    }

    /**
     * Generate tags
     */
    protected function createTags()
    {
        foreach ($this->tags as $tag) {
            factory(\App\Models\Tag::class)->create(['name' => $tag]);
        }
    }

    /**
     * Create single category
     */
    protected function createCategories()
    {
        foreach ($this->categories as $category => $childCategories) {

            if (is_array($childCategories)) {

                $parentCategory = $this->persistCategory($category);

                $this->persistChildCategory($parentCategory, $childCategories);

            } else {

                $this->persistCategory($childCategories);

            }

        }
    }

    /**
     * @param      $category
     * @param null $parentId
     *
     * @return mixed
     */
    protected function persistCategory($category, $parentId = NULL)
    {
        return factory(\App\Models\Category::class)->create([
            'name'      => $category,
            'parent_id' => $parentId
        ]);
    }

    /**
     * @param $parentCategory
     * @param $childCategories
     */
    protected function persistChildCategory($parentCategory, $childCategories)
    {
        foreach ($childCategories as $category => $title) {

            if (!is_array($title)) {
                $this->persistCategory($title, $parentCategory->id);
            } else {
                $persistCat = $this->persistCategory($category, $parentCategory->id);

                $this->persistChildCategory($persistCat, $title);

            }

        }
    }
}
