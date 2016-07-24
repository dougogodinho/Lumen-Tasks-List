<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);


$api->version('v1', ['namespace' => '\App\Http\Controllers'], function (Router $api) {

    $api->get('', function () {
        return 'test api is here!';
    });

    $api->get('tasks', 'TasksController@index');
    $api->put('tasks/sort', 'TasksController@sort');
    $api->post('tasks', 'TasksController@store');
    $api->put('tasks/{id}', 'TasksController@update');
    $api->delete('tasks/{id}', 'TasksController@destroy');

});