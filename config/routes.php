<?php

//$routes->get('/sandbox', function() {
//    echo AdminController::hash('minad', 'zaq1xsw2');
//});

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

$routes->post('/manage/boards', function() {
    BoardController::saveManageBoards();
});

$routes->get('/manage/boards', function() {
    BoardController::manageBoards();
});

$routes->post('/manage/create', function() {
    AdminController::createAdmin();
});

$routes->get('/manage/create', function() {
    AdminController::create();
});

$routes->post('/manage/delete', function() {
    AdminController::delete();
});

$routes->post('/manage/change', function() {
    AdminController::changePassword();
});

$routes->get('/manage/change/:id', function($id) {
    AdminController::change($id);
});

$routes->get('/manage', function() {
    AdminController::manage();
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
