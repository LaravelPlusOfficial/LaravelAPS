<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Menu
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = auth()->user();

        if ($request->route()->getPrefix() == '/dashboard' && $user) {

            $this->makeAdminMainMenu($user);

        }


        if ($request->route()->getPrefix() != '/dashboard') {

            $this->makeAppMainMenu($user);

            $this->makeAppOffCanvasMainMenu($user);

        }

        return $next($request);
    }

    protected function makeAdminMainMenu($user)
    {
        \Menu::make("mainMenu", function ($menu) use ($user) {

            $menu->add('Dashboard', ['route' => 'admin.dashboard', 'nickname' => 'dashboard'])
                ->prepend('<vue-svg name="icon-dashboard" :width="14" :height="14" classes="mr-1 fill-white"></vue-svg>');

            // Posts
            $menu->add('Posts')
                ->prepend('<vue-svg name="icon-edit" :width="14" :height="14" classes="mr-1 fill-white"></vue-svg>')
                ->link
                ->href('#');

            $menu->posts->add("All Posts", ['route' => 'admin.post.index']);
            $menu->posts->add("Add Post", ['route' => 'admin.post.create']);

            // Comments
            $menu->add('Comments')
                ->prepend('<vue-svg name="icon-message-circle" :width="14" :height="14" classes="mr-1 fill-white"></vue-svg>')
                ->link
                ->href(route('admin.comment.index'));


            if ($user->can('manage.taxonomy')) {
                $menu->posts->add("Categories", ['route' => 'admin.category.index']);
                $menu->posts->add("Tags", ['route' => 'admin.tag.index']);
            }

            // Pages
            if ($user->can('manage.pages')) {
                $menu->add('Pages')
                    ->prepend('<vue-svg name="icon-pages" :width="14" :height="14" classes="mr-1 fill-white"></vue-svg>')
                    ->link
                    ->href('#');

                $menu->pages->add("All Pages", ['route' => 'admin.page.index']);
                $menu->pages->add("Add Page", ['route' => 'admin.page.create']);
            }

            // Media
            $menu->add('Media')
                ->prepend('<vue-svg name="icon-image" :width="14" :height="14" classes="mr-1 fill-white"></vue-svg>')
                ->link
                ->href(route('admin.media.index'));

            if ($user->can('manage.queues')) {
                // Horizon
                $menu->add('Queues')
                    ->prepend('<vue-svg name="icon-flag" :width="14" :height="14" classes="mr-1 fill-white"></vue-svg>')
                    ->link
                    ->href(route('admin.queue.index'));
            }

            // Users
            if ($user->can('manage.users')) {
                $menu->add('Users', ['route' => 'admin.user.index'])
                    ->prepend('<vue-svg name="icon-users" :width="14" :height="14" classes="mr-1 fill-white"></vue-svg>');
            }

            // Profile
            $menu->add('Profile')
                ->prepend('<vue-svg name="icon-user" :width="14" :height="14" classes="mr-1 fill-white"></vue-svg>')
                ->link
                ->href(route('admin.profile.edit', $user->id));

            if ($user->can('manage.settings')) {

                // Settings
                $menu->add('Settings')
                    ->prepend('<vue-svg name="icon-settings" :width="14" :height="14" classes="mr-1 fill-white"></vue-svg>')
                    ->link
                    ->href('#');

                $menu->settings->add('All Settings', ['route' => 'admin.setting.index']);
                $menu->settings->add('Add Setting', ['route' => 'admin.setting.create']);
            }


            if ($user->can('manage.acl')) {
                // ACL
                $menu->add('Access Control')
                    ->prepend('<vue-svg name="icon-lock" :width="14" :height="14" classes="mr-1 fill-white"></vue-svg>')
                    ->link
                    ->href('#');

                $menu->accessControl->add("Roles", ['route' => 'admin.role.index']);
                $menu->accessControl->add("Permissions", ['route' => 'admin.permission.index']);
            }

        });
    }

    protected function makeAppMainMenu($user)
    {
        \Menu::make("mainMenu", function ($menu) use ($user) {

            $menu->add('Blog', [
                'route' => 'post.index',
                'class' => 'hide-below-lg',
                'svg'   => 'icon-file-text'
            ]);

            if ($user && $user->isAdmin()) {

                if (Auth::check()) {

                    $menu->add('Dashboard', [
                        'route'        => 'admin.dashboard',
                        'class'        => 'hide-below-lg',
                        'title-hidden' => 'true',
                        'svg'          => 'icon-dashboard'
                    ]);

                    $menu->add('Logout', [
                        'class'                 => 'hide-below-lg logout-button',
                        'svg'                   => 'icon-logout',
                        'title-hidden'          => 'true',
                        'onclick'               => 'event.preventDefault();document.getElementById("logout-form").submit();'
                    ]);

                } else {

                    $menu->add('Login', [
                        'route'        => 'login',
                        'class'        => 'hide-below-lg',
                        'title-hidden' => 'true',
                        'svg'          => 'icon-login',
                    ]);

                }

            }

            $menu->add('Menu', [
                'url'             => '/',
                'class'           => 'hide-above-lg hide-on-side',
                'svg'             => 'icon-menu',
                'svg-square-size' => '22',
                'title-hidden'    => 'true',
                'title-classes'   => 'hide-below-lg',
                '@click.prevent'  => 'toggleOffCanvas()'
            ]);

            $menu->add('Search', [
                'class'          => 'hide-below-lg hide-on-side',
                'svg'            => 'icon-search',
                'title-hidden'   => 'true',
                '@click.prevent' => "search=!search"
            ]);

        });
    }

    protected function makeAppOffCanvasMainMenu($user)
    {
        \Menu::make("mainMenuOffCanvas", function ($menu) use ($user) {

            $menu->add('Blog', [
                'route' => 'post.index',
                'class' => 'hide-below-lg',
                'svg'   => 'icon-file-text'
            ]);

            if ($user && $user->isAdmin()) {


                if (Auth::check()) {

                    $menu->add('Dashboard', [
                        'route'        => 'admin.dashboard',
                        'svg'          => 'icon-dashboard'
                    ]);

                    $menu->add('Logout', [
                        'class'   => 'hide-below-lg logout-button',
                        'svg'     => 'icon-logout',
                        'onclick' => 'event.preventDefault();document.getElementById("logout-form").submit();'
                    ]);

                } else {

                    $menu->add('Login', [
                        'route' => 'login',
                        'svg'   => 'icon-login',
                    ]);

                }

            }

        });
    }
}
