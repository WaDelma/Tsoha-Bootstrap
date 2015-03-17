<?php

/**
 * Description of thread_controller
 *
 * @author delma
 */
class ThreadController extends BaseController {

    public static function thread($board, $thread) {
        View::make('thread.html', array('boards' => array('v', 'pol'), 'board' => $board, 'messages' => array('lol', 'xD', 'asd')));
    }

}
