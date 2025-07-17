<?php

$config = [
	'default_template' => 'default'
];

$publicRoutes = ['home', 'login', 'identifier', 'register'];
$adminPublicRoutes = ['login','register'];

/*

auth => null (default): visible to everyone

auth => true: visible to logged-in users only

auth => false: visible to guests only

*/

$menuItems = [
    'Home' => [
        'link' => '/',
        'icon' => 'bi-house',
        'new_window' => false,
        'auth' => null
    ],
	'Identifier' => [
        'link' => '/identifier',
        'icon' => 'bi-broadcast',
        'new_window' => false,
        'auth' => null
    ],
	'Databases' => [
        'icon' => 'bi-database-fill-gear',
        'auth' => null,
        'submenu' => [
            'Databases' => [
                'link' => '/databases',
                'icon' => 'bi-database',
                'new_window' => false,
                'auth' => true
            ],
            'phpMyAdmin' => [
                'link' => '/phpmyadmin',
                'icon' => 'bi-database-fill-gear',
                'new_window' => true,
                'auth' => null
            ],
        ]
    ],
    'Users' => [
        'icon' => 'bi-gear',
        'auth' => true,
        'submenu' => [
            'User List' => [
                'link' => '/users',
                'icon' => 'bi-pencil',
                'new_window' => false,
                'auth' => null
            ],
            'Add New User' => [
                'link' => '/users/add',
                'icon' => 'bi-bar-chart',
                'new_window' => false,
                'auth' => null
            ],
        ]
    ],
	'MGMT Apps' => [
        'link' => '/mgmt',
        'icon' => 'bi-person-badge-fill',
        'new_window' => false,
        'auth' => true
    ],
	'Redirect Check' => [
        'link' => '/redirect-check',
        'icon' => 'bi-link-45deg',
        'new_window' => false,
        'auth' => true
    ],
    'Login' => [
        'link' => '/login',
        'icon' => 'bi-box-arrow-in-right',
        'new_window' => false,
        'auth' => false
    ]
];


?>