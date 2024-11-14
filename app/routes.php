<?php

$routes = [
    'home' => '/',
    'login' => '/login',
    'logout' => '/logout',
];

$modules = [
    'books' => [
        'index' => '/books',
        'create' => '/books/create',
        'store' => '/books/store',
        'show' => '/books/{id}',
        'edit' => '/books/{id}/edit',
        'update' => '/books/{id}/update',
        'destroy' => '/books/{id}/delete',
    ],
    'fines' => [
        'index' => '/fines',
        'create' => '/fines/create',
        'store' => '/fines/store',
        'show' => '/fines/{id}',
        'edit' => '/fines/{id}/edit',
        'update' => '/fines/{id}/update',
        'destroy' => '/fines/{id}/delete',
    ],
    'loans' => [
        'index' => '/loans',
        'create' => '/loans/create',
        'store' => '/loans/store',
        'show' => '/loans/{id}',
        'edit' => '/loans/{id}/edit',
        'update' => '/loans/{id}/update',
        'destroy' => '/loans/{id}/delete',
    ],
    'roles' => [
        'index' => '/roles',
        'create' => '/roles/create',
        'store' => '/roles/store',
        'show' => '/roles/{id}',
        'edit' => '/roles/{id}/edit',
        'update' => '/roles/{id}/update',
        'destroy' => '/roles/{id}/delete',
    ],
    'permissions' => [
        'index' => '/permissions',
        'create' => '/permissions/create',
        'store' => '/permissions/store',
        'show' => '/permissions/{id}',
        'edit' => '/permissions/{id}/edit',
        'update' => '/permissions/{id}/update',
        'destroy' => '/permissions/{id}/delete',
    ],
    'users' => [
        'index' => '/users',
        'create' => '/users/create',
        'store' => '/users/store',
        'show' => '/users/{id}',
        'edit' => '/users/{id}/edit',
        'update' => '/users/{id}/update',
        'destroy' => '/users/{id}/delete',
    ],
];

foreach ($modules as $module => $routes) {
    $controller = ucfirst($module) . 'Controller';
    foreach ($routes as $action => $url) {
        $routes[$module][$action] = [
            'url' => $url,
            'controller' => $controller,
            'method' => $action,
        ];
    }
}

return array_merge($routes, $modules);