<?php

$config = [
    // This is for your main frontend public facing site (if applicable)
    // Assuming your default frontend template is in templates/frontend/default
    'default_template' => 'frontend/default', 

    // This is specifically for your backend admin panel's default template.
    // Assuming your default backend template is in templates/backend/default
    'admin_default_template' => 'default' // Note: This is just 'default' here,
                                          // as renderAdmin adds 'backend/' to the path.
];

/*
    $publicRoutes:
    This array lists all routes (pages) that can be accessed WITHOUT a user being logged in.
    This applies to both your public frontend and your admin panel's login/registration pages.
*/
$publicRoutes = [
    'login',    // Your admin login page (and potentially frontend login)
];


/*
    $menuItems:
    This array defines your navigation structure.
    The 'auth' key dictates visibility:
    - null (default): visible to everyone (logged in or guest)
    - true: visible to logged-in users only
    - false: visible to guests only
*/

$menuItems = [
    'Home' => [
        'link' => '/', // This might be for frontend homepage, adjust if for admin dashboard
        'icon' => 'bi-house',
        'new_window' => false,
        'auth' => null // Visible to everyone
    ],
    'Identifier' => [
        'link' => '/identifier',
        'icon' => 'bi-broadcast',
        'new_window' => false,
        'auth' => null // Visible to everyone
    ],
    'Databases' => [
        'icon' => 'bi-database-fill-gear',
        'auth' => null, // Main menu item visible to everyone (sub-items have their own auth)
        'submenu' => [
            'Databases' => [
                'link' => '/databases',
                'icon' => 'bi-database',
                'new_window' => false,
                'auth' => true // Visible to logged-in users only
            ],
            'phpMyAdmin' => [
                'link' => '/phpmyadmin', // This link will point to /admin/?route=phpmyadmin
                'icon' => 'bi-database-fill-gear',
                'new_window' => true, // Opens in a new tab
                'auth' => null // Visible to everyone (be careful with external links!)
            ],
        ]
    ],
    'Users' => [
        'icon' => 'bi-gear', // This main menu item itself is protected (visible only to logged-in)
        'auth' => true,      // Visible to logged-in users only
        'submenu' => [
            'User List' => [
                'link' => '/users',
                'icon' => 'bi-pencil',
                'new_window' => false,
                'auth' => null // Sub-item visible to everyone (but parent is protected)
                               // This means 'User List' will only appear if parent 'Users' is visible
            ],
            'Add New User' => [
                'link' => '/users/add',
                'icon' => 'bi-bar-chart',
                'new_window' => false,
                'auth' => null // Sub-item visible to everyone
            ],
        ]
    ],
    'MGMT Apps' => [
        'link' => '/mgmt',
        'icon' => 'bi-person-badge-fill',
        'new_window' => false,
        'auth' => true // Visible to logged-in users only
    ],
    'Redirect Check' => [
        'link' => '/redirect-check',
        'icon' => 'bi-link-45deg',
        'new_window' => false,
        'auth' => true // Visible to logged-in users only
    ],
    'Login' => [
        'link' => '/login', // This link points to /admin/?route=login
        'icon' => 'bi-box-arrow-in-right',
        'new_window' => false,
        'auth' => false // Visible to guests (not logged in) only
    ]
    // You might also want a 'Logout' menu item visible only when logged in
    /*
    'Logout' => [
        'link' => '/logout', // Point to your logout route
        'icon' => 'bi-box-arrow-right',
        'new_window' => false,
        'auth' => true // Visible to logged-in users only
    ]
    */
];

?>