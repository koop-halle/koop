<?php
$app                          = (new \Silex\Application(
    [
        'debug'  => \Application\Util\DependencyContainer::getInstance()
                                                         ->getConfiguration()
                                                         ->isDevelopment()
        ,
        'logger' => \Application\Util\DependencyContainer::getInstance()->getLogger(),
    ]
))
    ->register(new \Silex\Provider\ValidatorServiceProvider())
    ->register(new \Silex\Provider\TwigServiceProvider())
    ->register(new \Silex\Provider\LocaleServiceProvider())
    ->register(new \Silex\Provider\TranslationServiceProvider(), [
        'locale_fallbacks' => ['de'],
    ])
    ->register(new \Ttskch\Silex\Provider\PaginationServiceProvider())
;
$app['knp_paginator.options'] = [
    'default_options' => [
        'sort_field_name'     => 'sort',
        'sort_direction_name' => 'direction',
        'filter_field_name'   => 'filterField',
        'filter_value_name'   => 'filterValue',
        'page_name'           => 'page',
        'distinct'            => true,
    ],
    'template'        => [
        'pagination' => '@knp_paginator_bundle/sliding.html.twig',
        'filtration' => '@knp_paginator_bundle/filtration.html.twig',
        'sortable'   => '@knp_paginator_bundle/sortable_link.html.twig',
    ],
    'page_range'      => 8,
];
$app->error(
    function (\Exception $exception, \Symfony\Component\HttpFoundation\Request $request, $code) use ($app) {
        return (new \Application\Action\Cms\ErrorAction())
            ->setException($exception)
            ->setCode($code)
            ->init($request, $app)
            ;
    }
);
$routeToActionMap = [
    'match' => [
        \Application\Router::ROUTE_HOME => \Application\Action\Cms\HomeAction::class,
    ],
    'get'   => [],
    'post'  => [],
    'put'   => [],
    'patch' => [],
];
foreach ($routeToActionMap as $method => $actions) {
    foreach ($actions as $route => $action) {
        $app->$method($route, $action . '::init');
    }
}
{
    $app->match(
        \Application\Router::ROUTE_HOME . 'XXXX',
        function ($id, \Symfony\Component\HttpFoundation\Request $request) use ($app) {
            return (new \Application\Action\Cms\HomeAction())
                ->setTournamentId($id)
                ->init($request, $app)
                ;
        }
    );
}
$app->run();
