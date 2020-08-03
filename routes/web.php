<?php

$router->get('/', function () use ($router) {
    return 'Chat API v1.0';
});

$router->group(['prefix' => 'api/v1', 'middleware' => ['auth', 'throttle:20,1']], function () use ($router) {
    // Retrieve all messages
    $router->get('messages', [
        'uses' => 'MessageController@getAllMessages'
    ]);

    // Retrieve all unread messages
    $router->get('messages/unread', [
        'uses' => 'MessageController@getAllUnreadMessages'
    ]);

    // Create a new message
    $router->post('message', [
        'uses' => 'MessageController@create'
    ]);

    // Update the read_at for a specified message
    $router->patch('message/read/{id:[0-9]+}', [
        'uses' => 'MessageController@markAsRead'
    ]);
});
