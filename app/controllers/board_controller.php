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
            $thread = new Thread(array('boardid' => $b->id));
            $thread->save();
            ThreadController::send($board, $thread->id);
        }
    }

    public static function board($board) {
        $b = Board::findByName($board);
        if (!$b) {
            Redirect::to('/');
        }
        $boards = Board::all();
        $threads = Thread::findForBoard($b->id);
        $ts = array();
        foreach ($threads as $thread) {
            $ts[$thread->id] = Post::findNewestForThread($thread, 3);
        }
        $admin = parent::get_user_logged_in();
        $control = $admin && $admin->hasControl($b);
        View::make('board.html', array('boards' => $boards, 'board' => $b, 'threads' => $ts, 'admin' => $admin, 'control' => $control));
    }

    public static function create() {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        if ($admin && $admin->hasControl($board)) {
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
                //TODO: Creator of a board should be able to control it
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
        if ($admin && $admin->hasControl($board)) {
            $board = Board::findByName($name);
            $threads = Thread::findForBoard($board->id);
            foreach ($threads as $thread) {
                $posts = Post::findForThread($thread->id);
                foreach ($posts as $post) {
                    $post->delete();
                }
                $thread->delete();
            }
            $board->delete();
            $users = User::all();
            foreach ($users as $user) {
                $posts = Post::findByUser($user->id);
                if (count($posts) === 0) {
                    $user->delete();
                }
            }
            Redirect::to('/');
        } else {
            Redirect::back(array('errors' => array('No permission to delete board')));
        }
    }

    public static function manageBoards() {
        parent::checkBanned();
        $admin = parent::get_user_logged_in();
        if ($admin && $admin->super) {
            $boards = Board::all();
            $admins = Admin::all();
            $admin = parent::get_user_logged_in();
            $adminBoard = AdminBoard::all();
            View::make('manageBoards.html', array('boards' => $boards, 'admins' => $admins, 'admin' => $admin, 'adminBoard' => $adminBoard));
        } else {
            Redirect::back(array('errors' => array('No permission to manage boards')));
        }
    }

    public static function saveManageBoards() {
        $ab = array();
        foreach ($_POST['adminboard'] as $a => $b) {
            foreach ($b as $c => $d) {
                $ab[$a][$c] = true;
            }
        }
        $adminboard = new AdminBoard(array('adminBoard' => $ab));
        $adminboard->save();
        Redirect::back();
    }

}
