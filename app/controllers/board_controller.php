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
        $threads = Thread::findForBoard($board);
        $ts = array();
        foreach ($threads as $thread) {
            $ts[$thread->id] = Post::findNewestForThread($thread, 3);
        }
        View::make('board.html', array('boards' => $boards, 'board' => $board, 'threads' => $ts));
    }

}
