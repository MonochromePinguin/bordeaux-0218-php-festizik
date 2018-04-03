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
    // 'Item' => [ // Controller
    //     ['index', '/', 'GET'], // action, url, method
    //     ['add', '/item/add', 'GET'], // action, url, method
    //     ['edit', '/item/edit/{id:\d+}', 'GET'], // action, url, method
    //     ['show', '/item/{id:\d+}', 'GET'], // action, url, method
    // ],
    'Item'=> [
        ['index', '/item', 'GET']
    ],
    'Admin' => [ // Controller
        ['list', '/list', 'GET'], // ● TEST !
        ['concerts', '/admin/concerts', 'POST'],
        ['articles', '/admin/articles', 'POST']
    ],
    'User' => [
        [ 'articles', '/', 'GET'],
        ['concerts', '/concerts', 'GET']
    ]
];
