<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use App\Services\Police\Contract\PoliceContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\Comment::class    => \App\Policies\CommentPolicy::class,
        \App\Models\Role::class       => \App\Policies\RolePolicy::class,
        \App\Models\Permission::class => \App\Policies\PermissionPolicy::class,
        \App\Models\User::class       => \App\Policies\UserPolicy::class,
        \App\Models\Media::class      => \App\Policies\MediaPolicy::class,
        \App\Models\Post::class       => \App\Policies\PostPolicy::class,

        // Taxonomies
        \App\Models\Category::class   => \App\Policies\TaxonomyPolicy::class,
        \App\Models\Tag::class        => \App\Policies\TaxonomyPolicy::class,

        // Settings
        \App\Models\Setting::class    => \App\Policies\SettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        resolve(PoliceContract::class)->registerPermissions();

        Passport::routes();
    }

}
