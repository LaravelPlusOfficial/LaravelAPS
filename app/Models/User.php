<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use App\Models\Traits\Roleable;
use Spatie\Sluggable\SlugOptions;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, Roleable, HasSlug;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'email_verified' => 'boolean',
        'social_links'   => 'array'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        //'email_verification_token',
        //'email_verified'
    ];

    /**
     * Model Boot method
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {

            if (!$user->email_verified) {
                $user->generateEmailConfirmationToken()->save();
            }

        });

    }

    public function country()
    {
        return $this->belongsTo(Country::class)->select('id', 'name');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'uploaded_by');
    }

    /**
     * Generate Email Verification Token
     *
     * @return $this
     */
    public function generateEmailConfirmationToken()
    {
        $tokenString = $this->email . '-' . Str::random(30) . '-' . time();

        $this->email_verification_token = hash_hmac('sha256', $tokenString, config('app.key'));

        return $this;
    }

    /**
     * Check if user has an admin role
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function owns($model, $columnName = 'user_id')
    {
        return $this->id == $model->{$columnName};
    }


    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('username');
    }

}
