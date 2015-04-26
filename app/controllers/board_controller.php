<?php

/**
 * Description of board_controller
 *
 * @author delma
 */
class BoardController extends BaseController {

    public static function edit($board) {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        $board = Board::findByName($board);
        if ($admin && $admin->hasControl($board)) {
            $board->description = filter_input(INPUT_POST, 'description');
            $errors = $board->errors();
            if (count($errors) === 0) {
                $board->update();
                Redirect::back();
            } else {
                Redirect::back(array('errors' => $errors));
            }
        } else {
            Redirect::back(array('errors' => array('No permission to edit board')));
        }
    }

    public static function send($board) {
        if (parent::isBanned()) {
            View::make('banned.html');
        } else {
            $b = Board::findByName($board);
            $thread = new Thread(array('boardid' => $b->id, 'title' => filter_input(INPUT_POST, 'title')));
            $errors = $thread->errors();
            if (count($errors) == 0) {
                $thread->save();
                ThreadController::send($board, $thread->id);
            } else {
                Redirect::back(array('errors' => $errors));
            }
        }
    }

    public static function board($boardid) {
        $data = array();
        $board = Board::findByName($boardid);
        if (!$board) {
            Redirect::to('/', array('errors' => array('Board doesn\'t exist')));
        }
        $data['board'] = $board;
        $data['boards'] = Board::all();
        $page_size = 10;
        Pager::page($data, Thread::countForBoard($board), $page_size);
        $threads = Thread::findForBoard($board, $data['cur_page'], $page_size);
        $ts = array();
        foreach ($threads as $thread) {
            $limit = 3;
            $posts = Post::findForThread($thread, 1, $limit);
            $p = array('title' => $thread->title, 'posts' => $posts);
            $count = Post::countForThread($thread);
            if ($count > $limit) {
                $p['count'] = $count;
            }
            $ts[$thread->id] = $p;
        }
        $data['threads'] = $ts;
        $admin = parent::get_user_logged_in();
        $data['admin'] = $admin;
        if ($admin) {
            $data['control'] = $admin->hasControl($board);
        }
        View::make('board.html', $data);
    }

    public static function create() {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        if ($admin) {
            $boards = Board::all();
            View::make('create.html', array('boards' => $boards, 'admin' => $admin));
        } else {
            Redirect::back(array('errors' => array('No permission to access page')));
        }
    }

    public static function createBoard() {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        if ($admin) {
            $name = filter_input(INPUT_POST, 'name');
            $description = filter_input(INPUT_POST, 'description');
            $board = new Board(array('name' => $name, 'description' => $description));
            $errors = $board->errors();
            if (count($errors) === 0) {
                $board->save();
                AdminBoard::own($admin, $board);
                Redirect::to('/' . $name);
            } else {
                Redirect::back(array('errors' => $errors));
            }
        } else {
            Redirect::back(array('errors' => array('No permission to create board')));
        }
    }

    public static function delete($name) {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        $board = Board::findByName($name);
        if ($admin && $admin->hasControl($board)) {
            $board->delete();
            User::deleteEmpty();
            Redirect::to('/');
        } else {
            Redirect::back(array('errors' => array('No permission to delete board')));
        }
    }

}
