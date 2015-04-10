<?php

$routes->get('/', function() {
    FrontPageController::frontpage();
});

$routes->post('/create', function() {
    BoardController::createBoard();
});

$routes->get('/create', function() {
    BoardController::create();
});

$routes->post('/admin', function() {
    AdminController::login();
});

$routes->get('/logout', function() {
    AdminController::logout();
});

$routes->get('/admin', function() {
    AdminController::page();
});

$routes->post('/ban', function() {
    AdminController::ban();
});

$routes->post('/:board/:thread/send', function($board, $thread) {
    ThreadController::send($board, $thread);
})->conditions(array('board' => '[a-zA-Z]+', 'thread' => '[0-9]+'));

$routes->get('/:board/:thread', function($board, $thread) {
    ThreadController::thread($board, $thread);
})->conditions(array('board' => '[a-zA-Z]+', 'thread' => '[0-9]+'));

$routes->post('/:board/send', function($board) {
    BoardController::send($board);
})->conditions(array('board' => '[a-zA-Z]+'));

$routes->post('/:board/delete', function($board) {
    BoardController::delete($board);
})->conditions(array('board' => '[a-zA-Z]+'));

$routes->post('/:board/edit', function($board) {
    BoardController::edit($board);
})->conditions(array('board' => '[a-zA-Z]+'));

$routes->get('/:board', function($board) {
    BoardController::board($board);
})->conditions(array('board' => '[a-zA-Z]+'));
