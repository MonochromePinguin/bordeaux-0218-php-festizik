<?php
/**
 * This file hold all routes definitions.
 *
 * PHP version 7
 *
 * @author   WCS <contact@wildcodeschool.fr>
 *
 * @link     https://github.com/WildCodeSchool/simple-mvc
 */

$routes = [
    'Item' => [ // Controller
        ['index', '/', 'GET'], // action, url, method
        ['infos', '/item/infos', 'GET'], // action, url, method
        ['edit', '/item/edit/{id:\d+}', 'GET'], // action, url, method
        ['show', '/item/{id:\d+}', 'GET'], // action, url, method
        ['benevol', '/benevol', 'GET'],
        ['insertedBenevol', '/benevol', 'POST']
    ],

    'Admin' => [ // Controller
        ['login', '/login', ['POST', 'GET']],  // action, url, method
        ['admin', '/admin', ['POST', 'GET']],
        ['logout', '/admin/logout', 'GET'],
    ]
    // 'Admin' => [ // Controller
    //     ['concerts', '/admin/concerts', 'POST'],
    //     ['articles', '/admin/articles', 'POST']
    // ],
    'User' => [
        ['concerts', '/concerts', 'GET']
    ]
];
