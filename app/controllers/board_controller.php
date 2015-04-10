<?php

/**
 * Description of board_controller
 *
 * @author delma
 */
class BoardController extends BaseController {

    public static function edit($board) {
        if (parent::isBanned()) {
            View::make('banned.html');
        } else {
            $admin = parent::get_user_logged_in();
            if ($admin) {
                $board = Board::findByName($board);
                $board->description = filter_input(INPUT_POST, 'description');
                $errors = $board->errors();
                if (count($errors) === 0) {
                    $board->update();
                    Redirect::back();
                } else {
                    Redirect::back(array('errors' => $errors));
                }
            } else {
                View::make('failed.html');
            }
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
//        Kint::dump($threads);
        foreach ($threads as $thread) {
            $ts[$thread->id] = Post::findNewestForThread($thread, 3);
        }
        $admin = parent::get_user_logged_in();
        View::make('board.html', array('boards' => $boards, 'board' => $b, 'threads' => $ts, 'admin' => $admin));
    }

    public static function create() {
        if (parent::isBanned()) {
            View::make('banned.html');
        } else {
            $admin = parent::get_user_logged_in();
            if ($admin) {
                $boards = Board::all();
                View::make('create.html', array('boards' => $boards, 'admin' => $admin));
            } else {
                View::make('failed.html');
            }
        }
    }

    public static function createBoard() {
        if (parent::isBanned()) {
            View::make('banned.html');
        } else {
            $admin = parent::get_user_logged_in();
            if ($admin) {
                $name = filter_input(INPUT_POST, 'name');
                $description = filter_input(INPUT_POST, 'description');
                $board = new Board(array('name' => $name, 'description' => $description));
                $errors = $board->errors();
                if (count($errors) === 0) {
                    $board->save();
                    Redirect::to('/' . $name);
                } else {
                    Redirect::back(array('errors' => $errors));
                }
            } else {
                View::make('failed.html');
            }
        }
    }

    public static function delete($name) {
        if (parent::isBanned()) {
            View::make('banned.html');
        } else {
            $admin = parent::get_user_logged_in();
            if ($admin) {
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
                View::make('failed.html');
            }
        }
    }

}
