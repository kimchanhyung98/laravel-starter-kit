<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Scopes\ViewablePostScope;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('any_post', function ($post_id) {
            return Post::withoutGlobalScope(ViewablePostScope::class)->findOrFail($post_id);
        });
    }
}
