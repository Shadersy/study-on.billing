<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/doc.json' => [[['_route' => 'app.swagger', '_controller' => 'nelmio_api_doc.controller.swagger'], null, ['GET' => 0], null, false, false, null]],
        '/api/v1/register' => [[['_route' => 'register', '_controller' => 'App\\Controller\\AuthController::register'], null, ['POST' => 0], null, false, false, null]],
        '/api' => [[['_route' => 'api', '_controller' => 'App\\Controller\\AuthController::api'], null, null, null, false, false, null]],
        '/api/v1/auth' => [[['_route' => 'login_check'], null, ['POST' => 0], null, false, false, null]],
        '/api/v1/token/refresh' => [[['_route' => 'refresh', '_controller' => 'App\\Controller\\AuthController::refresh'], null, ['POST' => 0], null, false, false, null]],
        '/api/v1/courses' => [[['_route' => 'courses', '_controller' => 'App\\Controller\\AuthController::courseList'], null, ['GET' => 0], null, false, false, null]],
        '/api/v1/transactions' => [[['_route' => 'transactions', '_controller' => 'App\\Controller\\AuthController::showTransactions'], null, ['GET' => 0], null, false, false, null]],
        '/api/doc' => [[['_route' => 'app.swagger_ui', '_controller' => 'nelmio_api_doc.controller.swagger_ui'], null, ['GET' => 0], null, false, false, null]],
        '/' => [[['_route' => 'main', '_controller' => 'App\\Controller\\AuthController::main'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/v1/courses/([^/]++)(?'
                    .'|/pay(*:73)'
                    .'|(*:80)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        73 => [[['_route' => 'pay', '_controller' => 'App\\Controller\\AuthController::doPayment'], ['code'], ['POST' => 0], null, false, false, null]],
        80 => [
            [['_route' => 'course', '_controller' => 'App\\Controller\\AuthController::showCourse'], ['code'], ['GET' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
