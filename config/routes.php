<?php

$routes->get('/', function() {
    FrontPageController::frontpage();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/:board/:thread', function($board, $thread) {
    ThreadController::thread($board, $thread);
})->conditions(array('board' => '[a-zA-Z]+', 'thread' => '[0-9]+'));

$routes->get('/:board', function($board) {
    BoardController::board($board);
})->conditions(array('board' => '[a-zA-Z]+'));
