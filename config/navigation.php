<?php

return [

    //  [
    //     'label' => 'Dashboard',

    //     'route' => 'vendor.dashboard',

    //     'icon' => 'home',

    //     'page' => [

    //         'title' => 'Dashboards',

    //         'heading' => 'Welcome Back',

    //         'description' => 'Overview of your WhatsApp Business',

    //         'browser_title' => 'Dashboard',

    //         'seo' => [
    //             'title' => 'Dashboard | Wantrix',
    //             'description' => 'Manage your WhatsApp Business Dashboard.',
    //             'keywords' => [
    //                 'dashboard',
    //                 'whatsapp',
    //                 'wantrix',
    //             ],
    //         ],

    //         'social' => [
    //             'title' => 'Wantrix Dashboard',
    //             'description' => 'Manage campaigns, contacts and analytics.',
    //             'image' => '/images/social/dashboard.png',
    //         ],
    //     ],
    // ],

    [
        'label'=>'Dashboard',

        'title'=>'Dashboard',

        'description'=>'Overview of your WhatsApp business.',

        'route'=>'vendor.dashboard',

        'icon'=>'home',
    ],

    [
        'label' => 'Contacts',
        'icon' => 'users',
        'children' => [

            [
                'label' => 'Contacts',
                'title'=>'Contacts',
                'description'=>'Manage all your customer contacts.',
                'route' => 'vendor.contacts',
                'icon' => 'users',
            ],

            [
                'label' => 'Import Contacts',
                'route' => 'vendor.contacts.import',
                'icon' => 'arrow-up-tray',
            ],

            [
                'label' => 'Groups',
                'route' => 'vendor.groups',
                'icon' => 'user-group',
            ],

        ],
    ],

    [
        'label' => 'Campaigns',
        'icon' => 'megaphone',
        'children' => [

            [
                'label'=>'Campaigns',
                'title'=>'Campaigns',
                'description'=>'Create and manage WhatsApp campaigns.',
                'route'=>'vendor.campaigns',
                'icon'=>'megaphone',
            ],

            [
                'label' => 'Templates',
                'route' => 'vendor.templates',
                'icon' => 'document-text',
            ],

            [
                'label' => 'Messages',
                'route' => 'vendor.messages',
                'icon' => 'chat-bubble-left-right',
            ],

        ],
    ],

    [
        'label' => 'Analytics',
        'icon' => 'chart-bar',
        'route' => 'vendor.analytics',
    ],

    [
        'label' => 'Channels',
        'icon' => 'device-phone-mobile',
        'children' => [

            [
                'label' => 'WhatsApp Accounts',
                'route' => 'vendor.whatsapp.accounts',
                'icon' => 'device-phone-mobile',
            ],

            [
                'label' => 'Meta Setup',
                'route' => 'vendor.meta.setup',
                'icon' => 'link',
            ],

        ],
    ],

    [
        'label' => 'Billing',
        'icon' => 'credit-card',
        'children' => [

            [
                'label' => 'Billing Overview',
                'route' => 'vendor.billing',
                'icon' => 'credit-card',
            ],

            [
                'label' => 'Invoices',
                'route' => 'vendor.billing.invoices',
                'icon' => 'document-currency-dollar',
            ],

            [
                'label' => 'Payments',
                'route' => 'vendor.billing.payments',
                'icon' => 'banknotes',
            ],

            [
                'label' => 'Subscription History',
                'route' => 'vendor.billing.history',
                'icon' => 'clock',
            ],

        ],
    ],

    [
        'label' => 'Settings',
        'icon' => 'cog-6-tooth',
        'children' => [

            [
                'label' => 'Company Settings',
                'route' => 'vendor.settings.company',
                'icon' => 'building-office',
            ],

            [
                'label' => 'Team Members',
                'route' => 'vendor.team-members',
                'icon' => 'user-plus',
            ],

        ],
    ],

];