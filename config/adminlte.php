<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => '天一建设PMS',

    'title_prefix' => '',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>TY</b>PMS',

    'logo_mini' => '<b>T</b>PM',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'blue',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => true,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'home',

    'logout_url' => 'logout',

    'logout_method' => null,

    'login_url' => 'login',

    'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        '主面板',
        [
            'text' => '报表',
            'icon' => 'pie-chart',
            'submenu' => [
                [
                    'icon' => 'bar-chart',
                    'text' => '应收工程款计划',
                    'url'  => 'charts/balance',
                ]
            ],
        ],
        [
            'text' => '工作台',
            'icon' => 'gamepad',
            'submenu' => [
                [
                    'text' => '产值申报',
                    'url'  => 'outputs',
                    'icon' => 'usd',
                ],
                [
                    'text' => '回款登记',
                    'url'  => 'receipts',
                    'icon' => 'university',
                ],
                [
                    'text' => '资金计划',
                    'url'  => 'plans/capital',
                    'icon' => 'calendar',
                ],
                [
                    'text' => '部位结算',
                    'url'  => 'sections',
                    'icon' => 'cubes',
                ],
            ],
        ],
        [
            'text' => '档案管理',
            'icon' => 'book',
            'submenu' => [
                [
                    'text' => '项目档案',
                    'url'  => 'projects',
                    'icon' => 'building-o',
                ],
                [
                    'text' => '合同档案',
                    'url'  => 'contracts',
                    'icon' => 'compress',
                ],
            ],
        ],
        [
            'text' => '系统管理',
            'icon' => 'cogs',
            'submenu' => [
                [
                    'text' => '档案同步',
                    'url'  => 'settings/sync',
                    'icon' => 'refresh',
                ],
                [
                    'text' => '用户管理',
                    'url'  => 'users',
                    'icon' => 'user',
                ],
                [
                    'text'        => '角色管理',
                    'url'         => 'roles',
                    'icon'        => 'users',
                ],
                [
                    'text'        => '权限管理',
                    'url'         => 'permissions',
                    'icon'        => 'user-secret',
                ],
            ],
        ],
        '敬请期待',
        [
            'text' => 'Change Password',
            'url'  => 'admin/settings',
            'icon' => 'lock',
            'can'  => 'admin',
        ],
        '账户设置',
        [
            'text' => 'Profile',
            'url'  => 'admin/settings',
            'icon' => 'user',
            'can'  => 'admin',
            'label'       => 4,
            'label_color' => 'success',
        ],
        [
            'text'    => 'Multilevel',
            'icon'    => 'share',
            'can'  => 'admin',
            'submenu' => [
                [
                    'text' => 'Level One',
                    'url'  => '#',
                ],
                [
                    'text'    => 'Level One',
                    'url'     => '#',
                    'submenu' => [
                        [
                            'text' => 'Level Two',
                            'url'  => '#',
                        ],
                        [
                            'text'    => 'Level Two',
                            'url'     => '#',
                            'submenu' => [
                                [
                                    'text' => 'Level Three',
                                    'url'  => '#',
                                ],
                                [
                                    'text' => 'Level Three',
                                    'url'  => '#',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'Level One',
                    'url'  => '#',
                ],
            ],
        ],
        'LABELS',
        [
            'text'       => 'Important',
            'icon_color' => 'red',
            'can'  => 'admin',

        ],
        [
            'text'       => 'Warning',
            'icon_color' => 'yellow',
            'can'  => 'admin',

        ],
        [
            'text'       => 'Information',
            'icon_color' => 'aqua',
            'can'  => 'admin',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        App\AdminLte\Menu\Filters\HrefFilter::class,
        App\AdminLte\Menu\Filters\ActiveFilter::class,
        App\AdminLte\Menu\Filters\SubmenuFilter::class,
        App\AdminLte\Menu\Filters\ClassesFilter::class,
        App\AdminLte\Menu\Filters\GateFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => true,
    ],
];
