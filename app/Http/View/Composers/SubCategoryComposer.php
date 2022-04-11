<?php

namespace App\Http\View\Composers;

use App\Models\SubCategory;
use Illuminate\View\View;

class SubCategoryComposer
{
    /**
     * The SubCategory repository implementation.
     *
     * @var Bind SubCategory model
     */
    protected $subCategories;

    /**
     * Dropdown variable name associated with class
     *
     * @var string
     */
    protected $name = 'subCategories';

    /**
     * Create a new profile composer.
     *
     * @param  SubCategory  $categories
     * @return void
     */
    public function __construct(SubCategory $subCategories)
    {
        // Dependencies automatically resolved by service container...
        $this->subCategories        = $subCategories;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
       $view->with($this->name, $this->populate());
    }

    /**
     * Resolve Data from the class
     *
     * @param App\Models\SubCategory $subCategories
     * @return Collection
     */
    protected function populate()
    {
        return $this->subCategories
                    ->onlyParent()
                    ->get();
    }
}