<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/api/doc.json' => [[['_route' => 'app.swagger', '_controller' => 'nelmio_api_doc.controller.swagger'], null, ['GET' => 0], null, false, false, null]],
        '/api/v1/register' => [[['_route' => 'register', '_controller' => 'App\\Controller\\AuthController::register'], null, ['POST' => 0], null, false, false, null]],
        '/api/v1/current' => [[['_route' => 'api', '_controller' => 'App\\Controller\\AuthController::api'], null, null, null, false, false, null]],
        '/api/v1/auth' => [[['_route' => 'login_check'], null, ['POST' => 0], null, false, false, null]],
        '/api/v1/token/refresh' => [[['_route' => 'refresh', '_controller' => 'App\\Controller\\AuthController::refresh'], null, ['POST' => 0], null, false, false, null]],
        '/api/v1/courses' => [
            [['_route' => 'courses', '_controller' => 'App\\Controller\\TransactionController::courseList'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'newcourse', '_controller' => 'App\\Controller\\AdminUtilsController::createCourseByAdmin'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/v1/transactions' => [[['_route' => 'transactions', '_controller' => 'App\\Controller\\TransactionController::showTransactions'], null, ['GET' => 0], null, false, false, null]],
        '/api/v1/doc' => [[['_route' => 'app.swagger_ui', '_controller' => 'nelmio_api_doc.controller.swagger_ui'], null, ['GET' => 0], null, false, false, null]],
        '/' => [[['_route' => 'main', '_controller' => 'App\\Controller\\AuthController::main'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/([^/]++)(?'
                        .'|/(?'
                            .'|search/results(*:102)'
                            .'|router(*:116)'
                            .'|exception(?'
                                .'|(*:136)'
                                .'|\\.css(*:149)'
                            .')'
                        .')'
                        .'|(*:159)'
                    .')'
                .')'
                .'|/api/v1/(?'
                    .'|courses/([^/]++)(?'
                        .'|/pay(*:203)'
                        .'|(*:211)'
                    .')'
                    .'|deposite/([^/]++)(*:237)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        102 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        116 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        136 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        149 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        159 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        203 => [[['_route' => 'pay', '_controller' => 'App\\Controller\\TransactionController::doPayment'], ['code'], ['POST' => 0], null, false, false, null]],
        211 => [
            [['_route' => 'course', '_controller' => 'App\\Controller\\TransactionController::showCourse'], ['code'], ['GET' => 0], null, false, true, null],
            [['_route' => 'editcourse', '_controller' => 'App\\Controller\\AdminUtilsController::editCourse'], ['code'], ['POST' => 0], null, false, true, null],
        ],
        237 => [
            [['_route' => 'deposite', '_controller' => 'App\\Controller\\TransactionController::doDeposite'], ['sum'], ['POST' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
