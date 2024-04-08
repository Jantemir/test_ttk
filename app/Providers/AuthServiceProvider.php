<?php

namespace App\Providers;

use App\Policies\AuthorPolicy;
use App\Policies\BookPolicy;
use App\Policies\SectionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
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
        Gate::define('books.viewHidden', [BookPolicy::class,  'viewHidden']);
        Gate::define('books.hide', [BookPolicy::class,  'hide']);
        Gate::resource('books', BookPolicy::class);

        Gate::define('sections.viewHidden', [SectionPolicy::class,  'viewHidden']);
        Gate::define('sections.hide', [SectionPolicy::class,  'hide']);
        Gate::resource('sections', SectionPolicy::class);

        Gate::define('authors.viewHidden', [AuthorPolicy::class,  'viewHidden']);
        Gate::define('authors.hide', [AuthorPolicy::class,  'hide']);
        Gate::resource('authors', AuthorPolicy::class);
    }
}
