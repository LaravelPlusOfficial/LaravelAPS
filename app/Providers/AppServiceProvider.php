<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Observers\PostObservers;
use App\Services\Seo\Seo;
use Laravel\Horizon\Horizon;
use Illuminate\Http\Request;
use App\Services\Post\PostRepo;
use App\Services\Police\Police;
use App\Services\Avatar\Avatar;
use App\Services\Country\Country;
use App\Services\Sitemap\Sitemap;
use App\Services\Settings\Setting;
use Illuminate\Support\Collection;
use App\Services\Archives\Archive;
use App\Observers\CommentObservers;
use App\Services\Taxonomy\Taxonomy;
use App\Services\Police\PoliceClerk;
use Illuminate\Support\ServiceProvider;
use App\Services\Seo\Contract\SeoContract;
use App\Services\Avatar\Contract\AvatarContract;
use App\Services\Post\Contract\PostRepoContract;
use App\Services\Police\Contract\PoliceContract;
use App\Services\Sitemap\Contract\SitemapContract;
use App\Services\Country\Contract\CountryContract;
use App\Services\Settings\Contract\SettingContract;
use App\Services\Archives\Contract\ArchiveContract;
use App\Services\Taxonomy\Contract\TaxonomyContract;
use App\Services\Police\Contract\PoliceClerkContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Horizon::auth(function ($request) {
            return $request->user()->can('manage.queues');
        });

        Comment::observe(CommentObservers::class);
        Post::observe(PostObservers::class);

        \Validator::extend('spamfree', 'App\Rules\SpamFree@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PoliceContract::class, function ($app) {
            return new Police();
        });

        $this->app->singleton(TaxonomyContract::class, function ($app) {
            return new Taxonomy();
        });

        $this->app->singleton(CountryContract::class, function ($app) {
            return new Country();
        });

        $this->app->singleton(PostRepoContract::class, function ($app) {
            return new PostRepo();
        });

        $this->app->singleton(SeoContract::class, function ($app) {
            return new Seo();
        });

        $this->app->singleton(SitemapContract::class, function ($app) {
            return new Sitemap();
        });

        $this->app->singleton(AvatarContract::class, function ($app) {
            return new Avatar();
        });

        $this->app->singleton(ArchiveContract::class, function ($app) {
            return new Archive();
        });

        $this->app->singleton(PoliceClerkContract::class, function ($app) {
            return new PoliceClerk();
        });

        $this->app->singleton(SettingContract::class, function ($app) {
            return new Setting(resolve(\App\Models\Setting::class), resolve(Request::class));
        });


        Collection::macro('groupByFirstLetter', function ($column = null) {

            return $this->groupBy(function ($item) use ($column) {

                if ($column) {
                    return substr(is_array($item) ? $item[$column] : $item->$column, 0, 1);
                }
                return null;
            });

        });

    }
}
