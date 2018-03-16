<?php

return [

    'media' => [

        'disk' => 'public', // Filesystem

        'image_variations' => [
            'large'     => [
                'width'  => 1200,
                'height' => 627,
            ],
            'thumbnail' => [
                'width'  => 300,
                'height' => 300,
            ]
        ],

        'site_default_user_avatar' => [
            'width'  => 300,
            'height' => 300,
            'folder' => 'site/images' // for settings only
        ],

        'seo_default_site_image' => [
            'width'  => 1200,
            'height' => 627,
            'folder' => 'site/images'
        ],

        'site_welcome_hero_background_image' => [
            'width'  => 1200,
            'height' => 627,
            'folder' => 'site/images'
        ],

        'blog_image' => [
            'width'  => 1200,
            'height' => 627,
            'folder' => 'site/images'
        ],
    ],

    'acl' => [

        // Acl slug separator
        'slug_separator' => '.',

        // Roles are not updateable,
        // Every new permission will automatically assign to these roles
        'freezed_roles' => [
            'admin'
        ],

        // These permissions should not updateable,
        // & are required to operate application properly
        'freezed_permissions' => [
            'manage users',
            'manage acl'
        ]
    ],

    'post' => [

        'types' => [
            'post',
            'page'
        ],

        'formats' => [
            'standard',
            'image',
            'video',
            'audio'
        ],
    ],


    'users' => [
        'allowed_social_links' => [
            'facebook_url'     => 'https://www.facebook.com/',
            'google_plus_url'  => 'https://plus.google.com/',
            'twitter_username' => '@',
            'github_url'       => 'https://github.com',
            'linkedin_url'     => 'https://www.linkedin.com/'
        ]
    ],
];


