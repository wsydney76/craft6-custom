<?php

namespace App\Providers;

use App\Http\Middleware\InjectCpStyles;
use App\Mixins\ArticleMixin;
use App\Support\BladeTwigExtension;
use App\View\Composers\ArticleOfTheDayComposer;
use App\View\Composers\FooterComposer;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Support\Facades\Twig;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // require_once app_path('Support/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Kernel $kernel, Request $request): void
    {
        View::composer(
            ['components.partials.article-of-the-day', 'components.layouts.footer'],
            ArticleOfTheDayComposer::class,
        );
        View::composer('components.layouts.footer', FooterComposer::class);

        Entry::mixin(new ArticleMixin());

        Twig::registerExtension(new BladeTwigExtension());

        if ($request->isCpRequest()) {
            // $kernel->pushMiddleware(InjectCpStyles::class);
        }
    }
}
