<?php

namespace App\Providers;

use App\Repositories\HookContentRepositoryEloquent;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(HookContentRepositoryEloquent $hookcontent)
    {

        Blade::directive('hookRender', function ($expression) use ($hookcontent){
            $expression = substr($expression, 1, -1);
            return $hookcontent->renderHookContentDataHtml($expression);
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
