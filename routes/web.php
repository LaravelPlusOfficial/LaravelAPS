<?php

Route::get('/social-auto-publish/enable/{provider}', 'Social\AutoPublishController@enableProvider')
    ->name('social.auto.publish.enable.provider');

Route::get('/social-auto-publish/disable/{provider}', 'Social\AutoPublishController@disableProvider')
    ->name('social.auto.publish.disable.provider');

Route::get('/social-auto-publish/{provider}/callback', 'Social\AutoPublishController@fromProvider')
    ->name('social.auto.publish.from.provider');

Route::get('auth-by/{provider}', 'Auth\SocialiteController@toProvider')->name('socialite.to.provider');
Route::get('socialite/{provider}/callback', 'Auth\SocialiteController@fromProvider')->name('socialite.from.provider');
Route::post('social/register', 'Auth\SocialRegisterController@register')->name('socialite.register');
Route::get('welcome-{username}', 'Auth\SocialiteController@registered')->name('socialite.registered');

// Auth Routed
Auth::routes();

Route::middleware(['auth', 'email.confirmed'])->group(function () {
    // Create Comments
    Route::post('comments', 'App\CommentsController@store')->name('comment.store');
    Route::post('replies', 'App\CommentsController@addReply')->name('reply.store');
    Route::patch('comments/{comment}', 'App\CommentsController@update')->name('comment.update');
    Route::delete('comments/{comment}', 'App\CommentsController@destroy')->name('comment.destroy');

});

// Dashboard
Route::middleware(['auth', 'email.confirmed'])->prefix('dashboard')->group(function () {

    // Dashboard
    Route::get('/', 'Admin\DashboardController@dashboard')->name('admin.dashboard');

    // POSTS
    Route::get('/posts', 'Admin\PostsController@index')->name('admin.post.index');
    Route::get('/posts/create', 'Admin\PostsController@create')->name('admin.post.create');
    Route::get('/posts/{post}/edit', 'Admin\PostsController@edit')->name('admin.post.edit');
    Route::post('/posts', 'Admin\PostsController@store')->name('admin.post.store');
    Route::patch('/posts/{post}', 'Admin\PostsController@update')->name('admin.post.update');
    Route::patch('/posts/{post}/republish-to-social-media/{provider}', 'Admin\PostsController@rePublishToSocialMedia')
        ->name('admin.post.republish-to-social-media');
    Route::delete('/posts/{post}', 'Admin\PostsController@destroy')->name('admin.post.destroy');

    // Pages
    Route::get('/pages', 'Admin\PagesController@index')->name('admin.page.index');
    Route::get('/pages/create', 'Admin\PagesController@create')->name('admin.page.create');
    Route::get('/pages/{page}/edit', 'Admin\PagesController@edit')->name('admin.page.edit');
    Route::post('/pages', 'Admin\PagesController@store')->name('admin.page.store');
    Route::patch('/pages/{page}', 'Admin\PagesController@update')->name('admin.page.update');
    Route::delete('/pages/{page}', 'Admin\PagesController@destroy')->name('admin.page.destroy');

    // Media
    Route::get('/media', 'Admin\MediaController@index')->name('admin.media.index');
    Route::get('/media/{media}', 'Admin\MediaController@show')->name('admin.media.show');
    Route::post('/media', 'Admin\MediaController@store')->name('admin.media.store');
    Route::delete('/media/{media}', 'Admin\MediaController@destroy')->name('admin.media.destroy');

    // Category
    Route::get('/categories', 'Admin\CategoriesController@index')->name('admin.category.index');
    Route::post('/categories', 'Admin\CategoriesController@store')->name('admin.category.store');
    Route::patch('/categories/{category}', 'Admin\CategoriesController@update')->name('admin.category.update');
    Route::delete('/categories/{category}', 'Admin\CategoriesController@destroy')->name('admin.category.destroy');

    // Tags
    Route::get('/tags', 'Admin\TagsController@index')->name('admin.tag.index');
    Route::get('/tags/tag-by-name/{name}', 'Admin\TagsController@tagByName')->name('admin.tag.show.byName');
    Route::post('/tags', 'Admin\TagsController@store')->name('admin.tag.store');
    Route::patch('/tags/{tag}', 'Admin\TagsController@update')->name('admin.tag.update');
    Route::delete('/tags/{tag}', 'Admin\TagsController@destroy')->name('admin.tag.destroy');

    // Queues
    Route::get('/queues', 'Admin\QueuesController@index')->name('admin.queue.index');

    // User
    Route::get('/users', 'Admin\UsersController@index')->name('admin.user.index');
    Route::patch('/users/{user}', 'Admin\UsersController@update')->name('admin.user.update');

    //Comments
    Route::get('/comments', 'Admin\CommentsController@index')->name('admin.comment.index');
    Route::patch('/comments/{comment}', 'Admin\CommentsController@update')->name('admin.comment.update');
    Route::delete('/comments/{comment}/delete', 'Admin\CommentsController@destroy')->name('admin.comment.destroy');

    // Profile
    Route::get('/profile/{user}/edit', 'Admin\UsersController@edit')->name('admin.profile.edit');
    Route::patch('/profile/{user}', 'Admin\UsersController@update')->name('admin.profile.update');
    Route::delete('/profile/{user}', 'Admin\UsersController@destroy')->name('admin.profile.destroy');

    // Avatar
    Route::post('/avatar/{user}', 'Admin\AvatarController@update')->name('admin.avatar.update');
    Route::delete('/avatar/{user}', 'Admin\AvatarController@destroy')->name('admin.avatar.destroy');

    // Roles
    Route::get('/roles', 'Admin\RolesController@index')->name('admin.role.index');
    Route::get('/roles/create', 'Admin\RolesController@create')->name('admin.role.create');
    Route::post('/roles', 'Admin\RolesController@store')->name('admin.role.store');
    Route::get('/roles/{role}/edit', 'Admin\RolesController@edit')->name('admin.role.edit');
    Route::patch('/roles/{role}', 'Admin\RolesController@update')->name('admin.role.update');
    Route::delete('/roles/{role}', 'Admin\RolesController@destroy')->name('admin.role.destroy');

    // Permissions
    Route::get('/permissions', 'Admin\PermissionsController@index')->name('admin.permission.index');
    Route::post('/permissions', 'Admin\PermissionsController@store')->name('admin.permission.store');
    Route::delete('/permissions/{permission}', 'Admin\PermissionsController@destroy')->name('admin.permission.destroy');

    Route::get('/settings', 'Admin\SettingsController@index')->name('admin.setting.index');
    Route::get('/settings/create', 'Admin\SettingsController@create')->name('admin.setting.create');
    Route::post('/settings', 'Admin\SettingsController@store')->name('admin.setting.store');
    Route::patch('/settings', 'Admin\SettingsController@update')->name('admin.setting.update');
    Route::delete('/settings', 'Admin\SettingsController@destroy')->name('admin.setting.destroy');

});

// Email Confirmation
Route::middleware(['guest'])->group(function () {

    Route::get('email-confirmation/resend', 'Auth\EmailVerificationController@show')
        ->name('email.confirmation.show');

    Route::post('email-confirmation/resend', 'Auth\EmailVerificationController@create')
        ->name('email.confirmation.create')->middleware(['throttle:10,5']);

    Route::get('email-confirmation/{token}', 'Auth\EmailVerificationController@confirm')
        ->name('email.confirmation.confirm');
});

// Home
Route::get('/', 'App\HomeController@home');
Route::get('home', 'App\HomeController@home')->name('home');
Route::get('welcome', 'App\HomeController@welcome')->name('welcome');

// Comments
Route::get('comments', 'App\CommentsController@index')->name('comment.index');
Route::post('comments/replies', 'App\CommentsController@addReply')
    ->name('comment.replies.create')->middleware(['auth', 'email.confirmed']);
Route::patch('comments/{comment}', 'App\CommentsController@update')
    ->name('comment.update')
    ->middleware(['auth', 'email.confirmed']);

// Newsletter
Route::post('newsletter/subscribe', 'App\SubscribersController@store')->name('newsletter.subscribe');
Route::delete('newsletter/subscribe/{email}', 'App\SubscribersController@destroy')->name('newsletter.unsubscribe');

// Sitemap
Route::get('sitemap', 'App\SitemapsController@index');
Route::get('generate/sitemap', 'App\SitemapsController@store');

// Search
Route::get('search', 'App\SearchController@index')->name('app.search');
Route::post('search', 'App\SearchController@index')->name('app.search');

// Pages
Route::get('contact', 'Actions\ContactController@show')->name('contact-us');
Route::post('contact', 'Actions\ContactController@store');

// Posts
Route::get('blog', 'App\PostsController@index')->name('post.index');
Route::get('u/{shortUrl}', 'App\PostsController@shortUrlShow')->name('post.show.short-url');
Route::get('{slug}', 'App\PostsController@show')->name('post.show');

// Archives
Route::get('{archiveType}/{archiveSlug}', 'App\ArchivesController@index')->name('archive.index');

