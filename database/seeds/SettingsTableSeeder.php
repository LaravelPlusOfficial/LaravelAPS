<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (array_sort_recursive($this->settings()) as $group => $settings) {

            foreach ($settings as $label => $settingOptions) {

                $value = empty(optional($settingOptions)['value']) ? null : optional($settingOptions)['value'];


                factory(Setting::class)->create([
                    'group'      => str_slug($group, '_'),
                    'label'      => title_case(str_replace('_', ' ', $label)), //group_name e.g seo_title
                    'key'        => str_slug("{$group} {$label}", '_'), //group_name e.g seo_title
                    'value'      => $value,
                    'type'       => $settingOptions['type'],
                    'options'    => optional($settingOptions)['options'],
                    'help'       => optional($settingOptions)['help'],
                    'help_label' => optional($settingOptions)['help_label'],
                    'disabled'   => optional($settingOptions)['disabled'] ? true : false,
                    'hidden'     => optional($settingOptions)['hidden'] ? true : false,
                ]);

            }

        }


    }

    protected function settings()
    {
        return [
            'seo'              => [
                'default_title'           => [
                    'type'  => 'textarea',
                    'value' => config('app.name')
                ],
                'default_title_separator' => [
                    'type'  => 'input',
                    'value' => '&bull;'
                ],
                'title_length'            => [
                    'type'  => 'number',
                    'value' => '70'
                ],
                'default_description'     => [
                    'type'  => 'textarea',
                    'value' => env('SITE_DESCRIPTION', 'Web development tutorial, code, snippets and packages')
                ],
                'description_length'      => [
                    'type'  => 'number',
                    'value' => '160'
                ],
                'default_site_image'      => [
                    'type'  => 'image',
                    'value' => ''
                ],
                'robots_meta'             => [
                    'type'    => 'select',
                    'value'   => 'index',
                    'options' => [
                        'all'               => 'All',
                        'index, follow'     => 'Index & Follow',
                        'index, nofollow'   => 'Index & No Follow',
                        'noindex, follow'   => 'No Index & Follow',
                        'noindex, nofollow' => 'No Index & No Follow',

                    ],
                    'help'    => 'visit <a href="https://developers.google.com/search/reference/robots_meta_tag">Google</a> for more info'
                ],
            ],
            'Contact'          => [
                'service_email'   => [
                    'type'  => 'email',
                    'value' => env('EMAIL_SERVICE', ''),
                    'help'  => 'For system messages'
                ],
                'help_desk_email' => [
                    'type'  => 'email',
                    'value' => env('EMAIL_HELP_DESK', ''),
                    'help'  => 'Users relation',
                ]
            ],
            'Google'           => [
                'plus_url'  => [
                    'type' => 'input',
                    'help' => 'Google plus full url e.g. '
                ],
                'analytics' => [
                    'type' => 'input',
                    'help' => 'Just code eg "UA-12345678-1"'
                ],
                'adsense'   => [
                    'type' => 'input',
                    'help' => 'Just code eg "ca-pub-123123123123123"'
                ]
            ],
            'facebook'         => [
                'admin'     => [
                    'type'  => 'input',
                    'value' => ''
                ],
                'publisher' => [
                    'type'  => 'input',
                    'value' => '',
                    'help'  => 'Facebook url'
                ],
                'page_id'   => [
                    'type'  => 'input',
                    'value' => '710844062416439'
                ]
            ],
            'twitter'          => [
                'site_username' => [
                    'type'  => 'input',
                    'value' => '@LaravelPlus',
                    'help'  => '@username'
                ]
            ],
            'blog'             => [
                'title'       => [
                    'type'  => 'input',
                ],
                'description' => [
                    'type'  => 'textarea',
                ],
                'image'       => [
                    'type'  => 'image',
                ],
            ],
            'post'             => [
                'default_pagination_count' => [
                    'type'  => 'number',
                    'value' => '10',
                ],
            ],
            'security'         => [
                'spam_keywords' => [
                    'type'  => 'textarea',
                    'value' => 'yahoo customer support, Claims you are a winner, Meet singles, Be your own boss'
                ]
            ],
            'site'             => [
                'welcome_hero_background_image' => [
                    'type'  => 'image',
                    'value' => ''
                    //'value' => '/defaults/welcome-hero-background.jpg'
                ],
                'welcome_hero_title'            => [
                    'type'  => 'textarea',
                    'value' => 'Welcome to <span class="c-primary tt-u fw-300">Laravel<span class="fw-700">APS</span></span>',
                    'help'  => 'HTML allowed'
                ],
                'welcome_hero_sub_title'        => [
                    'type'  => 'textarea',
                    'value' => 'Open source <span class="fw-700">Hackable</span> Content Management System',
                    'help'  => 'HTML allowed'
                ],
                'default_user_avatar'           => [
                    'type'  => 'image',
                    'value' => ''
                ],
                'theme_color'                   => [
                    'type'  => 'color',
                    'value' => '#E83E8C'
                ]
            ],
            'users'            => [
                'registeration_enabled' => [
                    'type'    => 'select',
                    'value'   => 'enable',
                    'options' => [
                        'enable'  => 'enable',
                        'disable' => 'disable'
                    ]
                ],
                'default_role'          => [
                    'type'    => 'select',
                    'value'   => 'subscriber',
                    'options' => $this->getRoles()
                ],
            ],
            'social_auto_post' => [
                'facebook'       => [
                    'type'    => 'select',
                    'value'   => 'disable',
                    'options' => [
                        'enable'  => 'enable',
                        'disable' => 'disable'
                    ],
                    'help'    => 'Before enabling, Facebook Page Id must be filled in Facebook Section'
                ],
                'facebook_token' => [
                    'type'     => 'input',
                    'disabled' => true
                ],
                'twitter'        => [
                    'type'    => 'select',
                    'value'   => 'disable',
                    'options' => [
                        'enable'  => 'enable',
                        'disable' => 'disable'
                    ],
                ],
            ]
        ];
    }

    protected function getRoles()
    {
        $roles = [];

        foreach ( (new AccessControlTableSeeder())->getRoles() as $role) {

            $roles[str_slug($role, '_')] = $role;

        }

        return $roles;
    }
}
