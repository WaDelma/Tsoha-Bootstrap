<?php

/**
 * Description of board_controller
 *
 * @author delma
 */
class BoardController extends BaseController {

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
        $boards = Board::all();
        $b = Board::findByName($board);
        $threads = Thread::findForBoard($b->id);
        $ts = array();
//        Kint::dump($threads);
        foreach ($threads as $thread) {
            $ts[$thread->id] = Post::findNewestForThread($thread, 3);
        }
        $admin = parent::get_user_logged_in();
        View::make('board.html', array('boards' => $boards, 'board' => $b, 'threads' => $ts, 'admin' => $admin));
    }

}
