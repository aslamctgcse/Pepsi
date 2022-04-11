<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Providers\AbstractViewComposerServiceProvider as ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Shared Namespace for the Composer.
     *
     * @var string
     */
    protected $namespace = 'App\Http\View\Composers';

    /**
     * List Composers to register.
     *
     * @var Array
     */
    protected $composers = [
        
        'CategoryComposer'      =>  [
                                        'website.home.index',
                                        // 'website.product.*',
                                    ],

        // 'SubCategoryComposer'   =>  [
        //                                 'admin.subcategory.*',
        //                                 'admin.product.*',
        //                             ],
        // 'SubChildCategoryComposer'   =>  [
        //                                     'admin.subcategory.*',
        //                                     'admin.product.edit',
        //                                 ],

    ];

}
