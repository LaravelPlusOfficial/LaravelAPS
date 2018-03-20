<?php

return [

    'media' => [

        'disk' => 'public', // Filesystem

        // Image variation for the images stored in Media
        // On Storing orginal file, images will be also converted into the following
        // variation. You can add or remove the variation as desired
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

        // User avatar location and size
        'site_default_user_avatar' => [
            'width'  => 300,
            'height' => 300,
            'folder' => 'site/images' // for settings only
        ],

        // Site image dimensions and location
        // act as default og:image
        'seo_default_site_image' => [
            'width'  => 1200,
            'height' => 627,
            'folder' => 'site/images'
        ],

        // Site welcome page hero background
        'site_welcome_hero_background_image' => [
            'width'  => 1200,
            'height' => 627,
            'folder' => 'site/images'
        ],

        // blog image dimensions and images
        // act as og:image for blog page
        'blog_image' => [
            'width'  => 1200,
            'height' => 627,
            'folder' => 'site/images'
        ],
    ],

    // Access Control List
    'acl' => [

        // Acl slug separator
        // Dont change unless you know what you doing
        'slug_separator' => '.',

        // Roles are not updateable,
        // Every new permission will automatically assign to these roles
        'freezed_roles' => [
            'admin'
        ],

        // These permissions should not updateable or deleteable,
        // & are required to operate application properly
        'freezed_permissions' => [
            'manage users',
            'manage acl'
        ]
    ],

    // Posts
    'post' => [

        // Available type of post
        // Add or remove posts types
        'types' => [
            'post',
            'page'
        ],

        // Post formats
        'formats' => [
            'standard',
            // 'image',
            // 'video',
            // 'audio'
        ],
    ],

    // User profile social links
    'users' => [

        // Allowed links to filled by user
        'allowed_social_links' => [
            'facebook_url'     => 'https://www.facebook.com/',
            'google_plus_url'  => 'https://plus.google.com/',
            'twitter_username' => '@',
            'github_url'       => 'https://github.com',
            'linkedin_url'     => 'https://www.linkedin.com/'
        ]

    ],
];


