<?php

namespace App\Http\View\Composers;

use DB
use Illuminate\View\View;

class CategoryComposer
{
    /**
     * The Category repository implementation.
     *
     * @var Bind Category model
     */
    // protected $categories;

    /**
     * Dropdown variable name associated with class
     *
     * @var string
     */
    protected $name = 'categories';

    /**
     * Create a new profile composer.
     *
     * @param  Category  $categories
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        // $this->categories   =   $categories;
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
     * @param App\Models\Category $categories
     * @return Collection
     */
    protected function populate()
    {
        return  DB::table('categories')->get();
    }
}